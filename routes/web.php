<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Requests\Auth\LoginRequest;
use App\View\Components\ViewFile;
use App\Http\Controllers\File;
use Illuminate\Foundation\Http\FormRequest;

Route::get('/{path}', function () {
  if(isset($_GET['view']) && Auth::check())
    return (new ViewFile())->view_file();
  if(Auth::check())
    return view('components.main');
  return view('auth.login');
})->where('path', '.*');

Route::post('/{path}', function(){
  if(!Auth::check())
    return (new AuthenticatedSessionController())->store(LoginRequest::createFrom(request()));
  return (new File())->upload();
})->where('path', '.*');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
