@extends('admin.layouts.admin')

{{--@section('title','Buat Retur Baru')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Buat Transaksi Prorate Baru</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.transactions.prorate.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

            @if(count($errors))
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12 alert alert-danger alert-dismissible fade in" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">
                    Nomor Transaksi
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                           name="code" value="{{ $autoNumber }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auto_number"></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="flat" id="auto_number" name="auto_number" checked="checked"> Auto Number
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer" >
                    Customer
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" id="customer" name="customer" class="form-control col-md-7 col-xs-12"></select>
                    <input type="hidden" id="customer_id" name="customer_id" class="form-control col-md-7 col-xs-12" value="{{ $customer->id ?? '-1' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">
                    Tanggal
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="date" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('date')) parsley-error @endif"
                           name="date" value="{{ old('date') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="registration_fee">
                    Registration Fee
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="registration_fee" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('registration_fee')) parsley-error @endif"
                           name="registration_fee"/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_method" >
                    Metode Pembayaran
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="payment_method" name="payment_method" class="form-control col-md-7 col-xs-12">
                        <option value="-1" selected> - Pilih Metode Pembyaran - </option>
                        <option value="TUNAI">UANG TUNAI</option>
                        <option value="TRANSFER">TRANSFER BANK</option>
                        <option value="DEBIT">KARTU DEBIT</option>
                    </select>
                </div>
            </div>

            <hr/>

            @if(!empty($customer))
                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                        <h3 class="text-center">Detil Transaksi</h3>
                        <a class="add-modal btn btn-info" style="margin-bottom: 10px;">
                            <span class="glyphicon glyphicon-plus-sign"></span> Tambah
                        </a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="detail_table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%">
                                        Kelas
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Trainer
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Hari
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Biaya Cuti
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Lama Cuti
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Subtotal
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        Tindakan
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <input id="index_counter" name="index_counter" type="hidden" value="0"/>

            <hr/>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger" href="{{ route('admin.transactions') }}"> Batal</a>
                    <input type="submit" class="btn btn-success" value="Simpan">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <!-- Modal form to add new detail -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="schedule_add">Kelas:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="schedule_add" name="schedule_add"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="trainer_add">Trainer:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="trainer_add" name="trainer_add" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="cuti_add">Lama Cuti:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="cuti_add" name="cuti_add">
                                    <option value="-1">- Pilih Berapa Pertemuan -</option>
                                    <option value="1">1 Bulan</option>
                                    <option value="2">2 Bulan</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="4">4 Bulan</option>
                                    <option value="5">5 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="price_add">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_add" name="price_add" readonly>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to delete a form -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Apakah anda yakin ingin menghapus detail ini?</h3>
                    <br />
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="schedule_delete">Kelas:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="schedule_delete" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="trainer_delete">Trainer:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="trainer_delete" readonly>
                            </div>
                        </div>
                        <input type="hidden" name="deleted_id"/>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="button" class="btn btn-danger delete" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/select2.css')) }}
    {{ Html::style(mix('assets/admin/css/bootstrap-datetimepicker.css')) }}
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
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
    {{ Html::script(mix('assets/admin/js/autonumeric.js')) }}
    {{ Html::script(mix('assets/admin/js/stringbuilder.js')) }}
    {{ Html::script(mix('assets/admin/js/bootstrap-datetimepicker.js')) }}
    <script type="text/javascript">
        // Date Picker
        $('#date').datetimepicker({
            format: "DD MMM Y"
        });

        // Auto Numbering
        $('#auto_number').change(function(){
            if(this.checked){
                $('#code').val('{{ $autoNumber }}');
                $('#code').prop('readonly', true);
            }
            else{
                $('#code').val('');
                $('#code').prop('readonly', false);
            }
        });

        $('#customer').select2({
            placeholder: {
                id: '{{ $customer->id ?? '-1' }}',
                text: '{{ $customer->name ?? ' - Pilih Customer - ' }}'
            },
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('select.customers') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        $('#customer').on('select2:select', function (e) {
            var data = e.params.data;
            var createUrl = '{{ route('admin.transactions.prorate.create') }}';
            window.location.replace(createUrl + '?customer=' + data.id);
        });

        // Add autonumeric
        priceAddFormat = new AutoNumeric('#price_add', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0,
            emptyInputBehavior: "zero",
            modifyValueOnWheel: false
        });

        // Count cuti price for add
        $('#cuti_add').on('change', function (e) {
            var valueMonthAdd = this.value;

            if(valueMonthAdd !== -1){
                var monthAdd = parseInt(valueMonthAdd);
                var finalPriceAdd = monthAdd * 150000;

                priceAddFormat.clear();
                priceAddFormat.set(finalPriceAdd, {
                    decimalCharacter: ',',
                    digitGroupSeparator: '.',
                    minimumValue: '0',
                    decimalPlaces: 0,
                    emptyInputBehavior: "zero",
                    modifyValueOnWheel: false
                });
            }
            else{
                priceAddFormat.clear();
            }
        });

        // Add new detail
        $(document).on('click', '.add-modal', function() {
            var customerId = $('#customer_id').val();
            $('#schedule_add').select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Kelas - '
                },
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('select.schedule_prorates') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            customer: customerId,
                            course_type: '2'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#schedule_add').on('select2:select', function (e) {
                var data = e.params.data;
                var splitted = data.id.split('#');
                $('#trainer_add').val(splitted[2]);

                document.getElementById('month_add').value = '-1';
                priceAddFormat.clear();
            });

            $('.modal-title').text('Tambah Detail');
            $('#addModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        $('.modal-footer').on('click', '.add', function() {
            var scheduleAdd = $('#schedule_add').val();
            var normalPriceAdd = $('#normal_price_add').val();
            var monthAddStr = $('#month_add').val();

            // Validate schedule
            if(!scheduleAdd || scheduleAdd === ""){
                alert('Mohon pilih kelas!');
                return false;
            }

            // Validate prorate
            if(monthAddStr === "-1"){
                alert('Mohon pilih lama cuti!');
                return false;
            }

            var monthAddInt = parseInt(monthAddStr);
            var subTotalAddInt = monthAddInt * 150000;
            var subTotalAddStr = rupiahFormat(subTotalAdd);

            // Split schedule value
            var splitted = scheduleAdd.split('#');

            // Increase idx
            var idx = $('#index_counter').val();
            idx++;
            $('#index_counter').val(idx);

            var sbAdd = new stringbuilder();

            sbAdd.append("<tr class='item" + idx + "'>");
            sbAdd.append("<td class='text-center'>" + splitted[1] + "<input type='hidden' name='schedule[]'  value='" + splitted[0] + "'/>")
            sbAdd.append("<td class='text-center'>" + splitted[2] + "</td>");
            sbAdd.append("<td class='text-center'>" + splitted[3] + "</td>");
            sbAdd.append("<td class='text-right>150.000</td>");
            sbAdd.append("<td class='text-right'>" + monthAddStr + " Bulan<input type='hidden' name='month[]' value='" + monthAddStr + "'/></td>");
            sbAdd.append("<td class='text-right'>" + subTotalAddStr + "</td>");
            sbAdd.append("<td class='text-center'>");
            sbAdd.append("<a class='delete-modal btn btn-danger' data-id='" + idx + "' data-schedule='" + scheduleAdd + "'><span class='glyphicon glyphicon-trash'></span></a>");
            sbAdd.append("</td>");
            sbAdd.append("</tr>");

            $('#detail_table').append(sbAdd.toString());

            // Reset add form modal
            $('#schedule_add').val(null).trigger('change');
            $('#trainer_add').val('');
            document.getElementById('month_add').value = '-1';
            priceAddFormat.clear();
        });

        $("#addModal").on('hidden.bs.modal', function () {
            // Reset add form modal
            $('#schedule_add').val(null).trigger('change');
            $('#trainer_add').val('');
            document.getElementById('month_add').value = '-1';
            priceAddFormat.clear();
        });

        // Delete detail
        var deletedId = "0";
        $(document).on('click', '.delete-modal', function() {
            $('.modal-title').text('Hapus Detail');
            deletedId = $(this).data('id');
            var scheduleDelete = $(this).data('schedule');
            var splitted = scheduleDelete.split('#');
            $('#schedule_delete').val(splitted[1]);
            $('#trainer_delete').val(splitted[2]);
            $('#deleteModal').modal('show');
        });

        $('.modal-footer').on('click', '.delete', function() {
            // Decrease idx
            var idx = $('#index_counter').val();
            idx--;
            $('#index_counter').val(idx);

            $('.item' + deletedId).remove();
        });

        function rupiahFormat(nStr) {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }
    </script>
@endsection