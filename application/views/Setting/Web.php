<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_web">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><ion-icon name="bookmark-outline" style="color: red;"></ion-icon> Formulir</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id_web" id="id_web" class="form-control" value="<?= $web->id ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="email_web" class="control-label">Email <span class="text-danger">**</span></label>
                        </div>
                        <div class="col-md-6">
                            <label for="nohp_web" class="control-label">Nomor Hp <span class="text-danger">**</span></label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" name="email_web" id="email_web" class="form-control" value="<?= $web->email ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="nohp_web" id="nohp_web" class="form-control" value="<?= $web->nohp ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="nama_web" class="control-label">Nama <span class="text-danger">**</span></label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <input type="text" name="nama_web" id="nama_web" class="form-control" value="<?= $web->nama ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="alamat_web" class="control-label">Alamat <span class="text-danger">**</span></label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <textarea name="alamat_web" id="alamat_web" class="form-control"><?= $web->alamat ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="logo_web" class="control-label">Logo</label>
                                </div>
                            </div>
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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="wallpaper_web" class="control-label">Latar Belakang</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <img id="preview_theme" class="rounded mx-auto d-block" style="border: 2px solid grey; width: 100%; height: 300px; background-position: center; background-size: cover;" src="<?= base_url('assets/img/web/') . $web->bg_theme; ?>" alt="User profile picture">
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="bg_theme" aria-describedby="inputGroupFileAddon01" name="bg_theme" onchange="readURLTheme(this)">
                                            <label class="custom-file-label" id="label-gambar" for="inputGroupFile01">Cari Gambar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-success" id="btnSimpan" onclick="simpan()" <?= (($created > 0) ? '' : 'disabled') ?>><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var nama_web = $('#nama_web');
    var nohp_web = $('#nohp_web');
    var email_web = $('#email_web');
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
</script>