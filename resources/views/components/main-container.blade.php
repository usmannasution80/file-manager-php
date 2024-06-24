<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width"/>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{asset('fontawesome/css/fontawesome.css')}}"/>
    <link rel="stylesheet" href="{{asset('fontawesome/css/brands.css')}}"/>
    <link rel="stylesheet" href="{{asset('fontawesome/css/solid.css')}}"/>
  </head>
  <body class="bg-secondary">
    {{ $slot }}
    <script src="{{asset('bootstrap/js/bootstrap.js')}}"></script>
  </body>
</html>