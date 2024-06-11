<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_pendaftaran">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><ion-icon name="bookmark-outline" style="color: red;"></ion-icon> Pendaftaran Member</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4 col-12 mb-3">
            <select name="kode_poli" id="kode_poli" class="select2_poli" data-placeholder="~ Pilih Poli" onchange="getPoli(this.value)"></select>
        </div>
        <div class="col-md-4 col-12">
            <div class="row">
                <div class="col-md-4 col-5 mb-3">
                    <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-5 mb-3">
                    <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-2 mb-3">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="filter($('#kode_poli').val())" title="Filter" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"><ion-icon name="filter-outline"></ion-icon> Filter</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Health/form_pendaftaran/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tablePendaftaran" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" class="bg-primary">#</th>
                            <th width="15%" class="bg-primary">No. Trx</th>
                            <th width="10%" class="bg-primary">Member</th>
                            <th class="bg-primary">Tgl/Jam Masuk</th>
                            <th class="bg-primary">Tgl/Jam Keluar</th>
                            <th class="bg-primary">Poli</th>
                            <th class="bg-primary">Dokter</th>
                            <th class="bg-primary">Status</th>
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
    var table = $('#tablePendaftaran');

    //fungsi ubah berdasarkan lemparan kode
    function ubah(kode_pendaftaran) {
        // jalankan fungsi
        getUrl('Health/form_pendaftaran/' + kode_pendaftaran);
    }

    // fungsi hapus berdasarkan no_trx
    function hapus(no_trx) {
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
                    url: siteUrl + 'Health/delPendaftaran/' + no_trx,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pendaftaran", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Pendaftaran", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    // fungsi group by poli
    function getPoli(x) {
        filter(x);
    }

    // fungsi aktif/non-aktif akun
    function actived(no_trx) {
        var pesan = "Pendaftaran ini akan dibatalkan!";
        var pesan2 = "dibatalkan!";
        // ajukan pertanyaaan
        Swal.fire({
            title: "Kamu yakin?",
            text: pesan,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, " + pesan2,
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin

                // jalankan fungsi
                $.ajax({
                    url: siteUrl + 'Health/activedpendaftaran/' + no_trx,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pengguna", "Berhasil " + pesan2, "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Pengguna", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }
</script>