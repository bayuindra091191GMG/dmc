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
                        Photo
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        :
                        @if(!empty($customer->photo_path))
                            <img src="{{ asset('storage/students/'. $customer->photo_path) }}" id="existing_photo" style="width: 400px; height: auto;">
                        @else
                            Tidak Ada Foto
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        ID Member
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->member_id }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nama
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->barcode ?? '-' }}
                    </div>
                </div>

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
                        : {{ $customer->parent_name ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Usia
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->age ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tanggal Lahir
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $dob }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Nomor Telepon
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $customer->phone ?? '-' }}
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
                        : {{ $customer->address ?? '-' }}
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

                @if($schedules != null)
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-xs-12 column">
                            <h4 class="text-center">DAFTAR KELAS YANG DIIKUTI</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr >
                                        <th class="text-center" style="width: 5%">
                                            No
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Nama
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Trainer
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Tipe
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Hari
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Jumlah Pertemuan
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            Masa Kelas
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Fee
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Status
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                            Action
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php($no = 1)
                                    @foreach($schedules as $schedule)
                                        <tr>
                                            <td class="text-center">
                                                {{ $no }}
                                            </td>
                                            <td class="text-center">
                                                {{ $schedule->course->name }}
                                            </td>
                                            <td class="text-center">
                                                @if($schedule->course->type == 1)
                                                    BEBAS
                                                @else
                                                    {{ $schedule->course->coach->name }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($schedule->course->type == 1)
                                                    MUAYTHAI
                                                @elseif($schedule->course->type == 2)
                                                    DANCE
                                                @elseif($schedule->course->type == 3)
                                                    PRIVATE
                                                @else
                                                    GYMNASTIC
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ strtoupper($schedule->day) }}
                                            </td>
                                            <td class="text-center">
                                                {{ $schedule->meeting_amount }}
                                            </td>
                                            <td class="text-center">
                                                {{ $schedule->start_date_string }} - {{ $schedule->finish_date_string }}
                                            </td>
                                            <td class="text-right">
                                                {{ $schedule->course->price_string }}
                                            </td>
                                            <td class="text-center">
                                                {{ strtoupper($schedule->status->description) }}
                                            </td>
                                            <td class="text-center">
                                                <a class='btn btn-xs btn-info' href="{{route('admin.schedules.edit', ['schedule'=>$schedule->id])}}" data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>
                                                @if($schedule->status_id !== 6)
                                                    <a class='btn btn-xs btn-danger stop-modal' data-toggle='tooltip' data-placement='top' data-schedule-id="{{ $schedule->id }}"><i class='fa fa-trash'></i></a>
                                                @endif
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

    <div id="stopModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin menghapus jadwal kelas ini?</h3>
                    <br />

                    <form role="form">
                        <input type="hidden" id="schedule_id" name="schedule_id"/>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Tidak
                        </button>
                        <button type="submit" class="btn btn-danger stop">
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
        $(document).on('click', '.stop-modal', function(){
            $('#stopModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#schedule_id').val($(this).data('schedule-id'));
        });

        $('.modal-footer').on('click', '.stop', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.schedules.destroy') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#schedule_id').val()
                },
                success: function(data) {
                    if ((data.errors)){
                        if(data.errors === "TRANSACTION_DETAIL"){
                            setTimeout(function () {
                                toastr.error('Jadwal Tidak dapat dihapus karena terdapat Transaksi yang dibuat!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                            }, 500);
                        }
                        else if(data.errors === "LEAF"){
                            setTimeout(function () {
                                toastr.error('Jadwal Tidak dapat dihapus karena terdapat Cuti yang dibuat!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                            }, 500);
                        }
                        else if(data.errors === "ATTENDANCE"){
                            setTimeout(function () {
                                toastr.error('Jadwal Tidak dapat dihapus karena terdapat Absensi yang dibuat!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                            }, 500);
                        }
                        else{
                            setTimeout(function () {
                                toastr.error('Gagal menghapus jadwal kelas!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                            }, 500);
                        }
                    }
                    else{
                        window.location.reload();
                    }
                }
            });
        });
    </script>
@endsection