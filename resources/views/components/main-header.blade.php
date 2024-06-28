<div class="card-header">
  <div id="links" style="white-space: nowrap">
    @foreach($links as $link)
      <a href="{{$link}}" style="color: black; text-decoration: none" class="">
        @if($loop->first)
          <i class="fa-solid fa-house"></i>
        @else
          <b>
            {{preg_replace('/^.*\\//i', '', $link)}}
          </b>
        @endif
      </a>
      @if(!$loop->last)
        <i class="fa-solid fa-chevron-right"></i>
      @endif
    @endforeach
  </div>
</div>
<script>

  let links = document.getElementById('links');
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

  links.style.overflow = 'hidden';
</script>