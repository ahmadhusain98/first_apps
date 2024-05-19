<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_gudang">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b># Form Gudang</b></h4>
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
                                                <input type="text" class="form-control" id="kodeGudang" name="kodeGudang" placeholder="Otomatis" readonly value="<?= (!empty($gudang) ? $gudang->kode_gudang : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama">Nama <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" onkeyup="ubah_nama(this.value, 'nama')" value="<?= (!empty($gudang) ? $gudang->nama : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="bagian" class="control-label">Bagian <span class="text-danger">**</span></label>
                                                <select name="bagian" id="bagian" class="form-control select2_global" data-placeholder="~ Pilih">
                                                    <option value="">~ Pilih</option>
                                                    <option value="Internal" <?= (!empty($gudang) ? ($gudang->bagian == 'Internal') ? 'selected' : '' : '') ?>>Internal</option>
                                                    <option value="Logistik" <?= (!empty($gudang) ? ($gudang->bagian == 'Logistik') ? 'selected' : '' : '') ?>>Logistik</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="vat">Pajak (11%) <span class="text-danger">**</span></label>
                                                <select name="vat" id="vat" class="form-control select2_global" data-placeholder="~ Pilih">
                                                    <option value="">~ Pilih</option>
                                                    <option value="1" <?= (!empty($gudang) ? ($gudang->vat == '1') ? 'selected' : '' : '') ?>>Ya</option>
                                                    <option value="0" <?= (!empty($gudang) ? ($gudang->vat == '0') ? 'selected' : '' : '') ?>>Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="keterangan">Keterangan <span class="text-danger">**</span></label>
                                                <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan Keterangan" onkeyup="ubah_nama(this.value, 'keterangan')"><?= (!empty($gudang) ? $gudang->keterangan : '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm" onclick="getUrl('Master/gudang')" id="btnKembali"><ion-icon name="play-back-outline"></ion-icon> Kembali</button>
                            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan"><ion-icon name="save-outline"></ion-icon> <?= (!empty($gudang) ? 'Perbarui' : 'Simpan') ?></button>
                            <?php if (!empty($gudang)) : ?>
                                <button type="button" class="btn btn-success float-right btn-sm" onclick="getUrl('Master/form_gudang/0')" id="btnBaru"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
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
    const form = $('#form_gudang');
    const btnSimpan = $('#btnSimpan');
    var kodeGudang = $('#kodeGudang');
    var nama = $('#nama');
    var bagian = $('#bagian');
    var vat = $('#vat');
    var keterangan = $('#keterangan');

    btnSimpan.attr('disabled', false);

    // fungsi simpan
    function save() {
        btnSimpan.attr('disabled', true);

        if (nama.val() == '' || nama.val() == null) { // jika nama null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Nama", "Form sudah diisi?", "question");
        }

        if (bagian.val() == '' || bagian.val() == null) { // jika bagian null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("No. Hp", "Form sudah diisi?", "question");
        }

        if (vat.val() == '' || vat.val() == null) { // jika vat null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Email", "Form sudah diisi?", "question");
        }

        if (keterangan.val() == '' || keterangan.val() == null) { // jika keterangan null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Fax", "Form sudah diisi?", "question");
        }

        if (kodeGudang.val() == '' || kodeGudang.val() == null) { // jika kode_gudang null/ kosong
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        $("#loading").modal("show");

        // jalankan proses cek gudang
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Master/cekGud',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(result) { // jika fungsi berjalan dengan baik
                    if (result.status == 1) { // jika mendapatkan respon 1
                        // jalankan fungsi proses berdasarkan param
                        proses(param);
                    } else { // selain itu
                        $("#loading").modal("hide");

                        Swal.fire("Nama", "Sudah ada!, silahkan isi nama lain ", "info");
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
            url: siteUrl + 'Master/gudang_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1
                    $("#loading").modal("hide");

                    Swal.fire("Gudang", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/gudang');
                    });
                } else { // selain itu
                    $("#loading").modal("hide");

                    Swal.fire("Gudang", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                $("#loading").modal("hide");

                error_proccess();
            }
        });
    }

    // fungsi reset form
    function reset() {
        if (kodeGudang.val() == '' || kodeGudang.val() == null) { // jika kode_gudangnya tidak ada isi/ null
            // kosongkan
            kodeGudang.val('');
        }

        nama.val('');
        vat.val('');
        bagian.val('');
        keterangan.val('');
    }
</script>