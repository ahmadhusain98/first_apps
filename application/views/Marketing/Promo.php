<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_promo">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Promo</span>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-success ml-1" onclick="getUrl('Marketing/form_promo/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-primary ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-6 col-12">
                            <div class="row float-right">
                                <div class="col-md-5 col-5 mb-3">
                                    <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-5 col-5 mb-3">
                                    <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-2 col-2 mb-3">
                                    <button type="button" class="btn btn-secondary btn-sm float-right" onclick="filter('')" title="Filter" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"><ion-icon name="filter-outline"></ion-icon></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tablePromo" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="10%">Nama</th>
                                            <th>Keterangan</th>
                                            <th>Persyaratan</th>
                                            <th width="15%">Diskon</th>
                                            <th>Status</th>
                                            <th width="15%">Aksi</th>
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
    var table = $('#tablePromo');

    //fungsi ubah berdasarkan lemparan kode
    function ubah(kode_promo) {
        // jalankan fungsi
        getUrl('Marketing/form_promo/' + kode_promo);
    }

    // fungsi hapus berdasarkan kode_promo
    function hapus(kode_promo) {
        // ajukan pertanyaaan
        Swal.fire({
            title: "Kamu yakin?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin

                // jalankan fungsi
                $.ajax({
                    url: siteUrl + 'Marketing/delPromo/' + kode_promo,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik
                        $("#loading").modal("hide");

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Promo", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu
                            $("#loading").modal("hide");

                            Swal.fire("Promo", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error
                        $("#loading").modal("hide");

                        error_proccess();
                    }
                });
            }
        });
    }
</script>