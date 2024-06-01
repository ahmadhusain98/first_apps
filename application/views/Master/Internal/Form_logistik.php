<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_logistik">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b># Form Logistik</b></h4>
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
                                                <input type="text" class="form-control" id="kodeLogistik" name="kodeLogistik" placeholder="Otomatis" readonly value="<?= (!empty($logistik) ? $logistik->kode_logistik : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama">Nama <span class="text-danger">**</span></label>
                                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" onkeyup="ubah_nama(this.value, 'nama')" value="<?= (!empty($logistik) ? $logistik->nama : '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="kode_satuan" class="control-label">Satuan <span class="text-danger">**</span></label>
                                                <select name="kode_satuan" id="kode_satuan" class="form-control select2_global" data-placeholder="~ Pilih">
                                                    <option value="">~ Pilih</option>
                                                    <?php foreach ($satuan as $s) : ?>
                                                        <option value="<?= $s->kode_satuan ?>" <?= (!empty($logistik) ? (($s->kode_satuan == $logistik->kode_satuan) ? 'selected' : '') : '') ?>><?= $s->keterangan ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="kode_kategori" class="control-label">Kategori <span class="text-danger">**</span></label>
                                                <select name="kode_kategori" id="kode_kategori" class="form-control select2_global" data-placeholder="~ Pilih">
                                                    <option value="">~ Pilih</option>
                                                    <?php foreach ($kategori as $k) : ?>
                                                        <option value="<?= $k->kode_kategori ?>" <?= (!empty($logistik) ? (($k->kode_kategori == $logistik->kode_kategori) ? 'selected' : '') : '') ?>><?= $k->keterangan ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="hna">HNA <span class="text-danger">**</span></label>
                                                <input type="text" name="hna" id="hna" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->hna, 2) : '0.00') ?>" onchange="formatRp(this.value, 'hna'); getHpp(this.value)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="hpp">HPP <span class="text-danger">**</span></label>
                                                <input type="text" name="hpp" id="hpp" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->hpp, 2) : '0.00') ?>" onchange="formatRp(this.value, 'hpp')" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="harga_jual">Jual <span class="text-danger">**</span></label>
                                                <input type="text" name="harga_jual" id="harga_jual" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->harga_jual, 2) : '0.00') ?>" onchange="formatRp(this.value, 'harga_jual'); cekHna(this.value)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nilai_persediaan">Nilai Persediaan <span class="text-danger">**</span></label>
                                                <input type="text" name="nilai_persediaan" id="nilai_persediaan" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->nilai_persediaan, 2) : '0.00') ?>" onchange="formatRp(this.value, 'nilai_persediaan')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm" onclick="getUrl('Master/logistik')" id="btnKembali"><ion-icon name="play-back-outline"></ion-icon> Kembali</button>
                            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan"><ion-icon name="save-outline"></ion-icon> <?= (!empty($logistik) ? 'Perbarui' : 'Simpan') ?></button>
                            <?php if (!empty($logistik)) : ?>
                                <button type="button" class="btn btn-success float-right btn-sm" onclick="getUrl('Master/form_logistik/0')" id="btnBaru"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
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
    const form = $('#form_logistik');
    const btnSimpan = $('#btnSimpan');
    var kodeLogistik = $('#kodeLogistik');
    var nama = $('#nama');
    var kode_satuan = $('#kode_satuan');
    var kode_kategori = $('#kode_kategori');
    var hna = $('#hna');
    var hpp = $('#hpp');
    var harga_jual = $('#harga_jual');
    var nilai_persediaan = $('#nilai_persediaan');

    btnSimpan.attr('disabled', false);

    // fungsi hitung hpp
    function getHpp(param) {
        var a = parseInt(param.replaceAll(',', ''));
        var result = a + (a * (<?= $pajak ?> / 100));
        formatRp(result, 'hpp');
    }

    // fungsi cek HNA
    function cekHna(param) {
        var x = hna.val();
        var a = parseInt(param.replaceAll(',', ''));
        var b = parseInt(x.replaceAll(',', ''));

        if (b > a) {
            Swal.fire("Jual", "Tidak boleh lebih kecil dari HNA", "question");
            formatRp(b, 'harga_jual');
        } else {
            formatRp(a, 'harga_jual');
        }
    }

    // fungsi simpan
    function save() {
        btnSimpan.attr('disabled', true);

        if (nama.val() == '' || nama.val() == null) { // jika nama null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Nama", "Form sudah diisi?", "question");
            return;
        }

        if (kode_satuan.val() == '' || kode_satuan.val() == null) { // jika kode_satuan null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Satuan", "Form sudah diisi?", "question");
            return;
        }

        if (kode_kategori.val() == '' || kode_kategori.val() == null) { // jika kode_kategori null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Kategori", "Form sudah diisi?", "question");
            return;
        }

        if (hna.val() == '' || hna.val() == null || hna.val() == '0.00') { // jika hna null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("HNA", "Form sudah diisi?", "question");
            return;
        }

        if (hpp.val() == '' || hpp.val() == null || hpp.val() == '0.00') { // jika hpp null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("HPP", "Form sudah diisi?", "question");
            return;
        }

        if (harga_jual.val() == '' || harga_jual.val() == null || harga_jual.val() == '0.00') { // jika harga_jual null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Jual", "Form sudah diisi?", "question");
            return;
        }

        if (nilai_persediaan.val() == '' || nilai_persediaan.val() == null || nilai_persediaan.val() == '0.00') { // jika nilai_persediaan null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Nilai Persediaan", "Form sudah diisi?", "question");
            return;
        }

        if (kodeLogistik.val() == '' || kodeLogistik.val() == null) { // jika kode_logistik null/ kosong
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek logistik
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Master/cekBar',
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
            url: siteUrl + 'Master/logistik_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Logistik", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/logistik');
                    });
                } else { // selain itu

                    Swal.fire("Logistik", "Gagal " + message + ", silahkan dicoba kembali", "info");
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
        if (kodeLogistik.val() == '' || kodeLogistik.val() == null) { // jika kode_logistiknya tidak ada isi/ null
            // kosongkan
            kodeLogistik.val('');
        }

        nama.val('');
        hna.val('0.00');
        hpp.val('0.00');
        kode_satuan.val('').change();
        kode_kategori.val('').change();
        harga_jual.val('0.00');
        nilai_persediaan.val('0.00');
    }
</script>