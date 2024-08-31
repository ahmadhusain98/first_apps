<form method="post" id="form_report">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Parameter</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 offset-3 col-12">
            <div class="row mb-3">
                <label for="laporan" class="control-label col-md-3 m-auto">Laporan <sup class="text-success">**</sup></label>
                <div class="col-md-9">
                    <select name="laporan" id="laporan" class="form-control select2_global" data-placeholder="~ Pilih Laporan" onchange="cekReport(this.value)">
                        <option value="">~ Pilih Laporan</option>
                        <optgroup label="Laporan Transaksi Pembelian">
                            <option value="1">** Pembelian</option>
                            <option value="1.1">** Pembelian Detail</option>
                            <option value="2">** Retur Pembelian</option>
                            <option value="2.1">** Retur Pembelian Detail</option>
                            <option value="3">** Riwayat Stok Pembelian</option>
                        </optgroup>
                        <optgroup label="Laporan Transaksi Penjualan">
                            <option value="4">** Penjualan</option>
                            <option value="5">** Retur Penjualan</option>
                            <option value="6">** Riwayat Stok Penjualan</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="periode" class="control-label col-md-3 m-auto">Periode <sup class="text-success">**</sup></label>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3" id="pemasok">
                <label for="kode_supplier" class="control-label col-md-3 m-auto">Pemasok <sup id="idSupplier" class="text-danger">**</sup></label>
                <div class="col-md-9">
                    <select name="kode_supplier" id="kode_supplier" class="form-control select2_supplier" data-placeholder="~ Pilih Pemasok">
                        <option value="">~ Pilih Pemasok</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3" id="barang">
                <label for="kode_barang" class="control-label col-md-3 m-auto">Barang <sup id="idBarang" class="text-danger">**</sup></label>
                <div class="col-md-9">
                    <select name="kode_barang" id="kode_barang" class="form-control select2_barang" data-placeholder="~ Pilih Barang">
                        <option value="">~ Pilih Gudang</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="kode_gudang" class="control-label col-md-3 m-auto">Gudang <sup id="idGudang" class="text-danger">**</sup></label>
                <div class="col-md-9">
                    <select name="kode_gudang" id="kode_gudang" class="form-control select2_gudang_int" data-placeholder="~ Pilih Gudang">
                        <option value="">~ Pilih Gudang</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-3 col-12">
            <div class="float-right">
                <button class="btn btn-primary" type="button" onclick="cetak(0)"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</button>
                <button class="btn btn-warning" type="button" onclick="cetak(1)"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</button>
                <button class="btn btn-success" type="button" onclick="cetak(2)"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</button>
            </div>
        </div>
    </div>
</form>

<script>
    const form = $('#form_report');

    var laporan = $('#laporan');
    var dari = $('#dari');
    var sampai = $('#sampai');
    var pemasok = $('#pemasok');
    var barang = $('#barang');
    var kode_supplier = $('#kode_supplier');
    var kode_gudang = $('#kode_gudang');
    var kode_barang = $('#kode_barang');
    var idSupplier = $('#idSupplier');
    var idGudang = $('#idGudang');
    var idBarang = $('#idBarang');

    barang.hide();

    // fungsi cek report
    function cekReport(param) {
        if (param == 1 || param == '1.1' || param == 2 || param == '2.1') {
            idSupplier.hide(200);
            idGudang.hide(200);
            barang.hide(200);
        } else if (param == 3) {
            pemasok.hide(200);
            idSupplier.hide(200);
            idGudang.show(200);
            barang.show(200);
        } else {
            idSupplier.show(200);
            idGudang.show(200);
            barang.show(200);
        }
    }

    // fungsi cetak
    function cetak(param) {
        if (laporan.val() == 1 || laporan.val() == '1' || laporan.val() == '1.1' || laporan.val() == 2 || laporan.val() == '2' || laporan.val() == '2.1') {} else if (laporan.val() == 3 || laporan.val() == '3') {
            if (kode_barang.val() == '' || kode_barang.val() == null) {
                return Swal.fire("Barang", "Form sudah diisi?", "question");
            }

            if (kode_gudang.val() == '' || kode_gudang.val() == null) {
                return Swal.fire("Gudang", "Form sudah diisi?", "question");
            }
        } else {
            if (kode_supplier.val() == '' || kode_supplier.val() == null) {
                return Swal.fire("Pemasok", "Form sudah diisi?", "question");
            }

            if (kode_gudang.val() == '' || kode_gudang.val() == null) {
                return Swal.fire("Gudang", "Form sudah diisi?", "question");
            }
        }

        var parameterString = `/${param}?laporan=${laporan.val()}&dari=${dari.val()}&sampai=${sampai.val()}&kode_supplier=${kode_supplier.val()}&kode_gudang=${kode_gudang.val()}&kode_barang=${kode_barang.val()}`;
        window.open(`${siteUrl}Laporan/report_print${parameterString}`, '_blank');
    }
</script>