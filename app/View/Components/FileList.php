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
      foreach(scandir($this->path) as $file){
        $icon;
        if(is_file($this->path . $file))
          $icon = 'fa-regular fa-file';
        else
          $icon = 'fa-regular fa-folder';
        $this->files[$file] = $icon;
      }
    }

  }

  public function render(): View|Closure|string{
    return view('components.file-list');
  }
}
