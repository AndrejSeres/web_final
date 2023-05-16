@extends('layouts.app')

@section('content')
    <header class="site-header">
        @include('layouts.nav')
    </header>
    <main>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mt-md-5 mb-md-5">
                        <h3 class="mb-5 mt-5">{{ __('home.welcome') }}</h3>
                    </div>
                </div>
            </div>
            </div>
        </section>
    </main>
    <footer>

    </footer>
</html>