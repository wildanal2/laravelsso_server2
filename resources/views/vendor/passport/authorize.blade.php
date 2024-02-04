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
                        <h5 class="card-title">Authorization Request</h5>
                        <p class="card-text mb-5"><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>
                        <!-- Scope List -->
                        @if (count($scopes) > 0)
                        <div class="scopes">
                            <p><strong>This application will be able to:</strong></p>

                            <ul>
                                @foreach ($scopes as $scope)
                                <li>{{ $scope->description }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <br>
                        <div class="buttons d-flex flex-row-reverse justify-content-evenly">
                            <!-- Authorize Button -->
                            <form method="post" action="{{ route('passport.authorizations.approve') }}">
                                @csrf

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                                <button type="submit" class="btn btn-success btn-approve">Authorize</button>
                            </form>

                            <!-- Cancel Button -->
                            <form method="post" action="{{ route('passport.authorizations.deny') }}">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                                <button class="btn btn-danger">Cancel</button>
                            </form>
                        </div> 
                        <hr>
                        <div class="row">
                            <div class="col-12" style="text-align: center; margin-top: 5px;">
                                <form method="get" action="{{ route('different-account') }}" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="current_url" value="{{ $request->fullUrl() }}">
                                    <button type="submit" class="btn btn- btn-block" style="width: auto;">Not {{ auth()->user()->name }} ? Login again</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection