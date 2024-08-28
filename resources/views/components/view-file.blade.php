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
    <button data-bs-toggle="modal" data-bs-target="#delete-modal" class="btn btn-danger">
      <i class="fa-solid fa-trash-can"></i>
      <span>Delete</span>
    </button>
    <button data-bs-toggle="modal" data-bs-target="#rename-modal" class="btn btn-warning">
      <i class="fa-solid fa-pen"></i>
      <span>Rename</span>
    </button>
    @endif

  </div>
  <table border="0" style="width:100%">
    <tr>
      <td>
        <a class="btn btn-primary" id="{{$prev_id}}" style="display:none">
          <i class="fa-solid fa-circle-left"></i>
          <span>Prev.</span>
        </a>
      </td>
      <td style="text-align:right">
        <a class="btn btn-primary" id="{{$next_id}}" style="display:none">
          <span>Next</span>
          <i class="fa-solid fa-circle-right"></i>
        </a>
      </td>
    </tr>
  </table>
</div>
<x-dialog id="rename-modal">
  <x-slot:title>
    Rename File
  </x-slot:title>
  <x-slot:content>
    <x-form input-id="rename-input" placeholder="New filename"/>
  </x-slot:content>
  <x-slot:footer>
    <button class="btn btn-secondary" data-bs-dismiss="modal">
      Cancel
    </button>
    <button id="rename-button" class="btn btn-primary">
      Save
    </button>
  </x-slot:footer>
</x-dialog>
<x-dialog id="delete-modal">
  <x-slot:title>
    WARNING!
  </x-slot:title>
  <x-slot:content>
    Are you sure to delete this file?
  </x-slot:content>
  <x-slot:footer>
    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button class="btn btn-danger" id="delete-button">Delete</button>
  </x-slot:footer>
</x-dialog>
<script>
  (() => {
    let mediaElement = document.getElementById('{{$mediaId}}');
    if(mediaElement){
      mediaElement.autoplay = strg('settings').autoplay_media;
      mediaElement.muted = mediaElement.autoplay;
    }
    let path = window.location.pathname.replace(/\/[^\/]+$/, '/');
    const set_prev_next = files => {
      let i = 0;
      let prev = document.getElementById('{{$prev_id}}');
      let next = document.getElementById('{{$next_id}}');
      for(file of files){
        if(file['filename'] === '{{$file_info['filename']}}'){
          if(i > 0){
            if(files[i-1].type !== 'directory'){
              prev.setAttribute('href', path + encodeURIComponent(files[i-1]['filename']));
              prev.style.display = 'inline-block';
            }
          }
          if(i < files.length-1){
            if(files[i+1].type !== 'directory'){
              next.setAttribute('href', path + encodeURIComponent(files[i+1]['filename']));
              next.style.display = 'inline-block';
            }
          }
          break;
        }
        i++;
      }
    };
    let files = strg('files');
    if(files)
      if(files.path === path)
        return set_prev_next(files.files);
    axios.get(path + '?list')
    .then(r => {
      files = strg('files', {path, files : r.data}).files;
      set_prev_next(files);
    }).catch(err => alert(err));
  })();
  (() => {
    document.getElementById('delete-button').onclick = function(e){
      axios.post('?delete')
      .then(r => {
        alert('File deleted!');
        window.location.href = decodeURI(window.location.pathname).replace(/\/[^/]+$/i, '/');
      })
      .catch(err => {
        alert(err);
      });
    };
    document.getElementById('rename-button').onclick = function(e){
      let filename = document.getElementById('rename-input').value;
      axios.post('?rename', 'filename=' + encodeURIComponent(filename))
      .then(r => {
        alert('File name changed!');
        window.location.href = window.location.href.replace(/\/[^\/]+$/i, '/' + r.data)
      })
      .catch(err => alert(err));
    };
  })();
</script>
@endif