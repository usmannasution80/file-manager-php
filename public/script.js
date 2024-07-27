(() => {
  let btnShows = document.querySelectorAll('.popup-show');
  if(!btnShows.length)
    return;
  for(let btnShow of btnShows){
    btnShow.onclick = function(e){
      this.parentElement.querySelector('.popup-menu-content').style.display = 'block';
      e.stopPropagation();
    }
    contents = btnShow.parentElement.querySelectorAll('.popup-menu-content > ul > *');
    let contentWrapper = btnShow.parentElement.querySelector('.popup-menu-content > ul');
    for(let content of contents){
      let li = document.createElement('li');
      li.classList.add('list-group-item');
      li.appendChild(content);
      contentWrapper.appendChild(li);
    }
  }
  document.body.onclick = () => document.querySelector('.popup-menu-content').style.display = 'none';
})();