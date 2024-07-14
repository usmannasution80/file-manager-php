<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class File extends Controller{
  public function upload(){
    if(!isset($_FILES['file']))
      abort(400);
    $path = env('ROOT') . $_SERVER['REQUEST_URI'] . '/';
    $path = preg_replace('/\\/+/i', '/', $path);
    $path = urldecode($path);
    $files = scandir($path);
    $path .= $_FILES['file']['name'];
    $i = 1;
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    while(file_exists($path))
      $path = preg_replace('/(\\(\\d+\\))?\\.'.$extension.'$/i', ' (' . $i++ . ')', $path) . '.' . $extension;
    if(move_uploaded_file($_FILES['file']['tmp_name'], $path))
      return response()->noContent();
    else
      abort(500);
  }
}
