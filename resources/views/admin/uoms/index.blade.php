@extends('admin.layouts.admin')

@section('title', 'Index Satuan Unit')

@section('content')

    <div class="nav navbar-right">
        <a href="{{ route('admin.uoms.create') }}" class="btn btn-app">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="uoms-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi</th>
                <th>Tindakan</th>
            </tr>
            </thead>
            <tbody>
            {{--@foreach($uoms as $uom)--}}
                {{--<tr>--}}
                    {{--<td>{{ $uom->description }}</td>--}}
                    {{--<td>--}}
                        {{--<a class="btn btn-xs btn-info" href="{{ route('admin.uoms.edit', [$uom->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Ubah">--}}
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
            $('#uoms-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.uoms') !!}',
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false},
                    { data: 'description', name: 'description' },
                    { data: 'action', name:'action' }
                ]
            });
        });
    </script>
@endsection