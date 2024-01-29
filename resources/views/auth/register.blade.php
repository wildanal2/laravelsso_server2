@extends('layouts.app-nohead')

@section('title', 'Register')
@section('body-type', 'bg-register')

@section('content')

<!--start content-->
<main class="authentication-content mt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-4 mx-auto">
                <div class="card shadow rounded-5 overflow-hidden">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title">Sign Up</h5>
                        <p class="card-text mb-5">See your growth and get consulting support!</p>
                        <form class="form-body" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12 ">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-person-circle"></i></div>
                                        <input id="name" type="text" class="form-control radius-0 ps-5 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-envelope-fill"></i></div>
                                        <input type="email" class="form-control radius-0 ps-5 @error('email') is-invalid @enderror" name="email" placeholder="Email Address" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                                        <input type="password" class="form-control radius-0 ps-5 @error('password') is-invalid @enderror" id="password" placeholder="Enter Password" name="password" required autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                                        <input type="password" class="form-control radius-0 ps-5 @error('password-confirm') is-invalid @enderror" id="" placeholder="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">I Agree to the Trems &
                                            Conditions</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-warning radius-0">Sign Up</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p class="mb-0">Already have an account? <a href="authentication-signin.html">Sign up here</a></p>
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