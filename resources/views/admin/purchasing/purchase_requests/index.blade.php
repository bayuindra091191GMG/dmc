@extends('admin.layouts.admin')

@section('title', 'Daftar Purchase Request')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-left">
            <form class="form-inline" style="margin-bottom: 10px;">
                <div class="form-group">
                    <label for="filter-status">Status:</label>
                    <select id="filter-status" class="form-control" onchange="filterStatus(this)">
                        <option value="0" @if(empty($filterStatus) || $filterStatus == '0') selected @endif>Semua</option>
                        <option value="3" @if(!empty($filterStatus) && $filterStatus == '3') selected @endif>Open</option>
                        <option value="4" @if(!empty($filterStatus) && $filterStatus == '4') selected @endif>Close</option>
                        <option value="11" @if(!empty($filterStatus) && $filterStatus == '11') selected @endif>Close Manual</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="nav navbar-right">
            <a href="{{ route('admin.purchase_requests.before_create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="pr-table">
            <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nomor PR</th>
                <th class="text-center">Nomor MR</th>
                <th class="text-center">Departemen</th>
                <th class="text-center">Prioritas</th>
                <th class="text-center">Kode Unit</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Jatuh Tempo</th>
                <th class="text-center">Status</th>
                {{--<th class="text-center">Kapan Dibuat</th>--}}
                <th class="text-center">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script>
        $(function() {
            $('#pr-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('datatables.purchase_requests') !!}',
                    data: {
                        'status': '{{ $filterStatus }}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center' },
                    { data: 'code', name: 'code', class: 'text-center' },
                    { data: 'mr_code', name: 'mr_code', class: 'text-center' },
                    { data: 'department', name: 'department', class: 'text-center' },
                    { data: 'priority', name: 'priority', class: 'text-center' },
                    { data: 'machinery', name: 'machinery', class: 'text-center' },
                    { data: 'date', name: 'date', class: 'text-center' },
                    { data: 'limit_date', name: 'limit_date', class: 'text-center' },
                    { data: 'status', name: 'status', class: 'text-center' },
                    // { data: 'created_at', name: 'created_at', class: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        function filterStatus(e){
            // Get status filter value
            var status = e.value;

            var url = "/admin/purchase_requests?status=" + status;

            window.location = url;
        }
    </script>
@endsection
