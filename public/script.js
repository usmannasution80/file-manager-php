// definition of setFileList is inside FileList component
var setFileList = () => 1;

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
}

function getElementUpTo(currentElement, searchParent){
  let attributeKey, attributeValue;
  attributeValue = searchParent.replace(/^[^\[]*\[|\][^\]]*$|'|"/g, '');
  [attributeKey, attributeValue] = attributeValue.split('=');
  searchParent = searchParent.replace(/\[.*/, '').toLowerCase();
  while(true){
    currentElement = currentElement.parentNode;
    if(!currentElement.tagName)
      return null;
    if(searchParent)
      if(searchParent !== currentElement.tagName.toLowerCase())
        continue;
    if(attributeKey === 'class')
      if(currentElement.classList.contains(attributeValue))
        break;
    if(currentElement.getAttribute(attributeKey) === attributeValue)
      break;
  }
  return currentElement;
}

function sortFileList(files){
  if(!files){
    let {path, files} = strg('files');
    let dirs = [];
    let fls = [];
    for(let file of files){
      if(file.type === 'directory')
        dirs.push(file);
      else
        fls.push(file)
    }
    return strg('files', {path, files : [...sortFileList(dirs), ...sortFileList(fls)]});
  }
  const compareByName = (file1, file2) => {
    let filename1 = file2.filename.toLowerCase();
    let filename2 = file2.filename.toLowerCase();
    for(let i=0;i<filename1.length;i++){
      if(i >= filename2.length)
        return [file1, file2];
      if(filename1.charCodeAt(i) < filename2.charCodeAt(i))
        return [file1, file2];
      if(filename1.charCodeAt(i) > filename2.charCodeAt(i))
        return [file2, file1, true];
    }
    return [file1, file2];
  };
  const compareByDate = (file1, file2) => {
    if(file1.date > file2.date)
      return [file2, file1, true];
    return [file1, file2];
  };
  const compare = (file1, file2) => {
    switch(strg('sort-by')){
      case 'date':
        return compareByDate(file1, file2);
      default:
        return compareByName(file1, file2);
    }
  };
  let isThereChange = true;
  let length = files.length;
  while(isThereChange){
    isThereChange = false;
    for(let i=1; i<length; i++)
      [files[i-1], files[i], isThereChange = isThereChange] = compare(files[i-1], files[i]);
  }
  if(strg('sorting-order') === 'desc'){
    let tempFiles = [...files];
    for(let i=tempFiles.length-1,j=0;i>=0;i--)
      files[j++] = tempFiles[i];
  }
  return files;
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