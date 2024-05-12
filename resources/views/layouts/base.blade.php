<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="{{ asset('/css/app.css') }}" type="text/css" rel="stylesheet">
    @stack('head')
</head>
<body>
<div class="container-fluid">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @yield('content')

</div>
<script src="{{ asset('/js/app.js')}}"></script>
@stack('scripts')
</body>
</html>
