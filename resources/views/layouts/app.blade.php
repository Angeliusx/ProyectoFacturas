<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" type="image/x-icon"/>

    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('vendor/stisla-2.2.0/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/stisla-2.2.0/assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('vendor/stisla-2.2.0/assets/modules/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/stisla-2.2.0/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/stisla-2.2.0/assets/css/components.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="./output.css" rel="stylesheet">
    

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('vendor/stisla-2.2.0/assets/modules/jquery.min.js') }}" defer></script>
    <script src="{{ asset('vendor/stisla-2.2.0/assets/modules/popper.js') }}" defer></script>
    <script src="{{ asset('vendor/stisla-2.2.0/assets/modules/tooltip.js') }}" defer></script>
    <script src="{{ asset('vendor/stisla-2.2.0/assets/modules/bootstrap/js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('vendor/stisla-2.2.0/assets/modules/nicescroll/jquery.nicescroll.min.js') }}" defer></script>
    <script src="{{ asset('vendor/stisla-2.2.0/assets/modules/moment.min.js') }}" defer></script>
    <script src="{{ asset('vendor/stisla-2.2.0/assets/js/stisla.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://kit.fontawesome.com/23e155bdfa.js" crossorigin="anonymous"></script>
    @livewireScripts
</head>

<body class="font-sans antialiased" x-data="{ darkMode: false }" x-init="
    if (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      localStorage.setItem('darkMode', JSON.stringify(true));
    }
    darkMode = JSON.parse(localStorage.getItem('darkMode'));
    $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" x-cloak>
    <div x-bind:class="{'dark' : darkMode === true}" id="app">  
        <div class="main-wrapper">
            @include('layouts.navigation')
            @include('layouts.side-navigation')
            
            <!-- Modal --> 
            <div class="main-content dark:bg-gray-800"> 
                <div  class="section">   
                    {{ $header ?? '' }}
                </div>
                <div class="section-body dark:bg-gray-800">
                    {{ $slot ?? '' }} 
                </div>  
            </div>
            

            <footer class="main-footer">
                <div class="footer-left">
                    <small class="text-muted">Equipo Procesal Fernandez ©2023</small>
                </div>
            </footer>

            <div id="layout-skins-changer">
                <div class="skin-btn bg-primary" data-toggle="tooltip" data-placement="left" data-original-title="Cambiar Color">
                    <i class="fas fa-palette animated"></i>
                </div>

                <a href="#" class="skin-btn bg-default" data-toggle="tooltip" data-placement="left" data-original-title="Azul" data-value="default">
                    <i class="fas fa-check ml-0"></i>
                </a>
                <a href="#" class="skin-btn skin-cyan" data-toggle="tooltip" data-placement="left" data-original-title="Cyan" data-value="cyan">
                    <i class="ml-0"></i>
                </a>
                <a href="#" class="skin-btn skin-green" data-toggle="tooltip" data-placement="left" data-original-title="Verde" data-value="green">
                    <i class="ml-0"></i>
                </a>
                <a href="#" class="skin-btn skin-orange" data-toggle="tooltip" data-placement="left" data-original-title="Naranja" data-value="orange">
                    <i class="ml-0"></i>
                </a>
                <a href="#" class="skin-btn skin-red" data-toggle="tooltip" data-placement="left" data-original-title="Rojo" data-value="red">
                    <i class="ml-0"></i>
                </a>
                <a href="#" class="skin-btn skin-grey" data-toggle="tooltip" data-placement="left" data-original-title="Gris" data-value="grey">
                    <i class="ml-0"></i>
                </a>
                <a href="#" class="skin-btn skin-dark" data-toggle="tooltip" data-placement="left" data-original-title="Negro" data-value="dark">
                    <i class="ml-0"></i>
                </a>
            <div>

        </div>
        <script src="{{ asset('vendor/stisla-2.2.0/assets/js/scripts.js') }}" defer></script>
    </div>
    @stack('scripts')
    @livewireScripts
</body>
</html>


