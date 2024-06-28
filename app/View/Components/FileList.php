<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileList extends Component{

  public $files = [];

  public function __construct(){
    $path = env('ROOT', '/');
    $path = preg_replace('/\\/*$/i', '', $path);
    $path = $path . preg_replace('/\\/*$/i', '/', $_SERVER['REQUEST_URI']);
    if(is_dir($path)){
      foreach(scandir($path) as $file){
        $icon;
        if(is_file($path . $file))
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
