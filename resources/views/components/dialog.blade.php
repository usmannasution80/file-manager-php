<div class="modal fade" id="{{$id}}" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      @if(isset($title))
        <div class="modal-header">
          <h1 class="modal-title fs-5">
            {{$title}}
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      @endif
      <div class="modal-body">
        {{$content}}
      </div>
      @if(isset($footer))
        <div class="modal-footer">
          {{$footer}}
        </div>
      @endif
    </div>
  </div>
</div>