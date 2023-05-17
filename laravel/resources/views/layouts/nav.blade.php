<nav class="navbar navbar-expand-lg blue-header">
    <div class="container-fluid p-0">
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('menu.toggle') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('show.home') }}">{{ __('navbar.home') }}</a>
                    <div class="nav-line"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('show.welcome') }}">{{ __('navbar.welcome') }}</a>
                    <div class="nav-line"></div>
                </li>
                <li class="nav-item d-inline-flex align-self-center">
                    <a class="nav-link pr-1 pl-0 @if(\Lang::locale() == 'sk') font-weight-bolder @endif"
                       href="{{ url('locale/sk') }}">{{ __('navbar.lang_sk') }}</a>
                    <span class="nav-link pr-1 pl-0 pb-1">|</span>
                    <a class="nav-link pr-1 pl-0 @if(\Lang::locale() == 'en') font-weight-bolder @endif"
                       href="{{ url('locale/en') }}">{{ __('navbar.lang_en') }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

