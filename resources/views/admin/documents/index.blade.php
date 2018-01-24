@extends('admin.layouts.admin')

@section('title', 'Index Dokumen')

@section('content')

    <div class="nav navbar-right">
        <a href="{{ route('admin.documents.create') }}" class="btn btn-app">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="documents-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Deskripsi</th>
                <th>Tindakan</th>
            </tr>
            </thead>
            <tbody>
            {{--@foreach($documents as $document)--}}
                {{--<tr>--}}
                    {{--<td>{{ $document->description }}</td>--}}
                    {{--<td>--}}
                        {{--<a class="btn btn-xs btn-info" href="{{ route('admin.documents.edit', [$document->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Ubah">--}}
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
    {{ Html::style(mix('assets/admin/css/users/index.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/index.js')) }}
    <script>
        $(function() {
            $('#documents-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.documents') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'description', name: 'description' },
                    { data: 'action', name:'action' }
                ]
            });
        });
    </script>
@endsection