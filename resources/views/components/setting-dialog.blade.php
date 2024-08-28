<x-dialog id="{{$id}}">
  <x-slot:title>
    Setting
  </x-slot:title>
  <x-slot:content>
    <x-form
      inputId="{{$showStartWithPoint}}"
      type="checkbox"
      label="Show files/directories start with '.'"
      switch/>
    <x-form
      inputId="{{$showDoublePoint}}"
      type="checkbox"
      label="Show '..'"
      switch/>
    <x-form
      inputId="{{$autoplayMedia}}"
      type="checkbox"
      label="Autoplay media"
      switch/>
  </x-slot:content>
  <x-slot:footer>
    <button
    class="btn btn-primary"
    data-bs-dismiss="modal"
    id="{{$saveButton}}">
      Save
    </button>
  </x-slot:footer>
</x-dialog>
<script>
  (() => {
    document.getElementById('{{$saveButton}}').onclick = e => {
      strg('settings', {
        'show_start_with_point' : document.getElementById('{{$showStartWithPoint}}').checked,
        'show_double_point' : document.getElementById('{{$showDoublePoint}}').checked,
        'autoplay_media' : document.getElementById('{{$autoplayMedia}}').checked
      });
      setFileList();
    };
    document.getElementById('{{$id}}').addEventListener('show.bs.modal', e => {
      const settings = strg('settings');
      document.getElementById('{{$showStartWithPoint}}').checked = settings.show_start_with_point;
      document.getElementById('{{$showDoublePoint}}').checked = settings.show_double_point;
      document.getElementById('{{$autoplayMedia}}').checked = settings.autoplay_media;
    });
  })();
</script>