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
    $files = scandir($this->path);
    foreach($files as $file){
      array_push($this->files, [
        'filename' => $file,
        'type' => preg_replace('/\\/.*$/i', '', mime_content_type($this->path . '/' . $file))
      ]);
    }
  }

  public function as_json(){
    return json_encode($this->files);
  }

  public function render(): View|Closure|string{
    return view('components.file-list');
  }
}
