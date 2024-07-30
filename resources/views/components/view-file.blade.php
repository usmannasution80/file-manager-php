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
    <button href="{{$src}}?delete" class="btn btn-danger" id="delete-button">
      <i class="fa-solid fa-trash-can"></i>
      <span>Delete</span>
    </button>
  </div>
</div>
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
  })();
</script>
@endif