<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
echo _lock_so();
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_barang_in">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Pembelian</span>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-success ml-1" onclick="getUrl('Transaksi/form_barang_in/0')" <?= (($created > 0) ? _lock_button() : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
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
                                <table class="table table-striped table-hover table-bordered" id="tableBarangIn" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="15%">Invoice</th>
                                            <th width="20%">Tgl/Jam Beli</th>
                                            <th width="15%">Pemasok</th>
                                            <th width="15%">Gudang</th>
                                            <th width="10%">Pembeli</th>
                                            <th>Total</th>
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
    var table = $('#tableBarangIn');

    //fungsi ubah berdasarkan lemparan kode
    function ubah(invoice) {
        // jalankan fungsi
        getUrl('Transaksi/form_barang_in/' + invoice);
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
                    url: siteUrl + 'Transaksi/delBeliIn/' + invoice,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pembelian", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Pembelian", "Gagal di hapus!, silahkan dicoba kembali", "info");
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
            var pesan = "Pembelian ini akan di re-batalkan!";
            var pesan2 = "di re-batalkan!";
        } else {
            var pesan = "Pembelian ini akan dibatalkan!";
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
                    url: siteUrl + 'Transaksi/activedbarang_in/' + invoice + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pembelian", "Berhasil " + pesan2, "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Pembelian", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
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
            var pesan = "Pembelian ini akan di re-acc!";
            var pesan2 = "di re-acc!";
        } else {
            var pesan = "Pembelian ini akan diacc!";
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
                    url: siteUrl + 'Transaksi/accbarang_in/' + invoice + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Pembelian", "Berhasil " + pesan2, "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Pembelian", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    // fungsi cetak
    function cetak(x, y) {
        printsingle('Transaksi/single_print_bin/' + x + '/' + y);
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
                    const githubUrl = `${siteUrl}Transaksi/email/${x}?email=${email}`;
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
                    Swal.fire("Laporan Pembelian", "Berhasil dikirim via Email!, silahkan cek email", "success");
                } else {
                    Swal.fire("Laporan Pembelian", "Gagal dikirim via Email!, silahkan cek email", "info");
                }
            }
        });
    }
</script>