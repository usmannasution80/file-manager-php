// definition of setFileList is inside FileList component
var setFileList = () => 1;

function getElementUpTo(currentElement, searchParent){
  let attributeKey, attributeValue;
  attributeValue = searchParent.replace(/^[^\[]*\[?|\][^\]]*$|'|"/g, '');
  [attributeKey, attributeValue] = attributeValue.split('=');
  searchParent = searchParent.replace(/\[.*/, '').toLowerCase();
  while(true){
    currentElement = currentElement.parentNode;
    if(!currentElement.tagName)
      return null;
    if(searchParent)
      if(searchParent !== currentElement.tagName.toLowerCase())
        continue;
      else if(!attributeKey)
        break;
    if(attributeKey === 'class')
      if(currentElement.classList.contains(attributeValue))
        break;
    if(currentElement.getAttribute(attributeKey) === attributeValue)
      break;
  }
  return currentElement;
}

window.onload = () => {
  document.body.addEventListener('click', e => {
    let popupMenuAll = document.getElementsByClassName('popup-menu-content');
    let btnShow = getElementUpTo(e.target, '[class="popup-show"]');
    if(!btnShow)
      btnShow = e.target.querySelector('.popup-show');
    if(!btnShow){
      for(let popupMenu of popupMenuAll)
        popupMenu.style.display = 'none';
      return;
    }
    let viewportWidth = window.innerWidth;
    let popupMenuContent = btnShow.parentElement.querySelector('.popup-menu-content');
    let currentDisplay = popupMenuContent.style.display;
    for(let popupMenu of popupMenuAll)
      popupMenu.style.display = 'none';
    if(currentDisplay === 'block')
      return;
    popupMenuContent.style.display = 'block';
    let rect = popupMenuContent.getBoundingClientRect();
    let widthOnViewport = rect.left + popupMenuContent.offsetWidth;
    if(widthOnViewport > viewportWidth)
      popupMenuContent.style.right = 0;
    e.stopPropagation();
    if(btnShow.parentElement.querySelector('.list-group-item'))
      return;
    let contents = btnShow.parentElement.querySelectorAll('.popup-menu-content > ul > *');
    let contentWrapper = btnShow.parentElement.querySelector('.popup-menu-content > ul');
    for(let content of contents){
      let li = document.createElement('li');
      li.classList.add('list-group-item');
      li.appendChild(content);
      contentWrapper.appendChild(li);
    }
  });
};

strg('files', {});
strg('settings', {});