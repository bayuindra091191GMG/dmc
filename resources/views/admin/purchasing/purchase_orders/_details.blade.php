<div class="col-lg-2 col-md-2 col-xs-0"></div>
<div class="col-lg-8 col-md-8 col-xs-12 column">
    <table class="table table-bordered table-hover" id="tab_logic">
        <thead>
        <tr >
            <th class="text-center" style="width: 40%">
                Nomor Part
            </th>
            <th class="text-center" style="width: 20%">
                Jumlah
            </th>
            <th class="text-center" style="width: 40%">
                Remark
            </th>
        </tr>
        </thead>
        <tbody>
        <tr id='addr0'>
            <td class='field-item'>
                <select id="select0" name="item[]" class='form-control'></select>
            </td>
            <td>
                <input type='number' name='qty[]'  placeholder='Jumlah' class='form-control'/>
            </td>
            <td>
                <input type='text' name='remark[]' placeholder='Keterangan' class='form-control'/>
            </td>
        </tr>
        <tr id='addr1'></tr>
        </tbody>
    </table>
    <a id="add_row" class="btn btn-default pull-left">Tambah</a><a id='delete_row' class="pull-right btn btn-default">Hapus</a>
</div>
<div class="col-lg-2 col-md-2 col-xs-0"></div>