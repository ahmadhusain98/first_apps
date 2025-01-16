<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/fontawesome/css/all.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- select2 -->
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
    <link rel="icon" href="<?= base_url('assets/img/web/') . $web->logo ?>" type="image/ico">
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- responsive -->
    <style>
        /* select2 */
        .select2-selection__rendered {
            line-height: 27px !important;
        }

        .select2-container .select2-selection--single {
            height: 39px !important;
        }

        .select2-selection__arrow {
            height: 39px !important;
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

        <?php
        $color_bg = $this->M_global->getData('web_setting', ['id' => 1])->bg_theme;
        ?>.active {
            background-color: #f2f2f4 !important;
            color: <?= $color_bg ?> !important;
        }

        .letter {
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin: 26px auto 0;
            min-height: 300px;
            padding: 24px;
            position: relative;
            width: 80%;
        }

        .letter:before,
        .letter:after {
            content: "";
            height: 98%;
            position: absolute;
            width: 100%;
            z-index: -1;
        }

        .letter:before {
            background: #fafafa;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            left: -5px;
            top: 4px;
            transform: rotate(-2.5deg);
        }

        .letter:after {
            background: #f6f6f6;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
            right: -3px;
            top: 1px;
            transform: rotate(1.4deg);
        }
    </style>

    <?php
    // $sess = $this->session->userdata('kode_user');
    // $user_sess = $this->M_global->getData('user', ['kode_user' => $sess]);
    // if ($user_sess->actived < 1) {
    //     redirect('Auth/logout');
    // }

    $master_cabang = $this->M_global->getData('cabang', ["kode_cabang" => $this->session->userdata('cabang')]);
    $version_web = $this->M_global->getData('web_version', ["id_web" => $this->session->userdata('web_id')]);

    $tgl1 = strtotime(date('Y-m-d'));
    $tgl2 = strtotime($master_cabang->aktif_sampai);

    $jarak = ($tgl2 - $tgl1);

    $aktif_cabang = $jarak / 60 / 60 / 24;

    cek_so();

    // Hapus sampah otomatis

    $web_setting = $this->M_global->getData('web_setting', ['id' => $this->session->userdata('web_id')]); //ambil setting web

    $limit_trash = $web_setting->limit_trash_web; // ambil limit trash

    $sampah = $this->M_global->getDataSampah(); // ambil data sampah

    if (count($sampah) > 0) { // cek jika ada data sampah
        foreach ($sampah as $s) { // lakukan loop
            $date_trash = date('Y-m-d', strtotime($s->tgl . ' + ' . $limit_trash . ' days')); // tgl sampah ditambahkan dengan limit hari sampah

            if (date('Y-m-d') == $date_trash) { // jika hari ini sama dengan tgl sampah yang di tambah limit hari sampah
                $this->M_global->delData($s->tabel, ['tgl_hapus < ' => $date_trash]); // hapus data sampah dimana tgl hapus kurang dari tgl sampah yang di tambah limit hari sampah
            }
        }
    }
    ?>


    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center" id="loadering" style="background-color: #fcfefc;">
            <img class="animation__shake" src="<?= base_url('assets/img/loading_2.gif') ?>" alt="AdminLTELogo" height="200" width="200">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm fixed-top">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" type="button" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <span type="button" class="nav-link">Cabang: <?= $master_cabang->cabang ?></span>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <?= ($this->uri->segment(1) == 'Home' ? '' : '<button class="btn" onclick="showGuide()"><i class="fa-solid fa-circle-question"></i>&nbsp;&nbsp;Manual Guide</button>') ?>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" type="button" readonly>
                        <span class="badge badge-info" type="button" onclick="ganti_shift()"><?= 'Shift ~ ke: ' . $this->data["shift"] ?></span>
                    </a>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" type="button">
                        <ion-icon name="chatbubbles-outline" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Perpesanan"></ion-icon>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a type="button" class="dropdown-item"> -->
                <!-- Message Start -->
                <!-- <div class="media">
                                <img src="<?= base_url() ?>assets/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div> -->
                <!-- Message End -->
                <!-- </a>
                        <div class="dropdown-divider"></div>
                        <a type="button" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li> -->

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <?php
                    $cabang = $this->session->userdata('cabang');
                    $sintak = $this->db->query("SELECT * FROM (
                        SELECT id, no_trx AS invoice, 'pembayaran' AS url FROM pendaftaran
                        WHERE kode_cabang = '$cabang' AND status_trx = 0

                        UNION ALL

                        SELECT id, invoice AS invoice, 'kasir' AS url FROM barang_out_header 
                        WHERE kode_cabang = '$cabang' AND status_jual = 0 AND no_trx IS NULL

                        UNION ALL 
                        
                        SELECT id, invoice AS invoice, 'mutasi_cabang' AS url FROM mutasi_po_header
                        WHERE dari = '$cabang' AND status_po = 1 AND jenis_po = 1 AND invoice NOT IN (SELECT invoice_po FROM mutasi_header)

                        UNION ALL 
                        
                        SELECT id, invoice AS invoice, 'mutasi_gudang' AS url FROM mutasi_po_header
                        WHERE kode_cabang = '$cabang' AND status_po = 1 AND jenis_po = 0 AND invoice NOT IN (SELECT invoice_po FROM mutasi_header)

                        UNION ALL

                        SELECT id, invoice AS invoice, 'pre_order' AS url FROM barang_po_in_header
                        WHERE kode_cabang = '$cabang' AND is_valid = 1 AND invoice NOT IN (SELECT invoice_po FROM barang_in_header WHERE kode_cabang = '$cabang')
                    ) AS semuax
                    ORDER BY id DESC LIMIT 10")->result();
                    ?>
                    <a class="nav-link" data-toggle="dropdown" type="button">
                        <i class="fa-regular fa-bell"></i>&nbsp;&nbsp;Notifikasi&nbsp;&nbsp;
                        <?php if (count($sintak) > 0) : ?>
                            <span class="badge badge-warning navbar-badge"><?= number_format(count($sintak)) ?></span>
                        <?php endif ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header"><?= number_format(count($sintak)) ?> Notifikasi</span>
                        <div class="dropdown-divider"></div>
                        <a type="button" class="dropdown-item">
                            <?php
                            if (count($sintak) > 0) :
                                foreach ($sintak as $s) :
                                    if ($s->url == 'kasir') {
                                        $msg = 'Pbr.Ksr';
                                        $par_url = 'Kasir/form_kasir/0?invoice=' . $s->invoice;
                                    } else if ($s->url == 'pembayaran') {
                                        $msg = 'Pbr.Ksr';
                                        $par_url = 'Kasir/form_kasir/0?invoice=' . $s->invoice;
                                    } else if ($s->url == 'mutasi_cabang') {
                                        $msg = 'Mts.Cab';
                                        $par_url = 'Transaksi/form_mutasi/0?invoice=' . $s->invoice;
                                    } else if ($s->url == 'mutasi_gudang') {
                                        $msg = 'Mts.Gud';
                                        $par_url = 'Transaksi/form_mutasi/0?invoice=' . $s->invoice;
                                    } else if ($s->url == 'pre_order') {
                                        $msg = 'Trm.Brg';
                                        $par_url = 'Transaksi/form_barang_in/0?invoice=' . $s->invoice;
                                    } else {
                                        $msg = '';
                                        $par_url = '';
                                    }
                            ?>
                                    <a type="button" href="<?= site_url($par_url) ?>" class="pl-3 text-primary" style="text-decoration: none; margin-bottom: 10px;">
                                        <?= $msg ?> | <?= $s->invoice ?>
                                    </a>
                                <?php
                                endforeach;
                            else : ?>
                                <span style="color: grey; margin-bottom: 10px;">Tidak Ada Notifikasi</span>
                            <?php endif;
                            ?>
                        </a>
                        <!-- <div class="dropdown-divider"></div>
                        <a type="button" class="dropdown-item dropdown-footer" href="<?= site_url('Transaksi/barang_out') ?>">Lihat Semua Notifikasi</a> -->
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <button type="button" class="btn text-danger" style="background-color: transparent;" onclick="exit()"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;Keluar</button>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <!-- <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-position: center; background-size: cover;"> -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: <?= $web->bg_theme ?>;">
            <!-- Brand Logo -->
            <a type="button" href="<?= site_url('Home') ?>" class="brand-link" style="backdrop-filter: blur(10px);">
                <img src="<?= base_url('assets/img/web/') . $web->logo ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?= $nama_apps ?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar" style="backdrop-filter: blur(10px);">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex ms-auto">
                    <div class="image my-auto mr-2">
                        <img src="<?= base_url('assets/user/') . $this->data["foto"] ?>" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info ms-auto">
                        <a type="button" href="<?= site_url('Profile') ?>" class="d-block">
                            <?= $this->data["nama"] ?>
                            <br>
                            <span style="font-size: 10px;" class="text-white"><?= $this->M_global->getData('m_role', ['kode_role' => $this->data["kode_role"]])->keterangan ?></span>
                        </a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Cari Menu..." aria-label="Search">
                    </div>
                </div>

                <!-- Sidebar -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <?php
                        // ambil menu dari table m_menu kemudian tampung ke variable $menu
                        $menu = $this->db->query("SELECT m.* FROM m_menu m WHERE m.id IN (SELECT id_menu FROM akses_menu WHERE kode_role IN (SELECT kode_role FROM user WHERE kode_user = '" . $this->session->userdata('kode_user') . "')) ORDER BY m.id")->result();

                        // loop $menu
                        foreach ($menu as $m) :
                            if ($m->url == $this->uri->segment(1)) { // jika url menu sama dengan segment 1 dari url
                                // aktifkan
                                $aktifUrl = 'active';
                            } else { // selain itu
                                // nonaktifkan
                                $aktifUrl = '';
                            }
                        ?>
                            <?php
                            $cek_sm = $this->db->query('SELECT * FROM sub_menu WHERE id_menu = "' . $m->id . '"')->num_rows();
                            if ($cek_sm < 1) :
                            ?>
                                <li class="nav-item">
                                    <a type="button" class="nav-link <?= $aktifUrl ?>" onclick="getUrl('<?= $m->url ?>')">
                                        &nbsp;<?= $m->icon ?>
                                        <p class="<?= ($m->nama == 'Sampah Master') ? 'text-danger font-weight-bold' : '' ?>">
                                            <?php
                                            $data_sampah = $this->M_global->getDataSampah();

                                            if (count($data_sampah) > 0) {
                                                $count_sampah = ' <sup class="badge badge-primary">' . count($data_sampah) . '</sup>';
                                            } else {
                                                $count_sampah = '';
                                            }
                                            ?>
                                            <?= ($m->nama == 'Sampah Master') ? $m->nama . $count_sampah : $m->nama ?>
                                        </p>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li class="nav-item">
                                    <a type="button" class="nav-link <?= $aktifUrl ?>">
                                        &nbsp;<?= $m->icon ?>
                                        <p class="<?= ($m->nama == 'Sampah') ? 'text-danger font-weight-bold' : '' ?>">
                                            <?= $m->nama ?>
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <?php
                                        $sub_menu = $this->db->get_where("sub_menu", ["id_menu" => $m->id])->result();

                                        foreach ($sub_menu as $sm) :
                                            $cek_submenu2 = $this->db->query("SELECT sm2.* FROM sub_menu2 sm2 JOIN sub_menu sm ON sm2.id_submenu = sm.id WHERE sm.url_submenu IS NULL AND sm2.id_submenu = '$sm->id'")->num_rows();
                                        ?>
                                            <li class="nav-item">
                                                <?php if ($cek_submenu2 > 0) :
                                                    $cek_sub_menu = $this->db->query("SELECT s.*, m.nama, m.url, sm2.url_submenu2 FROM sub_menu s JOIN m_menu m ON s.id_menu = m.id JOIN sub_menu2 sm2 ON sm2.id_submenu = s.id WHERE sm2.url_submenu2 = '" . $this->uri->segment('2') . "'")->row();

                                                    if (!empty($cek_sub_menu->submenu)) {
                                                        if ($cek_sub_menu->submenu == $sm->submenu) {
                                                            $aktifUrl2 = 'active';
                                                        } else {
                                                            $aktifUrl2 = '';
                                                        }
                                                    } else {
                                                        $aktifUrl2 = '';
                                                    }

                                                ?>
                                                    <a type="button" class="nav-link <?= $aktifUrl2 ?>">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $sm->icon ?>
                                                        <p>
                                                            <?= $sm->submenu; ?>
                                                            <?php if ($cek_submenu2 > 0) : ?>
                                                                <i class="right fas fa-angle-left"></i>
                                                            <?php endif; ?>
                                                        </p>
                                                    </a>
                                                    <ul class="nav nav-treeview">
                                                        <?php
                                                        $sub_menu2 = $this->db->get_where("sub_menu2", ["id_submenu" => $sm->id])->result();
                                                        foreach ($sub_menu2 as $sm2) :
                                                            $aktifUrl3 = ($sm2->url_submenu2 == $this->uri->segment(2)) ? 'active' : '';
                                                        ?>
                                                            <li class="nav-item">
                                                                <a type="button" class="nav-link <?= $aktifUrl3 ?>" href="<?= site_url($m->url . '/' . $sm2->url_submenu2) ?>">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $sm2->icon ?>
                                                                    <p><?= $sm2->nama ?></p>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php else :
                                                    $cek_sub_menu = $this->db->query("SELECT s.*, m.nama, m.url FROM sub_menu s JOIN m_menu m ON s.id_menu = m.id WHERE s.id = '$sm->id'")->row();

                                                    if (($this->uri->segment(1) == $cek_sub_menu->url) && ($this->uri->segment(2) == $cek_sub_menu->url_submenu)) {
                                                        $aktifUrl2 = 'active';
                                                    } else {
                                                        $aktifUrl2 = '';
                                                    }
                                                ?>
                                                    <a type="button" class="nav-link <?= $aktifUrl2 ?>" href="<?= site_url($m->url . '/' . $sm->url_submenu) ?>">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $sm->icon ?>
                                                        <p><?= $sm->submenu; ?></p>
                                                    </a>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- wrapper -->
        <div class="content-wrapper" style="background-color: #f7f7f7; margin-top: 55px;">
            <!-- <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <ol class="breadcrumb float-sm-left">
                                <h2 class="font-weight-bold"><?= (!empty($page) ? $page : 'NULL') ?></h2>
                            </ol>
                        </div>
                        <div class="col-sm-6 m-auto">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a type="button" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Tooltip on left" title="Beranda" onclick="getUrl('Home')" class="text-dark"><ion-icon name="home-outline"></ion-icon></a></li>
                                <?php if ($this->uri->segment(1) != 'Home') : ?>
                                    <?php
                                    $sub_menul = $this->db->query("SELECT * FROM sub_menu sm WHERE sm.id_menu IN (SELECT id FROM m_menu WHERE url = '" . $this->data["menu"] . "')")->num_rows();
                                    if ($sub_menul > 0) :
                                    ?>
                                        <li class="breadcrumb-item active text-dark"><?= $this->data["menu"] ?></li>
                                        <li class="breadcrumb-item active text-dark"><?= $page ?></li>
                                    <?php else : ?>
                                        <li class="breadcrumb-item active text-dark"><?= $page ?></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- modal loading proses -->
            <div class="modal fade" id="loading" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="<?= base_url() ?>assets/img/loading_2.gif" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- body -->
            <section class="content">
                <div class="container-fluid">
                    <br>
                    <?= $content ?>
                </div>
            </section>
        </div>

        <br>
        <br>
        <br>

        <!-- modal manual guide -->
        <div class="modal fade" id="modal_mg" tabindex="-1" aria-labelledby="modal_mgLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content letter" style="border-radius: 0px;">
                    <div class="modal-header text-primary">
                        <h5 class="modal-title" style="font-weight: bold;" id="modal_mgLabel"></h5>
                    </div>
                    <div class="modal-body">
                        <div id="modal-isi"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <footer class="main-footer fixed-bottom shadow-lg">
            <!-- <strong>Copyright &copy; downtoup.dev</strong>
            2024
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> <?= $web_version ?> <button type="button" class="btn btn-danger btn-xs" onclick="clean_db()">Kosongkan Transaksi</button>
            </div> -->
            <?= $version_web->version ?>. <strong>Masa Aktif Cabang: <span class="text-danger"><?= number_format($aktif_cabang) ?></span> Hari</strong>
            <div class="float-right d-none d-sm-inline-block">
                <span type="button" class="nav-link" id="time"></span>
            </div>
        </footer>
    </div>

    <!-- ionicon -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- myscript -->
    <script>
        // load pertama kali
        const siteUrl = '<?= site_url() ?>';

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        // load pertama kali saat sistem berjalan
        $("#open_pass").hide();

        AOS.init();

        $(".select2_global").select2({
            placeholder: $(this).data('placeholder'),
            width: '100%',
            allowClear: true,
        });

        display_ct();

        function ganti_shift() {
            $('#modal_mgLabel').text(``);
            $('#modal-isi').text(``);

            $('#modal_mg').modal('show');
            $('#modal_mgLabel').html('Ganti Shift');
            $('#modal-isi').append(`
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Shift</label>
                        <div class="form-group">
                            <select class="form-control select2_new_shift" id="new_shift" name="new_shift" placeholder="Pilih Shift">
                                <option value="1" <?= ($this->session->userdata('shift') == 1) ? 'selected' : '' ?>>Shift 1</option>
                                <option value="2" <?= ($this->session->userdata('shift') == 2) ? 'selected' : '' ?>>Shift 2</option>
                                <option value="3" <?= ($this->session->userdata('shift') == 3) ? 'selected' : '' ?>>Shift 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Password</label>
                        <input type="password" class="form-control" id="shift_password" name="shift_password" placeholder="Password" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right" onclick="simpan_shift()">Update Shift</button>
                    </div>
                </div>
                `);

            $(".select2_new_shift").select2({
                placeholder: $(this).data('placeholder'),
                width: '100%',
                allowClear: true,
                dropdownParent: $("#modal_mg")
            });
        }


        function simpan_shift() {
            $('#modal_mg').modal('hide');

            var new_shift = $('#new_shift').val();
            var shift_password = $('#shift_password').val();

            $.ajax({
                url: siteUrl + 'Auth/ganti_shift?shift=' + new_shift + '&password=' + shift_password,
                type: 'POST',
                dataType: 'JSON',
                success: function(result) {
                    if (result.status == 1) {
                        Swal.fire({
                            title: "Shift",
                            text: "Berhasil di ganti!",
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Shift",
                            text: "Gagal di ganti!",
                            icon: "info"
                        });
                    }
                },
                error: function(result) { // jika fungsi error
                    // jalankan fungsi error
                    error_proccess();
                }
            })
        }

        // fungsi clean db
        function clean_db() {
            Swal.fire({
                title: "Kamu yakin?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, kosongkan!"
            }).then((result) => {
                if (result.isConfirmed) { // jika di konfirmasi "Ya"
                    // arahkan ke fungsi logout di controller Auth
                    $.ajax({
                        url: siteUrl + 'Auth/clean_db',
                        type: 'POST',
                        dataType: 'JSON',
                        success: function(result) {
                            if (result.status == 1) {
                                Swal.fire({
                                    title: "Database",
                                    text: "Berhasil di reset!",
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Database",
                                    text: "Gagal di reset!",
                                    icon: "info"
                                });
                            }
                        },
                        error: function(result) { // jika fungsi error
                            // jalankan fungsi error
                            error_proccess();
                        }
                    });
                }
            });
        }

        function display_c() {
            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct()', refresh)
        }

        function display_ct() {
            var x = new Date()
            var x1 = x.getMonth() + 1 + "/" + x.getDate() + "/" + x.getFullYear();
            x1 = x1 + " - " + x.getHours() + ":" + x.getMinutes() + ":" + x.getSeconds();
            document.getElementById('time').innerHTML = x1;
            display_c();
        }

        // fungsi hyperlink js
        function getUrl(url) {
            location.href = siteUrl + url;
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

        // notifikasi error
        function error_proccess() {
            Swal.fire({
                title: "Error",
                text: "Error dalam pemrosesan!",
                icon: "error"
            });
            return;
        }

        // uppercase
        function upperCase(params, forid) {
            $('#' + forid).val(params.toUpperCase())
        }

        // huruf besar diawal kata
        function ubah_nama(nama, forid) {
            // var nama_barang = nama.charAt(0).toUpperCase() + nama.slice(1);
            str = nama.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
            $("#" + forid).val(str);
        }

        // fungsi cek value harus berupa email
        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        // cek email berdsasarkan email
        function cekEmail(forid) {
            if (validateEmail($('#' + forid).val()) == false) {

                Swal.fire("Email", "Format sudah valid?", "question");
                return;
            }
        }

        // cek panjang karakter
        function cekLength(param, forid) {
            if (forid == 'kodepos' || forid == 'kode_pos') { // jika id nya kodepos

                // jalankan fungsi
                if (param.length > 5) { // jika panjang karakter lebih dari 5
                    // munculkan notif
                    Swal.fire('Kode Pos', "Maksimal 5 digit", "question");
                }

                // ambil 5 karakter dari depan lalu lempar ke id-nya
                $('#' + forid).val(param.slice(0, 5));
            } else if (forid == 'nik') { // jika id nya nik

                // jalankan fungsi
                if (param.length != 16) { // jika panjang karakter lebih dari 5
                    // munculkan notif
                    Swal.fire('NIK', "Harus 16 digit", "question");
                }

                // ambil 5 karakter dari depan lalu lempar ke id-nya
                $('#' + forid).val(param.slice(0, 16));

            } else if (forid == 'npwp') { // jika id nya npwp

                // jalankan fungsi
                if (param.length != 16) { // jika panjang karakter lebih dari 5
                    // munculkan notif
                    Swal.fire('NPWP', "Harus 16 digit", "question");
                }

                // ambil 5 karakter dari depan lalu lempar ke id-nya
                $('#' + forid).val(param.slice(0, 16));

            } else if (forid == 'sip') { // jika id nya sip

                // jalankan fungsi
                if (param.length != 15) { // jika panjang karakter lebih dari 5
                    // munculkan notif
                    Swal.fire('SIP', "Harus 15 digit", "question");
                }

                // ambil 5 karakter dari depan lalu lempar ke id-nya
                $('#' + forid).val(param.slice(0, 15));

            }
        }

        // fungsi ambil alamat
        function getAddress(param, forid) {
            // ambil karakter by forid (nik)
            var prov = param.slice(0, 2);
            var kot = param.slice(0, 4);
            var kec = param.slice(0, 6);

            // jalankan fungsi
            showAddress(prov, 'provinsi');
            showAddress(kot, 'kabupaten');
            showAddress(kec, 'kecamatan');
        }

        // fungsi menampilkan isi address
        function showAddress(param, forid) {

            if (param == '' || param == null || forid == '' || forid == null) {
                return Swal.fire('Kesalahan', "Terdapat kesalahan saat memuat!, coba lagi", "question");
            }

            if (forid == 'provinsi') { // jika forid = provinsi
                // isi table menjadi m_provinsi
                forid2 = 'm_provinsi';
            } else { // selain itu
                // isi table berdasarkan lemparan
                forid2 = forid;
            }

            // jalankan fungsi
            $.ajax({
                url: siteUrl + 'Master_show/getInfo/' + forid2 + '/' + param,
                type: 'POST',
                dataType: 'JSON',
                success: function(result) { // jika fungsi berjalan
                    $('#' + forid).html(`<option value="${result.id}">${result.text}</option>`);
                },
                error: function(result) { // jika fungsi error
                    // jalankan fungsi error
                    error_proccess();
                }
            });
        }

        // fungsi format Rupiah
        function formatRp(num, forid) {
            num = num.toString().replace(/\$|\,/g, '');

            num = Math.ceil(num);

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

            var result = (((sign) ? '' : '-') + '' + num);
            $('#' + forid).val(result);
        }

        // fungsi format Rupiah NoId
        function formatRpNoId(num) {
            num = num.toString().replace(/\$|\,/g, '');

            num = Math.ceil(num);

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

            return (((sign) ? '' : '-') + '' + num);
        }

        // fungsi preview
        function preview(url) {
            if (url == 'pendaftaran') {
                var poli_pendaftaran = $('#kode_poli').val()
                var tgl_dari_pendaftaran = $('#dari').val()
                var tgl_sampai_pendaftaran = $('#sampai').val()
            } else if ((url == 'kasir') || (url == 'barang_po_in') || (url == 'barang_in') || (url == 'barang_in_retur') || (url == 'barang_out') || (url == 'barang_out_retur') || (url == 'penyesuaian_stok') || (url == 'mutasi_po') || (url == 'mutasi')) {
                var poli_pendaftaran = ''
                var tgl_dari_pendaftaran = $('#dari').val()
                var tgl_sampai_pendaftaran = $('#sampai').val()
            } else {
                var poli_pendaftaran = ''
                var tgl_dari_pendaftaran = ''
                var tgl_sampai_pendaftaran = ''
            }

            var param = `?poli=${poli_pendaftaran}&dari=${tgl_dari_pendaftaran}&sampai=${tgl_sampai_pendaftaran}`
            window.open(`${siteUrl}Report/${url}/0${param}`, '_blank');
        }

        // fungsi print
        function print(url) {
            if (url == 'pendaftaran') {
                var poli_pendaftaran = $('#kode_poli').val()
                var tgl_dari_pendaftaran = $('#dari').val()
                var tgl_sampai_pendaftaran = $('#sampai').val()
            } else if ((url == 'kasir') || (url == 'barang_po_in') || (url == 'barang_in') || (url == 'barang_in_retur') || (url == 'barang_out') || (url == 'barang_out_retur') || (url == 'penyesuaian_stok') || (url == 'mutasi_po') || (url == 'mutasi')) {
                var poli_pendaftaran = ''
                var tgl_dari_pendaftaran = $('#dari').val()
                var tgl_sampai_pendaftaran = $('#sampai').val()
            } else {
                var poli_pendaftaran = ''
                var tgl_dari_pendaftaran = ''
                var tgl_sampai_pendaftaran = ''
            }

            var param = `?poli=${poli_pendaftaran}&dari=${tgl_dari_pendaftaran}&sampai=${tgl_sampai_pendaftaran}`
            window.open(`${siteUrl}Report/${url}/1${param}`, '_blank');
        }

        // fungsi export excel
        function excel(url) {
            if (url == 'pendaftaran') {
                var poli_pendaftaran = $('#kode_poli').val()
                var tgl_dari_pendaftaran = $('#dari').val()
                var tgl_sampai_pendaftaran = $('#sampai').val()
            } else if ((url == 'kasir') || (url == 'barang_po_in') || (url == 'barang_in') || (url == 'barang_in_retur') || (url == 'barang_out') || (url == 'barang_out_retur') || (url == 'penyesuaian_stok') || (url == 'mutasi_po') || (url == 'mutasi')) {
                var poli_pendaftaran = ''
                var tgl_dari_pendaftaran = $('#dari').val()
                var tgl_sampai_pendaftaran = $('#sampai').val()
            } else {
                var poli_pendaftaran = ''
                var tgl_dari_pendaftaran = ''
                var tgl_sampai_pendaftaran = ''
            }

            var param = `?poli=${poli_pendaftaran}&dari=${tgl_dari_pendaftaran}&sampai=${tgl_sampai_pendaftaran}`
            window.open(`${siteUrl}Report/${url}/2${param}`, '_blank');
        }

        function printsingle(url) {
            window.open(`${siteUrl}${url}/1`, '_blank');
        }

        // datatable
        $('#tableSederhana').DataTable({
            "destroy": true,
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollCollapse": false,
            "paging": true,
            "oLanguage": {
                "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
                "sInfoEmpty": "",
                "sInfoFiltered": "",
                "sSearch": "",
                "sSearchPlaceholder": "Cari data...",
                "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                "sLengthMenu": "_MENU_ Baris",
                "sZeroRecords": "<div class='text-center'>Data Kosong</div>",
                "oPaginate": {
                    "sPrevious": "Sebelumnya",
                    "sNext": "Berikutnya"
                }
            },
            "aLengthMenu": [
                [10, 25, 50, 75, 100, -1],
                [10, 25, 50, 75, 100, "Semua"]
            ],
            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }, ],
        });

        <?php if (!empty($list_data)) : ?>
            table.DataTable({
                "destroy": true,
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": `${siteUrl}${'<?= $list_data ?>/' + '<?= $param1 ?>'}`,
                    "type": "POST",
                },
                "scrollCollapse": false,
                "paging": true,
                "language": {
                    "emptyTable": "<div class='text-center'>Data Kosong</div>",
                    "infoEmpty": "",
                    "infoFiltered": "",
                    "search": "",
                    "searchPlaceholder": "Cari data...",
                    "info": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "lengthMenu": "_MENU_ Baris",
                    "zeroRecords": "<div class='text-center'>Data Kosong</div>",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Berikutnya"
                    }
                },
                "lengthMenu": [
                    [10, 25, 50, 75, 100, -1],
                    [10, 25, 50, 75, 100, "Semua"]
                ],
                "columnDefs": [{
                    "targets": [-1],
                    "orderable": false,
                }],
            });

            // fungsi filter tanggal dan parameter jika ada (jika tidak ada di kosongkan)
            function filter(x = '') {
                var dari = $('#dari').val();
                var sampai = $('#sampai').val();

                if (x == '' || x == null) {
                    var parameterString = `2~${dari}~${sampai}`;
                } else {
                    var parameterString = `2~${dari}~${sampai}/${x}`;
                }

                table.DataTable().ajax.url(siteUrl + '<?= $list_data ?>' + parameterString).load();
            }
        <?php endif; ?>

        function reloadTable() {
            // if ($.fn.DataTable.isDataTable(table)) {
            table.DataTable().ajax.reload(null, false);
            // }
        }

        // fungsi select2 global
        // inisial
        initailizeSelect2_pajak();
        initailizeSelect2_provinsi();
        initailizeSelect2_kabupaten(param = '');
        initailizeSelect2_kecamatan(param = '');
        initailizeSelect2_member("<?= (($this->uri->segment(1) == 'Health') ? 'Health' : 'Transaksi') ?>");
        initailizeSelect2_user();
        initailizeSelect2_poli();
        initailizeSelect2_dokter_poli(param = 'POL0000001');
        initailizeSelect2_ruang();
        initailizeSelect2_supplier();
        initailizeSelect2_gudang_int();
        initailizeSelect2_gudang_log();
        initailizeSelect2_pekerjaan();
        initailizeSelect2_agama();
        initailizeSelect2_pendidikan();
        initailizeSelect2_pendaftaran('');
        initailizeSelect2_penjualan();
        initailizeSelect2_penjualan_retur();
        initailizeSelect2_bank();
        initailizeSelect2_tipe_bank();
        initailizeSelect2_jual_for_retur();
        initailizeSelect2_promo(min_buy = '0');
        initailizeSelect2_barang();
        initailizeSelect2_kas_bank();
        initailizeSelect2_kategori_tarif();
        initailizeSelect2_all_cabang();
        initailizeSelect2_tarif_paket();
        initailizeSelect2_tarif_single();
        initailizeSelect2_terdaftar();
        initailizeSelect2_klasifikasi_akun();
        initailizeSelect2_akun_sel(param = '');

        // fungsi
        function initailizeSelect2_akun_sel(param) {
            if (param == '' || param == null || param == 'null') { // jika parameter kosong/ null
                // jalankan fungsi select2_default
                return select2_default('select2_akun_sel');
            }
            // jalan fungsi select2 asli
            $(".select2_akun_sel").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Akun',
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 1 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataAkunSel/' + param,
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

        function initailizeSelect2_klasifikasi_akun() {
            // jalan fungsi select2 asli
            $(".select2_klasifikasi_akun").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Klasifikasi',
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 1 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataKlasifikasiAkun/',
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

        function initailizeSelect2_terdaftar() {
            // jalan fungsi select2 asli
            $(".select2_terdaftar").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Cabang',
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 1 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataTerdaftar/',
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

        function initailizeSelect2_tarif_single() {
            // jalan fungsi select2 asli
            $(".select2_tarif_single").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Cabang',
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 1 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataTarifSingle/',
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

        function initailizeSelect2_tarif_paket() {
            // jalan fungsi select2 asli
            $(".select2_tarif_paket").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Cabang',
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 1 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataTarifPaket/',
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

        function initailizeSelect2_all_cabang() {
            $(".select2_all_cabang").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Cabang',
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 1 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataAllCabang/',
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

        function initailizeSelect2_kategori_tarif() {
            $(".select2_kategori_tarif").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Barang',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataKatTarif',
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

        function initailizeSelect2_kas_bank() {
            $(".select2_kas_bank").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Barang',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataKasBank',
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

        function initailizeSelect2_barang() {
            $(".select2_barang").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Barang',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataBarang',
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

        function initailizeSelect2_pajak() {
            $(".select2_pajak").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Pajak',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPajak',
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

        function initailizeSelect2_provinsi() {
            $(".select2_provinsi").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Provinsi',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataProvinsi',
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

        function select2_default(param) {
            $("." + param).select2({
                placeholder: $(this).data('placeholder'),
                width: '100%',
            });
        }

        function initailizeSelect2_kabupaten(param) {
            if (param == '' || param == null || param == 'null') { // jika parameter kosong/ null
                // jalankan fungsi select2_default
                select2_default('select2_kabupaten');
            } else { // selain itu
                // jalan fungsi select2 asli
                $(".select2_kabupaten").select2({
                    allowClear: true,
                    multiple: false,
                    placeholder: '~ Pilih Kabupaten',
                    dropdownAutoWidth: true,
                    width: '100%',
                    language: {
                        inputTooShort: function() {
                            return 'Ketikan Nomor minimal 1 huruf';
                        }
                    },
                    ajax: {
                        url: siteUrl + 'Select2_master/dataKabupaten/' + param,
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
        }

        function initailizeSelect2_kecamatan(param) {
            if (param == '' || param == null || param == 'null') { // jika parameter kosong/ null
                // jalankan fungsi select2_default
                select2_default('select2_kecamatan');
            } else { // selain itu
                // jalan fungsi select2 asli
                $(".select2_kecamatan").select2({
                    allowClear: true,
                    multiple: false,
                    placeholder: '~ Pilih Kecamatan',
                    dropdownAutoWidth: true,
                    width: '100%',
                    language: {
                        inputTooShort: function() {
                            return 'Ketikan Nomor minimal 1 huruf';
                        }
                    },
                    ajax: {
                        url: siteUrl + 'Select2_master/dataKecamatan/' + param,
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
        }

        function initailizeSelect2_member(param) {
            $(".select2_member").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Member',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataMember/' + param,
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

        function initailizeSelect2_user() {
            $(".select2_user").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih User',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataUser',
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

        function initailizeSelect2_poli() {
            $(".select2_poli").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Poli',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPoli',
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

        function initailizeSelect2_dokter_poli(param) {
            if (param == '' || param == null || param == 'null') { // jika parameter kosong/ null
                // jalankan fungsi select2_default
                select2_default('select2_dokter_poli');
            } else { // selain itu
                // jalan fungsi select2 asli
                $(".select2_dokter_poli").select2({
                    allowClear: true,
                    multiple: false,
                    placeholder: '~ Pilih Dokter',
                    dropdownAutoWidth: true,
                    width: '100%',
                    language: {
                        inputTooShort: function() {
                            return 'Ketikan Nomor minimal 1 huruf';
                        }
                    },
                    ajax: {
                        url: siteUrl + 'Select2_master/dataDokterPoli/' + param,
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
        }

        function initailizeSelect2_ruang() {
            $(".select2_ruang").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Ruang',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataRuang',
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

        function initailizeSelect2_supplier() {
            $(".select2_supplier").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Supplier',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataSupplier',
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

        function initailizeSelect2_gudang_int() {
            $(".select2_gudang_int").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Gudang',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataGudangInt',
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

        function initailizeSelect2_gudang_log() {
            $(".select2_gudang_log").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Gudang',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataGudangLog',
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

        function initailizeSelect2_pekerjaan() {
            $(".select2_pekerjaan").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Pekerjaan',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPekerjaan',
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

        function initailizeSelect2_agama() {
            $(".select2_agama").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Agama',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataAgama',
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

        function initailizeSelect2_pendidikan() {
            $(".select2_pendidikan").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Pendidikan',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPendidikan',
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

        function initailizeSelect2_pendaftaran(param) {
            $(".select2_pendaftaran").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Pendaftaran',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPendaftaran/' + param,
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

        function initailizeSelect2_penjualan() {
            $(".select2_penjualan").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Penjualan',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPenjualan',
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

        function initailizeSelect2_penjualan_retur() {
            $(".select2_penjualan_retur").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Retur Jual',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataReturJual',
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

        function initailizeSelect2_bank() {
            $(".select2_bank").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Bank',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataBank',
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

        function initailizeSelect2_tipe_bank() {
            $(".select2_tipe_bank").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Tipe Bank',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataTipeBank',
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

        function initailizeSelect2_jual_for_retur() {
            $(".select2_jual_for_retur").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Penjualan Untuk Di Retur',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataJualForRetur',
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

        function initailizeSelect2_promo(min_buy) {
            $(".select2_promo").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Promo',
                //minimumInputLength: 2,
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 2 huruf';
                    }
                },
                ajax: {
                    url: siteUrl + 'Select2_master/dataPromo/' + min_buy,
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
</body>

</html>