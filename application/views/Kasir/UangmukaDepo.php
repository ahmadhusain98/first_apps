<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_uangmukadepo">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Daftar Uang Muka</span>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-success ml-1" onclick="getUrl('Kasir/form_uangmuka/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Deposit Uang Muka</button>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-primary ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableUangMukaDepo" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="10%">Invoice</th>
                                            <th width="10%">Tgl/Jam Deposit</th>
                                            <th width="20%">Member</th>
                                            <th width="10%">Jenis Deposit</th>
                                            <th width="10%">Total</th>
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
    var table = $('#tableUangMukaDepo');

    // fungsi hapus berdasarkan invoice
    function hapus(inv) {
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
                    url: siteUrl + 'Kasir/delPembayaran_um/' + inv,
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
    function ubah(token_uangmukadepo) {
        // jalankan fungsi
        getUrl('Kasir/form_uangmuka/' + token_uangmukadepo);
    }
</script>