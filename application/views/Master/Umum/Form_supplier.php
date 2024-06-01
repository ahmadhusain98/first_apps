<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_supplier">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b># Form Pemasok</b></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="id" class="control-label">ID <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="kodeSupplier" name="kodeSupplier" placeholder="Otomatis" readonly value="<?= (!empty($supplier) ? $supplier->kode_supplier : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama">Nama <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" onkeyup="ubah_nama(this.value, 'nama')" value="<?= (!empty($supplier) ? $supplier->nama : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nohp" class="control-label">No. Hp <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Masukan No. Hp" value="<?= (!empty($supplier) ? $supplier->nohp : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="email">Email <span class="text-danger">**</span></label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" onchange="cekEmail('email')" value="<?= (!empty($supplier) ? $supplier->email : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="fax" class="control-label">Fax <span class="text-danger">**</span></label>
                                                <input type="number" class="form-control" id="fax" name="fax" placeholder="Masukan Fax" value="<?= (!empty($supplier) ? $supplier->fax : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="alamat">Alamat <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" onkeyup="ubah_nama(this.value, 'alamat')" value="<?= (!empty($supplier) ? $supplier->alamat : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm" onclick="getUrl('Master/supplier')" id="btnKembali"><ion-icon name="play-back-outline"></ion-icon> Kembali</button>
                            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan"><ion-icon name="save-outline"></ion-icon> <?= (!empty($supplier) ? 'Perbarui' : 'Simpan') ?></button>
                            <?php if (!empty($supplier)) : ?>
                                <button type="button" class="btn btn-success float-right btn-sm" onclick="getUrl('Master/form_supplier/0')" id="btnBaru"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
                            <?php else : ?>
                                <button type="button" class="btn btn-info float-right btn-sm" onclick="reset()" id="btnReset"><ion-icon name="refresh-outline"></ion-icon> Reset</button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var table;
    const form = $('#form_supplier');
    const btnSimpan = $('#btnSimpan');
    var kodeSupplier = $('#kodeSupplier');
    var nama = $('#nama');
    var nohp = $('#nohp');
    var email = $('#email');
    var fax = $('#fax');
    var alamat = $('#alamat');

    btnSimpan.attr('disabled', false);

    // fungsi simpan
    function save() {
        btnSimpan.attr('disabled', true);

        if (nama.val() == '' || nama.val() == null) { // jika nama null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Nama", "Form sudah diisi?", "question");
        }

        if (nohp.val() == '' || nohp.val() == null) { // jika nohp null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("No. Hp", "Form sudah diisi?", "question");
        }

        if (email.val() == '' || email.val() == null) { // jika email null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Email", "Form sudah diisi?", "question");
        }

        if (fax.val() == '' || fax.val() == null) { // jika fax null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Fax", "Form sudah diisi?", "question");
        }

        if (alamat.val() == '' || alamat.val() == null) { // jika alamat null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Alamat", "Form sudah diisi?", "question");
        }

        if (kodeSupplier.val() == '' || kodeSupplier.val() == null) { // jika kode_supplier null/ kosong
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek supplier
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Master/cekSup',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(result) { // jika fungsi berjalan dengan baik
                    if (result.status == 1) { // jika mendapatkan respon 1
                        // jalankan fungsi proses berdasarkan param
                        proses(param);
                    } else { // selain itu

                        Swal.fire("Nama", "Sudah ada!, silahkan isi nama lain ", "info");
                    }
                },
                error: function(result) { // jika fungsi error
                    btnSimpan.attr('disabled', false);

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
            url: siteUrl + 'Master/supplier_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Supplier", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/supplier');
                    });
                } else { // selain itu

                    Swal.fire("Supplier", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    // fungsi reset form
    function reset() {
        if (kodeSupplier.val() == '' || kodeSupplier.val() == null) { // jika kode_suppliernya tidak ada isi/ null
            // kosongkan
            kodeSupplier.val('');
        }

        nama.val('');
        email.val('');
        nohp.val('');
        fax.val('');
        alamat.val('');
    }
</script>