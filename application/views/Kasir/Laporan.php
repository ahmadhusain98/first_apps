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
                                    <select name="laporan" id="laporan" class="form-control select2_global" data-placeholder="~ Pilih Laporan">
                                        <option value="">~ Pilih Laporan</option>
                                        <optgroup label="Jenis Laporan">
                                            <option value="1">1) Penjualan</option>
                                            <option value="2">2) Retur Penjualan</option>
                                            <option value="3">3) Laporan Penjualan Poli</option>
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
                            <div class="row mb-3">
                                <label for="kode_user" class="control-label col-md-3 m-auto">User</label>
                                <div class="col-md-9">
                                    <select name="kode_user" id="kode_user" class="form-control select2_user" data-placeholder="~ Pilih User">
                                        <option value="">~ Pilih User</option>
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
    var kode_user = $('#kode_user');

    // fungsi cetak
    function cetak(param) {
        if (laporan.val() == '' || laporan.val() == null) { // jika laporan null/ kosong
            return Swal.fire("Laporan", "Form sudah diisi?", "question");
        }

        if (dari.val() == '' || dari.val() == null) { // jika dari null/ kosong
            return Swal.fire("Periode Dari", "Form sudah diisi?", "question");
        }

        if (sampai.val() == '' || sampai.val() == null) { // jika sampai null/ kosong
            return Swal.fire("Periode Sampai", "Form sudah diisi?", "question");
        }

        if (kode_user.val() == '' || kode_user.val() == null) { // jika kode_user null/ kosong
            return Swal.fire("User", "Form sudah diisi?", "question");
        }

        var parameterString = `/${param}?laporan=${laporan.val()}&dari=${dari.val()}&sampai=${sampai.val()}&kode_user=${kode_user.val()}`;
        window.open(`${siteUrl}Kasir/report_print${parameterString}`, '_blank');
    }
</script>