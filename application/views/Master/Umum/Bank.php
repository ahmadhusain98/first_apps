<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_bank">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b># Bank</b></h4>
                </div>
                <div class="card-body">
                    <div class="card shadow">
                        <div class="card-header"># Form</div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="id" class="control-label">ID <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="kodeBank" name="kodeBank" placeholder="Otomatis" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="keterangan">Keterangan <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Bank" onkeyup="ubah_nama(this.value, 'keterangan')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="save-outline"></ion-icon> Simpan</button>
                            <button type="button" class="btn btn-info float-right btn-sm" onclick="reset()" id="btnReset"><ion-icon name="refresh-outline"></ion-icon> Reset</button>
                        </div>
                    </div>
                    <br>
                    <div class="card shadow">
                        <div class="card-header">
                            <span># Daftar</span>
                            <button type="button" class="btn btn-sm btn-primary float-right" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered" id="tableBank" width="100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="5%">#</th>
                                                    <th width="20%">ID</th>
                                                    <th width="55%">Keterangan</th>
                                                    <th width="20%">Aksi</th>
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
    </div>
</div>

<script>
    // variable
    var table = $('#tableBank');
    const form = $('#form_bank');
    var kodeBank = $('#kodeBank');
    var keterangan = $('#keterangan');
    var btnSimpan = $('#btnSimpan');

    btnSimpan.attr('disabled', false);

    // fungsi simpan
    function save() {
        btnSimpan.attr('disabled', true);

        if (keterangan.val() == '' || keterangan.val() == null) { // jika keterangan null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Keterangan", "Form sudah diisi?", "question");
        }

        if (kodeBank.val() == '' || kodeBank.val() == null) { // jika kode_bank null/ kosong
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek bank
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Master/cekKat',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(result) { // jika fungsi berjalan dengan baik
                    if (result.status == 1) { // jika mendapatkan respon 1
                        // jalankan fungsi proses berdasarkan param
                        proses(param);
                    } else { // selain itu
                        $("#loading").modal("hide");

                        Swal.fire("Keterangan", "Sudah ada!, silahkan isi keterangan lain ", "info");
                    }
                },
                error: function(result) { // jika fungsi error
                    btnSimpan.attr('disabled', false);

                    $("#loading").modal("hide");

                    error_proccess();
                }
            });
        } else {
            proses(param);
        }

    }

    // fungsi proses dengan param
    function proses(param) {

        if (param == 1) { // jika param 1 berarti insert/tambah
            var message = 'dibuat!';
        } else { // selain itu berarti update/ubah
            var message = 'diperbarui!';
        }

        // jalankan proses dengan param insert/update
        $.ajax({
            url: siteUrl + 'Master/bank_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1
                    $("#loading").modal("hide");

                    Swal.fire("Bank", "Berhasil " + message, "success").then(() => {
                        reset();
                        reloadTable();
                    });
                } else { // selain itu
                    $("#loading").modal("hide");

                    Swal.fire("Bank", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                $("#loading").modal("hide");

                error_proccess();
            }
        });
    }

    //fungsi ubah berdasarkan lemparan kode
    function ubah(kode_bank) {
        // jalankan fungsi
        $.ajax({
            url: siteUrl + 'Master/getInfoBank/' + kode_bank,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) {
                if (result) { // jika hasilnya ada, isi form dengan hasil
                    kodeBank.val(kode_bank);
                    keterangan.val(result.keterangan);
                } else { // selain itu, kosongkan
                    reset();
                }
            }
        });
    }

    // fungsi reset form
    function reset() {
        kodeBank.val('');
        keterangan.val('');
    }

    // fungsi hapus berdasarkan kode_bank
    function hapus(kode_bank) {
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
                    url: siteUrl + 'Master/delBank/' + kode_bank,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik
                        btnSimpan.attr('disabled', false);

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            $("#loading").modal("hide");

                            Swal.fire("Bank", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu
                            $("#loading").modal("hide");

                            Swal.fire("Bank", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error
                        btnSimpan.attr('disabled', false);

                        $("#loading").modal("hide");

                        error_proccess();
                    }
                });
            }
        });
    }
</script>