<ul class="list-group list-group-flush" id="{{$file_list_id}}">
</ul>
<script>
  (() => {
    let fileListContainer = document.getElementById('{{$file_list_id}}');
    let url = (window.location.pathname + '/').replace(/\/+/, '/');
    let fileList = {!!json_encode($files)!!};
    let dirs = [];
    let files = [];
    for(let file of fileList){
      if(file['type'] === 'directory')
        dirs.push(file);
      else
        files.push(file);
    }
    files = [...dirs, ...files];
    for(file of files){
      let icon;
      switch(file['type']){
        case 'directory':
          icon = 'fa-regular fa-folder';
          break;
        case 'video':
          icon = 'fa-solid fa-file-video';
          break;
        case 'audio':
          icon = 'fa-solid fa-file-audio';
          break;
        case 'picture':
          icon = 'fa-solid fa-file-image';
          break;
        default:
          icon = 'fa-regular fa-file';
      }
      fileListContainer.innerHTML += `
        <li class="list-group-item" style="overflow: hidden">
          <a style="text-decoration: none; color: black" href="${url + encodeURIComponent(file['filename'])}">
            <table border="0">
              <tr>
                <td style="padding-right: 10px">
                  <i class="${icon}"></icon>
                </td>
                <td>
                  <span>${file['filename']}</span>
                </td>
              </tr>
            </table>
          </a>
        </li>
      `;
    }
  })();
</script>