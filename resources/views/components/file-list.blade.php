@if(!$is_file)
<ul class="list-group list-group-flush" id="file-list-ul">
</ul>
<script>
  (() => {
    let fileListContainer = document.getElementById('file-list-ul');
    let path = (window.location.pathname + '/').replace(/\/+/, '/');
    strg('files', {path, files : {!!json_encode($files)!!}});
    let dirs = [];
    let files = [];
    for(let file of strg('files').files){
      if(file['type'] === 'directory')
        dirs.push(file);
      else
        files.push(file);
    }
    files = strg('files', {path, files : [...dirs, ...files]}).files;
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

      let url = path + encodeURIComponent(file['filename']);

      fileListContainer.innerHTML += `
        <li class="list-group-item">
          <a href="${url}">
            <table>
              <tr>
                <td>
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
@endif