@if($is_file)
<div class="card-body">
  @if(preg_match('/audio/i', $type))
    <audio controls>
      <source src="{{$src}}"/>
    </audio>
  @elseif(preg_match('/video/i', $type))
    <video width="100%" controls>
      <source src="{{$src}}"/>
    </video>
  @elseif(preg_match('/image/i', $type))
    <img src="{{$src}}" width="100%"/>
  @else
    <span>File is not supported.</span>
  @endif
  <div style="text-align: center">
    <a href="{{$src}}" download class="btn btn-primary">
      <i class="fa-solid fa-download"></i>
      <span>Download</span>
    </a>
  </div>
</div>
@endif