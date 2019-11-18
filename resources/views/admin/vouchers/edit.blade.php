.@extends('admin.layouts.admin')

{{--@section('title', 'Tambah Site' )--}}

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2>Ubah data Voucher {{ $voucher->name }}</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('partials._success')
            @include('partials._error')
            {{ Form::open(['route'=>['admin.vouchers.update'],'method' => 'post','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >
                    Nama
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="name" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('name')) parsley-error @endif"
                           name="name" value="{{ $voucher->name }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description" >
                    Deskripsi
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="description" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('description')) parsley-error @endif"
                           name="description" value="{{ $voucher->description }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type" >
                    Tipe
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="type" name="type" class="form-control col-md-7 col-xs-12">
                        @if($voucher->type == 'goods')
                            <option value="goods" selected>Tukar Barang</option>
                        @else
                            <option value="goods">Tukar Barang</option>
                        @endif
                        @if($voucher->type == 'discount_percentage')
                            <option value="discount_percentage" selected>Diskon Persen</option>
                        @else
                            <option value="discount_percentage">Diskon Persen</option>
                        @endif
                        @if($voucher->type == 'discount_total')
                            <option value="discount_total" selected>Diskon Total</option>
                        @else
                            <option value="discount_total">Diskon Total</option>
                        @endif
                        @if($voucher->type == 'free_package')
                            <option value="free_package" selected>Free Package</option>
                        @else
                            <option value="free_package">Free Package</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="point_needed" >
                    Point yang dibutuhkan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="point_needed" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('point_needed')) parsley-error @endif"
                           name="point_needed" value="{{ $voucher->point_needed }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_percentage" >
                    Diskon Persen
                    *harus diisi jika tipe adalah Diskon Persen
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="discount_percentage" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('discount_percentage')) parsley-error @endif"
                           name="discount_percentage" value="{{ $voucher->discount_percentage }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_total" >
                    Diskon Total
                    *harus diisi jika tipe adalah Diskon Total
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="discount_total" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('discount_total')) parsley-error @endif"
                           name="discount_total" value="{{ $voucher->discount_total }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="free_package" >
                    Free Packages
                    *harus diisi jika tipe adalah Free Package
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="free_package" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('free_package')) parsley-error @endif"
                           name="free_package" value="{{ $voucher->free_package }}" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-danger" href="{{ route('admin.vouchers') }}"> Batal</a>
                    <button type="submit" class="btn btn-success"> Simpan</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/edit.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
@endsection