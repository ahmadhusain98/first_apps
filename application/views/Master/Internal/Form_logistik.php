<form method="post" id="form_logistik">
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
                                <input type="text" class="form-control" id="kodeLogistik" name="kodeLogistik" placeholder="Otomatis" value="<?= (!empty($logistik) ? $logistik->kode_logistik : '') ?>">
                            </div>
                        </div>
                    </div>
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
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nama">Nama <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" onkeyup="ubah_nama(this.value, 'nama')" value="<?= (!empty($logistik) ? $logistik->nama : '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="kode_satuan2" class="control-label">Satuan 2</label>
                                <select name="kode_satuan2" id="kode_satuan2" class="form-control select2_global" data-placeholder="~ Pilih">
                                    <option value="">~ Pilih</option>
                                    <?php foreach ($satuan as $s) : ?>
                                        <option value="<?= $s->kode_satuan ?>" <?= (!empty($logistik) ? (($s->kode_satuan == $logistik->kode_satuan2) ? 'selected' : '') : '') ?>><?= $s->keterangan ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="qty_satuan2">Qty Satuan 2</label>
                                <input type="text" name="qty_satuan2" id="qty_satuan2" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->qty_satuan2) : 0) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
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
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="kode_satuan3" class="control-label">Satuan 3</label>
                                <select name="kode_satuan3" id="kode_satuan3" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                                    <option value="">~ Pilih Satuan</option>
                                    <?php foreach ($satuan as $s) : ?>
                                        <option value="<?= $s->kode_satuan ?>" <?= (!empty($logistik) ? (($s->kode_satuan == $logistik->kode_satuan3) ? 'selected' : '') : '') ?>><?= $s->keterangan ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="qty_satuan3">Qty Satuan 3</label>
                                <input type="text" name="qty_satuan3" id="qty_satuan3" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->qty_satuan3) : 0) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="hna">HNA <span class="text-danger">**</span></label>
                                <input type="text" name="hna" id="hna" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->hna, 2) : '0') ?>" onchange="formatRp(this.value, 'hna'); getHpp(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="hpp">HPP <span class="text-danger">**</span></label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select name="opsi_hpp" id="opsi_hpp" class="form-control select2_global" onchange="cek_opsi_hpp(this.value)">
                                            <option value="1" <?= (!empty($logistik) ? (($logistik->opsi_hpp == 1) ? 'selected' : '') : '') ?>>Manual</option>
                                            <option value="2" <?= (!empty($logistik) ? (($logistik->opsi_hpp == 2) ? 'selected' : '') : '') ?>>Persentase</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" name="persentase_hpp" id="persentase_hpp" class="form-control text-right" placeholder="%" value="<?= (!empty($logistik) ? number_format($logistik->persentase_hpp) : '') ?>" onchange="get_hpp(this.value)">
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="hpp" id="hpp" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->hpp) : '0') ?>" onchange="formatRp(this.value, 'hpp'); cekHna(this.value, 'hpp')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="harga_jual">Jual <span class="text-danger">**</span></label>
                                <input type="text" name="harga_jual" id="harga_jual" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->harga_jual, 2) : '0') ?>" onchange="cekHpp(this.value, 'harga_jual')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nilai_persediaan">Nilai Persediaan <span class="text-danger">**</span></label>
                                <input type="text" name="nilai_persediaan" id="nilai_persediaan" class="form-control text-right" value="<?= (!empty($logistik) ? number_format($logistik->nilai_persediaan, 2) : '0') ?>" onchange="cekHpp(this.value, 'nilai_persediaan')">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="kode_cabang" class="control-label">Cabang <span class="text-danger">**</span></label>
                        <select name="kode_cabang[]" id="kode_cabang" class="form-control select2_global" data-placeholder="~ Pilih Cabang" multiple="multiple">
                            <option value="">~ Pilih Cabang</option>
                            <?php if (!empty($logistik)) :
                                $cabang_arr = [];
                                foreach ($barang_cabang as $bc) :
                                    $cabang_arr[] = $bc->kode_cabang;
                            ?>
                            <?php endforeach;
                            endif; ?>
                            <?php foreach ($cabang_all as $ca) : ?>
                                <option value="<?= $ca->kode_cabang ?>" <?= (!empty($logistik) ? (in_array($ca->kode_cabang, $cabang_arr) ? 'selected' : '') : '') ?>><?= $ca->cabang ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger" onclick="getUrl('Master/logistik')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
            <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
            <?php if (!empty($logistik)) : ?>
                <button type="button" class="btn btn-info float-right" onclick="getUrl('Master/form_logistik/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            <?php else : ?>
                <button type="button" class="btn btn-info float-right" onclick="reset()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
            <?php endif ?>
        </div>
    </div>
</form>

