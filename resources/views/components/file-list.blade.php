<ul class="list-group list-group-flush">
  @foreach($files as $file)
    <li class="list-group-item" style="overflow: hidden">
      <a style="text-decoration: none; color: black" href="{{url(preg_replace('/\\/*$/i', '', $_SERVER['REQUEST_URI']) . '/' . urlencode($file['filename']))}}">
        <table border="0">
          <tr>
            <td style="padding-right: 10px">
              @switch($file['type'])
                @case('directory')
                  <i class="fa-regular fa-folder"></i>
                  @break
                @case('video')
                  <i class="fa-solid fa-file-video"></i>
                  @break
                @case('audio')
                  <i class="fa-solid fa-file-audio"></i>
                  @break
                @case('image')
                  <i class="fa-solid fa-file-image"></i>
                  @break
                @default
                  <i class="fa-regular fa-file"></i>
              @endswitch
            </td>
            <td>
              <span>{{$file['filename']}}</span>
            </td>
          </tr>
        </table>
      </a>
    </li>
  @endforeach
</ul>