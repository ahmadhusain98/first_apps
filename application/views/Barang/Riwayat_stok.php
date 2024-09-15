<?= _lock_so() ?>

<form method="post" id="form_riwayat_stok">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Riwayat Stok Barang</span>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                    </div>
                </div>
                <div class="card-footer">
                    <select name="kode_gudang" id="kode_gudang" class="select2_gudang_int" data-placeholder="~ Pilih Gudang" onchange="getGudang(this.value)"></select>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tableRiwayatStok" width="100%" style="border-radius: 10px;">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                                            <th width="20%">Barang</th>
                                            <th width="15%">Gudang</th>
                                            <th width="12%">Min Stok</th>
                                            <th width="12%">Max Stok</th>
                                            <th width="10%">Stok</th>
                                            <th width="10%">Status</th>
                                            <th width="10%" style="border-radius: 0px 10px 0px 0px;">Histori</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // variable
    var table = $('#tableRiwayatStok');

    // fungsi group by gudang
    function getGudang(x) {
        if (x == '' || x == null) {
            var parameterString = '';
        } else {
            var parameterString = x;
        }

        table.DataTable().ajax.url(siteUrl + '<?= $list_data ?>' + parameterString).load();
    }

    // fungsi lihat histori barang
    function lihat(kode_barang, kode_gudang) {
        var param = `?kode_barang=${kode_barang}&kode_gudang=${kode_gudang}`
        window.open(`${siteUrl}Report/riwayat_stok/1${param}`, '_blank');
    }
</script>