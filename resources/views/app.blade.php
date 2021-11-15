<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <script src="//cdn.8thwall.com/web/aframe/8frame-1.2.0.min.js"></script>
        <script src="//cdn.8thwall.com/web/xrextras/xrextras.js"></script>
        <script async src="//apps.8thwall.com/xrweb?appKey=kpUcLoAOCG5h94F5XcfF0J6xziZqTN2tiq7mRDyxdS29lFRz0vcsD3s8H2pEXDtwXKufBV"></script>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
