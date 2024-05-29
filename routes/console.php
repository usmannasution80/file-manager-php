<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Artisan::command('inspire', function () {
  $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Artisan::command('create_admin {username} {email} {password}', function($username, $email, $password){
  $user = new User();
  $user->username = $username;
  $user->email = $email;
  $user->password = Hash::make($password);
  $user->save();
  echo 'New user created!';
});