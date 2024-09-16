<form method="post" id="form_report">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Laporan Sistem</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-3 col-12">
                            <div class="row mb-3">
                                <label for="laporan" class="control-label col-md-3 m-auto">Laporan <sup class="text-success">**</sup></label>
                                <div class="col-md-9">
                                    <select name="laporan" id="laporan" class="form-control select2_global" data-placeholder="~ Pilih Laporan" onchange="cekReport(this.value)">
                                        <option value="">~ Pilih Laporan</option>
                                        <optgroup label="Management Depan">
                                            <option value="0">0.1) Pendaftaran Member</option>
                                            <option value="0.1">0.2) Pendaftaran Tanpa Paket</option>
                                            <option value="0.2">0.3) Pendaftaran Dengan Paket</option>
                                        </optgroup>
                                        <optgroup label="Transaksi Pembelian">
                                            <option value="1">1.1) Pembelian</option>
                                            <option value="1.1">1.2) Pembelian Detail</option>
                                            <option value="2">2.1) Retur Pembelian</option>
                                            <option value="2.1">2.2) Retur Pembelian Detail</option>
                                            <option value="3">3.1) Riwayat Stok Pembelian</option>
                                        </optgroup>
                                        <optgroup label="Transaksi Penjualan">
                                            <option value="4">1.1) Penjualan</option>
                                            <option value="4.1">1.2) Penjualan Detail</option>
                                            <option value="5">2.1) Retur Penjualan</option>
                                            <option value="5.1">2.2) Retur Penjualan Detail</option>
                                            <option value="6">3.1) Riwayat Stok Penjualan</option>
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
                                        <option value="">~ Pilih Barang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="poli">
                                <label for="kode_poli" class="control-label col-md-3 m-auto">Poli <sup id="idPoli" class="text-danger">**</sup></label>
                                <div class="col-md-9">
                                    <select name="kode_poli" id="kode_poli" class="form-control select2_poli" data-placeholder="~ Pilih poli">
                                        <option value="">~ Pilih Poli</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="gudang">
                                <label for="kode_gudang" class="control-label col-md-3 m-auto">Gudang <sup id="idGudang" class="text-danger">**</sup></label>
                                <div class="col-md-9">
                                    <select name="kode_gudang" id="kode_gudang" class="form-control select2_gudang_int" data-placeholder="~ Pilih Gudang">
                                        <option value="">~ Pilih Gudang</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <button class="btn btn-primary" type="button" onclick="cetak(0)"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</button>
                                <button class="btn btn-warning" type="button" onclick="cetak(1)"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</button>
                                <button class="btn btn-success" type="button" onclick="cetak(2)"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</button>
                            </div>
                        </div>
                    </div>
                </div>
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
    var poli = $('#poli');
    var kode_poli = $('#kode_poli');
    var kode_supplier = $('#kode_supplier');
    var gudang = $('#gudang');
    var kode_gudang = $('#kode_gudang');
    var kode_barang = $('#kode_barang');
    var idSupplier = $('#idSupplier');
    var idGudang = $('#idGudang');
    var idPoli = $('#idPoli');
    var idBarang = $('#idBarang');

    barang.hide();
    poli.hide();
    idPoli.hide();

    // fungsi cek report
    function cekReport(param) {
        if (param == 0 || param == '0' || param == '0.1' || param == '0.2') {
            poli.fadeIn(0);
            idSupplier.fadeOut(0);
            idGudang.fadeOut(0);
            gudang.fadeOut(0);
            barang.fadeOut(0);
            pemasok.fadeOut(0);
        } else if (param == 1 || param == '1.1' || param == 2 || param == '2.1') {
            idSupplier.fadeOut(0);
            idGudang.fadeOut(0);
            barang.fadeOut(0);
            pemasok.fadeIn(0);
            poli.fadeOut(0);
        } else if (param == 3) {
            pemasok.fadeOut(0);
            idSupplier.fadeOut(0);
            idGudang.fadeIn(0);
            barang.fadeIn(0);
            poli.fadeOut(0);
        } else if (param == 4 || param == '4.1') {
            pemasok.fadeOut(0);
            idSupplier.fadeOut(0);
            idGudang.fadeOut(0);
            barang.fadeOut(0);
            poli.fadeOut(0);
        } else {
            pemasok.fadeIn(0);
            idSupplier.fadeIn(0);
            idGudang.fadeIn(0);
            barang.fadeOut(0);
            poli.fadeOut(0);
        }
    }

    // fungsi cetak
    function cetak(param) {
        if (laporan.val() == 0 || laporan.val() == '0.1' || laporan.val() == '0.2' || laporan.val() == 1 || laporan.val() == '1' || laporan.val() == '1.1' || laporan.val() == 2 || laporan.val() == '2' || laporan.val() == '2.1' || laporan.val() == 4 || laporan.val() == '4' || laporan.val() == '4.1') {} else if (laporan.val() == 3 || laporan.val() == '3') {
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

        var parameterString = `/${param}?laporan=${laporan.val()}&dari=${dari.val()}&sampai=${sampai.val()}&kode_supplier=${kode_supplier.val()}&kode_gudang=${kode_gudang.val()}&kode_barang=${kode_barang.val()}&kode_poli=${kode_poli.val()}`;
        window.open(`${siteUrl}Laporan/report_print${parameterString}`, '_blank');
    }
</script>