<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<style type="text/css">
    .invoice-title h2, .invoice-title h3 {
        display: inline-block;
    }

    .table > tbody > tr > .no-line {
        border-top: none;
    }

    .table > thead > tr > .no-line {
        border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
        border-top: 2px solid;
    }
</style>

<div class="container" style="width: 670px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="invoice-title">
                <h2><img src="{{URL::asset('assets/admin/images/DMC Clean.jpg')}}" width="50px"/> <br/></h2>
                <h3 class="pull-right">Invoice <br/>asdf</h3>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6">
                    <address>
                        <strong>Nama Kelas</strong>
                    </address>
                </div>
                <div class="col-lg-6 text-right">
                    <address>
                        <strong>Invoice Date:</strong><br>
                        asdf<br><br>
                    </address>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Invoice summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td class="text-center"><strong>Trainer</strong></td>
                                <td class="text-center"><strong>Tanggal Berlaku</strong></td>
                                <td class="text-center"><strong>Jumlah Pertemuan</strong></td>
                                <td class="text-center"><strong>Price</strong></td>
                                {{--<td class="text-center"><strong>Diskon</strong></td>--}}
                                <td class="text-center"><strong>Subtotal</strong></td>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>