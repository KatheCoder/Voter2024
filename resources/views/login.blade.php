@extends('layouts.base')
@push('head')
    <style>
        body {
            background-color: #006600; /* ANC green */
            background-image: url('{{ asset('images/anc.png') }}'); /* ANC flag */
            background-size: cover;
            background-position: center;
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;

        }
    </style>
@endpush

@section('content')
    <section class="py-3 py-md-5 py-xl-8" style="background-color: #ffcb03ab;">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-12 col-md-6 col-xl-7">
                    <div class="d-flex justify-content-center">
                        <div class="col-12 col-xl-9 text-center"> <!-- Added text-center class -->
                            <img class="img-fluid rounded mb-4" loading="lazy" src="{{ asset('images/anc-logo.png') }}" width="245" height="80" alt="ANC Logo">
                            <hr class="border-primary-subtle mb-4">
                            <h2 class="h1 mb-4">Dashboard</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-5">
                    <div class="card border-0 rounded-4" style="background-color: rgba(0, 0, 0, 0.6);">
                        @if($errors->has('loginError'))
                            <div class="alert alert-danger" role="alert">
                                {{ $errors->first('loginError') }}
                            </div>
                        @endif
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <h3 class="text-white text-center">Login</h3> <!-- Centered Login text -->
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                                            <label for="email" class="form-label">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-outline-light btn-lg" type="submit">Log in</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                                        <a href="#!" class="text-white">Forgot password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('images/img.png') }}" alt="Logo" class="position-absolute bottom-0 start-0 m-3" width="100" height="100">
    </section>

@endsection
