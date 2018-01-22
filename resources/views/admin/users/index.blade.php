@extends('admin.layouts.admin')

@section('title', 'Data Pengguna')

@section('content')
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="users-table">
            <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Roles</th>
                <th>Active</th>
                <th>Confirmed</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/index.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/index.js')) }}
    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.data') !!}',
                columns: [
                    { data: 'email', name: 'email' },
                    { data: 'name', name: 'name' },
                    { data: 'roles', name: 'roles' },
                    { data: 'active', name: 'active' },
                    { data: 'confirmed', name: 'confirmed' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name:'action' }
                ]
            });
        });
    </script>
@endsection
