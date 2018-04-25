@extends('admin.layouts.admin')

@section('title', 'Daftar Surat Jalan')

@section('content')

    <div class="row">
        @include('partials._success')
        <div class="nav navbar-left">
            <form class="form-inline" style="margin-bottom: 10px;">
                <div class="form-group">
                    <label for="filter-status">Status:</label>
                    <select id="filter-status" class="form-control" onchange="filterStatus(this)">
                        <option value="0" @if($filterStatus == '0') selected @endif>Semua</option>
                        <option value="3" @if($filterStatus == '3') selected @endif>Open</option>
                        <option value="4" @if($filterStatus == '4') selected @endif>Close</option>
                        <option value="5" @if($filterStatus == '5') selected @endif>Cancel</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="nav navbar-right">
            <a href="{{ route('admin.delivery_orders.create') }}" class="btn btn-app">
                <i class="fa fa-plus"></i> Tambah
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%" id="pr-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat Jalan</th>
                <th>Gudang Keberangkatan</th>
                <th>Gudang Tujuan</th>
                <th>Alat Berat</th>
                <th>Nomor PR</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Tindakan</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div id="confirm_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin mengkonfirmasi barang datang?</h3>
                    <br />

                    <form role="form">
                        <input type="hidden" id="confirmed-id" name="confirmed-id"/>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Tidak
                        </button>
                        <button type="submit" class="btn btn-success confirm">
                            <span class='glyphicon glyphicon-check'></span> Ya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="cancel_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin membatalkan surat jalan?</h3>
                    <br />

                    <form role="form">
                        <input type="hidden" id="canceled-id" name="canceled-id"/>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Tidak
                        </button>
                        <button type="submit" class="btn btn-success cancel">
                            <span class='glyphicon glyphicon-check'></span> Ya
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
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/datatables.js')) }}
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(function() {
            $('#pr-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('datatables.delivery_orders') !!}',
                    data: {
                        'status': '{{ $filterStatus }}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', orderable: false, searchable: false, class: 'text-center'},
                    { data: 'code', name: 'code', class: 'text-center'},
                    { data: 'from_warehouse', name: 'from_warehouse', class: 'text-center'},
                    { data: 'to_warehouse', name: 'to_warehouse', class: 'text-center'},
                    { data: 'machinery', name: 'machinery', class: 'text-center'},
                    { data: 'pr_code', name: 'pr_code', class: 'text-center'},
                    { data: 'remark', name: 'remark' },
                    { data: 'created_at', name: 'created_at', class: 'text-center'},
                    { data: 'status', name: 'status', class: 'text-center'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });

        function filterStatus(e){
            // Get status filter value
            var status = e.value;

            var url = "/admin/delivery_orders?status=" + status;

            window.location = url;
        }

        $(document).on('click', '.confirm-modal', function(){
            $('#confirm_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#confirmed-id').val($(this).data('id'));
        });

        $('.modal-footer').on('click', '.confirm', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.delivery_orders.confirm') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#confirmed-id').val(),
                },
                success: function(data) {
                    if ((data.errors)){
                        setTimeout(function () {
                            toastr.error('Gagal konfirmasi', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                        }, 500);
                    }
                    else{
                        window.location = '{{ route('admin.delivery_orders') }}';
                    }
                }
            });
        });

        $(document).on('click', '.cancel-modal', function(){
            $('#cancel_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#canceled-id').val($(this).data('id'));
        });

        $('.modal-footer').on('click', '.cancel', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.delivery_orders.cancel') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#canceled-id').val(),
                },
                success: function(data) {
                    if ((data.errors)){
                        setTimeout(function () {
                            toastr.error('Gagal membatalkan', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                        }, 500);
                    }
                    else{
                        window.location = '{{ route('admin.delivery_orders') }}';
                    }
                }
            });
        });
    </script>
@endsection
