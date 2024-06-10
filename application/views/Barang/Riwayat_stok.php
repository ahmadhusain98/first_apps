<?= _lock_so() ?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_riwayat_stok">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Riwayat Stok</span>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-primary ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3">
                            <select name="kode_gudang" id="kode_gudang" class="select2_gudang_int" data-placeholder="~ Pilih Gudang" onchange="getGudang(this.value)"></select>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="row float-right">
                                <div class="col-md-5 col-5 mb-3">
                                    <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-5 col-5 mb-3">
                                    <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-2 col-2 mb-3">
                                    <button type="button" class="btn btn-secondary btn-sm float-right" onclick="filter($('#kode_gudang').val())" title="Filter" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"><ion-icon name="filter-outline"></ion-icon></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableRiwayatStok" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="10%">Kode Barang</th>
                                            <th width="30%">Nama Barang</th>
                                            <th width="15%">Gudang</th>
                                            <th width="10%">Minimal Stok</th>
                                            <th width="10%">Maksimal Stok</th>
                                            <th width="10%">Stok Akhir</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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