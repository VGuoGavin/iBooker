<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="The room booking solution for event organizers">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#fff"/>
    <title>Booker</title>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <style>
        #loader {
            transition: all 0.3s ease-in-out;
            opacity: 1;
            visibility: visible;
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            z-index: 90000;
            margin-top: 0;
        }

        #loader.fadeOut {
            opacity: 0;
            visibility: none;
        }

        .spinner {
            width: 40px;
            height: 40px;
            position: absolute;
            top: calc(50% - 20px);
            left: calc(50% - 20px);
            background-color: #333;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
            animation: sk-scaleout 1.0s infinite ease-in-out;
        }

        @-webkit-keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0)
            }

            100% {
                -webkit-transform: scale(1.0);
                opacity: 0;
            }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            100% {
                -webkit-transform: scale(1.0);
                transform: scale(1.0);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            setTimeout(() => {
                loader.classList.add('fadeOut');
            }, 300);
            setTimeout(() => {
                loader.parentNode.removeChild(loader);
            }, 600);
        });
    </script>
    <div id='loader'>
        <div class="spinner"></div>
    </div>

    @include('layouts.front.nav')
    <main class="main-body">
        @yield('content')
    </main>
    @include('layouts.front.footer')
    <script src="{{ mix('js/app.js' )}}"></script>
    @yield('custom-script')
</body>
</html>
