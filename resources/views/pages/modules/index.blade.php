@extends('layouts.app')
@section('title', 'Module')

@section('head')
<link href="{{ url('') }}/assets/theme/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<style>
    .wid-td {
        max-width: 240px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .wid-td-link {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
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
            <a href="{{ route('module.form') }}" class="btn btn-sm btn-primary"><i class="lni lni-circle-plus" style="margin-top: -16px;"></i> New Module</a>

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
                            <p class="mb-1">Total Module</p>
                            <h4 class="mb-0">{{ $module_total }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-info text-white">
                            <i class="bx bx-vector"></i>
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
<script src="{{ url('') }}/assets/theme/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ url('') }}/assets/theme/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    function destroy(id, name) {
        Swal.fire({
            title: 'Are you sure to Delete Module?',
            text: 'You will Delete module`' + name + '`',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DC3741",
            confirmButtonText: 'Delete Module',
            cancelButtonText: 'cancel',
            reverseButtons: true
        }).then((result) => {
            var requestData = {};
            if (result.isConfirmed) {
                $.ajax({
                    type: 'delete',
                    url: "{{ url('') }}/modules/" + id,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire("Saved!", "" + response?.message, "success").then(() => {
                            location.reload();
                        });
                        Lobibox.notify("success", {
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            msg: "" + response?.message
                        });
                    },
                    error: function(error) {
                        console.error(error);
                        Lobibox.notify("error", {
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            msg: "Something Wrong! please try Again"
                        });
                    }
                });
            }

        });
    }

    $(document).ready(function() {
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
                url: "{{ route('module.get') }}",
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
                title: 'Code Name',
                data: 'codename',
            }, {
                title: 'Name',
                data: 'name',
            }, {
                title: 'Description',
                data: 'description',
                className: 'wid-td',
            }, {
                title: 'URL',
                data: 'url',
                className: 'text-center wid-td-link',
                render: (data, type, row, meta) => {
                    if (data) {
                        return '<a href="' + data + '">' + data + '</a>';
                    } else {
                        return '-';
                    }
                }
            }, {
                title: 'Version',
                data: 'last_version',
                className: 'text-center',
            }, {
                title: 'Revoked',
                data: 'revoked',
                className: 'text-center',
                render: (data) => {
                    return data ? '<span class="badge bg-danger">Revoked</span>' : '<span class="badge bg-success">False</span>';
                }
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
                            `<a href="${item.url}" class="btn btn-sm ${item.className} mx-1" ${attribute} data-toggle="tooltip" title="${item.text}" data-placement="left"><i class="${item.fontawesome}" style="margin-left:-1px"></i></a>`;
                    })
                    return html;
                }
            }],
            "fnDrawCallback": function() {
                $('[data-toggle="tooltip"]').tooltip();
                $('.btn-module-destroy').on('click', function() {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    destroy(id, name);
                });
            }
        });

    });
</script>
@endsection