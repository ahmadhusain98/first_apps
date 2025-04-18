<form method="post" id="form_user">
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
                                <input type="number" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="NIK" class="form-control" placeholder="NIK" id="nik" name="nik" value="<?= ((!empty($data_member)) ? $data_member->nik : '') ?>" onchange="getAddress(this.value, 'nik')" <?= (!empty($data_member) ? 'readonly' : '') ?>>
                            </div>
                            <div class="col-md-6">
                                <label for="nama">Nama <sup class="text-danger">**</sup></label>
                                <input type="hidden" class="form-control" id="kodeMember" name="kodeMember" value="<?= ((!empty($data_member)) ? $data_member->kode_member : '') ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="kode_prefix" id="kode_prefix" class="form-control select2-prefix" data-placeholder="~ Pilih Prefix">
                                            <?php if (!empty($data_member)) : ?>
                                                <?php $prefix = $this->M_global->getData('m_prefix', ['kode_prefix' => $data_member->kode_prefix]) ?>
                                                <option value="<?= $data_member->kode_prefix ?>"><?= $prefix->nama ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Nama Lengkap" class="form-control" placeholder="Nama Lengkap" id="nama" name="nama" value="<?= ((!empty($data_member)) ? $data_member->nama : '') ?>" onkeyup="ubah_nama(this.value, 'nama')">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email">Email <sup class="text-danger">**</sup></label>
                                <input type="email" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Email" class="form-control" placeholder="Email" id="email" name="email" onchange="cekEmail('email')" value="<?= ((!empty($data_member)) ? $data_member->email : '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="nohp">No. Hp <sup class="text-danger">**</sup></label>
                                <input type="number" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="No. HP" class="form-control" placeholder="No. Hp" id="nohp" name="nohp" value="<?= ((!empty($data_member)) ? $data_member->nohp : '') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tmp_lahir">Tempat Lahir <sup class="text-danger">**</sup></label>
                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Tempat Lahir" class="form-control" placeholder="Tempat Lahir" id="tmp_lahir" name="tmp_lahir" value="<?= ((!empty($data_member)) ? $data_member->tmp_lahir : '') ?>" onkeyup="ubah_nama(this.value, 'tmp_lahir')">
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_lahir">Tanggal Lahir <sup class="text-danger">**</sup></label>
                                <input type="date" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Tgl Lahir" class="form-control" placeholder="Tgl Lahir" id="tgl_lahir" name="tgl_lahir" value="<?= ((!empty($data_member)) ? (isset($data_member->tgl_lahir) ? date('Y-m-d', strtotime($data_member->tgl_lahir)) : date('Y-m-d')) : date('Y-m-d')) ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password">Sandi <sup class="text-danger">**</sup></label>
                                <input type="password" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Sandi" class="form-control" placeholder="Sandi" id="password" name="password" value="<?= ((!empty($data_member)) ? $data_member->secondpass : '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="pendidikan">Pendidikan <sup class="text-danger">**</sup></label>
                                <select name="pendidikan" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Pendidikan" id="pendidikan" class="select2_pendidikan" data-placeholder="~ Pilih Pendidikan">
                                    <?php
                                    if (!empty($data_member)) {
                                        $pend = $this->M_global->getData('m_pendidikan', ['kode_pendidikan' => $data_member->pendidikan]);
                                        echo "<option value='" . $pend->kode_pendidikan . "'>" . $pend->keterangan . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pekerjaan">Pekerjaan <sup class="text-danger">**</sup></label>
                                <select name="pekerjaan" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Pekerjaan" id="pekerjaan" class="select2_pekerjaan" data-placeholder="~ Pilih Pekerjaan">
                                    <?php
                                    if (!empty($data_member)) {
                                        $pek = $this->M_global->getData('m_pekerjaan', ['kode_pekerjaan' => $data_member->pekerjaan]);
                                        echo "<option value='" . $pek->kode_pekerjaan . "'>" . $pek->keterangan . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="agama">Agama <sup class="text-danger">**</sup></label>
                                <select name="agama" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Agama" id="agama" class="select2_agama" data-placeholder="~ Pilih Agama">
                                    <?php
                                    if (!empty($data_member)) {
                                        $agam = $this->M_global->getData('m_agama', ['kode_agama' => $data_member->agama]);
                                        echo "<option value='" . $agam->kode_agama . "'>" . $agam->keterangan . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jkel">Jenis Kelamin <sup class="text-danger">**</sup></label>
                                <select name="jkel" id="jkel" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Gender" class="form-control select2_global" data-placeholder="~ Pilih Gender">
                                    <option value="">~ Pilih Gender</option>
                                    <option value="P" <?= (!empty($data_member) ? (($data_member->jkel == 'P') ? 'selected' : '') : '') ?>>Laki-laki</option>
                                    <option value="W" <?= (!empty($data_member) ? (($data_member->jkel == 'W') ? 'selected' : '') : '') ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="provinsi">Provinsi <sup class="text-danger">**</sup></label>
                                <select name="provinsi" id="provinsi" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Provinsi" class="form-control select2_provinsi" data-placeholder="~ Pilih Provinsi" onchange="getKabupaten(this.value)">
                                    <?php
                                    if (!empty($data_member)) {
                                        $prov = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $data_member->provinsi]);
                                        echo "<option value='" . $prov->kode_provinsi . "'>" . $prov->provinsi . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kabupaten">Kabupaten <sup class="text-danger">**</sup></label>
                                <select name="kabupaten" id="kabupaten" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Kabupaten" class="form-control select2_kabupaten" data-placeholder="~ Pilih Kabupaten" onchange="getKecamatan(this.value)">
                                    <?php
                                    if (!empty($data_member)) {
                                        $prov = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $data_member->kabupaten]);
                                        echo "<option value='" . $prov->kode_kabupaten . "'>" . $prov->kabupaten . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kecamatan">Kecamatan <sup class="text-danger">**</sup></label>
                                <select name="kecamatan" id="kecamatan" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Kecamatan" class="form-control select2_kecamatan" data-placeholder="~ Pilih Kecamatan">
                                    <?php
                                    if (!empty($data_member)) {
                                        $prov = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $data_member->kecamatan]);
                                        echo "<option value='" . $prov->kode_kecamatan . "'>" . $prov->kecamatan . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="desa">Desa <sup class="text-danger">**</sup></label>
                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Desa" class="form-control" placeholder="Desa" id="desa" name="desa" value="<?= ((!empty($data_member)) ? $data_member->desa : '') ?>" onkeyup="ubah_nama(this.value, 'desa')">
                            </div>
                            <div class="col-md-6">
                                <label for="kodepos">Kode POS <sup class="text-danger">**</sup></label>
                                <input type="number" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Kode Pos" class="form-control" placeholder="Kode Pos" id="kodepos" name="kodepos" value="<?= ((!empty($data_member)) ? $data_member->kodepos : '') ?>" onkeyup="cekLength(this.value, 'kodepos')">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rt">RT <sup class="text-danger">**</sup></label>
                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="RT" class="form-control" placeholder="RT" id="rt" name="rt" value="<?= ((!empty($data_member)) ? $data_member->rt : '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="rw">RW <sup class="text-danger">**</sup></label>
                                <input type="number" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="RW" class="form-control" placeholder="RW" id="rw" name="rw" value="<?= ((!empty($data_member)) ? $data_member->rw : '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger" onclick="getUrl('Health/daftar')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
                            <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
                            <?php if (!empty($data_member)) : ?>
                                <button type="button" class="btn btn-info float-right" onclick="getUrl('Health/form_daftar/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
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
    const form = $('#form_user');
    const btnSimpan = $('#btnSimpan');
    var kodeMember = $('#kodeMember');
    var nik = $('#nik');
    var nama = $('#nama');
    var email = $('#email');
    var password = $('#password');
    var nohp = $('#nohp');
    var tmp_lahir = $('#tmp_lahir');
    var tgl_lahir = $('#tgl_lahir');
    var pekerjaan = $('#pekerjaan');
    var agama = $('#agama');
    var pendidikan = $('#pendidikan');
    var jkel = $('#jkel');
    var provinsi = $('#provinsi');
    var kabupaten = $('#kabupaten');
    var kecamatan = $('#kecamatan');
    var desa = $('#desa');
    var kodepos = $('#kodepos');
    var rt = $('#rt');
    var rw = $('#rw');

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

    // fungsi save/update
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

        if (password.val() == '' || password.val() == null) { // jika password kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Sandi", "Form sudah diisi?", "question");
        }

        if (nohp.val() == '' || nohp.val() == null) { // jika nohp kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("No. Hp", "Form sudah diisi?", "question");
        }

        if (tmp_lahir.val() == '' || tmp_lahir.val() == null) { // jika tmp_lahir kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Tempat Lahir", "Form sudah diisi?", "question");
        }

        if (tgl_lahir.val() == '' || tgl_lahir.val() == null) { // jika tgl_lahir kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Tgl Lahir", "Form sudah diisi?", "question");
        }

        if (pekerjaan.val() == '' || pekerjaan.val() == null) { // jika pekerjaan kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Pekerjaan", "Form sudah diisi?", "question");
        }

        if (agama.val() == '' || agama.val() == null) { // jika agama kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Agama", "Form sudah diisi?", "question");
        }

        if (pendidikan.val() == '' || pendidikan.val() == null) { // jika pendidikan kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Pendidikan", "Form sudah diisi?", "question");
        }

        if (jkel.val() == '' || jkel.val() == null) { // jika jkel kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("Gender", "Form sudah diisi?", "question");
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

        if (rt.val() == '' || rt.val() == null) { // jika rt kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("RT", "Form sudah diisi?", "question");
        }

        if (rw.val() == '' || rw.val() == null) { // jika rw kosong/ null
            btnSimpan.attr('disabled', false);
            return Swal.fire("RW", "Form sudah diisi?", "question");
        }

        if (kodeMember.val() == '' || kodeMember.val() == null) { // jika kode member kosong/ null
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek logistik
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Health/cekNik',
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

    // fungsi proses
    function proses(param) {
        if (param == 1) { // jika param 1 berarti insert/tambah
            var message = 'dibuat!';
        } else { // selain itu berarti update/ubah
            var message = 'diperbarui!';
        }

        // jalankan proses dengan param insert/update
        $.ajax({
            url: siteUrl + 'Health/member_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Member", "Berhasil " + message, "success").then(() => {
                        getUrl('Health/daftar');
                    });
                } else { // selain itu

                    Swal.fire("Member", "Gagal " + message + ", silahkan dicoba kembali", "info");
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
        if (kodeMember.val() == '' || kodeMember.val() == null) {
            kodeMember.val('');
        }

        nik.val('');
        nama.val('');
        email.val('');
        password.val('');
        nohp.val('');
        tmp_lahir.val('');
        jkel.val('').change();
        provinsi.val('').change();
        kabupaten.val('').change();
        kecamatan.val('').change();
        pendidikan.val('').change();
        pekerjaan.val('').change();
        agama.val('').change();
        desa.val('');
        kodepos.val('');
        rt.val('');
        rw.val('');
    }

    function showGuide() {
        // clean text
        $('#modal_mgLabel').text(``);
        $('#modal-isi').text(``);

        $('#modal_mg').modal('show'); // show modal

        // isi text
        $('#modal_mgLabel').append(`Manual Guide Pendaftaran Member`);
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