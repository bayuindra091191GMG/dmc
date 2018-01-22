@extends('admin.layouts.admin')

@section('title', 'Ubah Metode Pembayaran' )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.payment_methods.update', $payment_method->id],'method' => 'put','class'=>'form-horizontal form-label-left']) }}

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description" >
                        Deskripsi Metode Pembayaran
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="description" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('description')) parsley-error @endif"
                               name="description" value="{{ $payment_method->description }}" required>
                        @if($errors->has('description'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('name') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fee">
                        Biaya Tambahan Metode Pembayaran
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12 price-format">
                        <input id="fee" type="number" class="form-control col-md-7 col-xs-12 @if($errors->has('fee')) parsley-error @endif"
                               name="fee" value="{{ $payment_method->fee }}" required>
                        @if($errors->has('fee'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('fee') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">
                        Status Metode Pembayaran
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                        <select id="status" class="form-control" name="status">
                            <option value="true" selected>Aktif</option>
                            <option value="false">Non aktif</option>
                        </select>
                        @if($errors->has('status'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('status') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ URL::previous() }}"> Batal</a>
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
    {{ Html::script(mix('assets/admin/js/payment_method/custom.js')) }}
@endsection