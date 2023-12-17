<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wedding Organizer Schedule | {{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/ico.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    @vite('/resources/js/app.js')
    @if (auth()->user())
        <meta name="username" content="{{ auth()->user()->username }}">
    @endif
    @isset($head)
        {{ $head }}
    @endisset
</head>

<body {{ $attributes->class(['hold-transition']) }}>
    {{ $slot }}
</body>

</html>
