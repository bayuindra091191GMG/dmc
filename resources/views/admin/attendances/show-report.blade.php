@extends('admin.layouts.admin')

@section('title','Data Absensi '. $course->name . ' Bulan ' . $chosenDate)

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar-left">
                <a class="btn btn-default" href="{{ route('admin.attendances.report') }}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
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
                        Nama Kelas
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $course->name }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Tipe
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        :
                        @if($course->type == 1)
                            Package
                        @else
                            Class
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Trainer
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        :
                        @if($course->coach->name == "Default")
                            Any Trainer
                        @else
                            {{ $course->coach->name }}
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Fee
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : Rp{{ $course->price_string }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Jumlah Pertemuan
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $course->meeting_amount }}
                    </div>
                </div>

                @if($course->type == 1)
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12">
                            Valid
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            : {{ $course->valid }} Hari
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Hari & Jam
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        :
                        @if($course->type == 1)
                            Bebas
                        @else
                            @for($i = 0; $i<count($days); $i++)
                                {{ $days[$i] }} - {{ $hours[$i] }},
                            @endfor
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12">
                        Status
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        : {{ $course->status->description }}
                    </div>
                </div>

                <hr/>

                @if($attendanceData != null)
                        <div class="form-group">
                            <div class="col-lg-12 col-md-12 col-xs-12 column">
                                <h4 class="text-center">Absensi</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Pertemuan ke 1
                                            </th>
                                            <th>
                                                Pertemuan ke 2
                                            </th>
                                            <th>
                                                Pertemuan ke 3
                                            </th>
                                            <th>
                                                Pertemuan ke 4
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($idx = 0)
                                        @foreach($customers as $customer)
                                            <tr>
                                                <td>
                                                    {{ $customer->customer->name }}
                                                </td>
                                                @for($j=0; $j<4; $j++)
                                                    <td>
                                                        @if(count($attendanceData[$idx]) > $j)
                                                            {{ $attendanceData[$idx][$j]->created_at }}
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                @endfor
                                            </tr>
                                            @php($idx++)
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

        {{--$('.modal-footer').on('click', '.closed', function() {--}}
            {{--$.ajax({--}}
                {{--type: 'POST',--}}
                {{--url: '{{ route('admin.material_requests.close') }}',--}}
                {{--data: {--}}
                    {{--'_token': '{{ csrf_token() }}',--}}
                    {{--'id': $('#closed-id').val(),--}}
                    {{--'reason': $('#reason').val()--}}
                {{--},--}}
                {{--success: function(data) {--}}
                    {{--if ((data.errors)){--}}
                        {{--setTimeout(function () {--}}
                            {{--toastr.error('Gagal menutup MR!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});--}}
                        {{--}, 500);--}}
                    {{--}--}}
                    {{--else{--}}
                        {{--window.location.reload();--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    </script>
@endsection