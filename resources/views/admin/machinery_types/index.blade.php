@extends('admin.layouts.admin')

@section('title', 'Index Tipe Alat Berat')

@section('content')

    <div class="nav navbar-right">
        <a href="{{ route('admin.machinery_types.create') }}" class="btn btn-app">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Deskripsi</th>
                <th>Tindakan</th>
            </tr>
            </thead>
            <tbody>
            @foreach($machinery_types as $machinery_type)
                <tr>
                    <td>{{ $machinery_type->id }}</td>
                    <td>{{ $machinery_type->description }}</td>
                    <td>
                        <a class="btn btn-xs btn-info" href="{{ route('admin.machinery_types.edit', [$machinery_type->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Ubah">
                            <i class="fa fa-pencil"></i>
                        </a>
                        {{--@if(!$user->hasRole('administrator'))--}}
                            {{--<button class="btn btn-xs btn-danger user_destroy"--}}
                                    {{--data-url="{{ route('admin.users.destroy', [$user->id]) }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.delete') }}">--}}
                                {{--<i class="fa fa-trash"></i>--}}
                            {{--</button>--}}
                        {{--@endif--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection