<form method="post" id="form_user">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="input-group mb-3">
                    <input type="hidden" class="form-control" placeholder="Nama Lengkap" id="kodeUser" name="kodeUser" value="<?= ((!empty($data_user)) ? $data_user->kode_user : '') ?>">
                    <input type="text" class="form-control" placeholder="Nama Lengkap" id="nama" name="nama" value="<?= ((!empty($data_user)) ? $data_user->nama : '') ?>" onkeyup="ubah_nama(this.value, 'nama')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <ion-icon name="person-outline"></ion-icon>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email" onchange="cekEmail('email')" value="<?= ((!empty($data_user)) ? $data_user->email : '') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <ion-icon name="mail-outline"></ion-icon>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Sandi" id="password" name="password" value="<?= ((!empty($data_user)) ? $data_user->secondpass : '') ?>">
                    <div class="input-group-append" onclick="pass()">
                        <div class="input-group-text">
                            <ion-icon name="lock-closed-outline" id="lock_pass"></ion-icon>
                            <ion-icon name="lock-open-outline" id="open_pass"></ion-icon>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <select name="jkel" id="jkel" class="form-control select2_global" data-placeholder="~ Pilih Gender">
                        <option value="">~ Pilih Gender</option>
                        <option value="P" <?= (!empty($data_user) ? (($data_user->jkel == 'P') ? 'selected' : '') : '') ?>>Laki-laki</option>
                        <option value="W" <?= (!empty($data_user) ? (($data_user->jkel == 'W') ? 'selected' : '') : '') ?>>Perempuan</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <select name="kode_role" id="kode_role" class="form-control select2_global" data-placeholder="~ Pilih Role">
                        <option value="">~ Pilih Role</option>
                        <?php foreach ($role as $r) : ?>
                            <option value="<?= $r->kode_role ?>" <?= (!empty($data_user) ? (($data_user->kode_role == $r->kode_role) ? 'selected' : '') : '') ?>><?= $r->keterangan ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger" onclick="getUrl('Master/user')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
            <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
            <?php if (!empty($data_user)) : ?>
                <button type="button" class="btn btn-info float-right" onclick="getUrl('Master/form_user/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            <?php else : ?>
                <button type="button" class="btn btn-info float-right" onclick="reset()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
            <?php endif ?>
        </div>
    </div>
</form>

<script>
    var table;
    const form = $('#form_user');
    const btnSimpan = $('#btnSimpan');
    var kodeUser = $('#kodeUser');
    var nama = $('#nama');
    var email = $('#email');
    var password = $('#password');
    var jkel = $('#jkel');
    var kode_role = $('#kode_role');

    btnSimpan.attr('disabled', false);

    // fungsi daftarkan akun
    function save() {

        if (nama.val() == "" || nama.val() == null) { // jika nama null/ kosong
            Swal.fire("Nama Lengkap", "Form sudah diisi?", "question");
            return;
        }

        if (email.val() == "" || email.val() == null) { // jika email null/ kosong
            Swal.fire("Email", "Form sudah diisi?", "question");
            return;
        }

        if (password.val() == "" || password.val() == null) { // jika password null/ kosong
            Swal.fire("Sandi", "Form sudah diisi?", "question");
            return;
        }

        if (jkel.val() == "" || jkel.val() == null) { // jika jkel null/ kosong
            Swal.fire("Gender", "Form sudah diisi?", "question");
            return;
        }

        if (kode_role.val() == "" || kode_role.val() == null) { // jika kode_role null/ kosong
            Swal.fire("Role", "Form sudah diisi?", "question");
            return;
        }

        if (kodeUser.val() == "" || kodeUser.val() == null) {
            var param = 1;
        } else {
            var param = 2;
        }

        // jalankan proses cek logistik
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Master/cekUser',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(result) { // jika fungsi berjalan dengan baik
                    if (result.status == 1) { // jika mendapatkan respon 1
                        // jalankan fungsi proses berdasarkan param
                        proses(param);
                    } else { // selain itu
                        btnSimpan.attr('disabled', false);

                        Swal.fire("Email", "Sudah digunakan!, silahkan gunakan email lain ", "info");
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
            url: siteUrl + 'Master/user_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Pengguna", "Berhasil " + message, "success").then(() => {
                        getUrl('Master/user');
                    });
                } else { // selain itu

                    Swal.fire("Pengguna", "Gagal " + message + ", silahkan dicoba kembali", "info");
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
        if (kodeUser.val() == '' || kodeUser.val() == null) { // jika kode_usernya tidak ada isi/ null
            // kosongkan
            kodeUser.val('');
        }

        nama.val('');
        email.val('');
        password.val('');
        jkel.val('').change();
        kode_role.val('').change();
    }
</script>