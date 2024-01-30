@extends('layouts.app')
@section('title', ($client ? 'Update':'Create').' Modules')

@section('head')

@endsection

@section('content')
<div class="row">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('title')</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    @php
                    $segments = request()->segments();
                    $url = '';
                    @endphp

                    @foreach ($segments as $key => $segment)
                    @php
                    $url .= '/' . $segment;
                    @endphp

                    <li class="breadcrumb-item {{ $key === count($segments) - 1 ? 'active' : '' }}">
                        @if ($key === count($segments) - 1)
                        {{ ucfirst($segment) }}
                        @else
                        <a href="{{ url('').$url }}">{{ ucfirst($segment) }}</a>
                        @endif
                    </li>
                    @endforeach
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="lni lni-chevron-left-circle" style="margin-top: -16px;"></i> Back</a>

        </div>
    </div>
    <div class="col-xl-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center">
                            <h5 class="mb-0">Module {{ ($client ? 'Update':'Create') }}</h5>
                        </div>
                        <hr />
                        {{ csrf_field() }}
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-3 col-form-label">Application Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $client->name ?? '') }}" placeholder="Enter Application Name" autocomplete="nama-application" required>
                                @error('nama')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="redirect" class="col-sm-3 col-form-label">Redirect</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('redirect') is-invalid @enderror" id="redirect" name="redirect" value="{{ old('redirect', $client->redirect ?? '') }}" placeholder="Enter URI redirect (Callabck) Application">
                                @error('redirect')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        @if (isset($client))
                        <div class="row mb-3">
                            <label for="id" class="col-sm-3 col-form-label">Client ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('id') is-invalid @enderror" id="id" name="id" value="{{ old('id', $client->id ?? '') }}" disabled>
                                @error('id')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="secret" class="col-sm-3 col-form-label">Secret ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('secret') is-invalid @enderror" id="secret" name="secret" value="{{ old('secret', $client->secret ?? '') }}" disabled>
                                @error('secret')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <br>
                                <button type="submit" class="btn btn-primary px-5 pt-10">{{ ($client ? 'Update':'Create') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {


    });
</script>
@endsection