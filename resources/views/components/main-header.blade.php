<div id="header" class="card-header container">
  <div class="row align-items-center">
    <div class="col" id="header-links">
      @foreach($links as $link)
        <a href="{{$link}}">
          @if($loop->first)
            <i class="fa-solid fa-house"></i>
          @else
            <b>
              {{preg_replace('/^.*\\//i', '', urldecode($link))}}
            </b>
          @endif
        </a>
        @if(!$loop->last)
          <i class="fa-solid fa-chevron-right"></i>
        @endif
      @endforeach
    </div>
    <div id="header-setting-button">
      <x-popup-menu>
        <x-slot:button>
          <button class="btn btn-primary">
            <i class="fa-solid fa-gear"></i>
          </button>
        </x-slot:button>
        <x-slot:content>
          <span data-bs-toggle="modal" data-bs-target="#{{$sortingOptionDialog}}">
            <i class="fa-solid fa-arrow-down-a-z"></i>
            <span>Sorting Options</span>
          </span>
          @if(!$is_path_file)
            <span data-bs-toggle="modal" data-bs-target="#{{$uploadDialog}}">
              <i class="fa-solid fa-file-arrow-up"></i>
              <span>Upload</span>
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
    <button class="btn btn-primary" id="{{$saveSortingButton}}">
      Save
    </button>
  </x-slot:footer>
</x-dialog>
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
    let links = document.getElementById('header-links');
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
          window.location.reload();
        }
      }
    };
    document.getElementById('{{$sortingOptionDialog}}').addEventListener('show.bs.modal', e => {
      let sortingOrderInputs = document.querySelectorAll('input[name={{$sortingOrderInput}}]');
      for(let input of sortingOrderInputs)
        input.checked = input.value === (strg('sorting-order') || 'asc');
    });
  })();
</script>