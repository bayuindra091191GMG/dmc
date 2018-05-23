@extends('admin.layouts.admin')

@section('title','Data Murid '. $customer->name)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.customers') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="navbar-right">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form class="form-horizontal form-label-left box-section">

                @if(\Illuminate\Support\Facades\Session::has('message'))
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            @include('partials._success')
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nama
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nama Orang Tua
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->parent_name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Umur
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->age }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor Telepon
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->phone }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Email
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->email }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Alamat
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->address }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Status
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->status->description }}
                    </div>
                </div>

                <hr/>

                @if($courses != null)
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-xs-12 column">
                            <h4 class="text-center">Kelas</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr >
                                        <th class="text-center" style="width: 3%">
                                            No
                                        </th>
                                        <th class="text-center" style="width: 20%">
                                            Nama
                                        </th>
                                        <th class="text-center" style="width: 20%">
                                            Trainer
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Tipe
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Fee
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Hari yang diambil
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Masa Kelas
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php($no = 1)
                                    @foreach($courses as $course)
                                        <tr>
                                            <td class="text-center">
                                                {{ $no }}
                                            </td>
                                            <td class="text-center">
                                                {{ $course->course->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $course->course->coach->name }}
                                            </td>
                                            <td class="text-center">
                                                @if($course->type == 1)
                                                    Paket
                                                @else
                                                    Kelas
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                Rp{{ $course->course->price_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ $course->day }}
                                            </td>
                                            <td class="text-center">
                                                {{ $course->start_date_string }} - {{ $course->finish_date_string }}
                                            </td>
                                        </tr>
                                        @php($no++)
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div id="closeModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin menutup dokumen MR ini secara manual?</h3>
                    <br />

                    <form role="form">
                        <input type="hidden" id="closed-id" name="closed-id"/>
                        <label for="reason">Alasan Hapus:</label>
                        <textarea id="reason" name="reason" rows="5" class="form-control col-md-7 col-xs-12" style="resize: vertical"></textarea>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Tidak
                        </button>
                        <button type="submit" class="btn btn-danger closed">
                            <span class='glyphicon glyphicon-trash'></span> Ya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/datatables.css')) }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .box-section{
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 2px;
            padding: 10px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', '.close-modal', function(){
            $('#closeModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#closed-id').val($(this).data('id'));
        });

        $('.modal-footer').on('click', '.closed', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.material_requests.close') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#closed-id').val(),
                    'reason': $('#reason').val()
                },
                success: function(data) {
                    if ((data.errors)){
                        setTimeout(function () {
                            toastr.error('Gagal menutup MR!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                        }, 500);
                    }
                    else{
                        window.location.reload();
                    }
                }
            });
        });
    </script>
@endsection