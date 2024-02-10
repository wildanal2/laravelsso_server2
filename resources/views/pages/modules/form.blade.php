@extends('layouts.app')
@section('title', ($module ? 'Update':'Create').' Modules')

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
                            <h5 class="mb-0">Module {{ ($module ? 'Update':'Create') }}</h5>
                        </div>
                        <hr />
                        {{ csrf_field() }}
                        <div class="row my-6">
                            <label for="codename" class="col-sm-3 col-form-label required-field">Code Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('codename') is-invalid @enderror" id="codename" name="codename" value="{{ old('codename', $module->codename ?? '') }}" placeholder="Enter Code Name" autocomplete="codename" required>
                                @error('codename')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row my-6">
                            <label for="name" class="col-sm-3 col-form-label required-field">Module Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $module->name ?? '') }}" placeholder="Enter Module Name" autocomplete="name-module" required>
                                @error('name')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" cols="3" placeholder="Enter description (optional)">{{ old('description', $module->description ?? '') }}</textarea>
                                @error('description')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="url" class="col-sm-3 col-form-label">URI</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $module->url ?? '') }}" placeholder="Enter URI Application" autocomplete="url">
                                @error('url')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="callback" class="col-sm-3 col-form-label required-field">Url Auth Callback</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('callback') is-invalid @enderror" id="callback" name="callback" value="{{ old('callback', $oauth?->redirect ?? '') }}" placeholder="Enter URL Auth Callback (domain.com/auth/callback)" autocomplete="callback" required>
                                @error('callback')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="last_version" class="col-sm-3 col-form-label">Version</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('last_version') is-invalid @enderror" id="last_version" name="last_version" value="{{ old('last_version', $module->last_version ?? '') }}" placeholder="Enter module version (1.0)" autocomplete="last_version">
                                @error('last_version')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="revoked" class="col-sm-3 col-form-label">Revoked</label>
                            <div class="col-sm-9">
                                <input class="form-check-input pt-2 @error('revoked') is-invalid @enderror" type="checkbox" id="revoked" name="revoked" {{ ($oauth?->revoked?"checked":"")}}>
                                <label class="form-check-label pl-1" for="revoked" id="label-revoked">{!! ($oauth?->revoked?'<span class="badge bg-danger">Revoked</span>' : '<span class="badge bg-success">False</span>') !!}</label>
                                @error('revoked')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <br>
                                <button type="submit" class="btn btn-primary text-light-blue-900 px-5">{{ ($module ? 'Update':'Create') }}</button>
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
        $('#revoked').change(function() {
            dat = $(this).val();
            chk = $(this).prop('checked');
            $('#label-revoked').html(chk ? '<span class="badge bg-danger">Revoked</span>' : '<span class="badge bg-success">False</span>')
        });

    });
</script>
@endsection