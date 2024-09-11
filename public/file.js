const file = {


  currentPath:function currentPath(){
    let path = window.location.pathname.replace(/\/+/g, '/');
    path = path.replace(/\/+$/, '');
    return path;
  },


  parentDirectory:function currentDirectory(){
    return this.currentPath().replace(/\/[^\/]*$/, '');
  },


  currentDirectoryList:async function currentDirectoryList(){
    let {path, files} = strg('files') || {};
    if(!files || path !== this.currentPath()){
      let result;
      try {
        result = await axios.get(this.currentPath() + '?list');
        files = strg('files', {
          path : this.currentPath(),
          files : result.data
        }).files;
      }catch(error){
        if(error.code !== 400){
          console.log(error);
          files = null;
        }
        if(path === this.parentDirectory())
          return files;
        try {
          result = await axios.get(this.parentDirectory() + '?list');
          files = strg('files', {
            path : this.parentDirectory(),
            files : result.data
          }).files;
        }catch(error){
          console.log(error);
          files = null;
        }
      }
    }
    if(files)
      return this.sortFileList();
    return files;
  },


  sortFileList : function sortFileList(files){
    if(arguments.length === 0){
      let {path, files} = strg('files');
      let dirs = [];
      let fls = [];
      if(!files)
        return [];
      for(let file of files){
        if(file.type === 'directory')
          dirs.push(file);
        else
          fls.push(file);
      }
      return strg('files', {path, files : [...sortFileList(dirs), ...sortFileList(fls)]}).files;
    }
    const compareByName = (file1, file2) => {
      let filename1 = file1.filename.toLowerCase();
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


};