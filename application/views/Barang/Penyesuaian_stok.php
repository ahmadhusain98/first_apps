<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_penyesuaian_stok">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Penyesuaian</span>
                    <div class="float-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-down"></i>&nbsp;&nbsp;Unduh
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="preview('penyesuaian_stok')"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</a></li>
                                <li><a class="dropdown-item" href="#" onclick="print('penyesuaian_stok')"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</a></li>
                                <li><a class="dropdown-item" href="#" onclick="excel('penyesuaian_stok')"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                        <button type="button" class="btn btn-success" onclick="getUrl('Transaksi/form_penyesuaian_stok/0')" <?= (($created == 1) ? _lock_button() : 'disabled') ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-5 col-5">
                            <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-5 col-5">
                            <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-2 col-2">
                            <button type="button" style="width: 100%;" class="btn btn-info" onclick="filter($('#kode_gudang').val())" title="Filter" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"><ion-icon name="filter-outline"></ion-icon> Filter</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tablePenyesuaianStok" width="100%" style="border-radius: 10px;">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                                            <th width="20%">Tgl/Jam Penyesuaian</th>
                                            <th width="25%">Kode Penyesuaian</th>
                                            <th width="25%">Gudang</th>
                                            <th width="15%">Tipe Penyesuaian</th>
                                            <th width="10%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
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

<script>
    // variable
    var table = $('#tablePenyesuaianStok');

    //fungsi ubah berdasarkan lemparan kode
    function ubah(invoice) {
        // jalankan fungsi
        getUrl('Transaksi/form_penyesuaian_stok/' + invoice);
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
                    url: siteUrl + 'Transaksi/delPenyeStok/' + invoice,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Penyesuaian Stok", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Penyesuaian Stok", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    // fungsi acc/unacc
    function valided(invoice, param) {
        if (param == 0) {
            var pesan = "Penyesuaian Stok ini akan di re-acc!";
            var pesan2 = "di re-acc!";
        } else {
            var pesan = "Penyesuaian Stok ini akan diacc!";
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
                    url: siteUrl + 'Transaksi/accpenyesuaian_stok/' + invoice + '/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Penyesuaian Stok", "Berhasil " + pesan2, "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Penyesuaian Stok", "Gagal " + pesan2 + ", silahkan dicoba kembali", "info");
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
        printsingle('Transaksi/single_print_ps/' + x + '/' + y);
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
                    Swal.fire("Laporan Penyesuaian Stok", "Berhasil dikirim via Email!, silahkan cek email", "success");
                } else {
                    Swal.fire("Laporan Penyesuaian Stok", "Gagal dikirim via Email!, silahkan cek email", "info");
                }
            }
        });
    }
</script>