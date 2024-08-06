<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\View\Components\ViewFile;

class FileList extends ViewFile {

  public $files = [];
  public $file_list_id = null;

  public function __construct($path = null){

    parent::__construct($path);

    if(!$this->is_file){

      $this->file_list_id = generate_random_id();

      $files = scandir($this->path);

      foreach($files as $file){
        array_push($this->files, [
          'filename' => $file,
          'type' => preg_replace('/\\/.*$/i', '', mime_content_type($this->path . '/' . $file))
        ]);
      }

    }

  }

  public function render(): View|Closure|string{
    return view('components.file-list');
  }
}
