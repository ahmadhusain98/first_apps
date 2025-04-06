<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_web">
    <div class="row" data-aos="zoom-in"
        data-aos-offset="200"
        data-aos-delay="50"
        data-aos-duration="1500"
        data-aos-easing="ease-in-out"
        data-aos-mirror="true"
        data-aos-once="false"
        data-aos-anchor-placement="top-center">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-6 col-12">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="email_web" class="control-label">Email <span class="text-danger">**</span></label>
                                        <input type="hidden" name="id_web" id="id_web" class="form-control" value="<?= $web->id ?>">
                                        <input type="text" name="email_web" id="email_web" class="form-control" value="<?= $web->email ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="kode_email" class="control-label">Kode Email Apps <span class="text-danger">**</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" placeholder="Sandi" id="kode_email" name="kode_email" value="<?= $web->kode_email ?>">
                                            <div class="input-group-append" onclick="pass_mail()">
                                                <div class="input-group-text">
                                                    <i class="fa-solid fa-fw fa-lock text-success" id="lock_pass"></i>
                                                    <i class="fa-solid fa-lock-open text-danger" id="open_pass"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="nama_web" class="control-label">Nama <span class="text-danger">**</span></label>
                                        <input type="text" name="nama_web" id="nama_web" class="form-control" value="<?= $web->nama ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="nohp_web" class="control-label">Nomor Hp <span class="text-danger">**</span></label>
                                        <input type="text" name="nohp_web" id="nohp_web" class="form-control" value="<?= $web->nohp ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="alamat_web" class="control-label">Alamat <span class="text-danger">**</span></label>
                                        <textarea name="alamat_web" id="alamat_web" class="form-control"><?= $web->alamat ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="limit_trash_web" class="control-label">Limit Sampah (Hari) <span class="text-danger">**</span></label>
                                        <input type="number" name="limit_trash_web" id="limit_trash_web" class="form-control" value="<?= $web->limit_trash_web ?>" placeholder="x Hari">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="logo_web" class="control-label">Logo</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img id="preview_img" class="rounded mx-auto d-block" style="border: 2px solid grey; width: 100%;" src="<?= base_url('assets/img/web/') . $web->logo; ?>" alt="User profile picture">
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
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="wallpaper_web" class="control-label">Watermark</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img id="preview_theme" class="rounded mx-auto d-block" style="border: 2px solid grey; width: 100%; height: 100px; background-position: center; background-size: cover;" src="<?= base_url('assets/img/web/') . $web->watermark; ?>" alt="User profile picture">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="watermark" aria-describedby="inputGroupFileAddon01" name="watermark" onchange="readURLTheme(this)">
                                                        <label class="custom-file-label" id="label-gambar" for="inputGroupFile01">Cari Gambar</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="bg_theme" class="control-label">Tema Sidebar</label>
                                        <input type="color" name="bg_theme" id="bg_theme" class="form-control" value="<?= $web->bg_theme ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <?php if ($created == 1) : ?>
                                <button type="button" class="btn btn-success" id="btnSimpan" onclick="simpan()"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var nama_web = $('#nama_web');
    var nohp_web = $('#nohp_web');
    var email_web = $('#email_web');
    var kode_email = $('#kode_email');
    var alamat_web = $('#alamat_web');
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

    // preview image
    function readURLTheme(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#div_preview_foto2').css("display", "block");
                $('#preview_theme').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#div_preview_foto2').css("display", "none");
            $('#preview_theme').attr('src', '');
        }
    }

    // fungsi simpan
    function simpan() {
        btnSimpan.attr('disabled', true);

        if (email_web.val() == '' || email_web.val() == null) {
            btnSimpan.attr('disabled', false);

            return Swal.fire("Email Website", "Form sudah diisi?", "question");
        }

        if (kode_email.val() == '' || kode_email.val() == null) {
            btnSimpan.attr('disabled', false);

            return Swal.fire("Kode Email Apps", "Form sudah diisi?", "question");
        }

        if (nohp_web.val() == '' || nohp_web.val() == null) {
            btnSimpan.attr('disabled', false);

            return Swal.fire("Nomor Hp Website", "Form sudah diisi?", "question");
        }

        if (nama_web.val() == '' || nama_web.val() == null) {
            btnSimpan.attr('disabled', false);

            return Swal.fire("Nama Website", "Form sudah diisi?", "question");
        }

        if (alamat_web.val() == '' || alamat_web.val() == null) {
            btnSimpan.attr('disabled', false);

            return Swal.fire("Alamat Website", "Form sudah diisi?", "question");
        }

        proses();
    }

    function proses() {
        // jalankan proses
        var form = $('#form_web')[0];
        var data = new FormData(form);

        $.ajax({
            url: siteUrl + 'Setting_apps/proses',
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

                    Swal.fire("Profile Website", "Berhasil di perbarui!", "success").then(() => {
                        getUrl('Setting_apps');
                    });
                } else {

                    Swal.fire("Profile Website", "Gagal di perbarui!, silahkan dicoba kembali", "info");
                }
            },
            error: function(result) {
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    $("#open_pass").hide();

    // fungsi tampil/sembunyi password
    function pass_mail() {
        if (document.getElementById("kode_email").type == "password") { // jika icon password gembok di klik
            // ubah tipe password menjadi text
            document.getElementById("kode_email").type = "text";

            // tampilkan icon buka
            $("#open_pass").show();

            // sembunyikan icon gembok
            $("#lock_pass").hide();
        } else { // selain itu
            // ubah tipe password menjadi passwword
            document.getElementById("kode_email").type = "password";
            // sembunyikan icon buka
            $("#open_pass").hide();

            // tampilkan icon gembok
            $("#lock_pass").show();
        }
    }

    function showGuide() {
        // clean text
        $('#modal_mgLabel').text(``);
        $('#modal-isi').text(``);

        $('#modal_mg').modal('show'); // show modal

        // isi text
        $('#modal_mgLabel').append(`Manual Guide Setting Apps`);
        $('#modal-isi').append(`
            <ol>
                <li style="font-weight: bold;">Update Setting</li>
                <p>
                    <ul>
                        <li>Ubah isi Form yang akan di ubah<br>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
            </ol>
        `);
    }
</script>