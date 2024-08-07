<ul class="list-group list-group-flush" id="{{$file_list_id}}">
</ul>
<script>
  (() => {
    let fileListContainer = document.getElementById('{{$file_list_id}}');
    let path = (window.location.pathname + '/').replace(/\/+/, '/');
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
    let i = 0;
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

      let url = path + encodeURIComponent(file['filename']) + '?';

      if(i > 0)
        if(files[i-1]['type'] !== 'directory')
          url += 'prev=' + encodeURIComponent(files[i-1]['filename']) + '&';

      if(i+1 < files.length)
        if(files[i+1]['type'] !== 'directory')
          url += 'next=' + encodeURIComponent(files[i+1]['filename']);

      fileListContainer.innerHTML += `
        <li class="list-group-item" style="overflow: hidden">
          <a style="text-decoration: none; color: black" href="${url}">
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
      i++;
    }
  })();
</script>