<div id="{{$loadingName}}" class="loading container text-center">
  <div class="row align-items-center">
    <div class="col">
      <div class="spinner-border text-light" role="loading">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</div>
<script>
  let {{$loadingName}} = document.getElementById('{{$loadingName}}');
  let {{$loadingName}}Show = () => {{$loadingName}}.style.display = 'block';
  let {{$loadingName}}Hide = () => {{$loadingName}}.style.display = 'none';
</script>