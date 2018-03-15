<select id="from_site_warehouse" name="from_site_warehouse" class="form-control col-md-7 col-xs-12 @if($errors->has('from_site_warehouse')) parsley-error @endif">
    <option value="-1" @if(empty(old('from_site_warehouse'))) selected @endif> - Pilih Gudang - </option>
    @foreach($warehouses as $warehouse)
        <option value="{{ $warehouse->id }}" {{ old('from_site_warehouse') == $warehouse->id ? "selected":"" }}>{{ $warehouse->code - $warehouse->name }}</option>
    @endforeach
</select>