<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_kasir">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><ion-icon name="bookmark-outline" style="color: red;"></ion-icon> Daftar Pembayaran</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-md-4 col-4 mb-3">
                    <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-4 mb-3">
                    <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-4 mb-3">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="filter('')" title="Filter" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"><ion-icon name="filter-outline"></ion-icon> Filter</button>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                <button type="button" class="btn btn-warning" onclick="getUrl('Kasir/form_kasir/0/retur')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Pembayaran Retur Penjualan</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Kasir/form_kasir/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Pembayaran Penjualan</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tablePembayaran" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" class="bg-primary">#</th>
                            <th width="13%" class="bg-primary">Tgl/Jam Pembayaran</th>
                            <th width="15%" class="bg-primary">Invoice</th>
                            <th width="10%" class="bg-primary">Invoice Penjualan</th>
                            <th width="10%" class="bg-primary">No. Transaksi</th>
                            <th width="15%" class="bg-primary">Jenis Pembayaran</th>
                            <th width="10%" class="bg-primary">Penerima</th>
                            <th width="10%" class="bg-primary">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    var table = $('#tablePembayaran');

    // fungsi aktif/non-aktif akun
    function actived(token_pembayaran, param) {
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
                    url: siteUrl + 'Kasir/actived_pembayaran/' + token_pembayaran + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pembayaran", "Berhasil " + pesan2, "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Pembayaran", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    // fungsi hapus berdasarkan invoice
    function hapus(token_pembayaran) {
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
                    url: siteUrl + 'Kasir/delPembayaran/' + token_pembayaran,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pembayaran", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Pembayaran", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    //fungsi ubah berdasarkan lemparan kode
    function ubah(token_pembayaran) {
        // jalankan fungsi
        getUrl('Kasir/form_kasir/' + token_pembayaran);
    }

    // fungsi cetak
    function cetak(x, y) {
        printsingle('Kasir/print_kwitansi/' + x + '/' + y);
    }

    // fungsi kirim email
    function email(x) {
        Swal.fire({
            title: "Masukan Email",
            input: "text",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Kirim",
            cancelButtonText: "Tutup",
            showLoaderOnConfirm: true,
            preConfirm: async (email) => {
                try {
                    const githubUrl = `${siteUrl}Kasir/email/${x}?email=${email}`;
                    const response = await fetch(githubUrl);
                    if (!response.ok) {
                        return Swal.showValidationMessage(`${JSON.stringify(await response.json())}`);
                    }
                    return response.json();
                } catch (error) {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value.status == 1) {
                    Swal.fire("Kwitansi", "Berhasil dikirim via Email!, silahkan cek email", "success");
                } else {
                    Swal.fire("Kwitansi", "Gagal dikirim via Email!, silahkan cek email", "info");
                }
            }
        });
    }
</script>