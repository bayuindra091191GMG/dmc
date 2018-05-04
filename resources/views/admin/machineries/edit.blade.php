@extends('admin.layouts.admin')

@section('title', 'Ubah Alat Berat' )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.machineries.update', $machinery->id],'method' => 'put','id' => 'general-form','class'=>'form-horizontal form-label-left']) }}


            @if(\Illuminate\Support\Facades\Session::has('message'))
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @include('partials._success')
                    </div>
                </div>
            @endif

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
                    Kode Alat Berat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="code" type="text" class="form-control col-md-7 col-xs-12" value="{{ $machinery->code }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machinery_category" >
                    Kategori Alat Berat
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="machinery_category" name="machinery_category" class="form-control col-md-7 col-xs-12 @if($errors->has('machinery_category')) parsley-error @endif">
                        @foreach($machineryCategories as $machineryCategory)
                            <option value="{{ $machineryCategory->id }}" {{ $machinery->category_id == $machineryCategory->id ? "selected":"" }}>{{ $machineryCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machinery_brand" >
                    Merek Alat Berat
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="machinery_brand" name="machinery_brand" class="form-control col-md-7 col-xs-12 @if($errors->has('machinery_brand')) parsley-error @endif">
                        @foreach($machineryBrands as $machineryBrand)
                            <option value="{{ $machineryBrand->id }}" {{ $machinery->brand_id == $machineryBrand->id ? "selected":"" }}>{{ $machineryBrand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machinery_type" >
                    Tipe Alat Berat
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="machinery_type" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('machinery_type')) parsley-error @endif"
                           name="machinery_type" value="{{ $machinery->type }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sn_chasis">
                    S/N Chasis
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="sn_chasis" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('sn_chasis')) parsley-error @endif"
                           name="sn_chasis" value="{{ $machinery->sn_chasis }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sn_engine">
                    S/N Engine
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="sn_engine" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('sn_engine')) parsley-error @endif"
                           name="sn_engine" value="{{ $machinery->sn_engine }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="production_year">
                    Tahun Produksi
                </label>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <input id="production_year" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('production_year')) parsley-error @endif"
                           name="production_year" value="{{ $machinery->production_year }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="purchase_date">
                    Tanggal Pembelian
                </label>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <input id="purchase_date" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('purchase_date')) parsley-error @endif"
                           name="purchase_date" value="{{ $purchaseDate }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="location">
                    Lokasi
                </label>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <input id="location" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('location')) parsley-error @endif"
                           name="location" value="{{ $machinery->location }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status" >
                    Status
                </label>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <select id="status" name="status" class="form-control col-md-7 col-xs-12 @if($errors->has('status')) parsley-error @endif">
                        <option value="6" {{ $machinery->status_id == "6" ? "selected":"" }}>RDU</option>
                        <option value="9" {{ $machinery->status_id == "9" ? "selected":"" }}>BDN</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description" >
                    Keterangan Tambahan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="description" name="description" rows="5" class="form-control col-md-7 col-xs-12 @if($errors->has('description')) parsley-error @endif" style="resize: vertical">{{ $machinery->description }}</textarea>
                </div>
            </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-danger" href="{{ route('admin.machineries') }}"> Batal</a>
                        <button type="submit" class="btn btn-success"> Simpan</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/select2.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/select2.js')) }}
@endsection