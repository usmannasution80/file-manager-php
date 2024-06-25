<div class="card-header">
  @foreach($links as $link)
    <a href="{{$link}}" class="btn">
      @if($loop->first)
        <i class="fa-solid fa-house"></i>
      @else
        <b>
          {{preg_replace('/^.*\\//i', '', $link)}}
        </b>
      @endif
    </a>
    @if(!$loop->last)
      <i class="fa-solid fa-chevron-right"></i>
    @endif
  @endforeach
</div>