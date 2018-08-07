@extends('admin.layouts.admin')

{{--@section('title','Buat Retur Baru')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Ubah Transaksi Prorate {{ $header->code }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.transactions.update', $header->id],'method' => 'put','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
                    <input id="code" name="code" type="text" class="form-control col-md-7 col-xs-12" value="{{ $header->code }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer" >
                    Customer
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="customer" name="customer" type="text" class="form-control col-md-7 col-xs-12" value="{{ $header->customer->name }}" readonly>
                    <input id="customer_id" type="hidden" value="{{ $header->customer_id }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">
                    Tanggal
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="date" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('date')) parsley-error @endif"
                           name="date" value="{{ $date }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="registration_fee">
                    Registration Fee
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="registration_fee" name="registration_fee" type="text" class="form-control col-md-7 col-xs-12" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger" href="{{ route('admin.transactions.show', ['transaction' => $header->id]) }}"> Batal</a>
                    <input type="submit" class="btn btn-success" value="Simpan">
                </div>
            </div>

            <hr/>

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
                                <th class="text-center" style="width: 10%">
                                    Hari
                                </th>
                                <th class="text-center" style="width: 15%">
                                    Prorate
                                </th>
                                <th class="text-center" style="width: 15%">
                                    Harga Prorate
                                </th>
                                {{--<th class="text-center" style="width: 10%">--}}
                                    {{--Diskon--}}
                                {{--</th>--}}
                                <th class="text-center" style="width: 15%">
                                    Subtotal
                                </th>
                                <th class="text-center" style="width: 15%">
                                    Tindakan
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($header->transaction_details as $detail)
                                <tr class="item{{ $detail->id }}">
                                    <td class="text-center">{{ $detail->schedule->course->name }}</td>
                                    <td class="text-center">{{ $detail->schedule->course->coach->name }}</td>
                                    <td class="text-center">{{ $detail->schedule->day }}</td>
                                    <td class="text-center">{{ $detail->prorate }} Pertemuan</td>
                                    <td class="text-right">{{ $detail->prorate_price_string }}</td>
                                    {{--<td class="text-right">{{ $detail->discount_string ?? '0' }}</td>--}}
                                    <td class="text-right">{{ $detail->subtotal_string }}</td>
                                    <td class="text-center">
                                        @php( $scheduleValue = $detail->schedule->id. '#'. $detail->schedule->course->name. '#'. $detail->schedule->course->coach->name. '#'. $detail->schedule->day. '#'. $detail->schedule->course->price )
                                        <a class="edit-modal btn btn-info" data-id="{{ $detail->id }}" data-schedule="{{ $scheduleValue }}" data-prorate="{{ $detail->prorate }}" data-normal-price="{{ $detail->price }}" data-price="{{ $detail->prorate_price }}"><span class="glyphicon glyphicon-edit"></span></a>
                                        {{--<a class="delete-modal btn btn-danger" data-id="{{ $detail->id }}" data-schedule="{{ $scheduleValue }}" data-prorate="{{ $detail->prorate }}" data-normal-price="{{ $detail->price }}" data-price="{{ $detail->prorate_price }}"><span class="glyphicon glyphicon-trash"></span></a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
                                <p class="errorSchedule text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="trainer_add">Trainer:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="trainer_add" name="trainer_add" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="prorate_add">Prorate:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="prorate_add" name="prorate_add">
                                    <option value="-1">- Pilih Berapa Pertemuan -</option>
                                    <option value="1">1 Pertemuan</option>
                                    <option value="2">2 Pertemuan</option>
                                    <option value="3">3 Pertemuan</option>
                                </select>
                                <p class="errorProrate text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <input type="hidden" id="normal_price_add" name="normal_price_add"/>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="price_add">Harga:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="price_add" name="price_add" readonly>
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-sm-2" for="discount_add">Diskon:</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<input type="text" class="form-control" id="discount_add" name="discount_add">--}}
                                {{--<p class="errorDiscount text-center alert alert-danger hidden"></p>--}}
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
                                <p class="errorSchedule text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="trainer_edit">Trainer:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="trainer_edit" name="trainer_edit" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="prorate_edit">Prorate:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="prorate_edit" name="prorate_edit">
                                    <option value="1">1 Pertemuan</option>
                                    <option value="2">2 Pertemuan</option>
                                    <option value="3">3 Pertemuan</option>
                                </select>
                                <p class="errorProrate text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <input type="hidden" id="normal_price_edit" name="normal_price_edit"/>
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
                                {{--<p class="errorDiscount text-center alert alert-danger hidden"></p>--}}
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
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript">
        // Date Picker
        $('#date').datetimepicker({
            format: "DD MMM Y"
        });

        // Add autonumeric
        var fee = '{{ $header->registration_fee }}';
        var feeClean = fee.replace(/\./g,'');

        regisrationFeeFormat = new AutoNumeric('#registration_fee', feeClean, {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            minimumValue: '0',
            decimalPlaces: 0
        });

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

        // Count prorate price for add
        $('#prorate_add').on('change', function (e) {
            var valueProrateAdd = this.value;

            var normalPriceAdd = parseFloat($('#normal_price_add').val());

            if(normalPriceAdd && normalPriceAdd !== 0){
                var splitted = $('#schedule_add').val().split('#');
                var finalPriceAdd = 0;

                var prorateAddInt = parseInt(valueProrateAdd);;
                var isDanceBaby = splitted[5] === '8';
                if(isDanceBaby){
                    finalPriceAdd = normalPriceAdd * ( prorateAddInt / 8 );
                }
                else{
                    if(prorateAddInt > 3){
                        prorateAddInt = 3;
                    }
                    finalPriceAdd = normalPriceAdd * ( prorateAddInt / 4 );
                }

                priceAddFormat.clear();
                priceAddFormat.set(finalPriceAdd, {
                    decimalCharacter: ',',
                    digitGroupSeparator: '.',
                    minimumValue: '0',
                    decimalPlaces: 0
                });
            }
        });

        // Count prorate price for edit
        $('#prorate_edit').on('change', function (e) {
            var valueProrateEdit = this.value;
            var splitted = $('#schedule_old_value').val().split('#');

            var normalPriceEdit = parseFloat($('#normal_price_edit').val());
            var finalPriceEdit = 0;

            var prorateEditInt = parseInt(valueProrateEdit);
            var isDanceBaby = splitted[5] === '8';
            if(isDanceBaby){
                finalPriceEdit = normalPriceEdit * ( prorateEditInt / 8 );
            }
            else{
                if(prorateEditInt > 3){
                    prorateEditInt = 3;
                }
                finalPriceEdit = normalPriceEdit * ( prorateEditInt / 4 );
            }

            priceEditFormat.clear();
            priceEditFormat.set(finalPriceEdit, {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                minimumValue: '0',
                decimalPlaces: 0
            });
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
                    url: '{{ route('select.schedules') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            customer: customerId,
                            course_type: 2
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

                $('#normal_price_add').val(splitted[4]);

                if($('#prorate_add').val() !== '-1'){
                    // Automatically count final prorate price
                    var valueProrateAdd = $('#prorate_add').val();
                    var normalPriceAdd = parseFloat(splitted[4]);
                    var finalPriceAdd = 0;

                    var prorateAddInt = parseInt(valueProrateAdd);;
                    var isDanceBaby = splitted[5] === '8';
                    if(isDanceBaby){
                        finalPriceAdd = normalPriceAdd * ( prorateAddInt / 8 );
                    }
                    else{
                        if(prorateAddInt > 3){
                            prorateAddInt = 3;
                        }
                        finalPriceAdd = normalPriceAdd * ( prorateAddInt / 4 );
                    }

                    priceAddFormat.clear();
                    priceAddFormat.set(finalPriceAdd, {
                        decimalCharacter: ',',
                        digitGroupSeparator: '.',
                        minimumValue: '0',
                        decimalPlaces: 0
                    });
                }
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
            var priceAdd = $('#price_add').val();
            // var discountAdd = $('#discount_add').val();
            var prorateAdd = $('#prorate_add').val();

            // Validate schedule
            if(!scheduleAdd || scheduleAdd === ""){
                alert('Mohon pilih kelas!');
                return false;
            }

            // Validate prorate
            if(prorateAdd === "-1"){
                alert('Mohon pilih prorate!');
                return false;
            }

            // Validate price
            if(!priceAdd || priceAdd === "" || priceAdd === "0"){
                alert('Mohon isi harga!')
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.prorate_details.store') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'header_id': '{{ $header->id }}',
                    'schedule': scheduleAdd,
                    'prorate': prorateAdd,
                    'prorate_price': priceAdd,
                    // 'discount': discountAdd
                },
                success: function(data) {
                    $('.errorSchedule').addClass('hidden');

                    if ((data.errors)) {
                        if(data.errors === 'EXISTS'){
                            setTimeout(function () {
                                $('#addModal').modal('show');
                                toastr.error('Kelas sudah ada!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                            }, 500);
                        }else{
                            setTimeout(function () {
                                $('#addModal').modal('show');
                                toastr.error('Gagal simpan data!', 'Peringatan', {timeOut: 5000});
                            }, 500);

                            if (data.errors.schedule) {
                                $('.errorSchedule').removeClass('hidden');
                                $('.errorSchedule').text(data.errors.schedule);
                            }
                            else if(data.errors.prorate){
                                $('.errorProrate').removeClass('hidden');
                                $('.errorProrate').text(data.errors.prorate);
                            }
                            else if(data.errors.discount){
                                $('.errorDiscount').removeClass('hidden');
                                $('.errorDiscount').text(data.errors.discount);
                            }
                        }
                    } else {
                        toastr.success('Berhasil simpan detail!', 'Sukses', {timeOut: 5000});

                        // Split schedule value
                        var splitted = scheduleAdd.split('#');

                        // Filter variables
                        var price = 0;
                        if(priceAdd && priceAdd !== "" && priceAdd !== "0"){
                            var priceClean = priceAdd.replace(/\./g,'');
                            price = parseFloat(priceClean);
                        }
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

                        sbAdd.append("<tr class='item" + data.id + "'>");
                        sbAdd.append("<td class='text-center'>" + splitted[1] + "</td>")
                        sbAdd.append("<td class='text-center'>" + splitted[2] + "</td>");
                        sbAdd.append("<td class='text-center'>" + splitted[3] + "</td>");
                        sbAdd.append("<td class='text-center'>" + prorateAdd + " Pertemuan</td>");
                        sbAdd.append("<td class='text-right'>" + priceAdd + "</td>");

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
                        var subtotalString = rupiahFormat(price);

                        sbAdd.append("<td class='text-right'>" + subtotalString + "<input type='hidden' value='" + subtotalString + "'/></td>");

                        sbAdd.append("<td class='text-center'>");
                        sbAdd.append("<a class='edit-modal btn btn-info' data-id='" + idx + "' data-schedule='" + scheduleAdd + "' data-prorate='" + prorateAdd + "' data-normal-price='" + normalPriceAdd + "' data-price='" + price + "'><span class='glyphicon glyphicon-edit'></span></a>");
                        // sbAdd.append("<a class='delete-modal btn btn-danger' data-id='" + idx + "' data-schedule='" + scheduleAdd + "' data-prorate='" + prorateAdd + "' data-normal-price='" + normalPriceAdd + "' data-price='" + price + "' data-discount='" + discount + "'><span class='glyphicon glyphicon-trash'></span></a>");
                        sbAdd.append("</td>");
                        sbAdd.append("</tr>");

                        $('#detail_table').append(sbAdd.toString());

                        // Reset add form modal
                        $('#schedule_add').val(null).trigger('change');
                        $('#trainer_add').val('');
                        document.getElementById('prorate_add').value = '-1';
                        $('#normal_price_add').val('');
                        priceAddFormat.clear();
                        // $('#discount_add').val('');
                    }
                },
            });


        });

        $("#addModal").on('hidden.bs.modal', function () {
            // Reset add form modal
            $('#schedule_add').val(null).trigger('change');
            $('#trainer_add').val('');
            document.getElementById('prorate_add').value = '-1';
            $('#normal_price_add').val('');
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

                $('#normal_price_edit').val(splitted[4]);

                // Automatically count final prorate price
                var valueProrateEdit = $('#prorate_edit').val();
                var normalPriceEdit = parseFloat(splitted[4]);
                var finalPriceEdit = 0;

                var prorateEditInt = parseInt(valueProrateEdit);;
                var isDanceBaby = splitted[5] === '8';
                if(isDanceBaby){
                    finalPriceEdit = normalPriceEdit * ( prorateEditInt / 8 );
                }
                else{
                    if(prorateEditInt > 3){
                        prorateEditInt = 3;
                    }
                    finalPriceEdit = normalPriceEdit * ( prorateEditInt / 4 );
                }

                priceEditFormat.clear();
                priceEditFormat.set(finalPriceEdit, {
                    decimalCharacter: ',',
                    digitGroupSeparator: '.',
                    minimumValue: '0',
                    decimalPlaces: 0
                });
            });

            // Get current price
            $('#normal_price_edit').val($(this).data('normal-price'));

            priceEditFormat.clear();
            priceEditFormat.set($(this).data('price'), {
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                minimumValue: '0',
                decimalPlaces: 0
            });

            document.getElementById('prorate_edit').value = $(this).data('prorate');

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
            var normalPriceEdit = $('#normal_price_edit').val();
            var priceEdit = $('#price_edit').val();
            // var discountEdit = $('#discount_edit').val();
            var editedRowId = $('#edited_row_id').val();
            var prorateEdit = $('#prorate_edit').val();

            var scheduleValue = '';
            if(scheduleEdit && scheduleEdit !== '-1'){
                scheduleValue = scheduleEdit;
            }
            else{
                scheduleValue = $('#schedule_old_value').val();
            }

            $.ajax({
                type: 'PUT',
                url: '{{ route('admin.prorate_details.update') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id' : editedRowId,
                    'schedule': scheduleEdit,
                    'prorate': prorateEdit,
                    'prorate_price': priceEdit,
                    // 'discount': $('#discount_edit').val()
                },
                success: function(data) {
                    $('.errorSchedule').addClass('hidden');

                    if ((data.errors)) {
                        if(data.errors === 'EXISTS'){
                            setTimeout(function () {
                                $('#editModal').modal('show');
                                toastr.error('Kelas sudah ada!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                            }, 500);
                        }
                        else{
                            setTimeout(function () {
                                $('#editModal').modal('show');
                                toastr.error('Gagal ubah detail!', 'Peringatan', {timeOut: 5000});
                            }, 500);

                            if (data.errors.schedule) {
                                $('.errorSchedule').removeClass('hidden');
                                $('.errorSchedule').text(data.errors.schedule);
                            }
                            else if(data.errors.prorate){
                                $('.errorProrate').removeClass('hidden');
                                $('.errorProrate').text(data.errors.prorate);
                            }
                            else if(data.errors.discount){
                                $('.errorDiscount').removeClass('hidden');
                                $('.errorDiscount').text(data.errors.discount);
                            }
                        }
                    } else {
                        toastr.success('Berhasil ubah data!', 'Sukses', {timeOut: 5000});

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
                        sbEdit.append("<td class='text-center'>" + prorateEdit + " Pertemuan</td>");
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
                        sbEdit.append("<a class='edit-modal btn btn-info' data-id='" + editedRowId + "' data-schedule='" + scheduleValue + "' data-prorate='" + prorateEdit + "' data-normal-price='" + normalPriceEdit + "' data-price='" + price + "'><span class='glyphicon glyphicon-edit'></span></a>");
                        // sbEdit.append("<a class='delete-modal btn btn-danger' data-id='" + editedRowId + "' data-schedule='" + scheduleValue + "' data-prorate='" + prorateEdit + "' data-normal-price='" + normalPriceEdit + "' data-price='" + price + "' data-discount='" + discount + "'><span class='glyphicon glyphicon-trash'></span></a>");
                        sbEdit.append("</td>");
                        sbEdit.append("</tr>");

                        $('.item' + editedRowId).replaceWith(sbEdit.toString());

                        // Reset edit form modal
                        $('#edited_row_id').val('');
                        $('#schedule_edit').val(null).trigger('change');
                        $('#trainer_edit').val('');
                        $('#normal_price_edit').val('');
                        priceEditFormat.clear();
                        // $('#discount_edit').val('');
                    }
                }
            });


        });

        $("#editModal").on('hidden.bs.modal', function () {
            // Reset edit form modal
            $('#edited_row_id').val('');
            $('#schedule_edit').val(null).trigger('change');
            $('#trainer_edit').val('');
            $('#normal_price_edit').val('');
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
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.prorate_details.delete') }}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'detail_id': deletedId,
                    'header_id': '{{ $header->id }}'
                },
                success: function(data) {
                    if ((data.errors)){
                        setTimeout(function () {
                            toastr.error('Gagal hapus detail!', 'Peringatan', {timeOut: 5000});
                        }, 500);
                    }
                    else{
                        toastr.success('Berhasil menghapus detail!', 'Sukses', {timeOut: 5000});
                        $('.item' + deletedId).remove();
                    }
                }
            });
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