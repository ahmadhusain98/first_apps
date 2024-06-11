<form method="post" id="form_barang">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><ion-icon name="bookmark-outline" style="color: red;"></ion-icon> Formulir</span>
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
                                <input type="text" class="form-control" id="kodeBarang" name="kodeBarang" placeholder="Otomatis" readonly value="<?= (!empty($barang) ? $barang->kode_barang : '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nama">Nama <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" onkeyup="ubah_nama(this.value, 'nama')" value="<?= (!empty($barang) ? $barang->nama : '') ?>">
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
                                        <option value="<?= $s->kode_satuan ?>" <?= (!empty($barang) ? (($s->kode_satuan == $barang->kode_satuan) ? 'selected' : '') : '') ?>><?= $s->keterangan ?></option>
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
                                        <option value="<?= $k->kode_kategori ?>" <?= (!empty($barang) ? (($k->kode_kategori == $barang->kode_kategori) ? 'selected' : '') : '') ?>><?= $k->keterangan ?></option>
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
                                <input type="text" name="hna" id="hna" class="form-control text-right" value="<?= (!empty($barang) ? number_format($barang->hna) : '0') ?>" onchange="formatRp(this.value, 'hna'); getHpp(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="hpp">HPP <span class="text-danger">**</span></label>
                                <input type="text" name="hpp" id="hpp" class="form-control text-right" value="<?= (!empty($barang) ? number_format($barang->hpp) : '0') ?>" onchange="formatRp(this.value, 'hpp'); cekHna(this.value, 'hpp')">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="harga_jual">Jual <span class="text-danger">**</span></label>
                                <input type="text" name="harga_jual" id="harga_jual" class="form-control text-right" value="<?= (!empty($barang) ? number_format($barang->harga_jual) : '0') ?>" onchange="formatRp(this.value, 'harga_jual'); cekHpp(this.value, 'harga_jual')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nilai_persediaan">Nilai Persediaan <span class="text-danger">**</span></label>
                                <input type="text" name="nilai_persediaan" id="nilai_persediaan" class="form-control text-right" value="<?= (!empty($barang) ? number_format($barang->nilai_persediaan) : '0') ?>" onchange="formatRp(this.value, 'nilai_persediaan'); cekHpp(this.value, 'nilai_persediaan')">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="stok_min">Stok Minimal <span class="text-danger">**</span></label>
                                <input type="text" name="stok_min" id="stok_min" class="form-control text-right" value="<?= (!empty($barang) ? number_format($barang->stok_min) : '0') ?>" onchange="formatRp(this.value, 'stok_min')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="stok_max">Stok Maksimal <span class="text-danger">**</span></label>
                                <input type="text" name="stok_max" id="stok_max" class="form-control text-right" value="<?= (!empty($barang) ? number_format($barang->stok_max) : '0') ?>" onchange="formatRp(this.value, 'stok_max')">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="filefoto" class="control-label">Gambar</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <img id="preview_img" class="rounded mx-auto d-block" style="border: 2px solid grey; width: 100%;" src="<?= base_url('assets/img/obat/') . (!empty($barang) ? $barang->image : 'default.jpg'); ?>" alt="User profile picture">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="filefoto" aria-describedby="inputGroupFileAddon01" name="filefoto" onchange="readURL(this)">
                                        <label class="custom-file-label" id="label-gambar" for="inputGroupFile01">Cari Gambar</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="kode_jenis" class="control-label">Jenis Obat <span class="text-danger">**</span></label>
                                <select name="kode_jenis[]" id="kode_jenis" class="form-control select2_global" data-placeholder="~ Pilih" multiple="multiple">
                                    <option value="">~ Pilih</option>
                                    <?php if (!empty($barang)) :
                                        $bj_arr = [];
                                        foreach ($barang_jenis as $bj) :
                                            $bj_arr[] = $bj->kode_jenis;
                                    ?>
                                    <?php endforeach;
                                    endif; ?>
                                    <?php foreach ($jenis as $j) : ?>
                                        <option value="<?= $j->kode_jenis ?>" <?= (!empty($barang) ? (in_array($j->kode_jenis, $bj_arr) ? 'selected' : '') : '') ?>><?= $j->keterangan ?></option>
                                    <?php endforeach; ?>
                                </select>
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
            <button type="button" class="btn btn-danger btn-sm" onclick="getUrl('Master/barang')" id="btnKembali"><ion-icon name="play-back-outline"></ion-icon> Kembali</button>
            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan"><ion-icon name="save-outline"></ion-icon> <?= (!empty($barang) ? 'Perbarui' : 'Simpan') ?></button>
            <?php if (!empty($barang)) : ?>
                <button type="button" class="btn btn-success float-right btn-sm" onclick="getUrl('Master/form_barang/0')" id="btnBaru"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
            <?php else : ?>
                <button type="button" class="btn btn-info float-right btn-sm" onclick="reset()" id="btnReset"><ion-icon name="refresh-outline"></ion-icon> Reset</button>
            <?php endif ?>
        </div>
    </div>
</form>

<script>
    var table;
    const form = $('#form_barang');
    const btnSimpan = $('#btnSimpan');
    var kodeBarang = $('#kodeBarang');
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
        if (param < 1) {
            hna.val(formatNonRp(param));
            Swal.fire("Nama", "Sudah ada!, silahkan isi nama lain ", "info");
            return;
        }

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
            Swal.fire("HPP", "Tidak boleh lebih kecil dari HNA", "question");
            formatRp(b, 'hpp');
        } else {
            formatRp(a, 'hpp');
        }
    }

    // fungsi cek HPP
    function cekHpp(param, forid) {
        var x = hpp.val();
        var a = parseInt(param.replaceAll(',', ''));
        var b = parseInt(x.replaceAll(',', ''));

        if (forid == 'harga_jual') {
            if (b > a) {
                Swal.fire("Jual", "Tidak boleh lebih kecil dari HPP", "question");
                formatRp(b, 'harga_jual');
            } else {
                formatRp(a, 'harga_jual');
            }
        } else {
            if (b > a) {
                Swal.fire("Nilai Persediaan", "Tidak boleh lebih kecil dari HPP", "question");
                formatRp(b, 'nilai_persediaan');
            } else {
                formatRp(a, 'nilai_persediaan');
            }
        }
    }

    // preview image
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#div_preview_foto').css("display", "block");
                $('#preview_img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#div_preview_foto').css("display", "none");
            $('#preview_img').attr('src', '');
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

        if (kodeBarang.val() == '' || kodeBarang.val() == null) { // jika kode_barang null/ kosong
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek barang
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
                        btnSimpan.attr('disabled', false);

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
        var form = $('#form_barang')[0];
        var data = new FormData(form);

        $.ajax({
            url: siteUrl + 'Master/barang_proses/' + param,
            type: "POST",
            enctype: 'multipart/form-data',
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Barang", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/barang');
                    });
                } else { // selain itu

                    Swal.fire("Barang", "Gagal " + message + ", silahkan dicoba kembali", "info");
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
        if (kodeBarang.val() == '' || kodeBarang.val() == null) { // jika kode_barangnya tidak ada isi/ null
            // kosongkan
            kodeBarang.val('');
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