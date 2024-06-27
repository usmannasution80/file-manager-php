<ul class="list-group list-group-flush">
  @foreach($files as $file => $icon)
    <li class="list-group-item">
      <a style="text-decoration: none; color: black" href="{{url(preg_replace('/\\/*$/i', '', $_SERVER['REQUEST_URI']) . '/' . $file)}}">
        <i class="{{$icon}}"></i>
        <span>{{$file}}</span>
      </a>
    </li>
  @endforeach
</ul>