<?php
if ($data_perawat) {
    $user = $this->M_global->getData('user', ['kode_user' => $data_perawat->kode_perawat]);
    if ($user) {
        $password   = $user->secondpass;
        $jkel       = $user->jkel;
    } else {
        $password   = '';
        $jkel       = '';
    }
} else {
    $password       = '';
    $jkel           = '';
}
?>

<form method="post" id="form_perawat">
    <div class="row" data-aos="fade-down"
        data-aos-easing="linear"
        data-aos-duration="1000">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nik">NIK <sup class="text-danger">**</sup></label>
                                <input type="number" class="form-control" placeholder="NIK" id="nik" name="nik" value="<?= ((!empty($data_perawat)) ? $data_perawat->nik : '') ?>" onchange="getAddress(this.value, 'nik'); cekLength(this.value, 'nik')" <?= (!empty($data_perawat) ? 'readonly' : '') ?> maxlength="16">
                            </div>
                            <div class="col-md-6">
                                <label for="nama">Nama <sup class="text-danger">**</sup></label>
                                <input type="hidden" class="form-control" id="kodePerawat" name="kodePerawat" value="<?= ((!empty($data_perawat)) ? $data_perawat->kode_perawat : '') ?>">
                                <input type="text" class="form-control" placeholder="Nama Lengkap" id="nama" name="nama" value="<?= ((!empty($data_perawat)) ? $data_perawat->nama : '') ?>" onkeyup="ubah_nama(this.value, 'nama')">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email">Email <sup class="text-danger">**</sup></label>
                                <input type="email" class="form-control" placeholder="Email" id="email" name="email" onchange="cekEmail('email')" value="<?= ((!empty($data_perawat)) ? $data_perawat->email : '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="nohp">No. Hp <sup class="text-danger">**</sup></label>
                                <input type="number" class="form-control" placeholder="No. Hp" id="nohp" name="nohp" value="<?= ((!empty($data_perawat)) ? $data_perawat->nohp : '') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="npwp">NPWP (16 Digit) <sup class="text-danger">**</sup></label>
                                <input type="text" class="form-control" placeholder="NPWP" id="npwp" name="npwp" value="<?= ((!empty($data_perawat)) ? $data_perawat->npwp : '') ?>" onchange="cekLength(this.value, 'npwp')" maxlength="16">
                            </div>
                            <div class="col-md-6">
                                <label for="sip">SIP (15 Digit) <sup class="text-danger">**</sup></label>
                                <input type="text" class="form-control" placeholder="No. SIP" id="sip" name="sip" value="<?= ((!empty($data_perawat)) ? $data_perawat->sip : '') ?>" onchange="cekLength(this.value, 'sip')" maxlength="15">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tgl_mulai">Tgl Mulai <sup class="text-danger">**</sup></label>
                                <input type="date" class="form-control" title="Tgl Mulai" id="tgl_mulai" name="tgl_mulai" value="<?= ((!empty($data_perawat)) ? date('Y-m-d', strtotime($data_perawat->tgl_mulai)) : date('Y-m-d')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_berhenti">Tgl Berhenti <sup class="text-danger">**</sup></label>
                                <input type="date" class="form-control" title="Tgl Berhenti" id="tgl_berhenti" name="tgl_berhenti" value="<?= ((!empty($data_perawat)) ? date('Y-m-d', strtotime($data_perawat->tgl_berhenti)) : date('Y-m-d')) ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="statusPerawat">Status <sup class="text-danger">**</sup></label>
                                <select name="statusPerawat" id="statusPerawat" class="form-control select2_global" data-placeholder="~ Pilih Status">
                                    <option value="">~ Pilih Status</option>
                                    <option value="1" <?= (!empty($data_perawat) ? (($data_perawat->status == 1) ? 'selected' : '') : '') ?>>Aktif</option>
                                    <option value="0" <?= (!empty($data_perawat) ? (($data_perawat->status == 0) ? 'selected' : '') : '') ?>>Non-aktif</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="provinsi">Provinsi <sup class="text-danger">**</sup></label>
                                <select name="provinsi" id="provinsi" class="form-control select2_provinsi" data-placeholder="~ Pilih Provinsi" onchange="getKabupaten(this.value)">
                                    <?php
                                    if (!empty($data_perawat)) {
                                        $prov = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $data_perawat->provinsi]);
                                        echo "<option value='" . $prov->kode_provinsi . "'>" . $prov->provinsi . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kabupaten">Kabupaten <sup class="text-danger">**</sup></label>
                                <select name="kabupaten" id="kabupaten" class="form-control select2_kabupaten" data-placeholder="~ Pilih Kabupaten" onchange="getKecamatan(this.value)">
                                    <?php
                                    if (!empty($data_perawat)) {
                                        $prov = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $data_perawat->kabupaten]);
                                        echo "<option value='" . $prov->kode_kabupaten . "'>" . $prov->kabupaten . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kecamatan">Kecamatan <sup class="text-danger">**</sup></label>
                                <select name="kecamatan" id="kecamatan" class="form-control select2_kecamatan" data-placeholder="~ Pilih Kecamatan">
                                    <?php
                                    if (!empty($data_perawat)) {
                                        $prov = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $data_perawat->kecamatan]);
                                        echo "<option value='" . $prov->kode_kecamatan . "'>" . $prov->kecamatan . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="desa">Desa <sup class="text-danger">**</sup></label>
                                <input type="text" class="form-control" placeholder="Desa" id="desa" name="desa" value="<?= ((!empty($data_perawat)) ? $data_perawat->desa : '') ?>" onkeyup="ubah_nama(this.value, 'desa')">
                            </div>
                            <div class="col-md-6">
                                <label for="kodepos">Kodepos <sup class="text-danger">**</sup></label>
                                <input type="number" class="form-control" placeholder="Kode Pos" id="kodepos" name="kodepos" value="<?= ((!empty($data_perawat)) ? $data_perawat->kodepos : '') ?>" onkeyup="cekLength(this.value, 'kodepos')">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kode_poli">Poli <sup class="text-danger">**</sup></label>
                                <select name="kode_poli[]" id="kode_poli" class="form-control select2_global" data-placeholder="~ Pilih Poli" multiple="multiple">
                                    <option value="">~ Pilih Poli</option>
                                    <?php if (!empty($data_perawat)) :
                                        $dp_arr = [];
                                        foreach ($perawat_poli as $dp) :
                                            $dp_arr[] = $dp->kode_poli;
                                    ?>
                                    <?php endforeach;
                                    endif; ?>
                                    <?php foreach ($poli as $p) : ?>
                                        <option value="<?= $p->kode_poli ?>" <?= (!empty($data_perawat) ? (in_array($p->kode_poli, $dp_arr) ? 'selected' : '') : '') ?>><?= $p->keterangan ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kode_cabang">Cabang <sup class="text-danger">**</sup></label>
                                <div class="row">
                                    <div class="col-md-11">
                                        <select name="kode_cabang[]" id="kode_cabang" class="form-control select2_global" data-placeholder="~ Pilih Cabang" multiple="multiple">
                                            <option value="">~ Pilih Cabang</option>
                                            <?php if (!empty($data_perawat)) :
                                                $dp_arr = [];
                                                foreach ($perawat_cabang as $dp) :
                                                    $dp_arr[] = $dp->kode_cabang;
                                            ?>
                                            <?php endforeach;
                                            endif; ?>
                                            <?php foreach ($cabang as $c) : ?>
                                                <option value="<?= $c->kode_cabang ?>" <?= (!empty($data_perawat) ? (in_array($c->kode_cabang, $dp_arr) ? 'selected' : '') : '') ?>><?= $c->cabang ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="checkbox" name="kode_cabang_all" id="kode_cabang_all" class="form-control" title="Semua Cabang">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jkel">Gender <sup class="text-danger">**</sup></label>
                                <select name="jkel" id="jkel" class="form-control select2_global" data-placeholder="~ Pilih Gender">
                                    <option value="">~ Pilih Gender</option>
                                    <option value="P" <?= ($jkel == 'P') ? 'selected' : '' ?>>Pria</option>
                                    <option value="W" <?= ($jkel == 'W') ? 'selected' : '' ?>>Wanita</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="password">Sandi <sup class="text-danger">**</sup></label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Sandi" id="password" name="password" value="<?= $password ?>">
                                    <div class="input-group-append" onclick="pass()">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-fw fa-lock text-success" id="lock_pass"></i>
                                            <i class="fa-solid fa-lock-open text-danger" id="open_pass"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger" onclick="getUrl('Master/perawat')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
                            <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
                            <?php if (!empty($data_perawat)) : ?>
                                <button type="button" class="btn btn-info float-right" onclick="getUrl('Master/form_perawat/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                            <?php else : ?>
                                <button type="button" class="btn btn-info float-right" onclick="reseting()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var table;
    const form = $('#form_perawat');
    const btnSimpan = $('#btnSimpan');
    var kodePerawat = $('#kodePerawat');
    var nik = $('#nik');
    var nama = $('#nama');
    var email = $('#email');
    var nohp = $('#nohp');
    var npwp = $('#npwp');
    var sip = $('#sip');
    var tgl_mulai = $('#tgl_mulai');
    var tgl_berhenti = $('#tgl_berhenti');
    var statusPerawat = $('#statusPerawat');
    var provinsi = $('#provinsi');
    var kabupaten = $('#kabupaten');
    var kecamatan = $('#kecamatan');
    var desa = $('#desa');
    var kodepos = $('#kodepos');
    var kode_poli = $('#kode_poli');
    var bodyPoli = $('#bodyPoli');
    var jumlahBarisPoli = $('#jumlahBarisPoli');

    btnSimpan.attr('disabled', false);

    // fungsi get kabupaten berdasarkan kode provinsi
    function getKabupaten(kode_provinsi) {
        if (kode_provinsi == '' || kode_provinsi == null) { // jika kode provinsi kosong/ null
            // tampilkan notif
            Swal.fire("Provinsi", "Sudah dipilih?", "question");
            // set param jadi kosong
            var param = '';
        } else {
            // set param menjadi kode provinsi
            var param = kode_provinsi;
        }

        // jalankan select2 berdasarkan param
        initailizeSelect2_kabupaten(param);
    }

    // fungsi get kecamatan berdasarkan kode kabupaten
    function getKecamatan(kode_kabupaten) {
        if (kode_kabupaten == '' || kode_kabupaten == null) { // jika kode provinsi kosong/ null
            // tampilkan notif
            Swal.fire("Kabupaten", "Sudah dipilih?", "question");
            // set param jadi kosong
            var param = '';
        } else {
            // set param menjadi kode kabupaten
            var param = kode_kabupaten;
        }
        initailizeSelect2_kecamatan(param);
    }

    // fungsi daftarkan akun
    function save() {
        btnSimpan.attr('disabled', true);

        if (nik.val() == '' || nik.val() == null) { // jika nik kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("NIK", "Form sudah diisi?", "question");
        }

        if (nama.val() == '' || nama.val() == null) { // jika nama kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Nama", "Form sudah diisi?", "question");
        }

        if (email.val() == '' || email.val() == null) { // jika email kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Email", "Form sudah diisi?", "question");
        }

        if (nohp.val() == '' || nohp.val() == null) { // jika nohp kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("No. Hp", "Form sudah diisi?", "question");
        }

        if (npwp.val() == '' || npwp.val() == null) { // jika npwp kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("NPWP", "Form sudah diisi?", "question");
        }

        if (sip.val() == '' || sip.val() == null) { // jika sip kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("No. SIP", "Form sudah diisi?", "question");
        }

        if (tgl_mulai.val() == '' || tgl_mulai.val() == null) { // jika tgl_mulai kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Tgl Mulai", "Form sudah diisi?", "question");
        }

        if (tgl_berhenti.val() == '' || tgl_berhenti.val() == null) { // jika tgl_berhenti kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Tgl Berhenti", "Form sudah diisi?", "question");
        }

        if (statusPerawat.val() == '' || statusPerawat.val() == null) { // jika statusPerawat kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Status", "Form sudah diisi?", "question");
        }

        if (provinsi.val() == '' || provinsi.val() == null) { // jika provinsi kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Provinsi", "Form sudah diisi?", "question");
        }

        if (kabupaten.val() == '' || kabupaten.val() == null) { // jika kabupaten kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Kabupaten", "Form sudah diisi?", "question");
        }

        if (kecamatan.val() == '' || kecamatan.val() == null) { // jika kecamatan kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Kecamatan", "Form sudah diisi?", "question");
        }

        if (desa.val() == '' || desa.val() == null) { // jika desa kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Desa", "Form sudah diisi?", "question");
        }

        if (kodepos.val() == '' || kodepos.val() == null) { // jika kodepos kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Kode Pos", "Form sudah diisi?", "question");
        }

        if (kodePerawat.val() == '' || kodePerawat.val() == null) { // jika kode perawat kosong/ null
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek logistik
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Master/cekPerawat',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(result) { // jika fungsi berjalan dengan baik
                    if (result.status == 1) { // jika mendapatkan respon 1
                        // jalankan fungsi proses berdasarkan param
                        proses(param);
                    } else { // selain itu
                        btnSimpan.attr('disabled', false);

                        Swal.fire("NIK", "Sudah digunakan!, silahkan gunakan nik lain ", "info");
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
            url: siteUrl + 'Master/perawat_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Perawat", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/perawat');
                    });
                } else { // selain itu

                    Swal.fire("Perawat", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    // fungsi reset form
    function reseting() {
        if (kodePerawat.val() == '' || kodePerawat.val() == null) { // jika kode_perawatnya tidak ada isi/ null
            // kosongkan
            kodePerawat.val('');
        }

        nik.val('');
        nama.val('');
        email.val('');
        nohp.val('');
        npwp.val('');
        sip.val('');
        statusPerawat.val('').change();
        provinsi.val('').change();
        kabupaten.val('').change();
        kecamatan.val('').change();
        desa.val('').change();
        kodepos.val('').change();
        kode_poli.val('').change();
    }

    function showGuide() {
        // clean text
        $('#modal_mgLabel').text(``);
        $('#modal-isi').text(``);

        $('#modal_mg').modal('show'); // show modal

        // isi text
        $('#modal_mgLabel').append(`Manual Guide Master Perawat`);
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