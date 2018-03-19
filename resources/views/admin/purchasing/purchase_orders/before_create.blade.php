@extends('admin.layouts.admin')

@section('title', 'Pilih Purchase Request terlebih dahulu')

@section('content')

    <div class="row">
        <div class="navbar-left">
            <a class="btn btn-default" href="{{ route('admin.purchase_orders') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="pr-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nomor PR</th>
                <th>Departemen</th>
                <th>Kode Unit</th>
                <th>Tanggal</th>
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
            $('#pr-table').DataTable({
                processing: true,
                serverSide: true,
                {{--ajax: '{!! route('datatables.purchase_requests') !!}',--}}
                ajax: {
                    url: '{!! route('datatables.purchase_requests') !!}',
                    data: {
                        'mode': 'before_create',
                        'status': '{{ !empty($statusFilter) ? $statusFilter : 'default'}}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center' },
                    { data: 'code', name: 'code', class: 'text-center' },
                    { data: 'department', name: 'department', class: 'text-center' },
                    { data: 'machinery', name: 'machinery', class: 'text-center' },
                    { data: 'created_at', name: 'created_at', class: 'text-center' },
                    { data: 'action', name: 'action',orderable: false, searchable: false, class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