<script>
    var table;
    const form = $('#form_logistik');
    const btnSimpan = $('#btnSimpan');
    var kodeLogistik = $('#kodeLogistik');
    var nama = $('#nama');
    var kode_satuan = $('#kode_satuan');
    var kode_satuan2 = $('#kode_satuan2');
    var kode_satuan3 = $('#kode_satuan3');
    var qty_satuan2 = $('#qty_satuan2');
    var qty_satuan3 = $('#qty_satuan3');
    var kode_kategori = $('#kode_kategori');
    var hna = $('#hna');
    var hpp = $('#hpp');
    var opsi_hpp = $('#opsi_hpp');
    var persentase_hpp = $('#persentase_hpp');
    var kode_cabang = $('#kode_cabang');
    var harga_jual = $('#harga_jual');
    var nilai_persediaan = $('#nilai_persediaan');

    btnSimpan.attr('disabled', false);

    <?php if (!empty($logistik)) : ?>
        <?php if ($logistik->opsi_hpp == 1) : ?>
            persentase_hpp.attr('readonly', true);
            hpp.attr('readonly', false);
        <?php else : ?>
            persentase_hpp.attr('readonly', false);
            hpp.attr('readonly', true);
        <?php endif; ?>
    <?php else : ?>
        persentase_hpp.attr('readonly', true);
        hpp.attr('readonly', false);
    <?php endif; ?>

    // opsi hpp
    function cek_opsi_hpp(param) {
        var harga_awal = hna.val();
        if (param == 1) {
            persentase_hpp.attr('readonly', true);
            hpp.attr('readonly', false);
            formatRp(harga_awal, 'hpp');
            persentase_hpp.val('');
        } else {
            persentase_hpp.attr('readonly', false);
            hpp.attr('readonly', true);
            formatRp(0, 'hpp');
            persentase_hpp.val(persentase_hpp.val());
        }
    }

    // get hpp
    function get_hpp(persentase) {
        if (persentase > 100) {
            var harga_awal = Number(parseInt(hna.val().replaceAll(',', '')));
            var harga_tambahan = harga_awal * 1;
            var harga_hpp = harga_awal + harga_tambahan;

            formatRp(100, 'persentase_hpp');
            formatRp(harga_hpp, 'hpp');
            return Swal.fire("Persentase", "Maksimal adalah 100%", "info");
        }

        var harga_awal = Number(parseInt(hna.val().replaceAll(',', '')));
        var harga_tambahan = harga_awal * (Number(persentase) / 100);
        var harga_hpp = harga_awal + harga_tambahan;
        formatRp(persentase, 'persentase_hpp');
        formatRp(harga_hpp, 'hpp');
    }

    // fungsi hitung hpp
    function getHpp(param) {
        // if (param < 1) {
        //     hna.val(formatNonRp(param));
        //     Swal.fire("Nama", "Sudah ada!, silahkan isi nama lain ", "info");
        //     return;
        // }

        var a = parseInt(param.replaceAll(',', ''));
        // var result = a + (a * (<?= $pajak ?> / 100));
        // formatRp(result, 'hpp');
        var result = a;
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

        if (hna.val() == '' || hna.val() == null || hna.val() == '0') { // jika hna null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("HNA", "Form sudah diisi?", "question");
            return;
        }

        if (hpp.val() == '' || hpp.val() == null || hpp.val() == '0') { // jika hpp null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("HPP", "Form sudah diisi?", "question");
            return;
        }

        if (harga_jual.val() == '' || harga_jual.val() == null || harga_jual.val() == '0') { // jika harga_jual null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Jual", "Form sudah diisi?", "question");
            return;
        }

        if (nilai_persediaan.val() == '' || nilai_persediaan.val() == null || nilai_persediaan.val() == '0') { // jika nilai_persediaan null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Nilai Persediaan", "Form sudah diisi?", "question");
            return;
        }

        if (kode_cabang.val() == '' || kode_cabang.val() == null || kode_cabang.val() == '0') { // jika kode_cabang null/ kosong
            btnSimpan.attr('disabled', false);

            Swal.fire("Cabang", "Form sudah diisi?", "question");
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
        hna.val('0');
        hpp.val('0');
        kode_satuan.val('').change();
        kode_kategori.val('').change();
        harga_jual.val('0');
        nilai_persediaan.val('0');
    }

    function showGuide() {
        // clean text
        $('#modal_mgLabel').text(``);
        $('#modal-isi').text(``);

        $('#modal_mg').modal('show'); // show modal

        // isi text
        $('#modal_mgLabel').append(`Manual Guide Master Logistik`);
        $('#modal-isi').append(`
            <ol>
                <li style="font-weight: bold;">Tambah Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Tambah</li>
                        <li>Selanjutnya isikan Form yang tersedia<br>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
                <li style="font-weight: bold;">Ubah Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Ubah pada list data yang ingin di ubah</li>
                        <li>Ubah isi Form yang akan di ubah<br>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
                <li style="font-weight: bold;">Hapus Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Hapus pada list data yang ingin di hapus</li>
                        <li>Saat Muncul Pop Up, klik "Ya, Hapus"</li>
                    </ul>
                </p>
            </ol>
        `);
    }
</script>