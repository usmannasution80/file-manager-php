<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ViewFile extends Component{

  public $is_file = false;
  public $path = '';
  public $src = null;
  public $type = null;

  public function __construct(){

    $this->set_path();
    $this->is_file = !is_dir($this->path);

    if($this->is_file){
      $this->src = $_SERVER['REQUEST_URI'] . '?view';
      $this->type = mime_content_type($this->path);
    }

  }

  public function set_path(){
    $this->path = env('ROOT', '/');
    $this->path = preg_replace('/\\/*$/i', '', $this->path);
    $this->path = $this->path . preg_replace('/\\/*$/i', '', $_SERVER['REQUEST_URI']);
    $this->path = preg_replace('/\\?.*$/i', '', $this->path);
    $this->path = urldecode($this->path);
  }

  public function render(): View|Closure|string{
    return view('components.view-file');
  }

  public function view_file(){
    header('Content-Type : ' . $this->type);
    header('Content-Dispotition: inline');
    return readfile($this->path);
  }

}
