<form method="post" id="form_akun" style="margin-top: 100px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <span class="h3"># Kode Member: <?= $data_user->kode_member ?></span>
                    <button type="button" class="btn btn-danger btn-sm float-right" onclick="exit()"><ion-icon name="alert-circle-outline"></ion-icon> Keluar</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card shadow">
                                        <div class="card-body p-1">
                                            <img id="preview_img" class="rounded mx-auto d-block" style="border: 2px solid grey; width: 100%;" src="<?= base_url('assets/user/') . $data_user->foto; ?>" alt="User profile picture">
                                        </div>
                                        <div class="card-footer p-0">
                                            <button type="button" class="btn btn-primary" disabled style="width: 100%; border-radius: 0px;">Foto Profil</button>
                                        </div>
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
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Nama Lengkap" id="nama" name="nama" value="<?= $data_user->nama ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <ion-icon name="person-outline"></ion-icon>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" id="email" name="email" onchange="cekEmail(this.value)" value="<?= $data_user->email ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <ion-icon name="mail-outline"></ion-icon>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Sandi" id="secondpass" name="secondpass" value="<?= $data_user->secondpass ?>">
                                <div class="input-group-append" onclick="secondpassshow()">
                                    <div class="input-group-text">
                                        <ion-icon name="lock-closed-outline" id="lock_pass"></ion-icon>
                                        <ion-icon name="lock-open-outline" id="open_pass"></ion-icon>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <select name="jkel" id="jkel" class="form-control select2_global" data-placeholder="~ Pilih Gender">
                                    <option value="">~ Pilih Gender</option>
                                    <option value="P" <?= ($data_user->jkel == 'P') ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="W" <?= ($data_user->jkel == 'W') ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" id="btnSimpan" class="btn btn-success btn-sm float-right" onclick="simpan('<?= $data_user->kode_member ?>')"><ion-icon name="reload-outline"></ion-icon> Perbarui</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var nama = $("#nama");
    var email = $("#email");
    var secondpass = $("#secondpass");
    var jkel = $("#jkel");
    var btnSimpan = $('#btnSimpan');

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

    // fungsi keluar sistem
    function exit() {
        Swal.fire({
            title: "Kamu yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, keluar!"
        }).then((result) => {
            if (result.isConfirmed) { // jika di konfirmasi "Ya"
                // arahkan ke fungsi logout di controller Auth
                getUrl('Auth/logout')
            }
        });
    }

    // fungsi tampil/sembunyi secondpass
    function secondpassshow() {
        if (document.getElementById("secondpass").type == "password") { // jika icon secondpass gembok di klik
            // ubah tipe secondpass menjadi text
            document.getElementById("secondpass").type = "text";

            // tampilkan icon buka
            $("#open_pass").show();

            // sembunyikan icon gembok
            $("#lock_pass").hide();
        } else { // selain itu
            // ubah tipe secondpass menjadi passwword
            document.getElementById("secondpass").type = "password";
            // sembunyikan icon buka
            $("#open_pass").hide();

            // tampilkan icon gembok
            $("#lock_pass").show();
        }
    }

    // fungsi simpan
    function simpan(kode_member) {
        btnSimpan.attr('disabled', true);

        if (nama.val() == '' || nama.val() == null) {
            btnSimpan.attr('disabled', false);

            Swal.fire("Nama", "Form sudah diisi?", "question");
            return;
        }

        if (email.val() == '' || email.val() == null) {
            btnSimpan.attr('disabled', false);

            Swal.fire("Email Website", "Form sudah diisi?", "question");
            return;
        }

        if (secondpass.val() == '' || secondpass.val() == null) {
            btnSimpan.attr('disabled', false);

            Swal.fire("Sandi", "Form sudah diisi?", "question");
            return;
        }

        if (jkel.val() == '' || jkel.val() == null) {
            btnSimpan.attr('disabled', false);

            Swal.fire("Gender", "Form sudah diisi?", "question");
            return;
        }

        proses(kode_member);
    }

    function proses(kode_member) {
        // jalankan proses
        var form = $('#form_akun')[0];
        var data = new FormData(form);

        $.ajax({
            url: siteUrl + 'Profile/updateAkunMember/' + kode_member,
            type: "POST",
            enctype: 'multipart/form-data',
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(result) {
                btnSimpan.attr('disabled', false);

                if (result.status == 1) {

                    Swal.fire("Profile Akun", "Berhasil di perbarui!", "success").then(() => {
                        getUrl('Profile/profile_member');
                    });
                } else {

                    Swal.fire("Profile Akun", "Gagal di perbarui!, silahkan dicoba kembali", "info");
                }
            },
            error: function(result) {
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }
</script>