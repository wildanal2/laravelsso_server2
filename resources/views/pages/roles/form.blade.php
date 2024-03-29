@extends('layouts.app')
@section('title', ($role ? 'Update':'Create').' Role')

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
                            <h5 class="mb-0">Role {{ ($role ? 'Update':'Create') }}</h5>
                        </div>
                        <hr />
                        {{ csrf_field() }}
                        <div class="row mb-3 mt-6">
                            <label for="nama" class="col-sm-2 col-form-label required-field">Role Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $role->name ?? '') }}" placeholder="Enter Role Name" autocomplete="nama" required>
                                @error('nama')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-2 col-form-label">Have Permissions</label>
                            <div class="col-sm-10 pt-3">
                                @foreach ($modulFeature as $module)
                                <div class="pb-3">
                                    <h3 class="font-semibold pb-1">{{ $module['module_name'] }} - module</h3>
                                    <div class="border w-full py-2">
                                        @foreach ($module['data'] ??[] as $key => $feature)
                                        <div class="mx-3 border-b pt-2 pb-1">
                                            <span class="font-semibold">{{ $feature['feature_name'] }}</span>
                                            <input class="form-check-input ml-2 feature-toggle" type="checkbox" value="feat-{{ $feature['feature_name'] }}">
                                        </div>
                                        <div class="col-sm-10 ml-3 pt-2 px-3" style="display: grid; gap: 5px; grid-template-columns: repeat(3, minmax(0, 1fr));">
                                            @foreach ($feature['permissions'] ??[] as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input feat-{{ $feature['feature_name'] }}" type="checkbox" value="{{ $permission['permission']['id'] }}" name="permissions[]" {{ $role?->permissions?->contains($permission['permission']['id'])?'checked':'' }}>
                                                <label class="form-check-label" for="flexCheckDefault">{{ $permission['permission']['name']  }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                <div class="">
                                    <h3 class="font-semibold pb-1">Other</h3>
                                    <div class="col-sm-10 border w-full py-3 px-3" style="display: grid; gap: 5px; grid-template-columns: repeat(3, minmax(0, 1fr));">
                                        @foreach ($other ??[] as $key => $permissions)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $permissions->id }}" name="permissions[]" {{ $role?->permissions?->contains($permissions->id)?'checked':'' }}>
                                            <label class="form-check-label" for="flexCheckDefault">{{ $permissions->name  }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <br>
                                <button type="submit" class="btn btn-primary text-light-blue-900 px-5">{{ ($role ? 'Update':'Create') }}</button>
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

        $('.feature-toggle').change(function() {
            dat = $(this).val();
            chk = $(this).prop('checked');
            $('.' + dat).prop('checked', chk);
        });
    });
</script>
@endsection