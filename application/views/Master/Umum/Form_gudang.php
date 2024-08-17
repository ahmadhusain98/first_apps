<form method="post" id="form_gudang">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
        </div>
    </div>
    <br>
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
                                <label for="pajak">Pajak <span class="text-danger">**</span></label>
                                <select name="pajak" id="pajak" class="form-control select2_pajak" data-placeholder="~ Pilih">
                                    <?php if (!empty($gudang)) : ?>
                                        <option value="<?= $gudang->pajak ?>"><?= $this->M_global->getData('m_pajak', ['kode_pajak' => $gudang->pajak])->nama; ?></option>
                                    <?php else: ?>
                                        <option value="">~ Pilih</option>
                                    <?php endif; ?>
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
    <br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger" onclick="getUrl('Master/gudang')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
            <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
            <?php if (!empty($gudang)) : ?>
                <button type="button" class="btn btn-info float-right" onclick="getUrl('Master/form_gudang/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            <?php else : ?>
                <button type="button" class="btn btn-info float-right" onclick="reset()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
            <?php endif ?>
        </div>
    </div>
</form>

<script>
    var table;
    const form = $('#form_gudang');
    const btnSimpan = $('#btnSimpan');
    var kodeGudang = $('#kodeGudang');
    var nama = $('#nama');
    var bagian = $('#bagian');
    var pajak = $('#pajak');
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

        if (pajak.val() == '' || pajak.val() == null) { // jika pajak null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Pajak", "Form sudah diisi?", "question");
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
            url: siteUrl + 'Master/gudang_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Gudang", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/gudang');
                    });
                } else { // selain itu

                    Swal.fire("Gudang", "Gagal " + message + ", silahkan dicoba kembali", "info");
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
        if (kodeGudang.val() == '' || kodeGudang.val() == null) { // jika kode_gudangnya tidak ada isi/ null
            // kosongkan
            kodeGudang.val('');
        }

        nama.val('');
        pajak.val(null).trigger('change');
        bagian.val(null).trigger('change');
        keterangan.val('');
    }
</script>