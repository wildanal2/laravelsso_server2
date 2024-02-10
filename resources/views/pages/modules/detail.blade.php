@extends('layouts.app')
@section('title', 'Module Detail')

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
                        <h5 class="mb-0">Module Detail</h5>
                    </div>
                    <hr />
                    <table cellpadding="5px" class="table-vcenter mt-3" width="100%">
                        <tr>
                            <th width="150px">
                                Module ID
                            </th>
                            <td class="text-center">
                                :
                            </td>
                            <td class="font-extrabold">
                                {{ $modul->codename ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="150px">
                                Module Name
                            </th>
                            <td class="text-center">
                                :
                            </td>
                            <td>
                                {{ $modul->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="150px">
                                Description
                            </th>
                            <td class="text-center">
                                :
                            </td>
                            <td>
                                {{ $modul->description ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th width="150px">
                                URI
                            </th>
                            <td class="text-center">
                                :
                            </td>
                            <td>
                                {!! $modul->url ? '<a href="'.$modul->url.'">'.$modul->url.'</a>':'' !!}
                            </td>
                        </tr>
                        <tr>
                            <th width="150px">
                                Version
                            </th>
                            <td class="text-center">
                                :
                            </td>
                            <td>
                                {{ $modul->last_version }}
                            </td>
                        </tr>
                        <tr>
                            <th width="150px" style="vertical-align: top">
                                OAuth Client
                            </th>
                            <td class="text-center" style="vertical-align: top">
                                :
                            </td>
                            <td class="relative">
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <a href="{{ url('').'/oclient/'.$oauth->id.'/edit' }}">
                                        <button class="btn-remove-permission middle none center mr-3 flex items-center justify-center rounded border border-blue-500 p-1 px-2 font-sans text-xs font-bold text-blue-500 transition-all hover:opacity-75 focus:ring focus:ring-blue-200 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-dark="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit OAuth Client">
                                            <i class="lni lni-pencil-alt text-sm leading-none pr-1"></i> Edit
                                        </button>
                                    </a>
                                </div>
                                <table cellpadding="5px" class="table-vcenter border mb-3" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%" class="text-left">
                                                Client Name
                                            </th>
                                            <th width="10px">:</th>
                                            <th class="text-left">
                                                {{ $oauth->name }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="text-left">
                                                Client ID
                                            </th>
                                            <th>:</th>
                                            <th class="">
                                                {{ $oauth->id }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="text-left">
                                                Secret
                                            </th>
                                            <th>:</th>
                                            <th class="">
                                                {{ $oauth->secret }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="text-left">
                                                Redirect Auth Callback
                                            </th>
                                            <th>:</th>
                                            <th class="">
                                                {{ $oauth->redirect }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="text-left">
                                                Revoked status
                                            </th>
                                            <th>:</th>
                                            <th class="">
                                                {!! $oauth->revoked ? '<span class="badge bg-danger">Revoked</span>':'<span class="badge bg-success">False</span>' !!}
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th width="150px" style="vertical-align: top">
                                Modul Feature
                            </th>
                            <td class="text-center" style="vertical-align: top">
                                :
                            </td>
                            <td class=" ">
                                <table cellpadding="5px" class="table-vcenter" width="100%">
                                    <thead>
                                        <tr class="border">
                                            <th width="40%" class="text-center border">
                                                Feature Name
                                            </th>
                                            <th class="font-extrabold text-center">
                                                Permission
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="container-feature">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-left pb-3">
                                                <button id="btn-add-mod-feature" class="bg-light-blue-500 text-white active:bg-light-blue-600 font-bold text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button" data-ripple-light="true">
                                                    <i class="lni lni-circle-plus"></i> Add Module Feature
                                                </button>
                                            </th>
                                        </tr>
                                        <tr class="border-t">
                                            <th colspan="2" class="text-center pt-3">
                                                <button id="btn-save-feature" class="w-full bg-green-500 text-white active:bg-green-600 font-bold text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button" data-ripple-light="true">
                                                    <i class="lni lni-save pr-1"></i> Save
                                                </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@php
if (!isset($modulFeature) || empty($modulFeature)||count($modulFeature)== 0) {
$modulFeature = [['name' => '', 'permission' => ['']]];
}
@endphp
@endsection

@section('script')
<script>
    var dataModFeat = {!!json_encode($modulFeature) !!};
    console.log(dataModFeat);

    // func
    function refreshView() {
        $('#container-feature').html('');
        dataModFeat.forEach((data, i) => {
            var perm = ``;
            data.permission.forEach((item, index) => {
                perm += `<div class="flex mt-2 items-end relative">
                            <span class="pb-1 pr-2">` + (index + 1) + `)</span>
                            <div class="relative h-11 w-full min-w-[150px]">
                                <input value="` + item + `" placeholder="Input Permission name" data-rowid="` + i + `" data-perm_index="` + index + `" class="input-permission peer h-full w-full border-b border-blue-gray-200 bg-transparent pt-4 pb-1.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border-blue-gray-200 focus:border-pink-500 focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50" placeholder=" " />
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <button
                                    data-rowid="` + i + `"
                                    data-perm_index="` + index + `"
                                    class="btn-remove-permission middle none center mr-3 flex items-center justify-center rounded border border-pink-500 p-1 font-sans text-xs font-bold uppercase text-pink-500 transition-all hover:opacity-75 focus:ring focus:ring-pink-200 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                    data-ripple-dark="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Permission ` + (index + 1) + `">
                                    <i class="lni lni-trash text-lg leading-none"></i>
                                </button>
                            </div>
                        </div>`;
            });

            var html = `<tr class="border tr-feature">
                            <td width = "40%" class = "text-center border p-2 relative">
                                <div class="relative h-11 w-full min-w-[150px]">
                                    <input value="` + data.name + `" data-rowid="` + i + `" class="input-feature peer h-full w-full border-b border-blue-gray-200 bg-transparent pt-4 pb-1.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border-blue-gray-200 focus:border-pink-500 focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50" placeholder=" " />
                                    <label class="after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-500 transition-all after:absolute after:-bottom-1.5 after:block after:w-full after:scale-x-0 after:border-b-2 after:border-pink-500 after:transition-transform after:duration-300 peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[4.25] peer-placeholder-shown:text-blue-gray-500 peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-pink-500 peer-focus:after:scale-x-100 peer-focus:after:border-pink-500 peer-disabled:text-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
                                        Feature name ` + (i + 1) + `
                                    </label> 
                                </div>
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <button
                                        data-rowid="` + i + `"
                                        class="btn-remove-feature middle none center mr-3 flex items-center justify-center rounded border border-pink-500 p-1 font-sans text-xs font-bold uppercase text-pink-500 transition-all hover:opacity-75 focus:ring focus:ring-pink-200 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                        data-ripple-dark="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Feature ` + (i + 1) + `">
                                        <i class="lni lni-trash text-lg leading-none"></i>
                                    </button>
                                </div>
                            </td> 
                            <td class = "text-center flex flex-column">
                                ` + perm + `
                                <div class="text-center mt-3">
                                    <button data-rowid="` + i + `" class="permission-add bg-light-blue-500 text-white active:bg-light-blue-600 font-bold text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button" data-ripple-light="true">
                                        <i class="lni lni-circle-plus"></i> Add Permission
                                    </button>
                                </div>
                            </td> 
                        </tr>`;

            $('#container-feature').append(html);
        });

        $('[data-bs-toggle="tooltip"]').tooltip();

        $('.input-feature').on('input', function() {
            var rowid = $(this).data('rowid');
            var input = $(this);
            dataModFeat[rowid].name = input.val();
        });
        $('.input-permission').on('input', function() {
            var rowid = $(this).data('rowid');
            var perm_index = $(this).data('perm_index');
            var input = $(this);
            dataModFeat[rowid].permission[perm_index] = input.val();
        });
        $('.permission-add').click(function() {
            var rowid = $(this).data('rowid');
            dataModFeat[rowid].permission.push('');
            refreshView();
        });
        $('.btn-remove-feature').click(function() {
            $('[data-bs-toggle="tooltip"]').tooltip("hide");
            var rowid = $(this).data('rowid');
            dataModFeat.splice(rowid, 1);
            refreshView();
        });
        $('.btn-remove-permission').click(function() {
            $('[data-bs-toggle="tooltip"]').tooltip("hide");
            var rowid = $(this).data('rowid');
            var perm_index = $(this).data('perm_index');
            dataModFeat[rowid].permission.splice(perm_index, 1);
            refreshView();
        });
    }

    $(document).ready(function() {
        refreshView();

        $('#btn-add-mod-feature').click(function() {
            dataModFeat.push({
                name: '',
                permission: ['']
            })
            console.log(dataModFeat)
            refreshView();
        });

        $('#btn-save-feature').click(function() {
            var pathname = window.location.pathname;
            var segments = pathname.split('/');
            var segmentTwo = segments[2];
            $.ajax({
                url: "{{ url('') }}/modules/" + segmentTwo + "/module-feature", // Ganti nilai_id_anda dengan nilai yang sebenarnya
                type: 'POST',
                dataType: 'json', // Jika server mengembalikan data dalam format JSON
                data: {
                    id: segmentTwo,
                    data: dataModFeat
                },
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
                    var errorMessage = JSON.parse(error.responseText)?.message;

                    console.error(errorMessage);
                    Lobibox.notify("error", {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        msg: "Something Wrong! please try Again. \n " + errorMessage
                    });
                }
            });
        });
    });
</script>
@endsection