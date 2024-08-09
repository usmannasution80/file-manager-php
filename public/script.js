function strg(key, value){
  if(arguments.length === 2){
    for(let i=0;i<localStorage.length;i++){
      if(localStorage.key(i).replace(/_[^_]+$/, '') === key)
        localStorage.removeItem(localStorage.key(i));
    }
    let savedValue;
    if(typeof value === 'object' && value !== null)
      savedValue = JSON.stringify(value);
    else
      savedValue = String(value);
    localStorage.setItem(
      key + '_' + typeof value,
      savedValue
    );
    return value;
  }
  let searchKey = key;
  for(let i=0;i<localStorage.length;i++){
    let key = localStorage.key(i);
    if(key.replace(/_[^_]+$/, '') === searchKey){
      let [type] = key.split('_').slice(-1);
      let value = localStorage.getItem(key);
      switch(type){
        case 'number':
          return Number(value);
        case 'boolean':
          return Boolean(value);
        case 'bigint':
          return BigInt(value);
        case 'symbol':
          return Symbol(value);
        case 'string':
          return String(value);
        case 'undefined':
          return undefined;
        default:
          if(value === 'null')
            return null;
          return JSON.parse(value);
      }
    }
  }
};

function getElementUpTo(currentElement, searchParent){
  do currentElement = currentElement.parentNode;
  while(currentElement.tagName.toLowerCase() !== searchParent.toLowerCase());
  return currentElement;
}

window.onload = () => {
  (() => {
    const hideAllPopupMenu = () => {
      let popupMenuAll = document.getElementsByClassName('popup-menu-content');
      for(let popupMenu of popupMenuAll)
        popupMenu.style.display = 'none';
    };
    let viewportWidth = window.innerWidth;
    let btnShows = document.querySelectorAll('.popup-show');
    if(!btnShows.length)
      return;
    for(let btnShow of btnShows){
      btnShow.onclick = function(e){
        hideAllPopupMenu();
        let popupMenuContent = this.parentElement.querySelector('.popup-menu-content');
        if(popupMenuContent.style.display === 'block')
          return popupMenuContent.style.display = 'none';
        popupMenuContent.style.display = 'block';
        let rect = popupMenuContent.getBoundingClientRect();
        let widthOnViewport = rect.left + popupMenuContent.offsetWidth;
        if(widthOnViewport > viewportWidth)
          popupMenuContent.style.right = 0;
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
    document.body.addEventListener('click', hideAllPopupMenu);
  })();
};