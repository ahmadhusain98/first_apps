<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $judul ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/fontawesome/css/all.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Scripts -->
    <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/adminlte.min.css">

    <!-- sweetalert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- animate -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Select2 js -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/jszip/jszip.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<!-- responsive -->
<style>
    /* select2 */
    .select2-selection__rendered {
        line-height: 32px !important;
    }

    .select2-container .select2-selection--single {
        height: 42px !important;
    }

    .select2-selection__arrow {
        height: 30px !important;
    }

    .border-primary {
        border: 1px solid #007bff;
    }

    .border-danger {
        border: 1px solid #c82333;
    }

    /* For mobile phones: */
    [class*="col-"] {
        width: 100%;
    }

    @media only screen and (min-width: 768px) {

        /* For desktop: */
        .col-1 {
            width: 8.33%;
        }

        .col-2 {
            width: 16.66%;
        }

        .col-3 {
            width: 25%;
        }

        .col-4 {
            width: 33.33%;
        }

        .col-5 {
            width: 41.66%;
        }

        .col-6 {
            width: 50%;
        }

        .col-7 {
            width: 58.33%;
        }

        .col-8 {
            width: 66.66%;
        }

        .col-9 {
            width: 75%;
        }

        .col-10 {
            width: 83.33%;
        }

        .col-11 {
            width: 91.66%;
        }

        .col-12 {
            width: 100%;
        }
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }
</style>

