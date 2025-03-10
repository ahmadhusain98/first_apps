<!-- view atur ulang -->

<div class="register-box" data-aos="fade-down">
    <div class="card card-outline card-primary" card-primary" style="background-color: rgba(255, 255, 255, 0.8); -webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px);">
        <div class="card-header text-center">
            <a type="button" class="h1"><b><?= $nama_apps ?></b></a>
            <!-- <br>
            <div class="h5"><?= $web_version_all->nama ?></div> -->
        </div>
        <div class="card-body">
            <p class="login-box-msg">Atur Ulang Sandi</p>
            <form id="form_repass" method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email" onchange="cekEmail(this.value)">
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Sandi Baru" id="password" name="password">
                    <div class="input-group-append" onclick="pass()">
                        <div class="input-group-text">
                            <i class="fa-solid fa-fw fa-lock text-success" id="lock_pass"></i>
                            <i class="fa-solid fa-lock-open text-danger" id="open_pass"></i>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control" placeholder="Kode Verifikasi" id="kode" name="kode">
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-danger btn-block" onclick="cekCode(2)">Dapatkan Kode</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary btn-block" onclick="aturSandi()">Atur Ulang Sandi</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <a type="button" class="text-center" onclick="getUrl('Auth')">Form Masuk</a>
        </div>
    </div>
</div>

<script>
    // variable
    var email = $("#email");
    const form = $('#form_repass');

    // fungsi cekCode
    function cekCode(param) {
        // jalankan fungsi getCode
        getCode(param, email.val());
    }

    // fungsi atur ulang sandi
    function aturSandi() {
        // tampilkan loading

        // jalankan fungsi
        $.ajax({
            url: siteUrl + 'Auth/atur_sandi',
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan
                if (result.status == 1) { // jika mendapatkan hasil status 1
                    // sembunyikan loading

                    Swal.fire({
                        title: "Sandi",
                        text: "Berhasil diatur ulang!, silahkan masuk",
                        icon: "success"
                    }).then((value) => {
                        // ketika notifikasi di klik ok, maka arahkan ke Auth
                        getUrl('Auth');
                    });
                } else {
                    // sembunyikan loading

                    Swal.fire({
                        title: "Sandi",
                        text: "Gagal diatur ulang!, silahkan coba lagi",
                        icon: "info"
                    })
                    return;
                }
            },
            error: function(result) { // jika fungsi gagal berjalan
                // sembunyikan loading

                // tampilkan notifikasi error
                error_proccess()
            }
        });
    }
</script>