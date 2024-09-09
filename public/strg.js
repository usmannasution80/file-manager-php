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
    strg.onUpdate();
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
strg.updateListeners = [];
strg.addUpdateListener = f => strg.onChangeListeners.push(f);
strg.onUpdate = () => {
  for(let f of strg.updateListeners)
    f();
};