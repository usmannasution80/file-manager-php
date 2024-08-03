<ul class="list-group list-group-flush">
  @foreach($files as $file => $icon)
    <li class="list-group-item" style="overflow: hidden">
      <a style="text-decoration: none; color: black" href="{{url(preg_replace('/\\/*$/i', '', $_SERVER['REQUEST_URI']) . '/' . urlencode($file))}}">
        <table border="0">
          <tr>
            <td style="padding-right: 10px">
              <i class="{{$icon}}"></i>
            </td>
            <td>
              <span>{{$file}}</span>
            </td>
          </tr>
        </table>
      </a>
    </li>
  @endforeach
</ul>