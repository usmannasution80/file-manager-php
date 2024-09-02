<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width"/>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{asset('fontawesome/css/fontawesome.css')}}"/>
    <link rel="stylesheet" href="{{asset('fontawesome/css/brands.css')}}"/>
    <link rel="stylesheet" href="{{asset('fontawesome/css/solid.css')}}"/>
    <link rel="stylesheet" href="{{asset('style.css')}}"/>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.js')}}"></script>
    <script src="{{asset('script.js')}}"></script>
    <script src="{{asset('flags.js')}}"></script>
  </head>
  <body class="bg-secondary">
    {{ $slot }}
  </body>
</html>