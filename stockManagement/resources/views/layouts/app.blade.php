<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Management System - @yield('page_title', 'Dashboard')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        .input-style {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        .btn-style {
            background-color: #041727;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-style:hover {
            background-color: #45a049;
        }
        .sidenav-link {
            display: block;
            padding: 10px 16px;
            color: #e2e8f0;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .sidenav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidenav-link.active {
            background-color: rgb(255, 255, 255);
           
            text:black
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <div class="fixed left-0 top-0 h-full z-20">
            @include('layouts.sidenav')
        </div>
        <div class="flex-1 ml-56 min-h-screen">
            <div class="fixed left-56 right-0 top-0 z-10">
                @include('layouts.header')
            </div>
            <main class="pt-24 px-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>