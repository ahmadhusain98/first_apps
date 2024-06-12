<?= _lock_so() ?>

<form method="post" id="form_riwayat_stok">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><ion-icon name="bookmark-outline" style="color: red;"></ion-icon> Daftar Riwayat Stok Barang</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-12">
            <select name="kode_gudang" id="kode_gudang" class="select2_gudang_int" data-placeholder="~ Pilih Gudang" onchange="getGudang(this.value)"></select>
        </div>
        <div class="col-md-6 col-12">
            <button type="button" class="btn btn-sm float-right mb-1 btn-primary ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableRiwayatStok" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" class="bg-primary">#</th>
                            <th width="10%" class="bg-primary">Kode Barang</th>
                            <th width="30%" class="bg-primary">Nama Barang</th>
                            <th width="15%" class="bg-primary">Gudang</th>
                            <th width="10%" class="bg-primary">Minimal Stok</th>
                            <th width="10%" class="bg-primary">Maksimal Stok</th>
                            <th width="10%" class="bg-primary">Stok Akhir</th>
                            <th width="10%" class="bg-primary">Aksi</th>
                        </tr>
                    </thead>
                </table>
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
</script>