@extends('admin.layouts.admin')

{{--@section('title','Buat Retur Baru')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Informasi Sesi/Kelas {{ $courseType }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table id="table_course_info" class="table table-striped table-bordered" style="width:100%; color: #000 !important;">
                    <thead>
                    <tr>
                        <th class="text-center">Nama Kelas</th>
                        <th class="text-center">Trainer</th>
                        <th class="text-center">Hari</th>
                        <th class="text-center">Jam</th>
                        <th class="text-center" style="width: 10%">Kapasitas</th>
                        <th class="text-center" style="width: 10%">Jumlah Student</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($couseDetails as $detail)
                       <tr>
                           <td><a href="{{ route('admin.courses.show', ['course' => $detail->course]) }}" style="text-decoration: underline;">{{ $detail->course->name }}</a></td>
                           <td class="text-center">{{ $detail->course->coach->name }}</td>
                           <td class="text-center">{{ $detail->day_name }}</td>
                           <td class="text-center">{{ $detail->time }}</td>
                           <td class="text-right">{{ $detail->max_capacity }}</td>
                           <td class="text-right">{{ $detail->current_capacity }}</td>
                       </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    @parent
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css"/>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
    <script type="text/javascript">
        $('#table_course_info').dataTable({
            'pageLength': 25
        });

    </script>
@endsection