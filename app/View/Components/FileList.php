<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\View\Components\ViewFile;

class FileList extends ViewFile {

  public $files = [];

  public function __construct(){

    parent::__construct();

    if(!$this->is_file){

      $files = $this->sort_files(scandir($this->path));

      foreach($files as $file){

        $icon;

        switch(preg_replace('/\\/.*$/i', '', mime_content_type($this->path . '/' . $file))){
          case 'directory':
            $icon = 'fa-regular fa-folder';
            break;
          case 'audio' :
            $icon = 'fa-solid fa-file-audio';
            break;
          case 'video':
            $icon = 'fa-solid fa-file-video';
            break;
          case 'image':
            $icon = 'fa-solid fa-file-image';
            break;
          default:
            $icon = 'fa-regular fa-file';
        }

        $this->files[$file] = $icon;
      }

    }

  }

  protected function sort_files($files){
    $dirs = [];
    $not_dirs = [];
    foreach($files as $file){
      if(is_dir($this->path . '/' . $file))
        array_push($dirs, $file);
      else
        array_push($not_dirs, $file);
    }
    return array_merge($dirs, $not_dirs);
  }

  public function render(): View|Closure|string{
    return view('components.file-list');
  }
}
