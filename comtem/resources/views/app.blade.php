<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="1000">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Daniil Sonufrijuks">
    <meta name="description" content="Buy and sell laptops, PCs, and components through live auctions. AI-powered deals on new and used computer hardware.">
    <!-- Keywords -->
    <meta name="keywords" content="laptop auction, PC auction, computer components, buy laptop online, sell PC parts, used hardware, GPU auction, CPU deals, computer parts shop, AI pricing">

    <!-- Open Graph (Facebook, LinkedIn, Discord previews) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Your Shop Name | Laptop, PC & Component Auctions">
    <meta property="og:description" content="Buy and sell laptops, PCs, and components through live auctions. AI-powered deals on new and used computer hardware.">
    <meta property="og:url" content="https://comtem.rvtdev.tech/">

{{--    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />--}}


    <title>COMTEM</title>

    <link rel="icon" type="image/png" href="{{ asset('m.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
{{--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
{{--    <script src="../js/Search/Search.js"></script>--}}

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
