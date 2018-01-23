@extends('admin.layouts.admin')

@section('title', 'Data Karyawan')

@section('content')
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="employees-table">
            <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Nomor Ponsel</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Departemen</th>
                <th>Site</th>
                <th>Tanggal Dibuat</th>
                <th>Tanggal Diubah</th>
                <th>Opsi</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/employees/index.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/employees/index.js')) }}
    <script>
        $(function() {
            $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.employees') !!}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'dob', name: 'dob' },
                    { data: 'address', name: 'address' },
                    { data: 'department', name: 'department' },
                    { data: 'site', name: 'site' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection
