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
          <span data-bs-toggle="modal" data-bs-target="#upload">
            <i class="fa-solid fa-file-arrow-up"></i>
            <span>Upload</span>
          </span>
          <span id="logout-button">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
          </span>
        </x-slot:content>
      </x-popup-menu>
    </div>
  </div>
</div>
<x-dialog id="upload">
  <x-slot:title>
    Upload File
  </x-slot:title>
  <x-slot:content>
    <x-form type="file" input-id="file-input"/>
  </x-slot:content>
  <x-slot:footer>
    <button id="upload-button" class="btn btn-primary">
      Upload
    </button>
  </x-slot:footer>
</x-dialog>
<x-loading loadingName="uploadLoading"/>
<script>

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

  document.getElementById('upload-button').onclick = e => {
    uploadLoadingShow();
    let formData = new FormData();
    formData.append('file', document.getElementById('file-input').files[0]);
    axios.post(window.location.href, formData, {headers: {
      'Content-Type': 'multipart/form-data'
    }})
    .then((response) => {
      uploadLoadingHide();
      window.location.reload();
    })
    .catch((error) => {
      uploadLoadingHide();
      alert(error)
    });
  };
  document.getElementById('logout-button').onclick = e => {
    axios.post('/?logout')
    .then(r => window.location.reload())
    .catch(r => window.location.reload());
  }
</script>