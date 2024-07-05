<div style="display:inline-block">
  <span class="popup-show">
    {{$button}}
  </span>
  <div class="card popup-menu-content" style="z-index:999">
    <ul class="list-group list-group-flush">
      {{$content}}
    </ul>
  </div>
</div>
<script>
  let btnShow = document.querySelector('.popup-show');
  btnShow.onclick = function(e){
    this.parentElement.querySelector('.popup-menu-content').style.display = 'block';
    e.stopPropagation();
  }
  let contents = btnShow.parentElement.querySelectorAll('.popup-menu-content > ul > *');
  let contentWrapper = btnShow.parentElement.querySelector('.popup-menu-content > ul');
  for(let content of contents){
    let li = document.createElement('li');
    li.classList.add('list-group-item');
    li.appendChild(content);
    contentWrapper.appendChild(li);
  }
  document.body.onclick = () => document.querySelector('.popup-menu-content').style.display = 'none';
</script>