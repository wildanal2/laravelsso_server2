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
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-2 col-form-label">Role Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $role->name ?? '') }}" placeholder="Enter Role Name" autocomplete="nama">
                                @error('nama')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-2 col-form-label">Have Permissions</label>
                            <div class="col-sm-10 px-3" style="display: grid; gap: 5px; grid-template-columns: repeat(3, minmax(0, 1fr));">
                                @foreach ( $permissions as $item)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $item->id }}" name="permissions[]" {{ $role->permissions->contains($item->id)?'checked':'' }}>
                                    <label class="form-check-label" for="flexCheckDefault">{{ $item->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <br>
                                <button type="submit" class="btn btn-primary px-5">{{ ($role ? 'Update':'Create') }}</button>
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