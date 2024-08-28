<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class File extends Controller{
  private $path;
  public function __construct(){
    $this->path = preg_replace('/\\?.*$/', '', $_SERVER['REQUEST_URI']);
    $this->path = env('ROOT') . preg_replace('/\\/+$/', '', $this->path);
    $this->path = preg_replace('/\\/+/', '/', $this->path);
    $this->path = urldecode($this->path);
  }
  public function upload(){
    if(!isset($_FILES['file']))
      abort(400);
    $files = scandir($this->path);
    $this->path .= '/' . $_FILES['file']['name'];
    $i = 1;
    $extension = pathinfo($this->path, PATHINFO_EXTENSION);
    while(file_exists($this->path))
      $this->path = preg_replace('/(\\(\\d+\\))?\\.'.$extension.'$/i', ' (' . $i++ . ')', $this->path) . '.' . $extension;
    if(move_uploaded_file($_FILES['file']['tmp_name'], $this->path))
      return response()->noContent();
    else
      abort(500);
  }
  public function delete(){
    set_error_handler(function(){});
    $scanAndDelete = function($path) use (&$scanAndDelete) {
      if(unlink($path) || rmdir($path))
        return;
      $files = scandir($path);
      foreach($files as $file)
        $scanAndDelete($path . '/' . $file);
      rmdir($path);
    };
    $scanAndDelete($this->path);
    restore_error_handler();
    return response()->noContent();
  }
  public function rename($new_name){
    $prev_name = preg_replace('/^.*\\/+/i', '', $this->path);
    $this->path = preg_replace("/$prev_name$/", '', $this->path);
    $this->path .= '/';
    rename($this->path . $prev_name, $this->path . $new_name);
    return urlencode($new_name);
  }
  public function newFolder($folderName){
    $this->path .= '/';
    if(file_exists($this->path . $folderName))
      return abort(400, $folderName . ' already exists');
    if(mkdir($this->path . $folderName))
      return response()->noContent();
    return abort(500);
  }
}
