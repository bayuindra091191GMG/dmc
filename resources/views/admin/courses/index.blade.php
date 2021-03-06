@extends('admin.layouts.admin')

@section('title', 'Data Kelas Tipe '. $courseType)

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-right">
            <a href="{{ route('admin.courses.create', ['type' => $type]) }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="users-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 10%;">No</th>
                    <th class="text-center" style="width: 10%;">Nama</th>
                    <th class="text-center" style="width: 10%;">Type</th>
                    <th class="text-center" style="width: 10%;">Trainer</th>
                    <th class="text-center" style="width: 20%;">Harga</th>
                    <th class="text-center" style="width: 10%;">Studio</th>
                    <th class="text-center" style="width: 20%;">Jumlah Pertemuan</th>
                    <th class="text-center" style="width: 10%;">Tanggal Dibuat</th>
                    <th class="text-center" style="width: 10%;">Action</th>
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
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('datatables.courses') !!}',
                    data: {
                        'type': '{{ $type }}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'name', name: 'name', class: 'text-center'},
                    { data: 'type', name: 'type', class: 'text-center'},
                    { data: 'coach', name: 'coach', class: 'text-center'},
                    { data: 'price', name: 'price', class: 'text-center'},
                    { data: 'studio', name: 'studio', class: 'text-center'},
                    { data: 'meeting_amount', name: 'meeting_amount', class: 'text-center'},
                    { data: 'created_at', name: 'created_at', class: 'text-center'},
                    { data: 'action', name:'action', orderable: false, searchable: false, class: 'text-center'}

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
{{--    @php($url='admin.'.$selectedCourse.'.courses')--}}
{{--    @include('partials._deleteJs', ['routeUrl' => 'admin.courses.destroy', 'redirectUrl' => $url])--}}
@endsection
