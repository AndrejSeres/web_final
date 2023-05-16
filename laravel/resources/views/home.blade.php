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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

