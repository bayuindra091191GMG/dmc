@extends('admin.layouts.admin')

@section('title', 'Ubah Alat Berat' )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.machineries.update', $machinery->id],'method' => 'put','class'=>'form-horizontal form-label-left']) }}


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
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="code" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('code')) parsley-error @endif"
                               name="code" value="{{ $machinery->code }}" required>
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
                    <select id="machinery_type" name="machinery_type" class="form-control col-md-7 col-xs-12 @if($errors->has('machinery_type')) parsley-error @endif">
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
                        <a class="btn btn-primary" href="{{ route('admin.machineries') }}"> Batal</a>
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
    <script>
        $('#machinery_type').select2({
            placeholder: {
                id: '{{ $machinery->type_id }}',
                text: '{{ $machinery->machinery_type->name }}'
            },
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('select.machinery_types') }}',
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
    </script>
@endsection