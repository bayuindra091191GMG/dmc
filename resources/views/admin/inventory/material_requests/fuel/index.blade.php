@extends('admin.layouts.admin')

@section('title', 'Daftar Material Request BBM')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-left">
            <form class="form-inline" style="margin-bottom: 10px;">
                <div class="form-group">
                    <label for="filter-status">Status:</label>
                    <select id="filter-status" class="form-control" onchange="filterStatus(this)">
                        <option value="0" @if($filterStatus == '0') selected @endif>Semua</option>
                        <option value="3" @if($filterStatus == '3') selected @endif>Open</option>
                        <option value="4" @if($filterStatus == '4') selected @endif>Close</option>
                        <option value="11" @if($filterStatus == '11') selected @endif>Close Manual</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="nav navbar-right">
            <a href="{{ route('admin.material_requests.fuel.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="mr-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nomor MR</th>
                <th>Departemen</th>
                <th>Kode Unit</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Tindakan</th>
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
            $('#mr-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('datatables.material_requests') !!}',
                    data: {
                        'type': 'fuel',
                        'status': '{{ $filterStatus }}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center' },
                    { data: 'code', name: 'code', class: 'text-center' },
                    { data: 'department', name: 'department', class: 'text-center' },
                    { data: 'machinery', name: 'machinery', class: 'text-center' },
                    { data: 'date', name: 'created_at', class: 'text-center' },
                    { data: 'status', name: 'status', class: 'text-center' },
                    { data: 'action', name: 'action',orderable: false, searchable: false, class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        function filterStatus(e){
            // Get status filter value
            var status = e.value;

            var url = "/admin/material_requests/bbm?status=" + status;

            window.location = url;
        }
    </script>
@endsection
