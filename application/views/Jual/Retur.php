<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
echo _lock_so();
?>

<form method="post" id="form_barang_out_retur">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><ion-icon name="bookmark-outline" style="color: red;"></ion-icon> Daftar Retur Penjualan</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4 col-12">
            <select name="kode_gudang" id="kode_gudang" class="select2_gudang_int" data-placeholder="~ Pilih Gudang" onchange="getGudang(this.value)"></select>
        </div>
        <div class="col-md-4 col-12">
            <div class="row">
                <div class="col-md-4 col-4">
                    <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-4">
                    <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-4">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="filter($('#kode_gudang').val())" title="Filter" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"><ion-icon name="filter-outline"></ion-icon> Filter</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Transaksi/form_barang_out_retur/0')" <?= (($created > 0) ? _lock_button() : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableBarangOutRetur" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" class="bg-primary">#</th>
                            <th width="25%" class="bg-primary">Invoice</th>
                            <th class="bg-primary">Invoice Jual</th>
                            <th width="15%" class="bg-primary">Tgl/Jam Jual</th>
                            <th width="15%" class="bg-primary">Gudang</th>
                            <th width="15%" class="bg-primary">Total</th>
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
    var table = $('#tableBarangOutRetur');

    //fungsi ubah berdasarkan lemparan kode
    function ubah(invoice) {
        // jalankan fungsi
        getUrl('Transaksi/form_barang_out_retur/' + invoice);
    }

    // fungsi hapus berdasarkan invoice
    function hapus(invoice) {
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
                    url: siteUrl + 'Transaksi/delBeliOutRetur/' + invoice,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            reloadTable();

                            Swal.fire("Retur Penjualan", "Berhasil di hapus!", "success");
                        } else { // selain itu

                            Swal.fire("Retur Penjualan", "Gagal di hapus!, silahkan dicoba kembali", "info");
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
    function actived(invoice, param) {
        if (param == 0) {
            var pesan = "Retur Penjualan ini akan di re-batalkan!";
            var pesan2 = "di re-batalkan!";
        } else {
            var pesan = "Retur Penjualan ini akan dibatalkan!";
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
                    url: siteUrl + 'Transaksi/activedbarang_out_retur/' + invoice + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            reloadTable();

                            Swal.fire("Retur Penjualan", "Berhasil " + pesan2, "success");
                        } else { // selain itu

                            Swal.fire("Retur Penjualan", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    // fungsi group by gudang
    function getGudang(x) {
        filter(x);
    }

    // fungsi acc/unacc
    function valided(invoice, param) {
        if (param == 0) {
            var pesan = "Penjualan ini akan di re-acc!";
            var pesan2 = "di re-acc!";
        } else {
            var pesan = "Penjualan ini akan diacc!";
            var pesan2 = "diacc!";
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
                    url: siteUrl + 'Transaksi/accbarang_out_retur/' + invoice + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            reloadTable();

                            Swal.fire("Penjualan", "Berhasil " + pesan2, "success");
                        } else { // selain itu

                            Swal.fire("Penjualan", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
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