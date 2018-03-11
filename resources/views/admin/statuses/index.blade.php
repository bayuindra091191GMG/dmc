@extends('admin.layouts.admin')

@section('title', 'Data Status')

@section('content')

    <div clas="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.statuses.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="employees-table">
            <thead>
            <tr>
                <th class="text-center" style="width: 25%;">ID</th>
                <th class="text-center" style="width: 50%;">Status</th>
                <th class="text-center" style="width: 25%;">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @include('partials._delete')
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(function() {
            $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.statuses') !!}',
                columns: [
                    { data: 'ID', class: 'text-center'},
                    { data: 'description', name: 'description', class: 'text-center'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        $(document).on('click', '.delete-modal', function(){
            $('#deleteModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#deleted-id').val($(this).data('id'));
        });
    </script>
    @include('partials._deleteJs', ['routeUrl' => 'admin.statuses.destroy', 'redirectUrl' => 'admin.statuses'])
@endsection
