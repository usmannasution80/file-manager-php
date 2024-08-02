@if($is_file)
<div class="card-body">
  @if(preg_match('/audio/i', $file_info['type']))
    <audio controls>
      <source src="{{$src}}"/>
    </audio>
  @elseif(preg_match('/video/i', $file_info['type']))
    <video width="100%" controls>
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
    <button data-bs-toggle="modal" data-bs-target="#delete-modal" class="btn btn-danger">
      <i class="fa-solid fa-trash-can"></i>
      <span>Delete</span>
    </button>
    <button data-bs-toggle="modal" data-bs-target="#rename-modal" class="btn btn-warning">
      <i class="fa-solid fa-pen"></i>
      <span>Rename</span>
    </button>
  </div>
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
        window.location.href = window.location.href.replace(/\/[^\/]+$/i, '/' + encodeURIComponent(filename))
      })
      .catch(err => alert(err));
    };
  })();
</script>
@endif