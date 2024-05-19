<form method="post" id="form_report">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="h3 font-weight-bold"># Parameter Laporan</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-3 col-12">
                            <div class="row mb-3">
                                <label for="laporan" class="control-label col-md-3 m-auto">Laporan</label>
                                <div class="col-md-9">
                                    <select name="laporan" id="laporan" class="form-control select2_global" data-placeholder="~ Pilih Laporan" onchange="cekReport(this.value)">
                                        <option value="">~ Pilih Laporan</option>
                                        <optgroup label="Jenis Laporan">
                                            <option value="1">1) Pembelian</option>
                                            <option value="2">2) Retur Pembelian</option>
                                            <option value="3">3) Riwayat Stok Pembelian</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="periode" class="control-label col-md-3 m-auto">Periode</label>
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
                                <label for="kode_supplier" class="control-label col-md-3 m-auto">Pemasok</label>
                                <div class="col-md-9">
                                    <select name="kode_supplier" id="kode_supplier" class="form-control select2_supplier" data-placeholder="~ Pilih Pemasok">
                                        <option value="">~ Pilih Pemasok</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kode_gudang" class="control-label col-md-3 m-auto">Gudang</label>
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
                        <div class="col-md-6 offset-3 col-12 text-center">
                            <button class="btn btn-primary btn-sm" type="button" onclick="cetak(0)"><ion-icon name="desktop-outline"></ion-icon> LAYAR</button>
                            <button class="btn btn-warning btn-sm" type="button" onclick="cetak(1)"><ion-icon name="document-text-outline"></ion-icon> PDF</button>
                            <button class="btn btn-success btn-sm" type="button" onclick="cetak(2)"><ion-icon name="grid-outline"></ion-icon> EXCEL</button>
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
    var kode_supplier = $('#kode_supplier');
    var kode_gudang = $('#kode_gudang');

    // fungsi cek report
    function cekReport(param) {
        if (param == 3) {
            pemasok.hide(200);
        } else {
            pemasok.show(200);
        }
    }

    // fungsi cetak
    function cetak(param) {
        if (kode_supplier.val() == '' || kode_supplier.val() == null) {
            return Swal.fire("Pemasok", "Form sudah diisi?", "question");
        }

        if (kode_gudang.val() == '' || kode_gudang.val() == null) {
            return Swal.fire("Gudang", "Form sudah diisi?", "question");
        }

        var parameterString = `/${param}?laporan=${laporan.val()}&dari=${dari.val()}&sampai=${sampai.val()}&kode_supplier=${kode_supplier.val()}&kode_gudang=${kode_gudang.val()}`;
        window.open(`${siteUrl}Transaksi/report_print${parameterString}`, '_blank');
    }
</script>