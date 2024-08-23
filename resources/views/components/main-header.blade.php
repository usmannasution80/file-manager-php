<div id="header" class="card-header container">
  <div class="row align-items-center">
    <div class="col" id="header-links"></div>
    <div id="header-setting-button">
      <x-popup-menu>
        <x-slot:button>
          <button class="btn btn-primary">
            <i class="fa-solid fa-gear"></i>
          </button>
        </x-slot:button>
        <x-slot:content>
          @if(!$is_path_file)
            <span data-bs-toggle="modal" data-bs-target="#{{$uploadDialog}}">
              <i class="fa-solid fa-file-arrow-up"></i>
              <span>Upload</span>
            </span>
            <span data-bs-toggle="modal" data-bs-target="#{{$settingDialog}}">
              <i class="fa-solid fa-gear"></i>
              <span>Setting</span>
            </span>
            <span data-bs-toggle="modal" data-bs-target="#{{$sortingOptionDialog}}">
              <i class="fa-solid fa-arrow-down-a-z"></i>
              <span>Sorting Options</span>
            </span>
          @endif
          @if(Auth::check())
            <span id="{{$logoutButton}}">
              <i class="fa-solid fa-right-from-bracket"></i>
              <span>Logout</span>
            </span>
          @else
            <a href="?login">
              <i class="fa-solid fa-right-to-bracket"></i>
              <span>Login</span>
            </a>
          @endif
        </x-slot:content>
      </x-popup-menu>
    </div>
  </div>
</div>
<x-dialog id="{{$sortingOptionDialog}}">
  <x-slot:title>
    Sorting Option
  </x-slot:title>
  <x-slot:content>
    <table border="0" class="w-100 mb-1">
      <tr>
        <td style="max-width:2em">
          Sort by :
        </td>
        <td>
          <x-form
            type="select"
            :options="$sortByOptions"
            name="{{$selectSortBy}}"/>
        </td>
      </tr>
    </table>
    <div>
      <span>Sorting Order : </span>
      <x-form
        type="radio"
        buttonLabel="btn-outline-primary"
        name="{{$sortingOrderInput}}"
        value="asc"
        label="ASC"
        inline/>
      <x-form
        type="radio"
        buttonLabel="btn-outline-primary"
        name="{{$sortingOrderInput}}"
        value="desc"
        label="DESC"
        inline/>
    </div>
  </x-slot:content>
  <x-slot:footer>
    <button class="btn btn-primary" id="{{$saveSortingButton}}" data-bs-dismiss="modal">
      Save
    </button>
  </x-slot:footer>
</x-dialog>
<x-setting-dialog id="{{$settingDialog}}"/>
@if(!$is_path_file)
  <x-dialog id="{{$uploadDialog}}">
    <x-slot:title>
      Upload File
    </x-slot:title>
    <x-slot:content>
      <x-form type="file" input-id="{{$fileInput}}"/>
    </x-slot:content>
    <x-slot:footer>
      <button id="{{$uploadButton}}" class="btn btn-primary">
        Upload
      </button>
    </x-slot:footer>
  </x-dialog>
  <x-loading loadingName="uploadLoading"/>
@endif
<x-loading loadingName="logoutLoading"/>
<script>

  (() => {
    let links = window.location.pathname.replace(/^\/+/, '/').replace(/\/+$/, '').replaceAll(/\/+/g, '/').split('/');
    let linksElements = '';
    for(let i=0;i<links.length;i++){
      if(i > 0){
        links[i] = links[i-1] + '/' + links[i];
        linksElements += `
          <a href="${links[i]}">
            <b>
              ${decodeURIComponent(links[i].replace(/^.*\//, ''))}
            </b>
          </a>
        `;
      }else{
        linksElements += `
          <a href="/">
            <i class="fa-solid fa-house"></i>
          </a>
        `;
      }
      if(i < links.length-1 && links.length > 1)
        linksElements += '<i class="fa-solid fa-chevron-right"></i>';
    }
    document.getElementById('header-links').innerHTML = linksElements;
    links = document.getElementById('header-links');
    let linkAnchors = links.getElementsByTagName('a');
    let isThereAnchorMissing = false;

    while(links.offsetWidth < links.scrollWidth){
      if(linkAnchors.length === 3)
        break;
      let index = Math.floor(linkAnchors.length / 2);
      linkAnchors[index].nextElementSibling.remove();
      linkAnchors[index].remove();
      isThereAnchorMissing = true;
    }

    if(isThereAnchorMissing){
      let index = Math.floor(linkAnchors.length / 2);
      let span = document.createElement('span');
      span.innerHTML = '...';
      span.classList.add('btn');
      linkAnchors[index].after(span);
      linkAnchors[index].remove();
    }

    @if(!$is_path_file)
    document.getElementById('{{$uploadButton}}').onclick = e => {
      uploadLoadingShow();
      let formData = new FormData();
      formData.append('file', document.getElementById('{{$fileInput}}').files[0]);
      axios.post(window.location.href, formData, {headers: {
        'Content-Type': 'multipart/form-data'
      }})
      .then((response) => {
        uploadLoadingHide();
        window.location.reload();
      })
      .catch((error) => {
        uploadLoadingHide();
        alert('Seems like you are not logged in!');
        window.location.reload();
      });
    };
    @endif

    @if(Auth::check())
    document.getElementById('{{$logoutButton}}').onclick = e => {
      logoutLoadingShow();
      axios.post('/?logout')
      .then(r => window.location.reload())
      .catch(r => window.location.reload());
    }
    @endif
    document.getElementById('{{$saveSortingButton}}').onclick = e => {
      let sortingOrderInputs = document.querySelectorAll('input[name={{$sortingOrderInput}}]');
      for(let input of sortingOrderInputs){
        if(input.checked){
          strg('sorting-order', input.value);
          break;
        }
      }
      strg('sort-by', document.querySelector('select[name="{{$selectSortBy}}"]').value);
      setFileList();
    };
    document.getElementById('{{$sortingOptionDialog}}').addEventListener('show.bs.modal', e => {
      let sortingOrderInputs = document.querySelectorAll('input[name={{$sortingOrderInput}}]');
      for(let input of sortingOrderInputs)
        input.checked = input.value === (strg('sorting-order') || 'asc');
      document.querySelector('select[name="{{$selectSortBy}}"]').value = strg('sort-by') || 'name';
    });
  })();
</script>