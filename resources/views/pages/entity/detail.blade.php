@extends('layouts.app')
@section('title', 'Entity Detail')

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
                        <h5 class="mb-0">Entity Detail</h5>
                    </div>
                    <hr />
                    <table cellpadding="5px" class="table-vcenter" width="100%">
                        <tr>
                            <th width="200px">
                                Company REG
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $entity->company_reg ?? '' }}
                            </td>
                            <td rowspan="3" style="vertical-align: top; text-align: right;">
                                <div style="display: flex; flex-direction: column;">
                                    @if ($entity->logo!='' ||$entity->logo)
                                    <img src="{{ url('').'/storage/'.$entity->logo }}" style="max-height:120px; max-width:120px; margin-left: auto;">
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th width="200px">
                                Name
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $entity->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="200px">
                                Description
                            </th>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $entity->description ?? '' }}
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