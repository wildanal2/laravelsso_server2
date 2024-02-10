@extends('layouts.app')
@section('title', 'Roles')

@section('head')
<link href="{{ url('') }}/assets/theme/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<style>
    .pemission-td {
        max-width: 400px;
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
            <a href="{{ route('roles.form') }}" class="btn btn-sm btn-primary"><i class="lni lni-circle-plus" style="margin-top: -16px;"></i> Create Role</a>

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
                            <p class="mb-1">Total Role</p>
                            <h4 class="mb-0">{{ $role_count }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-info text-white">
                            <i class="bx bxs-user-account"></i>
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

<!-- Modal -->
<div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permission Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="logModalBody">
                <!-- Teks log akan ditampilkan di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-gray-600" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ url('') }}/assets/theme/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ url('') }}/assets/theme/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    function destroyRole(id, name) {
        Swal.fire({
            title: 'Are you sure to Delete Role?',
            text: 'You will Role `' + name + '`',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DC3741",
            confirmButtonText: 'Delete Role',
            cancelButtonText: 'cancel',
            reverseButtons: true
        }).then((result) => {
            var requestData = {};
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ url('') }}/roles/" + id,
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
                url: "{{ route('roles.get') }}",
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
                title: 'Role Name',
                data: 'name',
                width: '25%',
            }, {
                title: 'Guard Name',
                data: 'guard_name',
                width: '10%',
            }, {
                title: 'Permission',
                data: 'permission',
                width: '35%',
                className: 'relative',
                render: ((data) => {
                    return `<div class="pemission-td"><p>` + data + `</p>
                    <button class="absolute inset-y-2 right-1 expand middle none center h-8 max-h-[32px] w-8 max-w-[32px] rounded-lg bg-light-blue-500 font-sans text-xs font-bold uppercase text-white shadow-md shadow-light-blue-500/20 transition-all hover:shadow-lg hover:shadow-light-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true" onClick="showMod('` + data + `')">
                        <i class="lni lni-keyword-research text-xs leading-none"></i>
                    </button></div>`;
                })
            }, {
                title: 'Aksi',
                data: 'id',
                orderable: false,
                searchable: false,
                width: '20%',
                className: 'no-wrap text-center',
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
                $('.btn-roles-destroy').on('click', function() {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    destroyRole(id, name);
                });
            }
        });

    });

    function showMod(data) {
        var permissionsArray = data.split(',').map(function(permission) {
            return permission.trim();
        });
        // Buat teks dengan new line
        var text = permissionsArray.map(function(permission) {
            return permission + ' (' + permission.split('.')[1].replace('-', ' ') + ')';
        }).join('\n');

        $('#logModalBody').html('');
        $('#logModalBody').append('<pre><code>' + (text) + '</code></pre>');

        $('#logModal').modal('show');
    };
</script>
@endsection