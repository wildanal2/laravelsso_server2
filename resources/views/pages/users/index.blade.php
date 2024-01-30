@extends('layouts.app')
@section('title', 'Users')

@section('head')
<link href="assets/theme/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

@endsection

@section('content')
<div class="row">
    <!--start breadcrumb-->
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
            <a href="{{ route('users.form') }}" class="btn btn-sm btn-primary"><i class="lni lni-circle-plus" style="margin-top: -16px;"></i> User Baru</a>

        </div>
    </div>
    <!--end breadcrumb-->

    <!--start widget-->
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
        <div class="col">
            <div class="card rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Users</p>
                            <h4 class="mb-0">{{ $user_count }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-info text-white">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end widget-->

    <h6 class="mb-2 text-uppercase">Data @yield('title')</h6>
    <hr />
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="main-table" class="table table-striped table-bordered" style="width:100%">
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="assets/theme/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/theme/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    var main_table = $('#main-table').DataTable({
        aLengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: 10,
        scrollX: true,
        scrollCollapse: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('users.get') }}",
            type: "GET",
        },
        columns: [{
            title: '#',
            data: 'id',
            render: (data, type, row, meta) => {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
            className: 'text-center',
            width: '75px',
        }, {
            title: 'Name',
            data: 'name',
        }, {
            title: 'Email',
            data: 'email',
        }, {
            title: 'Entitas',
            data: 'entity',
        }, {
            title: 'Role',
            data: 'role',
        }, {
            title: 'Aksi',
            data: 'id',
            orderable: false,
            searchable: false,
            width: '1px',
            className: 'no-wrap',
            render: (data, type, row, meta) => {
                let html = "";
                $.each(row.buttons, function(i, item) {
                    let attribute = '';
                    $.each(item.attribute, function(key, value) {
                        attribute += `${value.name}="${value.text}"`;
                    });
                    html +=
                        `<a href="${item.url}" class="btn ${item.className}" ${attribute} data-toggle="tooltip" title="${item.text}" data-placement="left"><i class="${item.fontawesome}"></i></a>`;
                })
                return html;
            }
        }],
        "fnDrawCallback": function() {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $(document).ready(function() {

    });
</script>
@endsection