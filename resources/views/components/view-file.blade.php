@if($is_file)
<div class="card-body">
  @if(preg_match('/audio/i', $file_info['type']))
    <audio id="{{$mediaId}}" controls>
      <source src="{{$src}}"/>
    </audio>
  @elseif(preg_match('/video/i', $file_info['type']))
    <video id="{{$mediaId}}" width="100%" controls>
      <source src="{{$src}}"/>
    </video>
  @elseif(preg_match('/image/i', $file_info['type']))
    <img src="{{$src}}" width="100%"/>
  @else
    <span>File is not supported.</span>
  @endif
  <table border="0">
    @foreach($file_info as $key => $value)
      <tr>
        <td>{{$key}}</td>
        <td style="padding: 0px 5px">:</td>
        <td>{{$value}}</td>
      </tr>
    @endforeach
  </table>
  <div style="text-align: center">
    <a href="{{$src}}" download class="btn btn-primary">
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
      if(files[i].filename === '{!!$file_info['filename']!!}'){
        currentIndex = i;
        break;
      }
    }
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