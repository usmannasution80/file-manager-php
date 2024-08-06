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
      $this->src = $_SERVER['REQUEST_URI'] . '?view';
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
    $files = (new FileList($path))->files;
    $i = 0;
    foreach($files as $file){
      if($file['filename'] === $this->file_info['filename']){
        if(isset($files[$i-1])){
          if((new ViewFile($path . $files[$i-1]['filename'], false))->is_file)
            $this->prev = $path . urlencode($files[$i-1]['filename']);
        }
        if(isset($files[$i+1])){
          if((new ViewFile($path . $files[$i+1]['filename'], false))->is_file)
            $this->next = $path . urlencode($files[$i+1]['filename']);
        }
        break;
      }
      $i++;
    }
  }

}
