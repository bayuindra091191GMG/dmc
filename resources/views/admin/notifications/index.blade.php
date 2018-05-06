@extends('admin.layouts.admin')

@section('title', 'Daftar Notifikasi')

@section('content')

    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="machinery-types-table">
            <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No</th>
                <th class="text-center" style="width: 20%;">Dokumen</th>
                <th class="text-center" style="width: 45%;">Notifikasi</th>
                <th class="text-center" style="width: 25%;">Pengirim</th>
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
            $('#machinery-types-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.notifications') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'document', name: 'document', class: 'text-center'},
                    { data: 'notification', name: 'notification' },
                    { data: 'sender', name: 'sender', class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection