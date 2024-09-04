<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\View\Components\FileList;

class ViewFile extends Component{

  public $is_file;
  public $path;
  public $fileInfoTable;
  public $next;
  public $prev;
  public $mediaId;
  public $renameInput;
  public $renameButton;
  public $renameModal;
  public $deleteModal;
  public $deleteButton;
  public $downloadButton;
  public $filetype;

  public function __construct($path = null, $set_prev_next = true){

    $this->set_path($path);
    $this->is_file = !is_dir($this->path);

    if($this->is_file){
      $this->next = generate_random_id();
      $this->prev = generate_random_id();
      $this->mediaId = generate_random_id();
      $this->renameInput = generate_random_id();
      $this->renameButton = generate_random_id();
      $this->renameModal = generate_random_id();
      $this->deleteButton = generate_random_id();
      $this->deleteModal = generate_random_id();
      $this->fileInfoTable = generate_random_id();
      $this->downloadButton = generate_random_id();
      $this->filetype = mime_content_type($this->path);
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
    header('Content-Transfer-Encoding: binary');
    header('Content-Type: ' . $this->filetype);
    header('Content-Length: '.filesize($this->path));
    header('Accept-Ranges: 0-' . (filesize($this->path) -1));
    header('Accept-Ranges: bytes');
    ob_clean();
    flush();
    $file = fopen($this->path, 'rb');
    while($chunk = fread($file, 8192)){
      echo $chunk;
      flush();
    }
    fclose($file);
  }

}
