
<nav class="main-nav">
    <div class="container">
        <a href="{{ route('dashboard.index') }}" class="logo">
            <img src="/static/img/logo-big.svg" height="30" class="d-inline-block align-top" alt="room booker logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks,#navbarLinks2" aria-controls="navbarLinks"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbarLinks" class="navbar-links left-links">
            <ul>
                <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Support') }}</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('faq') }}">FAQ</a>
                        <a class="dropdown-item" href="{{ route('contact') }}">{{ __('Contact us') }}</a>
                    </div>
                </li>
                <li role="separator" class="divider"></li>
            </ul>
        </div>
        <div id="navbarLinks2" class="navbar-links right-links">


            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li>
                        <a class="login" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>

                    <li>
                        @if (Route::has('register'))
                            <a class="dashboard-action" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <a class="dropdown-item"  href="{{ route('profile.edit') }}" >
                                {{ __('Profile') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>


        </div>
    </div>
</nav>
