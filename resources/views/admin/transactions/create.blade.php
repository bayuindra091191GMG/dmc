@extends('admin.layouts.admin')

{{--@section('title','Buat Retur Baru')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Buat Transaksi Baru</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.transactions.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer" >
                    Student
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" id="customer" name="customer" class="form-control col-md-7 col-xs-12">
                        @if(!empty($customer))
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="customer_id" name="customer_id" class="form-control col-md-7 col-xs-12" value="{{ $customer->id ?? '-1' }}">
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

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="voucher_code">
                    Voucher
                </label>
                <div class="col-md-5 col-sm-6 col-xs-12">
                    @if($vouchers == null)
                        <select id="voucher_code" name="voucher_code" class="form-control col-md-7 col-xs-12">
                            <option value="-1" selected> - Tidak Ada Voucher - </option>
                        </select>
                    @else
                        <select id="voucher_code" name="voucher_code" class="form-control col-md-7 col-xs-12">
                            @foreach($vouchers as $voucher)
                                <option value="{{ $voucher->voucher->name }}">{{ $voucher->voucher->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-md-1 col-sm-6 col-xs-12" style="text-align: right;">
                    <a href="#" id="checkVoucher" class="btn btn-primary">Gunakan</a>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="voucher_amount" id="voucher_label">
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="voucher_amount" type="text" class="form-control col-md-7 col-xs-12"
                           name="voucher_amount" readonly/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total">
                    Total
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="total" type="text" class="form-control col-md-7 col-xs-12"
                           name="total" value="0" readonly/>
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
                                    <th class="text-center" style="width: 20%">
                                        Kelas
                                    </th>
                                    <th class="text-center" style="width: 20%">
                                        Trainer
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        Hari
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        Harga
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        Jumlah Bulan/Paket
                                    </th>
                                    {{--<th class="text-center" style="width: 10%">--}}
                                        {{--Diskon--}}
                                    {{--</th>--}}
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
                            <label class="control-label col-sm-2" for="price_add">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_add" name="price_add" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="month_add">Jumlah Bulan:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="month_add" name="month_add" value="1">
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-sm-2" for="discount_add">Diskon:</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<input type="text" class="form-control" id="discount_add" name="discount_add">--}}
                            {{--</div>--}}
                        {{--</div>--}}
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

    <!-- Modal form to edit a detail -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <input type="hidden" id="edited_row_id"/>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="schedule_edit">Kelas:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="schedule_edit" name="schedule_edit"></select>
                                <input type="hidden" id="schedule_old_value"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="trainer_edit">Trainer:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="trainer_edit" name="trainer_edit" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="price_edit">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_edit" name="price_edit" readonly>
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-sm-2" for="discount_edit">Diskon:</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<input type="text" class="form-control" id="discount_edit" name="discount_edit">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Batal
                        </button>
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Simpan
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
                id: '-1',
                text: ' - Pilih Customer - '
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
            window.location.replace('/admin/transactions/create?customer=' + data.id);
        });

        // Add autonumeric
        regisrationFeeFormat = new AutoNumeric('#registration_fee', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

        @if(!empty(old('registration_fee')))

            regisrationFeeFormat.clear();

            var fee = '{{ old('registration_fee') }}';
            var feeClean = fee.replace(/\./g,'');

            regisrationFeeFormat.set(feeClean, {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                minimumValue: '0',
                decimalPlaces: 0
            });
        @endif

        priceAddFormat = new AutoNumeric('#price_add', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

        priceEditFormat = new AutoNumeric('#price_edit', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

        // discountAddFormat = new AutoNumeric('#discount_add', {
        //     decimalCharacter: ',',
        //     digitGroupSeparator: '.',
        //     minimumValue: '0',
        //     decimalPlaces: 0
        // });
        //
        // discountEditFormat = new AutoNumeric('#discount_edit', {
        //     decimalCharacter: ',',
        //     digitGroupSeparator: '.',
        //     minimumValue: '0',
        //     decimalPlaces: 0
        // });

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
                    url: '{{ route('select.schedules') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            customer: customerId,
                            course_type: 0
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
                $('#month_add').val(1);

                priceAddFormat.clear();
                priceAddFormat.set(splitted[4], {
                    decimalCharacter: ',',
                    digitGroupSeparator: '.',
                    minimumValue: '0',
                    decimalPlaces: 0
                });
            });

            $('.modal-title').text('Tambah Detail');
            $('#addModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('.modal-footer').on('click', '.add', function() {
            var scheduleAdd = $('#schedule_add').val();
            var priceAdd = $('#price_add').val();
            var monthAdd = $('#month_add').val();
            // var discountAdd = $('#discount_add').val();

            // Validate schedule
            if(!scheduleAdd || scheduleAdd === ""){
                alert('Mohon pilih kelas!');
                return false;
            }

            // Validate price
            if(!priceAdd || priceAdd === "" || priceAdd === "0"){
                alert('Mohon isi harga!')
                return false;
            }

            // Validate month
            if(!monthAdd || monthAdd === "" || monthAdd === "0"){
                alert('Mohon isi jumlah bulan!')
                return false;
            }

            // Split schedule value
            var splitted = scheduleAdd.split('#');

            // Filter variables
            var price = 0;
            let month = parseFloat(monthAdd);
            if(priceAdd && priceAdd !== "" && priceAdd !== "0"){
                var priceClean = priceAdd.replace(/\./g,'');
                price = parseFloat(priceClean);
            }

            // Count Subtotal
            let subtotal = price * month;

            // Add Total
            let tmpStrTotal = $('#total').val().replace(/\./g,'');
            let tmpTotal = parseFloat(tmpStrTotal);
            tmpTotal = tmpTotal + subtotal;
            $('#total').val(rupiahFormat(tmpTotal));

            // var discount = 0;
            // if(discountAdd && discountAdd !== "" && discountAdd !== "0"){
            //     var discountClean = discountAdd.replace(/\./g,'');
            //     discount = parseFloat(discountClean);
            //
            //     // Validate discount
            //     if(discount > price){
            //         alert('Diskon tidak boleh melebihi harga!')
            //         return false;
            //     }
            // }

            // Increase idx
            var idx = $('#index_counter').val();
            idx++;
            $('#index_counter').val(idx);

            var sbAdd = new stringbuilder();

            sbAdd.append("<tr class='item" + idx + "'>");
            sbAdd.append("<td class='text-center'>" + splitted[1] + "<input type='hidden' name='schedule[]'  value='" + splitted[0] + "'/>")
            sbAdd.append("<td class='text-center'>" + splitted[2] + "</td>");
            sbAdd.append("<td class='text-center'>" + splitted[3] + "</td>");
            sbAdd.append("<td class='text-right'>" + priceAdd + "<input type='hidden' name='price[]' value='" + priceAdd + "'/></td>");

            // if(discount > 0){
            //     sbAdd.append("<td class='text-right'>" + discountAdd + "<input type='hidden' name='discount[]' value='" + discountAdd + "'/></td>");
            // }
            // else{
            //     sbAdd.append("<td class='text-right'>0<input type='hidden' name='discount[]' value='0'/></td>");
            // }
            //
            // var subtotal = 0;
            // if(discount > 0){
            //     subtotal = price - discount;
            // }
            // else{
            //     subtotal = price;
            // }
            var subtotalString = rupiahFormat(subtotal);

            var priceId = 'priceId' + idx;
            sbAdd.append("<td class='text-center'>" + monthAdd + "<input type='hidden' name='months[]' value='" + monthAdd + "'/></td>");
            sbAdd.append("<td class='text-right'>" + subtotalString + "<input type='hidden' id='" + priceId + "' value='" + subtotalString + "'/></td>");

            sbAdd.append("<td class='text-center'>");
            // sbAdd.append("<a class='edit-modal btn btn-info' data-id='" + idx + "' data-schedule='" + scheduleAdd + "' data-price='" + price + "'><span class='glyphicon glyphicon-edit'></span></a>");
            sbAdd.append("<a class='delete-modal btn btn-danger' data-id='" + idx + "' data-schedule='" + scheduleAdd + "' data-price='" + price + "'><span class='glyphicon glyphicon-trash'></span></a>");
            sbAdd.append("</td>");
            sbAdd.append("</tr>");

            $('#detail_table').append(sbAdd.toString());

            // Reset add form modal
            $('#schedule_add').val(null).trigger('change');
            $('#trainer_add').val('');
            priceAddFormat.clear();
            // $('#discount_add').val('');
        });

        $("#addModal").on('hidden.bs.modal', function () {
            // Reset add form modal
            $('#schedule_add').val(null).trigger('change');
            $('#trainer_add').val('');
            priceAddFormat.clear();
            // $('#discount_add').val('');
        });

        // Edit detail
        $(document).on('click', '.edit-modal', function() {

            $('.modal-title').text('Ubah Detail');

            // Split schedule value
            var splitted = $(this).data('schedule').split('#');
            $('#edited_row_id').val($(this).data('id'));

            $('#schedule_old_value').val($(this).data('schedule'));
            $('#schedule_edit').select2({
                placeholder: {
                    id: $(this).data('schedule'),
                    text: splitted[1]
                },
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('select.schedules') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            customer: $('#customer_id').val()
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#schedule_edit').on('select2:select', function (e) {
                var data = e.params.data;
                var splitted = data.id.split('#');
                $('#trainer_edit').val(splitted[2]);

                priceEditFormat.clear();
                priceEditFormat.set(splitted[4], {
                    decimalCharacter: ',',
                    digitGroupSeparator: '.',
                    minimumValue: '0',
                    decimalPlaces: 0
                });
            });

            priceEditFormat.clear();
            priceEditFormat.set($(this).data('price'), {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                minimumValue: '0',
                decimalPlaces: 0
            });

            // discountEditFormat.clear();
            // discountEditFormat.set($(this).data('discount'), {
            //     decimalCharacter: ',',
            //     digitGroupSeparator: '.',
            //     minimumValue: '0',
            //     decimalPlaces: 0
            // });

            $('#trainer_edit').val(splitted[2]);
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            var scheduleEdit = $('#schedule_edit').val();
            var priceEdit = $('#price_edit').val();
            // var discountEdit = $('#discount_edit').val();
            var editedRowId = $('#edited_row_id').val();

            // Validate price
            if(!priceEdit || priceEdit === "" || priceEdit === "0"){
                alert('Mohon isi harga!')
                return false;
            }

            var scheduleValue = '';
            if(scheduleEdit && scheduleEdit !== '-1'){
                scheduleValue = scheduleEdit;
            }
            else{
                scheduleValue = $('#schedule_old_value').val();
            }

            // Split schedule value
            var splitted = scheduleValue.split('#');

            // Filter variables
            var price = 0;
            if(priceEdit && priceEdit !== "" && priceEdit !== "0"){
                var priceClean = priceEdit.replace(/\./g,'');
                price = parseFloat(priceClean);
            }
            // var discount = 0;
            // if(discountEdit && discountEdit !== "" && discountEdit !== "0"){
            //     var discountClean = discountEdit.replace(/\./g,'');
            //     discount = parseFloat(discountClean);
            //
            //     // Validate discount
            //     if(discount > price){
            //         alert('Diskon tidak boleh melebihi harga!')
            //         return false;
            //     }
            // }

            var sbEdit = new stringbuilder();

            sbEdit.append("<tr class='item" + editedRowId + "'>");
            sbEdit.append("<td class='text-center'>" + splitted[1] + "<input type='hidden' name='schedule[]'  value='" + splitted[0] + "'/>")
            sbEdit.append("<td class='text-center'>" + splitted[2] + "</td>");
            sbEdit.append("<td class='text-center'>" + splitted[3] + "</td>");
            sbEdit.append("<td class='text-right'>" + priceEdit + "<input type='hidden' name='price[]' value='" + priceEdit + "'/></td>");

            // if(discount > 0){
            //     sbEdit.append("<td class='text-right'>" + discountEdit + "<input type='hidden' name='discount[]' value='" + discountEdit + "'/></td>");
            // }
            // else{
            //     sbEdit.append("<td class='text-right'>0<input type='hidden' name='discount[]' value='0'/></td>");
            // }
            //
            // var subtotal = 0;
            // if(discount > 0){
            //     subtotal = price - discount;
            // }
            // else{
            //     subtotal = price;
            // }
            var subtotalString = rupiahFormat(price);

            sbEdit.append("<td class='text-right'>" + subtotalString + "<input type='hidden' value='" + subtotalString + "'/></td>");

            sbEdit.append("<td class='text-center'>");
            sbEdit.append("<a class='edit-modal btn btn-info' data-id='" + editedRowId + "' data-schedule='" + scheduleValue + "' data-price='" + price + "'><span class='glyphicon glyphicon-edit'></span></a>");
            sbEdit.append("<a class='delete-modal btn btn-danger' data-id='" + editedRowId + "' data-schedule='" + scheduleValue + "' data-price='" + price + "'><span class='glyphicon glyphicon-trash'></span></a>");
            sbEdit.append("</td>");
            sbEdit.append("</tr>");

            $('.item' + editedRowId).replaceWith(sbEdit.toString());

            // Reset edit form modal
            $('#edited_row_id').val('');
            $('#schedule_edit').val(null).trigger('change');
            $('#trainer_edit').val('');
            priceEditFormat.clear();
            // $('#discount_edit').val('');
        });

        $("#editModal").on('hidden.bs.modal', function () {
            // Reset edit form modal
            $('#edited_row_id').val('');
            $('#schedule_edit').val(null).trigger('change');
            $('#trainer_edit').val('');
            priceEditFormat.clear();
            // $('#discount_edit').val('');
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

            let tmpStrTotal = $('#total').val().replace(/\./g,'');
            let tmpSubTotal = $('#priceId'+deletedId).val().replace(/\./g,'');
            let subtotal = parseFloat(tmpSubTotal);
            let tmpTotal = parseFloat(tmpStrTotal);
            tmpTotal = tmpTotal - subtotal;
            $('#total').val(rupiahFormat(tmpTotal));

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

        $("#checkVoucher").on("click", function(){
            var voucherCode = $('#voucher_code').val();
            var customerId = $('#customer_id').val();

            $.ajax({
                url: "{{ route('admin.vouchers.check') }}",
                type: 'POST',
                datatype : "application/json",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'voucher_name': voucherCode,
                    'customer_id': customerId
                },
                success: function(result){
                    if(result.type === 'discount_total'){
                        let tmpStrTotal = $('#total').val().replace(/\./g,'');
                        let totalAmount = parseFloat(tmpStrTotal);
                        totalAmount -= result.discount_total;
                        $('#total').val(rupiahFormat(totalAmount));
                        $('#voucher_amount').val(rupiahFormat(result.discount_total));
                    }
                    else if(result.type === 'discount_percentage'){
                        let tmpStrTotal = $('#total').val().replace(/\./g,'');
                        let totalPercentage = parseFloat(tmpStrTotal);
                        totalPercentage = totalPercentage - (totalPercentage * result.discount_percentage / 100);
                        $('#total').val(rupiahFormat(totalPercentage));
                        $('#voucher_amount').val(result.discount_percentage + '%');
                    }
                    else if(result.type === 'free_package'){
                        $('#voucher_label').html('Free Package');
                        $('#voucher_amount').val(result.free_package);
                    }

                },
                error: function(errors){
                    alert(errors);
                }
            });
        });
    </script>
@endsection