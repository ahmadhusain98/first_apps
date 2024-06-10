<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_dokter">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Dokter</span>
                    <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-warning" onclick="print('dokter')"><ion-icon name="print-outline"></ion-icon> Cetak</button>
                        <button type="button" class="btn btn-primary" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                        <button type="button" class="btn btn-success" onclick="getUrl('Master/form_dokter/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableDokter" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>No. Hp</th>
                                            <th>Alamat</th>
                                            <th>Tgl Kerja</th>
                                            <th>Poli</th>
                                            <th>Status</th>
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
    var table = $('#tableDokter');

    //fungsi ubah berdasarkan lemparan kode
    function ubah(kode_dokter) {
        // jalankan fungsi
        getUrl('Master/form_dokter/' + kode_dokter);
    }

    // fungsi hapus berdasarkan kode_dokter
    function hapus(kode_dokter) {
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
                    url: siteUrl + 'Master/delDokter/' + kode_dokter,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Dokter", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Dokter", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    // fungsi aktif/non-aktif akun
    function actived(kode_dokter, param) {
        if (param == 1) {
            var pesan = "Akun ini akan diaktifkan!";
            var pesan2 = "diaktifkan!";
        } else {
            var pesan = "Akun ini akan dinonaktifkan!";
            var pesan2 = "dinonaktifkan!";
        }
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
                    url: siteUrl + 'Master/activeddokter/' + kode_dokter + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Dokter", "Berhasil " + pesan2, "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Dokter", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
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