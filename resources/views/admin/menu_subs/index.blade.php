@extends('admin.layouts.admin')

@section('title', 'Daftar Sub Menu')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.menu_subs.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="menus-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Sub Menu</th>
                <th>Route</th>
                <th>Menu</th>
                <th>Tindakan</th>
            </tr>
            </thead>
            <tbody>
            {{--@foreach($machinery_types as $machinery_type)--}}
            {{--<tr>--}}
            {{--<td>{{ $machinery_type->id }}</td>--}}
            {{--<td>{{ $machinery_type->description }}</td>--}}
            {{--<td>--}}
            {{--<a class="btn btn-xs btn-info" href="{{ route('admin.machinery_types.edit', [$machinery_type->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Ubah">--}}
            {{--<i class="fa fa-pencil"></i>--}}
            {{--</a>--}}
            {{--@if(!$user->hasRole('administrator'))--}}
            {{--<button class="btn btn-xs btn-danger user_destroy"--}}
            {{--data-url="{{ route('admin.users.destroy', [$user->id]) }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.delete') }}">--}}
            {{--<i class="fa fa-trash"></i>--}}
            {{--</button>--}}
            {{--@endif--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--@endforeach--}}
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
            $('#menus-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.menu_subs') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'name', name: 'name' },
                    { data: 'route', name: 'route' },
                    { data: 'menu', name: 'menu' },
                    { data: 'action', name:'action' }
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
    @include('partials._deleteJs', ['routeUrl' => 'admin.menu_subs.destroy', 'redirectUrl' => 'admin.menu_subs'])
@endsection