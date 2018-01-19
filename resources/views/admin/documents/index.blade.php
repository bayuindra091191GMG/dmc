@extends('admin.layouts.admin')

@section('title', 'Index Grup')

@section('content')

    <div class="nav navbar-right">
        <a href="{{ route('admin.groups.create') }}" class="btn btn-app">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal Dibuat</th>
                <th>Diubah Oleh</th>
                <th>Tanggal Diubah</th>
                <th>Tindakan</th>
            </tr>
            </thead>
            <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>{{ $group->code }}</td>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->createdBy->email }}</td>
                    <td>{{ $group->created_at }}</td>
                    <td>{{ $group->updatedBy->email }}</td>
                    <td>{{ $group->updated_at }}</td>
                    <td>
                        <a class="btn btn-xs btn-info" href="{{ route('admin.groups.edit', [$group->id]) }}" data-toggle="tooltip" data-placement="top" data-title="Ubah">
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