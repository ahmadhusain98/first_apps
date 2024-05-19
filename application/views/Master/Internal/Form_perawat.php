<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_perawat">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b># Form Perawat</b></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="card shadow">
                                    <div class="card-header"># Data Perawat</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">NIK <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" placeholder="NIK" id="nik" name="nik" value="<?= ((!empty($data_perawat)) ? $data_perawat->nik : '') ?>" onchange="getAddress(this.value, 'nik'); cekLength(this.value, 'nik')" <?= (!empty($data_perawat) ? 'readonly' : '') ?> maxlength="16">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="id-card-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Nama <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="hidden" class="form-control" id="kodePerawat" name="kodePerawat" value="<?= ((!empty($data_perawat)) ? $data_perawat->kode_perawat : '') ?>">
                                                    <input type="text" class="form-control" placeholder="Nama Lengkap" id="nama" name="nama" value="<?= ((!empty($data_perawat)) ? $data_perawat->nama : '') ?>" onkeyup="ubah_nama(this.value, 'nama')">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="person-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Email <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="email" class="form-control" placeholder="Email" id="email" name="email" onchange="cekEmail('email')" value="<?= ((!empty($data_perawat)) ? $data_perawat->email : '') ?>">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="mail-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">No. Hp <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" placeholder="No. Hp" id="nohp" name="nohp" value="<?= ((!empty($data_perawat)) ? $data_perawat->nohp : '') ?>">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="call-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">NPWP (16 Digit) <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="NPWP" id="npwp" name="npwp" value="<?= ((!empty($data_perawat)) ? $data_perawat->npwp : '') ?>" onchange="cekLength(this.value, 'npwp')" maxlength="16">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="card-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">SIP (15 Digit) <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="No. SIP" id="sip" name="sip" value="<?= ((!empty($data_perawat)) ? $data_perawat->sip : '') ?>" onchange="cekLength(this.value, 'sip')" maxlength="15">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="card-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Tgl Mulai <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="date" class="form-control" title="Tgl Mulai" id="tgl_mulai" name="tgl_mulai" value="<?= ((!empty($data_perawat)) ? date('Y-m-d', strtotime($data_perawat->tgl_mulai)) : date('Y-m-d')) ?>">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="today-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Tgl Berhenti <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="date" class="form-control" title="Tgl Berhenti" id="tgl_berhenti" name="tgl_berhenti" value="<?= ((!empty($data_perawat)) ? date('Y-m-d', strtotime($data_perawat->tgl_berhenti)) : date('Y-m-d')) ?>">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="today-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Status <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <select name="statusPerawat" id="statusPerawat" class="form-control select2_global" data-placeholder="~ Pilih Status">
                                                        <option value="">~ Pilih Status</option>
                                                        <option value="1" <?= (!empty($data_perawat) ? (($data_perawat->status == 1) ? 'selected' : '') : '') ?>>Aktif</option>
                                                        <option value="0" <?= (!empty($data_perawat) ? (($data_perawat->status == 0) ? 'selected' : '') : '') ?>>Non-aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Provinsi <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Kabupaten <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <select name="kabupaten" id="kabupaten" class="form-control select2_kabupaten" data-placeholder="~ Pilih Kabupaten" onchange="getKecamatan(this.value)">
                                                        <?php
                                                        if (!empty($data_perawat)) {
                                                            $prov = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $data_perawat->kabupaten]);
                                                            echo "<option value='" . $prov->kode_kabupaten . "'>" . $prov->kabupaten . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Kecamatan <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Desa <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Desa" id="desa" name="desa" value="<?= ((!empty($data_perawat)) ? $data_perawat->desa : '') ?>" onkeyup="ubah_nama(this.value, 'desa')">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="home-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Kodepos <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" placeholder="Kode Pos" id="kodepos" name="kodepos" value="<?= ((!empty($data_perawat)) ? $data_perawat->kodepos : '') ?>" onkeyup="cekLength(this.value, 'kodepos')">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <ion-icon name="locate-outline"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Poli <sup class="text-danger">**</sup></label>
                                                <div class="input-group mb-3">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm" onclick="getUrl('Master/perawat')" id="btnKembali"><ion-icon name="play-back-outline"></ion-icon> Kembali</button>
                            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan"><ion-icon name="save-outline"></ion-icon> <?= (!empty($data_perawat) ? 'Perbarui' : 'Simpan') ?></button>
                            <?php if (!empty($data_perawat)) : ?>
                                <button type="button" class="btn btn-success float-right btn-sm" onclick="getUrl('Master/form_perawat/0')" id="btnBaru"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
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
        $("#loading").modal("show");

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
                    $("#loading").modal("hide");

                    Swal.fire("Perawat", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/perawat');
                    });
                } else { // selain itu
                    $("#loading").modal("hide");

                    Swal.fire("Perawat", "Gagal " + message + ", silahkan dicoba kembali", "info");
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
        if (kodePerawat.val() == '' || kodePerawat.val() == null) { // jika kode_perawatnya tidak ada isi/ null
            // kosongkan
            kodePerawat.val('');
        }

        nama.val('');
        email.val('');
        password.val('');
        jkel.val('').change();
        kode_role.val('').change();
    }
</script>