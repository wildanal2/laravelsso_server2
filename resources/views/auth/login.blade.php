@extends('layouts.app-nohead')

@section('title', 'Login')
@section('body-type', 'bg-login')

@section('content')
<main class="authentication-content mt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-4 mx-auto">
                <div class="card shadow rounded-5 overflow-hidden">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title">Sign In</h5>
                        <p class="card-text mb-5">See your growth and get consulting support!</p>
                        <form class="form-body" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row g-3">
                            <div class="col-12">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="ms-auto position-relative">
                                    <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-envelope-fill"></i></div>
                                    <input type="email" class="form-control radius-0 ps-5 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Enter Password</label>
                                <div class="ms-auto position-relative">
                                    <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                                    <input type="password" class="form-control radius-0 ps-5 @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password" placeholder="Enter Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">Forgot Password ?</a>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                <button type="submit" class="btn btn-primary radius-0">Sign In</button>
                                </div>
                            </div>
                            <div class="col-12 d-none">
                                <p class="mb-0">Don't have an account yet? <a href="authentication-signup.html">Sign up here</a></p>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
