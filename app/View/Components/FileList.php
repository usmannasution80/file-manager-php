<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\View\Components\ViewFile;

class FileList extends ViewFile {

  public $files = [];

  public function __construct($path = null){

    parent::__construct($path);

    if(!$this->is_file)
      $this->set_files();
  }

  public function set_files(){
    set_error_handler(function(){});
    $files = scandir($this->path);
    foreach($files as $file){
      $type = mime_content_type($this->path . '/' . $file);
      if(!$type)
        $type = '?';
      array_push($this->files, [
        'filename' => $file,
        'type' => $type,
        'date' => filemtime($this->path . '/' . $file)
      ]);
    }
    restore_error_handler();
  }

  public function as_json(){
    return json_encode($this->files);
  }

  public function render(): View|Closure|string{
    return view('components.file-list');
  }
}
