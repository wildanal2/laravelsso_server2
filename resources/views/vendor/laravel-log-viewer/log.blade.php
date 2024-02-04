@extends('layouts.app')
@section('title', 'System Logs')

@section('head')
<link href="{{ url('') }}/assets/theme/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<style>
  pre {
    max-width: 100%;
    min-height: 40vh;
    max-height: 60vh;
    overflow-x: auto;
    /* Menambahkan bilah geser horizontal jika kontennya melebihi lebar maksimum */
    overflow-y: auto;
    /* Menambahkan bilah geser vertikal jika kontennya melebihi tinggi maksimum */
    white-space: pre-wrap;
    /* Membuat teks mematahkan baris jika melebihi lebar maksimum */
    border: 1px solid #ccc;
    padding: 10px;
    background-color: #f9f9f9;
  }
</style>
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
  <!-- End Head -->
  <!-- start menu file -->
  <div class="col-12 col-xl-3">
    <div class="card">
      <div class="card-body border-bottom">
        <div class="d-grid">
          <h6><i class="bx bx-folder me-2 text-info" aria-hidden="true"></i> Log Files</h6>
          @foreach($folders as $folder)
          <div class="list-group-item">
            <?php
            \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer::DirectoryTreeStructure($storage_path, $structure);
            ?>
          </div>
          @endforeach
        </div>
      </div>
      <div class="fm-menu">
        <div class="list-group list-group-flush m-3">
          @foreach($files as $file)
          <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}" class="list-group-item py-1" style="@if ($current_file == $file) background-color: #2c78e833; @endif"><i class='bi bi-file-earmark-break-fill me-1 text-info'></i><span>{{$file}}</span></a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <!-- end menu file -->
  <!-- start Content Log -->
  <div class="col-12 col-xl-9">

    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <h5 class="mb-0">Recent Files</h5>
          </div>
        </div>
        <div class="table-responsive mt-3">
          @if ($logs === null)
          <div>
            Log file >50M, please download it.
          </div>
          @else
          <table id="table-log" class="table table-striped" width="100%" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
            <thead>
              <tr>
                @if ($standardFormat)
                <th>Level</th>
                <th>Context</th>
                <th>Date</th>
                @else
                <th>Line number</th>
                @endif
                <th>Content</th>
              </tr>
            </thead>
            <tbody>

              @foreach($logs as $key => $log)
              <tr data-display="stack{{{$key}}}">
                @if ($standardFormat)
                <td class="nowrap text-{{{$log['level_class']}}}">
                  @if (in_array($log['level'], ['debug','info','notice']))
                  <span class="bx bx-info-circle" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                  @else
                  <span class="bx bx-error" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                  @endif
                </td>
                <td class="text">{{$log['context']}}</td>
                @endif
                <td class="date">{{{$log['date']}}}</td>
                <td class="text" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                  <button type="button" class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2" data-display="stack{{{$key}}}" data-level="{{$log['level']}}" data-time="{{{$log['date']}}}" data-logtext="{{{ json_encode($log['text']) }}}">
                    <span class="lni lni-keyword-research"></span>
                  </button>
                  {{{$log['text']}}}
                  @if (isset($log['in_file']))
                  <br />{{{$log['in_file']}}}
                  @endif
                  @if ($log['stack'])
                  <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                  </div>
                  @endif
                </td>
              </tr>
              @endforeach

            </tbody>
          </table>
          @endif
          <div class="p-3">
            @if($current_file)
            <a class="btn btn-sm btn-outline-primary" href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
              <span class="lni lni-cloud-download"></span> Download file
            </a>

            <a id="clean-log" class="btn btn-sm btn-outline-primary mx-2" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
              <span class="lni lni-reload"></span> Clean file
            </a>
            
            <a id="delete-log" class="btn btn-sm btn-outline-danger" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
              <span class="lni lni-remove-file"></span> Delete file
            </a>
            @if(count($files) > 1)
            <a id="delete-all-log" class="btn btn-sm btn-outline-danger mx-2" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
              <span class="lni lni-trash"></span> Delete all files
            </a>
            @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end Content Log -->

</div>

<!-- Modal -->
<div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Log Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="logModalBody">
        <!-- Teks log akan ditampilkan di sini -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ url('') }}/assets/theme/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ url('') }}/assets/theme/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

<script>
  function escapeHtml(html) {
    var text = document.createTextNode(html);
    var p = document.createElement('p');
    p.appendChild(text);
    return p.innerHTML;
  }

  $(document).ready(function() {
    $('.table-container tr').on('click', function() {
      $('#' + $(this).data('display')).toggle();
    });
    $('#table-log').DataTable({
      "order": [$('#table-log').data('orderingIndex'), 'desc'],
      "stateSave": true,
      "stateSaveCallback": function(settings, data) {
        window.localStorage.setItem("datatable", JSON.stringify(data));
      },
      "stateLoadCallback": function(settings) {
        var data = JSON.parse(window.localStorage.getItem("datatable"));
        if (data) data.start = 0;
        return data;
      }
    });
    $('#delete-log, #clean-log, #delete-all-log').click(function() {
      return confirm('Are you sure?');
    });

    $('.expand').on('click', function() {
      var logText = $(this).data('logtext');
      var time = $(this).data('time');
      var level = $(this).data('level');
      var decodedLogText = JSON.parse(logText);

      $('#logModalBody').html('');
      $('#logModalBody').append('<p>Level : ' + level + '</p>');
      $('#logModalBody').append('<p>Date Time : ' + escapeHtml(time) + '</p>');
      $('#logModalBody').append('<p>Message Content :</p>');
      $('#logModalBody').append('<pre><code>' + escapeHtml(decodedLogText) + '</code></pre>');

      document
        .querySelectorAll('pre')
        .forEach((block) => hljs.highlightElement(block));

      $('#logModal').modal('show');
    });

  });
</script>
@endsection