<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\View\Components\FileList;

class ViewFile extends Component{

  public $is_file = false;
  public $path = '';
  public $src = null;
  public $file_info = null;
  public $next = null;
  public $prev = null;

  public function __construct($path = null, $set_prev_next = true){

    $this->set_path($path);
    $this->is_file = !is_dir($this->path);

    if($this->is_file){
      $this->src = preg_replace('/\\?[^\\?]+$/', '?view', $_SERVER['REQUEST_URI']);
      $this->file_info = [];
      $this->file_info['filename'] = preg_replace('/^.*\\//i', '', $this->path);
      $this->file_info['type'] = mime_content_type($this->path);
      $this->file_info['size'] = round(filesize($this->path) / 1024 / 1024, 2) . 'MB';
      if($set_prev_next)
        $this->set_prev_next();
    }

  }

  public function set_path($path){
    $this->path = env('ROOT', '/');
    $this->path = preg_replace('/\\/*$/i', '', $this->path);
    $this->path = $this->path . preg_replace('/\\/*$/i', '', $path ? $path : $_SERVER['REQUEST_URI']);
    $this->path = preg_replace('/\\?.*$/i', '', $this->path);
    $this->path = urldecode($this->path);
  }

  public function render(): View|Closure|string{
    return view('components.view-file');
  }

  public function view_file(){
    header('Content-Dispotition: inline');
    header('Content-Type: ' . $this->file_info['type']);
    return readfile($this->path);
  }

  public function set_prev_next(){
    $path = preg_replace('/[^\\/]+$/', '', $_SERVER['REQUEST_URI']);
    if(isset($_GET['prev']))
      $this->prev = $path . $_GET['prev'];
    if(isset($_GET['next']))
      $this->next = $path . $_GET['next'];
    $files = (new FileList($path))->files;
  }

}
