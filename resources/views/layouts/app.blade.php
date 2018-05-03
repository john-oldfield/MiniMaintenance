<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{{asset('/img/favicon-32x32.png')}}}" sizes="32x32" />
        <link rel="icon" type="image/png" href="{{{asset('/img/favicon-16x16.png')}}}" sizes="16x16" />
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
        <title>{{config('app.name', 'MiniMaintenance.xyz')}}</title>
    </head>
    <body>
        @include('inc.dashboard')
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWflVUCi4rFq7skjfPNbbwa6GXtGp5MYY&callback=init"
  type="text/javascript"></script>
        <script src="js/custom.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script> 
    </body>
</html>