@extends('layouts.app')
@section('title', ($user ? 'Update':'Create').' Users')

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
                <form action="" method="post" id="form-main">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center">
                            <h5 class="mb-0">User {{ ($user ? 'Update':'Registration') }}</h5>
                        </div>
                        <hr />
                        {{ csrf_field() }}
                        <div class="row mb-3 mt-3">
                            <label for="name" class="col-sm-3 col-form-label required-field">Enter Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Enter Name for user" autocomplete="name" required>
                                @error('name')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-3 col-form-label required-field">Email Address</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="Email Address" autocomplete="new-email" required>
                                @error('email')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="module" class="col-sm-3 col-form-label">Modules</label>
                            <div class="col-sm-9">
                                <select class="form-control select2-module @error('module') is-invalid @enderror" name="module[]" multiple="multiple" data-placeholder="Select Module">
                                    @foreach ($module ??[] as $item)
                                    <option value="{{ $item->id }}" {{ $user?->hasModule?->pluck('id')->contains($item->id)? 'selected':'' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('module')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="entity" class="col-sm-3 col-form-label">Entity</label>
                            <div class="col-sm-9">
                                <select class="form-control select2-entity @error('entity') is-invalid @enderror" name="entity[]" multiple="multiple" data-placeholder="Select Entity">
                                    @foreach ($user->hasEntities ??[] as $item)
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('entity')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="role" class="col-sm-3 col-form-label required-field">Role</label>
                            <div class="col-sm-9">
                                <select class="form-control select2-role @error('role') is-invalid @enderror" name="role[]" multiple="multiple" data-placeholder="Select Role" required>
                                    @foreach ($role ??[] as $item)
                                    <option value="{{ $item->id }}" {{ $user?->roles?->pluck('id')->contains($item->id)? 'selected':'' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        @if (isset($user))
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Reset Password</label>
                            <div class="col-sm-9 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" value="" id="reset-pass">
                            </div>
                        </div>
                        @endif
                        <div class="row mb-3 reset-pass {{ isset($user)? 'd-none':'' }}">
                            <label for="new-password" class="col-sm-3 col-form-label required-field">Choose Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control @error('new-password') is-invalid @enderror" id="new-password" name="new-password" placeholder="Choose New Password" autocomplete="new-password">
                                @error('new-password')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 reset-pass {{ isset($user)? 'd-none':'' }}">
                            <label for="re-password" class="col-sm-3 col-form-label">Confirm Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control @error('re-password') is-invalid @enderror" id="re-password" name="re-password" placeholder="Confirm New Password">
                                @error('re-password')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary px-5 text-light-blue-900">{{ ($user ? 'Update':'Registration') }}</button>
                                @if ($user && $user->is_active !=0)
                                <button type="button" class="btn btn-warning mx-3 text-orange-300" id="btn-suspend-user">
                                    <i class="fadeIn animated bx bx-user-x" style="margin-top: -20px;"></i>Suspend</button>
                                @endif
                                @if ($user && $user->is_active ==0)
                                <button type="button" class="btn btn-success mx-3 text-green-500" id="btn-active-user">
                                    <i class="fadeIn animated bx bx-user-check" style="margin-top: -20px; padding-right: 5px;"></i>Set Active</button>
                                @endif
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
    $('.select2-entity').select2({
        ajax: {
            url: "{{ route('select2.get-entity') }}",
            data: function(item) {
                return {
                    term: item.term,
                    page: item.page,
                }
            },
            dataType: 'json',
            processResults: function(data, params) {
                params.page = params.page || 1;
                return data;
            },
            cache: true
        },
        allowClear: true,
        escapeMarkup: function(markup) {
            return markup;
        },
    });
    $('.select2-module').select2({
        ajax: {
            url: "{{ route('select2.get-module') }}",
            data: function(item) {
                return {
                    term: item.term,
                    page: item.page,
                }
            },
            dataType: 'json',
            processResults: function(data, params) {
                params.page = params.page || 1;
                return data;
            },
            cache: true
        },
        allowClear: true,
        escapeMarkup: function(markup) {
            return markup;
        },
    });

    function addNewInput(val) {
        var newInput = $('<input>').attr({
            type: 'hidden',
            name: 'is_active',
            value: val
        });
        // Append the new input to the container
        $('#form-main').append(newInput);
    }

    $(document).ready(function() {
        // 
        $('.select2-role').select2();

        $('#reset-pass').change(function() {
            var isChecked = $(this).prop('checked');
            if (isChecked) {
                $('.reset-pass').removeClass('d-none');
            } else {
                $('.reset-pass').addClass('d-none');
            }
        });

        $('#btn-suspend-user').on('click', function() {
            addNewInput(0);
            $('#form-main').submit();
        });

        $('#btn-active-user').on('click', function() {
            addNewInput(1);
            $('#form-main').submit();
        });
    });
</script>
@endsection