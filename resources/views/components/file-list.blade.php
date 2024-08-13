@if(!$is_file)
<ul class="list-group list-group-flush" id="file-list-ul">
</ul>
<x-dialog id="rename-modal">
  <x-slot:title>
    Rename File
  </x-slot:title>
  <x-slot:content>
    <div>
      <span>Current Filename : </span>
      <span id="current-filename"></span>
    </div>
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
    Are you sure to delete <span id="filename-to-delete"></span>?
  </x-slot:content>
  <x-slot:footer>
    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button class="btn btn-danger" id="delete-button">Delete</button>
  </x-slot:footer>
</x-dialog>
<x-loading loadingName="renameLoading"/>
<x-loading loadingName="deleteLoading"/>
<script>

  (() => {

    const sortFileList = files => {
      if(!files){
        let {path, files} = strg('files');
        let dirs = [];
        let fls = [];
        for(let file of files){
          if(file.type === 'directory')
            dirs.push(file);
          else
            fls.push(file)
        }
        return strg('files', {path, files : [...sortFileList(dirs), ...sortFileList(fls)]});
      }
      let isThereChange = true;
      let length = files.length;
      while(isThereChange){
        isThereChange = false;
        for(let i=1; i<length; i++){
          for(let j=0;j<files[i-1].filename.length;j++){
            if(j >= files[i].filename.length)
              break;
            if(files[i-1].filename.toLowerCase().charCodeAt(j) > files[i].filename.toLowerCase().charCodeAt(j)){
              let file = files[i];
              files[i] = files[i-1];
              files[i-1] = file;
              isThereChange = true;
              break;
            }else if(files[i-1].filename.toLowerCase().charCodeAt(j) < files[i].filename.toLowerCase().charCodeAt(j)){
              break;
            }
          }
        }
      }
      if(strg('sorting-order') === 'desc'){
        let tempFiles = [...files];
        for(let i=tempFiles.length-1,j=0;i>=0;i--)
          files[j++] = tempFiles[i];
      }
      return files;
    };

    let fileListContainer = document.getElementById('file-list-ul');
    let path = (window.location.pathname + '/').replace(/\/+/, '/');
    strg('files', {path, files : {!!json_encode($files)!!}});
    let files = sortFileList().files;
    let index = 0;

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
        <li class="list-group-item" index="${index++}">
          <table>
            <tr>
              <td>
                <i class="${icon}"></icon>
              </td>
              <td>
                <a href="${url}">
                  <span>${file['filename']}</span>
                </a>
              </td>
              <td>
                <x-popup-menu>
                  <x-slot:button>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                  </x-slot:button>
                  <x-slot:content>
                    <span data-bs-toggle="modal" data-bs-target="#delete-modal">
                      <i class="fa-solid fa-trash-can"></i>
                      <span>Delete</span>
                    </span>
                    <span data-bs-toggle="modal" data-bs-target="#rename-modal">
                      <i class="fa-solid fa-pen"></i>
                      <span>Rename</span>
                    </span>
                  </x-slot:content>
                </x-popup-menu>
              </td>
            </tr>
          </table>
        </li>
      `;
    }

    let editIndex;

    let deleteModalTogglers = fileListContainer.querySelectorAll('[data-bs-target="#delete-modal"]');
    for(let deleteModalToggler of deleteModalTogglers){
      deleteModalToggler.onclick = e => {
        let index = getElementUpTo(e.target.parentNode.parentNode, 'li').getAttribute('index');
        document.getElementById('filename-to-delete').innerHTML = files[index].filename;
        editIndex = index;
      };
    }

    let renameModalTogglers = fileListContainer.querySelectorAll('[data-bs-target="#rename-modal"]');
    for(let renameModalToggler of renameModalTogglers){
      renameModalToggler.onclick = e => {
        let index = getElementUpTo(e.target.parentNode.parentNode, 'li').getAttribute('index');
        document.getElementById('current-filename').innerHTML = files[index].filename;
        editIndex = index;
      };
    }

    document.getElementById('delete-button').onclick = e => {
      deleteLoadingShow();
      axios.post(path + encodeURIComponent(files[editIndex].filename) + '?delete')
      .then(r => {
        alert('File deleted');
        window.location.reload();
      })
      .catch(err => {
        alert('You should login first!');
        deleteLoadingHide();
      });
    };

    document.getElementById('rename-button').onclick = e => {
      renameLoadingShow();
      axios.post(path + encodeURIComponent(
        files[editIndex].filename) + '?rename',
        'filename=' + encodeURIComponent(document.getElementById('rename-input').value)
      ).then(r => {
        alert('File name changed');
        window.location.reload();
      })
      .catch(err => {
        alert('You should login first!');
        renameLoadingHide();
      });
    };

  })();
</script>
@endif