<body>

    <!-- modal loading proses -->
    <div class="modal fade" id="loading">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="<?= base_url() ?>assets/img/loading_2.gif" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand h3 font-weight-bold m-3" type="button" onclick="getUrl('App')"><?= strtoupper($nama_apps) ?></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php
        $sess_url1 = $this->uri->segment(1);
        $sess_url2 = $this->uri->segment(2);
        ?>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ((($sess_url1 == 'App' || $sess_url1 == '') && $sess_url2 == '') ? 'active' : '') ?>" type="button" onclick="getUrl('App')">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (($sess_url1 == 'App' && $sess_url2 == 'about') ? 'active' : '') ?>" type="button" onclick="getUrl('App/about')">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (($sess_url1 == 'App' && $sess_url2 == 'info') ? 'active' : '') ?>" type="button" onclick="getUrl('App/info')">Informasi</a>
                </li>
                <li class="nav-item">
                    <?php
                    if (!empty($this->data['email'])) :
                    ?>
                        <a class="nav-link <?= (($sess_url1 == 'Profile' && $sess_url2 == 'profile_member') ? 'active' : '') ?>" type="button" onclick="getUrl('Profile/profile_member')">Profile</a>
                    <?php else : ?>
                        <a class="nav-link <?= (($sess_url1 == 'Auth' && $sess_url2 == '') ? 'active' : '') ?>" type="button" onclick="getUrl('Auth')">Akun</a>
                    <?php endif ?>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-10 offset-1">
                <div class="container-fluid">
                    <div class="row mt-3">
                        <div class="col-12">
                            <?= $content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ionicon -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- myscript -->

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();

        // variable
        const siteUrl = '<?= site_url() ?>';

        // load pertama kali saat sistem berjalan
        $("#open_pass").hide();

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        $(".select2_global").select2({
            placeholder: $(this).data('placeholder'),
        });

        // fungsi format Rupiah NoId
        function formatRpNoId(num) {
            num = num.toString().replace(/\$|\,/g, '');

            if (isNaN(num)) num = "0";

            sign = (num == (num = Math.abs(num)));
            num = Math.floor(num * 100 + 0.50000000001);
            cents = num % 100;
            num = Math.floor(num / 100).toString();

            if (cents < 10) cents = "0" + cents;

            for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) {
                num = num.substring(0, num.length - (4 * i + 3)) + ',' +
                    num.substring(num.length - (4 * i + 3));
            }

            return (((sign) ? '' : '-') + '' + num + '.' + cents);
        }

        // cek email berdsasarkan email
        function cekEmailLog(mail) {
            if (mail == null || mail == "") { // jika email null/ tidak ada
                Swal.fire({
                    title: "Email",
                    text: "Form sudah diisi?",
                    icon: "question"
                });
                return;
            } else { // selain itu
                $.ajax({
                    url: siteUrl + 'Auth/cek_email?email=' + mail,
                    type: "POST",
                    dataType: "JSON",
                    success: function(result) { // jika fungsi berjalan
                        if (result.status == 1) { // jika mendapatkan hasil status 1
                            $('#email').val('');
                            Swal.fire({
                                title: "Email",
                                text: "Tidak ditemukan!, silahkan masuk daftarkan email",
                                icon: "error"
                            });
                            return;
                        }
                    },
                    error: function(result) { // jika fungsi gagal berjalan
                        // tampilkan notifikasi error
                        error_proccess()
                    }
                });
            }
        }

        // fungsi kirimkan kode
        function getCode(param, email) {
            // tampilkan loading

            // jalankan fungsi
            $.ajax({
                url: siteUrl + 'Auth/sendCode/' + param + '/?email=' + email,
                type: "POST",
                dataType: "JSON",
                success: function(result) { // jika fungsi berjalan
                    // sembunyikan loading

                    if (result.status == 1) { // jika mendapatkan hasil status 1
                        Swal.fire({
                            title: "Kode Validasi",
                            text: "Berhasil dikirim!, silahkan cek email anda",
                            icon: "success"
                        });
                    } else if (result.status == 2) { // jika mendapatkan hasil status 2
                        Swal.fire({
                            title: "Kode Validasi",
                            text: "Gagal dikirim!, silahkan coba lagi",
                            icon: "info"
                        });
                    } else { // selain itu
                        Swal.fire({
                            title: "Kode Validasi",
                            text: "Gagal dikirim!, email sudah digunakan",
                            icon: "info"
                        });
                    }
                },
                error: function(result) { // jika fungsi gagal berjalan
                    // sembunyikan loaing

                    // tampilkan notifikasi error
                    error_proccess()
                }
            });
        }

        // fungsi hyperlink dengan js
        function getUrl(url) {
            location.href = siteUrl + url;
        }

        // fungsi cek value harus berupa email
        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        // fungsi tampil/sembunyi password
        function pass() {
            if (document.getElementById("password").type == "password") { // jika icon password gembok di klik
                // ubah tipe password menjadi text
                document.getElementById("password").type = "text";

                // tampilkan icon buka
                $("#open_pass").show();

                // sembunyikan icon gembok
                $("#lock_pass").hide();
            } else { // selain itu
                // ubah tipe password menjadi passwword
                document.getElementById("password").type = "password";
                // sembunyikan icon buka
                $("#open_pass").hide();

                // tampilkan icon gembok
                $("#lock_pass").show();
            }
        }

        // fungsi notifikasi error
        function error_proccess() {
            Swal.fire({
                title: "Error",
                text: "Error dalam pemrosesan!",
                icon: "error"
            });
            return;
        }


        initailizeSelect2_kategori();
        initailizeSelect2_promo();

        function initailizeSelect2_kategori() {
            $(".select2_kategori").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Kategori',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataKategori',
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 100,
                    data: function(result) {
                        return {
                            searchTerm: result.term
                        };
                    },

                    processResults: function(result) {
                        return {
                            results: result
                        };
                    },
                    cache: true
                }
            });
        }

        function initailizeSelect2_promo() {
            $(".select2_promo").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Promo',
                dropdownParent: $('#m_promo'),
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPromo',
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 100,
                    data: function(result) {
                        return {
                            searchTerm: result.term
                        };
                    },

                    processResults: function(result) {
                        return {
                            results: result
                        };
                    },
                    cache: true
                }
            });
        }
    </script>

    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>assets/dist/js/adminlte.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="<?= base_url() ?>assets/dist/js/demo.js"></script>

    <script>
    </script>
</body>

</html>