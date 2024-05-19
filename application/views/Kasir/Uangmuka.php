<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_uangmuka">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Laporan Uang Muka</span>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-primary ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableUangMuka" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="20%">Member</th>
                                            <th width="20%">Tgl/Jam Terkahir</th>
                                            <th width="15%">Invoice Terakhir</th>
                                            <th width="15%">Masuk</th>
                                            <th width="15%">Keluar</th>
                                            <th width="15%">Tersedia</th>
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
    var table = $('#tableUangMuka');

    // fungsi aktif/non-aktif akun
    function actived(token_uangmuka, param) {
        if (param == 0) {
            var pesan = "Pembayaran ini akan di Acc!";
            var pesan2 = "di Acc!";
        } else {
            var pesan = "Pembayaran ini akan dibatalkan!";
            var pesan2 = "dibatalkan!";
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
                    url: siteUrl + 'Kasir/actived_uangmuka/' + token_uangmuka + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik
                        $("#loading").modal("hide");

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pembayaran", "Berhasil " + pesan2, "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu
                            $("#loading").modal("hide");

                            Swal.fire("Pembayaran", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
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

    // fungsi hapus berdasarkan invoice
    function hapus(token_uangmuka) {
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
                    url: siteUrl + 'Kasir/delPembayaran/' + token_uangmuka,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik
                        $("#loading").modal("hide");

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pembayaran", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu
                            $("#loading").modal("hide");

                            Swal.fire("Pembayaran", "Gagal di hapus!, silahkan dicoba kembali", "info");
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

    //fungsi ubah berdasarkan lemparan kode
    function ubah(token_uangmuka) {
        // jalankan fungsi
        getUrl('Kasir/form_uangmuka/' + token_uangmuka);
    }
</script>