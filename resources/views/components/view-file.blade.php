@if($is_file)
<div class="card-body">
  <div id="{{$mediaId}}-container"></div>
  <table id="{{$fileInfoTable}}" border="0" style="width:100%; table-layout:fixed"></table>
  <div style="text-align: center">
    <a id="{{$downloadButton}}" download class="btn btn-primary">
      <i class="fa-solid fa-download"></i>
      <span>Download</span>
    </a>

    @if(Auth::check())
    <button data-bs-toggle="modal" data-bs-target="#{{$deleteModal}}" class="btn btn-danger">
      <i class="fa-solid fa-trash-can"></i>
      <span>Delete</span>
    </button>
    <button data-bs-toggle="modal" data-bs-target="#{{$renameModal}}" class="btn btn-warning">
      <i class="fa-solid fa-pen"></i>
      <span>Rename</span>
    </button>
    @endif

  </div>
  <table border="0" style="width:100%">
    <tr>
      <td>
        <button class="btn btn-primary" id="{{$prev}}" style="display:none">
          <i class="fa-solid fa-circle-left"></i>
          <span>Prev.</span>
        </button>
      </td>
      <td style="text-align:right">
        <button class="btn btn-primary" id="{{$next}}" style="display:none">
          <span>Next</span>
          <i class="fa-solid fa-circle-right"></i>
        </button>
      </td>
    </tr>
  </table>
</div>

@if(Auth::check())
  <x-dialog id="{{$renameModal}}">
    <x-slot:title>
      Rename File
    </x-slot:title>
    <x-slot:content>
      <x-form inputId="{{$renameInput}}" placeholder="New filename"/>
    </x-slot:content>
    <x-slot:footer>
      <button class="btn btn-secondary" data-bs-dismiss="modal">
        Cancel
      </button>
      <button id="{{$renameButton}}" class="btn btn-primary">
        Save
      </button>
    </x-slot:footer>
  </x-dialog>
  <x-dialog id="{{$deleteModal}}">
    <x-slot:title>
      WARNING!
    </x-slot:title>
    <x-slot:content>
      Are you sure to delete this file?
    </x-slot:content>
    <x-slot:footer>
      <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      <button class="btn btn-danger" id="{{$deleteButton}}">Delete</button>
    </x-slot:footer>
  </x-dialog>
@endif

<x-loading loadingName="initLoading"/>
<script>
  (function init(){
    initLoadingShow();
    let currentIndex = null;
    let firstFileIndex = null;
    let files = sortFileList();
    let path = window.location.pathname.replace(/\/[^\/]+$/, '/');
    let currentFilename = decodeURIComponent(window.location.pathname.replace(path, ''));
    if(files)
      if(files.path !== path)
        files = null;
    if(!files)
      return axios.get(path + '?list')
      .then(r => {
        strg('files', {path, files : r.data});
        init();
      });
    initLoadingHide();
    files = files.files;
    for(let i=0;i<files.length;i++){
      if(files[i].type !== 'directory' && !firstFileIndex)
        firstFileIndex = i;
      if(files[i].filename === currentFilename){
        currentIndex = i;
        break;
      }
    }
    (() => {
      const mediaContainer = document.getElementById('{{$mediaId}}-container');
      const fileInfoTable = document.getElementById('{{$fileInfoTable}}');
      const downloadButton = document.getElementById('{{$downloadButton}}');
      const size = (files[currentIndex].size * Math.pow(10, -6)).toFixed(2) + 'MB';
      const fileInfoLabels = {
        filename : 'Filename',
        type : 'Type',
        size : 'Size'
      };
      let src = path + encodeURIComponent(files[currentIndex].filename) + '?view';
      let fileInfoTableHTML = '';
      downloadButton.href = src;
      switch(files[currentIndex].type.replace(/\/.*$/, '')){
        case 'video':
          mediaContainer.innerHTML = `<video id="{{$mediaId}}" width="100%" controls>
            <source src="${src}"/>
          </video>`;
          break;
        case 'audio':
          mediaContainer.innerHTML = `<audio id="{{$mediaId}}" controls>
            <source src="${src}"/>
          </audio>`;
          break;
        case 'image':
          mediaContainer.innerHTML = `<img src="${src}" width="100%"/>`;
          break;
        default:
          mediaContainer.innerHTML = '<div>The file is not supported to play inline in browser</div>';
      }
      for(let label in fileInfoLabels){
        fileInfoTableHTML += `<tr>
          <td style="width:70px">${fileInfoLabels[label]}</td>
          <td style="width:20px">:</td>
          <td style="word-wrap: break-word">${label === 'size' ? size : files[currentIndex][label]}</td>
        </tr>`;
      }
      fileInfoTable.innerHTML = fileInfoTableHTML;
    })();
    const navigate = flag => {
      switch(flag){
        case NEXT_FILE :
          window.location.href = path + encodeURIComponent(files[currentIndex+1].filename);
          break;
        case PREVIOUS_FILE :
          window.location.href = path + encodeURIComponent(files[currentIndex-1].filename);
          break;
        case NEXT_MEDIA :
          for(let i=currentIndex+1;i<files.length;i++){
            if(/audio|video/.test(files[i].type)){
              window.location.href = path + encodeURIComponent(files[i].filename);
              break;
            }
          }
      }
    };
    let mediaElement = document.getElementById('{{$mediaId}}');
    if(mediaElement){
      let {
        autoplay_media,
        next_media_autoplay
      } = strg('settings');
      mediaElement.autoplay = strg('settings').autoplay_media;
      mediaElement.muted = mediaElement.autoplay;
      if(next_media_autoplay) mediaElement.onended = e => {
        if(currentIndex < files.length-1)
          navigate(NEXT_MEDIA)
      };
    }
    if(currentIndex > firstFileIndex){
      let prev = document.getElementById('{{$prev}}');
      prev.onclick = e => navigate(PREVIOUS_FILE);
      prev.style.display = 'inline-block';
    }
    if(currentIndex < files.length-1){
      let next = document.getElementById('{{$next}}');
      next.onclick = e => navigate(NEXT_FILE);
      next.style.display = 'inline-block';
    }

    @if(Auth::check())

      document.getElementById('{{$deleteButton}}').onclick = function(e){
        axios.post('?delete')
        .then(r => {
          alert('File deleted!');
          window.location.href = decodeURI(window.location.pathname).replace(/\/[^/]+$/i, '/');
        })
        .catch(err => {
          alert(err);
        });
      };
      document.getElementById('{{$renameButton}}').onclick = function(e){
        let filename = document.getElementById('{{$renameInput}}').value;
        axios.post('?rename', 'filename=' + encodeURIComponent(filename))
        .then(r => {
          alert('File name changed!');
          window.location.href = window.location.href.replace(/\/[^\/]+$/i, '/' + r.data)
        })
        .catch(err => alert(err));
      };

    @endif

  })();
</script>
@endif