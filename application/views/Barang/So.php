<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<?= _lock_so() ?>

<div class="row">
    <div class="col-md-12">
        <form id="form_schedule_so" method="post">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Jadwal SO</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <span class="h5">PERIODE DARI</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Tanggal</label>
                                    <input type="hidden" id="id_so" name="id_so" value="<?= ((!empty($cek_jadwal)) ? $cek_jadwal->id : null) ?>">
                                    <input type="date" name="tgl_dari_so" id="tgl_dari_so" value="<?= ((!empty($cek_jadwal)) ? date('Y-m-d', strtotime($cek_jadwal->tgl_dari)) : date('Y-m-d')) ?>" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Jam</label>
                                    <input type="time" name="jam_dari_so" id="jam_dari_so" value="<?= ((!empty($cek_jadwal)) ? date('H:i:s', strtotime($cek_jadwal->jam_dari)) : date('H:i:s', strtotime('23:59:59'))) ?>" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <span class="h5">PERIODE SAMPAI</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Tanggal</label>
                                    <input type="date" name="tgl_sampai_so" id="tgl_sampai_so" value="<?= ((!empty($cek_jadwal)) ? date('Y-m-d', strtotime($cek_jadwal->tgl_sampai)) : date('Y-m-d')) ?>" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Jam</label>
                                    <input type="time" name="jam_sampai_so" id="jam_sampai_so" value="<?= ((!empty($cek_jadwal)) ? date('H:i:s', strtotime($cek_jadwal->jam_sampai)) : date('H:i:s', strtotime('23:59:59'))) ?>" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm float-right" id="btnSchedule" onclick="buat_schedule()"><ion-icon name="options-outline"></ion-icon> Jalankan Proses SO</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const form_lock = $('#form_schedule_so')
    var tgl_dari_so = $('#tgl_dari_so')
    var jam_dari_so = $('#jam_dari_so')
    var tgl_sampai_so = $('#tgl_sampai_so')
    var jam_sampai_so = $('#jam_sampai_so')

    function buat_schedule() {
        if (tgl_dari_so.val() == '' || jam_dari_so.val() == '' || tgl_sampai_so.val() == '' || jam_sampai_so.val() == '' ||
            tgl_dari_so.val() == null || jam_dari_so.val() == null || tgl_sampai_so.val() == null || jam_sampai_so.val() == null
        ) {
            return Swal.fire("Jadwal SO", "Form harus lengkap!, silahkan dicoba kembali", "info");
        }

        $.ajax({
            url: siteUrl + 'Transaksi/schedule_so',
            type: 'POST',
            data: form_lock.serialize(),
            dataType: 'JSON',
            success: function(result) { // jika fungsi berjalan dengan baik

                if (result.status == 1) { // jika mendapatkan hasil 1
                    Swal.fire("Jadwal Stok", "Berhasil di kunci!", "success").then(() => {
                        reloadTable();
                    });
                } else { // selain itu

                    Swal.fire("Jadwal Stok", "Gagal di kunci!, silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error

                error_proccess();
            }
        })
    }
</script>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_so">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Stock Opname</span>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-success ml-1" onclick="getUrl('Transaksi/form_so/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
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
                                    <button type="button" class="btn btn-secondary btn-sm float-right" onclick="filter($('#kode_gudang').val())" title="Filter" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"><ion-icon name="filter-outline"></ion-icon></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tablePenyesuaianStok" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="20%">Tgl/Jam Penyesuaian</th>
                                            <th width="25%">Kode Penyesuaian</th>
                                            <th width="25%">Gudang</th>
                                            <th width="15%">Tipe Penyesuaian</th>
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