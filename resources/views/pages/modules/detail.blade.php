@extends('layouts.app')
@section('title', 'Client Detail')

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
                <div class="border p-4 rounded">
                    <div class="card-title d-flex align-items-center">
                        <h5 class="mb-0">Client Detail</h5>
                    </div>
                    <hr />
                    <table cellpadding="5px" class="table-vcenter">
                        <tr>
                            <th width="200px">
                                Client Name
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $client->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="200px">
                                Client ID
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $client->id ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="200px">
                                Client secret
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $client->secret ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="200px">
                                Client Redirect
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $client->redirect ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="200px">
                                Revoked status
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {!! $client->revoked ? '<span class="badge bg-danger">Revoked</span>':'<span class="badge bg-success">Active</span>' !!}
                            </td>
                        </tr>
                    </table>


                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                    </div>
                </div>
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