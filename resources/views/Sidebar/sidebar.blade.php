<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!---CSS-->
    <link href="{{ asset('css/Sidebar/sidebar.css') }}" rel="stylesheet" />

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>

    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="{{ asset('assets/logo2.png') }}" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name">LS Technologies</span>
                    <span class="profession">Suporte</span>
                </div>

                <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <a id="UserName" class="text" href="#">
                    {{ Auth::user()->name }}
                </a>
                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="search" placeholder="Procurar...">
                </li>
                <ul class="menu-links">
                    @if (Route::has('central'))
                        <li class="nav-link @if (request()->is('central')) active @endif">
                            <a href="{{ route('central') }}">
                                <i class='bx bx-phone icon'></i>
                                <span class="text nav-text">Central</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::has('colaboradores'))
                        <li class="nav-link @if (request()->is('colaboradores')) active @endif">
                            <a href="{{ route('colaboradores') }}">
                                <i class='bx bx-user icon'></i>
                                <span class="text nav-text">Colaboradores</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::has('servidores'))
                        <li class="nav-link @if (request()->is('servidores')) active @endif">
                            <a href="#">
                                <i class='bx bxs-server icon'></i>
                                <span class="text nav-text">Servidores</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::has('relatorios'))
                        <li class="nav-link @if (request()->is('relatorios')) active @endif">
                            <a href="{{ route('relatorios') }}">
                                <i class='bx bx-pie-chart-alt icon'></i>
                                <span class="text nav-text">Relatórios</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="bottom-content">
                <li class="">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf

                        </form>
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">{{ __('Logout') }}</span>
                    </a>
                </li>


                <li class="mode">
                    <div class="moon-sun">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark Mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
        </div>

    </nav>

    <section class="home">
        <div class="content">
            @yield('content')
        </div>
    </section>
    <script src="{{ asset('js/Sidebar/sidebar.js') }}"></script>
</body>

</html>