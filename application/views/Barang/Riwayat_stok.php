<?= _lock_so() ?>

<form method="post" id="form_riwayat_stok">
    <div class="row" data-aos="fade-down"
        data-aos-easing="linear"
        data-aos-duration="1000">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Riwayat Stok Barang</span>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                        <button type="button" class="btn btn-success" onclick="sinkron()"><i class="fa-solid fa-shuffle"></i>&nbsp;&nbsp;Sinkronisasi</button>
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

<div class="modal fade" id="loading_rs" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="<?= base_url() ?>assets/img/loading_2.gif" style="width: 100%;">
            </div>
        </div>
    </div>
</div>

<script>
    // variable
    var table = $('#tableRiwayatStok');
    $('#loadering2').hide();

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

    function sinkron() {
        $.ajax({
            url: `${siteUrl}Transaksi/sinkron`,
            type: `POST`,
            dataType: `JSON`,
            success: function(result) {
                reloadTable()

                setTimeout(function() {
                    if (result.status == 1) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Berhasil Sinkronisasi!",
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "info",
                            title: "Gagal Sinkronisasi!",
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                }, 1000);
            },
            error: function(error) {
                error_proccess();
            }
        });
    }
</script>