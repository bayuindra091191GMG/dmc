@extends('admin.layouts.admin')

{{--@section('title','Buat Retur Baru')--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Langkah 3 - Registrasi Transaksi {{ $courseType }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            {{ Form::open(['route'=>['admin.registration.step-three.store'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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

            <input type="hidden" name="type" id="type" value="{{ $type }}"/>

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
                           name="date" value="{{ $today }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student" >
                    Student
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input id="student" type="text" class="form-control col-md-7 col-xs-12"
                           name="student" value="{{ $student->name }}" readonly>
                    <input type="hidden" id="customer_id" name="customer_id" class="form-control col-md-7 col-xs-12" value="{{ $student->id ?? '-1' }}">
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="{{ route('admin.customers.show', ['customer' => $student->id]) }}" target="_blank" class="btn btn-primary" id="schedule_check">Cek Jadwal Teregistrasi Student</a>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="registration_fee">
                    Registration Fee
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="registration_fee" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('registration_fee')) parsley-error @endif"
                           name="registration_fee" autocomplete="off"/>
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
                        <input type="hidden" name="is_discount" value="0" id="is_discount"/>
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
                           name="total" value="{{ $totalPrice }}" readonly/>
                </div>
            </div>

            <hr/>

            @if(!empty($student))
                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-xs-12 box-section">
                        <h3 class="text-center">Detil Transaksi</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="detail_table">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        Kelas
                                    </th>
                                    <th class="text-center">
                                        Trainer
                                    </th>
                                    <th class="text-center">
                                        Hari
                                    </th>
                                    <th class="text-center">
                                        Jumlah Pertemuan
                                    </th>
                                    <th class="text-center">
                                        Harga
                                    </th>
                                    <th class="text-center">
                                        Jumlah Bulan
                                    </th>
{{--                                    <th class="text-center" style="width: 10%">--}}
{{--                                        Diskon--}}
{{--                                    </th>--}}
                                    <th class="text-center">
                                        Subtotal
                                    </th>
{{--                                    <th class="text-center" style="width: 15%">--}}
{{--                                        Tindakan--}}
{{--                                    </th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $schedule)
                                        <tr id="{{ $schedule->id }}">
                                            <td>
                                                <input type="hidden" name="schedule_ids[]" value="{{ $schedule->id }}">
                                                {{ $schedule->course->name }}
                                            </td>
                                            <td>{{ $schedule->course->coach_id === 0 ? 'Tidak Ada Coach' : $schedule->course->coach->name}}</td>
                                            <td>{{ $schedule->day }}</td>
                                            <td class="text-right">{{ $schedule->meeting_amount }}</td>
                                            <td class="text-right">
                                                <input type="hidden" id="price_{{ $schedule->id }}" value="{{ $schedule->course->price }}">
                                                {{ $schedule->course->price_string }}
                                            </td>
                                            <td><input type="number" name="months[]" id="month_{{ $schedule->id }}" class="form-control text-right auto-blur" value="1" onfocusout="countSubtotal('{{ $schedule->id }}')"/></td>
                                            <td class="text-right" id="subtotal_{{ $schedule->id }}">{{ $schedule->course->price_string }}</td>
                                        </tr>
                                    @endforeach
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
{{--                    <a class="btn btn-danger" href="{{ route('admin.transactions') }}"> Batal</a>--}}
                    <input type="submit" class="btn btn-success" value="Simpan">
                </div>
            </div>
            {{ Form::close() }}
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
            format: "DD MMM YYYY"
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

        function countSubtotal(idx){
            let price = parseFloat($('#price_' + idx).val());
            let month = parseFloat($('#month_' + idx).val());

            let subtotal = price * month;
            let subtotalStr = rupiahFormat(subtotal);
            $('#subtotal_' + idx).html(subtotalStr);
        }

        // Auto onfocusout when enter is pressed
        $('.auto-blur').keypress(function (e) {
            if (e.which == 13) {
                $(this).blur();
            }
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
                    $('#is_discount').val(1);
                },
                error: function(errors){
                    alert(errors);
                }
            });
        });
    </script>
@endsection