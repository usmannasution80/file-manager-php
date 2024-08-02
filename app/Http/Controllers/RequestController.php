<?php

namespace App\Http\Controllers;

use App\View\Components\ViewFile;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\File;
use App\Http\Requests\Auth\LoginRequest;

class RequestController extends Controller{
  public function get(){
    if(isset($_GET['view']) && Auth::check())
      return (new ViewFile())->view_file();
    if(Auth::check())
      return view('components.main');
    return view('auth.login');
  }
  public function post(){
    if(!Auth::check())
      return (new AuthenticatedSessionController())->store(LoginRequest::createFrom(request()));
    if(isset($_GET['logout']))
      return (new AuthenticatedSessionController())->destroy(request());
    if(isset($_GET['delete']))
      return (new File())->delete();
    if(isset($_GET['rename']))
      return (new File())->rename($_POST['filename']);
    return (new File())->upload();
  }
}
