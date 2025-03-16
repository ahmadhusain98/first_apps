<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_auth");
    }

    // login page
    public function index()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            'judul'             => 'Selamat Datang',
            'nama_apps'         => $web_setting->nama,
            'web_version'       => $web_version->version,
            'web_version_all'   => $web_version,
            'web'               => $web_setting,
        ];

        if (!empty($this->session->userdata('email'))) {
            redirect('Home');
        } else {
            $this->template->load('Template/Auth', 'Auth/Login', $parameter);
        }
    }

    // count notif
    public function count_notif()
    {
        $cabang = $this->session->userdata('cabang');
        $cek_dok = $this->M_global->getData('dokter', ['kode_dokter' => $this->session->userdata('kode_user')]);
        $cek_per = $this->M_global->getData('perawat', ['kode_perawat' => $this->session->userdata('kode_user')]);

        if ($cek_dok) {
            if ($this->session->userdata('kode_role') == 'R0001') {
                $sintak = $this->db->query('SELECT p.id, p.no_trx AS invoice, "emr" AS url, p.tgl_daftar AS tgl, p.jam_daftar AS jam FROM pendaftaran p WHERE p.status_trx <> 1 AND p.kode_cabang = "' . $cabang . '" AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx) AND no_trx IN (SELECT no_trx FROM emr_per)')->result();
            } else {
                $sintak = $this->db->query('SELECT p.id, p.no_trx AS invoice, "emr" AS url, p.tgl_daftar AS tgl, p.jam_daftar AS jam FROM pendaftaran p WHERE p.kode_dokter = "' . $cek_dok->kode_dokter . '" AND p.status_trx <> 1 AND p.kode_cabang = "' . $cabang . '" AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx) AND no_trx IN (SELECT no_trx FROM emr_per)')->result();
            }
        } else if ($cek_per) {
            $sintak = $this->db->query('SELECT p.id, p.no_trx AS invoice, "emr2" AS url, p.tgl_daftar AS tgl, p.jam_daftar AS jam FROM pendaftaran p WHERE p.status_trx < 1 AND p.kode_cabang = "' . $cabang . '" AND NOT EXISTS (SELECT 1 FROM emr_per ep WHERE ep.no_trx = p.no_trx)')->result();
        } else {
            if (($this->session->userdata('kode_role') == 'R0001') || ($this->session->userdata('kode_role') == 'R0004')) {
                $sintak = $this->db->query("SELECT *
                FROM (
                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'pembayaran' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam
                    FROM pendaftaran p
                    JOIN tarif_paket_pasien t USING (no_trx)
                    WHERE p.kode_cabang = '$cabang' AND p.status_trx = 0

                    UNION ALL

                    SELECT
                        id,
                        no_trx AS invoice,
                        'kasir' AS url,
                        tgl_jual AS tgl,
                        jam_jual AS jam
                    FROM barang_out_header
                    WHERE kode_cabang = '$cabang' AND status_jual = 0

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_cabang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam
                    FROM mutasi_po_header
                    WHERE dari = '$cabang' AND status_po = 1 AND jenis_po = 1
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_gudang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam
                    FROM mutasi_po_header
                    WHERE kode_cabang = '$cabang' AND status_po = 1 AND jenis_po = 0
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'pre_order' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam
                    FROM barang_po_in_header
                    WHERE kode_cabang = '$cabang' AND is_valid = 1
                    AND NOT EXISTS (SELECT 1 FROM barang_in_header WHERE kode_cabang = '$cabang' AND invoice_po = barang_po_in_header.invoice)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam
                    FROM pendaftaran p
                    WHERE p.status_trx <> 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx)
                    AND EXISTS (SELECT 1 FROM emr_per WHERE kode_cabang = '$cabang' AND no_trx = p.no_trx)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr2' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam
                    FROM pendaftaran p
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_per ep WHERE ep.no_trx = p.no_trx)

                    UNION ALL

                    SELECT 
                        d.id, 
                        d.no_trx AS invoice,
                        'jual' AS url,
                        d.date_dok AS tgl,
                        d.time_dok AS jam
                    FROM emr_dok d
                    JOIN pendaftaran p USING (no_trx)
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang' AND (d.eracikan <> '' OR d.no_trx IN (SELECT no_trx FROM emr_per_barang)) AND d.no_trx NOT IN (SELECT no_trx FROM barang_out_header)
                ) AS semuax
                ORDER BY id DESC
            LIMIT 10")->result();
            } else {
                $sintak = $this->db->query("SELECT *
                FROM (
                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_cabang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam
                    FROM mutasi_po_header
                    WHERE dari = '$cabang' AND status_po = 1 AND jenis_po = 1
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_gudang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam
                    FROM mutasi_po_header
                    WHERE kode_cabang = '$cabang' AND status_po = 1 AND jenis_po = 0
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'pre_order' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam
                    FROM barang_po_in_header
                    WHERE kode_cabang = '$cabang' AND is_valid = 1
                    AND NOT EXISTS (SELECT 1 FROM barang_in_header WHERE kode_cabang = '$cabang' AND invoice_po = barang_po_in_header.invoice)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam
                    FROM pendaftaran p
                    WHERE p.status_trx <> 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx)
                    AND EXISTS (SELECT 1 FROM emr_per WHERE kode_cabang = '$cabang' AND no_trx = p.no_trx)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr2' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam
                    FROM pendaftaran p
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_per ep WHERE ep.no_trx = p.no_trx)

                    UNION ALL

                    SELECT 
                        d.id, 
                        d.no_trx AS invoice,
                        'jual' AS url,
                        d.date_dok AS tgl,
                        d.time_dok AS jam
                    FROM emr_dok d
                    JOIN pendaftaran p USING (no_trx)
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang' AND (d.eracikan <> '' OR d.no_trx IN (SELECT no_trx FROM emr_per_barang)) AND d.no_trx NOT IN (SELECT no_trx FROM barang_out_header)
                ) AS semuax
                ORDER BY id DESC
            LIMIT 10")->result();
            }
        }
        if (count($sintak) > 0) {
            echo '<span class="badge badge-warning navbar-badge">
                <div>' . number_format(count($sintak)) . '</div>
            </span>';
        } else {
            echo '';
        }
    }

    // notifikasi live
    public function notif_live()
    {
        $cabang = $this->session->userdata('cabang');
        $cek_dok = $this->M_global->getData('dokter', ['kode_dokter' => $this->session->userdata('kode_user')]);
        $cek_per = $this->M_global->getData('perawat', ['kode_perawat' => $this->session->userdata('kode_user')]);

        if (!empty($cek_dok)) {
            if ($this->session->userdata('kode_role') == 'R0001') {
                $sintak = $this->db->query('SELECT p.id, p.no_trx AS invoice, "emr" AS url, p.tgl_daftar AS tgl, p.jam_daftar AS jam, p.kode_member FROM pendaftaran p WHERE p.status_trx <> 1 AND p.kode_cabang = "' . $cabang . '" AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx) AND no_trx IN (SELECT no_trx FROM emr_per)')->result();
            } else {
                $sintak = $this->db->query('SELECT p.id, p.no_trx AS invoice, "emr" AS url, p.tgl_daftar AS tgl, p.jam_daftar AS jam, p.kode_member FROM pendaftaran p WHERE p.kode_dokter = "' . $cek_dok->kode_dokter . '" AND p.status_trx <> 1 AND p.kode_cabang = "' . $cabang . '" AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx) AND no_trx IN (SELECT no_trx FROM emr_per)')->result();
            }
        } else if ($cek_per) {
            $sintak = $this->db->query('SELECT p.id, p.no_trx AS invoice, "emr2" AS url, p.tgl_daftar AS tgl, p.jam_daftar AS jam, p.kode_member FROM pendaftaran p WHERE p.status_trx < 1 AND p.kode_cabang = "' . $cabang . '" AND NOT EXISTS (SELECT 1 FROM emr_per ep WHERE ep.no_trx = p.no_trx)')->result();
        } else {
            if (($this->session->userdata('kode_role') == 'R0001') || ($this->session->userdata('kode_role') == 'R0004')) {
                $sintak = $this->db->query("SELECT *
                FROM (
                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'pembayaran' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam,
                        p.kode_member
                    FROM pendaftaran p
                    JOIN tarif_paket_pasien t USING (no_trx)
                    WHERE p.kode_cabang = '$cabang' AND p.status_trx = 0

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'kasir' AS url,
                        tgl_jual AS tgl,
                        jam_jual AS jam,
                        '' AS kode_member
                    FROM barang_out_header
                    WHERE kode_cabang = '$cabang' AND status_jual = 0

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_cabang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam,
                        '' AS kode_member
                    FROM mutasi_po_header
                    WHERE dari = '$cabang' AND status_po = 1 AND jenis_po = 1
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_gudang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam,
                        '' AS kode_member
                    FROM mutasi_po_header
                    WHERE kode_cabang = '$cabang' AND status_po = 1 AND jenis_po = 0
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'pre_order' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam,
                        '' AS kode_member
                    FROM barang_po_in_header
                    WHERE kode_cabang = '$cabang' AND is_valid = 1
                    AND NOT EXISTS (SELECT 1 FROM barang_in_header WHERE kode_cabang = '$cabang' AND invoice_po = barang_po_in_header.invoice)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam,
                        p.kode_member
                    FROM pendaftaran p
                    WHERE p.status_trx <> 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx)
                    AND EXISTS (SELECT 1 FROM emr_per WHERE kode_cabang = '$cabang' AND no_trx = p.no_trx)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr2' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam,
                        p.kode_member
                    FROM pendaftaran p
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_per ep WHERE ep.no_trx = p.no_trx)

                    UNION ALL

                    SELECT 
                        d.id, 
                        d.no_trx AS invoice,
                        'jual' AS url,
                        d.date_dok AS tgl,
                        d.time_dok AS jam,
                        p.kode_member
                    FROM emr_dok d
                    JOIN pendaftaran p USING (no_trx)
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang' AND (d.eracikan <> '' OR d.no_trx IN (SELECT no_trx FROM emr_per_barang)) AND d.no_trx NOT IN (SELECT no_trx FROM barang_out_header)
                ) AS semuax
                ORDER BY id DESC
            LIMIT 10")->result();
            } else {
                $sintak = $this->db->query("SELECT *
                FROM (
                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_cabang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam,
                        '' AS kode_member
                    FROM mutasi_po_header
                    WHERE dari = '$cabang' AND status_po = 1 AND jenis_po = 1
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'mutasi_gudang' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam,
                        '' AS kode_member
                    FROM mutasi_po_header
                    WHERE kode_cabang = '$cabang' AND status_po = 1 AND jenis_po = 0
                    AND NOT EXISTS (SELECT 1 FROM mutasi_header WHERE invoice_po = mutasi_po_header.invoice)

                    UNION ALL

                    SELECT
                        id,
                        invoice AS invoice,
                        'pre_order' AS url,
                        tgl_po AS tgl,
                        jam_po AS jam,
                        '' AS kode_member
                    FROM barang_po_in_header
                    WHERE kode_cabang = '$cabang' AND is_valid = 1
                    AND NOT EXISTS (SELECT 1 FROM barang_in_header WHERE kode_cabang = '$cabang' AND invoice_po = barang_po_in_header.invoice)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam,
                        p.kode_member
                    FROM pendaftaran p
                    WHERE p.status_trx <> 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_dok ed WHERE ed.no_trx = p.no_trx)
                    AND EXISTS (SELECT 1 FROM emr_per WHERE kode_cabang = '$cabang' AND no_trx = p.no_trx)

                    UNION ALL

                    SELECT
                        p.id,
                        p.no_trx AS invoice,
                        'emr2' AS url,
                        p.tgl_daftar AS tgl,
                        p.jam_daftar AS jam,
                        p.kode_member
                    FROM pendaftaran p
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang'
                    AND NOT EXISTS (SELECT 1 FROM emr_per ep WHERE ep.no_trx = p.no_trx)

                    UNION ALL

                    SELECT 
                        d.id, 
                        d.no_trx AS invoice,
                        'jual' AS url,
                        d.date_dok AS tgl,
                        d.time_dok AS jam,
                        p.kode_member
                    FROM emr_dok d
                    JOIN pendaftaran p USING (no_trx)
                    WHERE p.status_trx < 1 AND p.kode_cabang = '$cabang' AND (d.eracikan <> '' OR d.no_trx IN (SELECT no_trx FROM emr_per_barang)) AND d.no_trx NOT IN (SELECT no_trx FROM barang_out_header)
                ) AS semuax
                ORDER BY id DESC
            LIMIT 10")->result();
            }
        }
?>
        <a type="button" class="dropdown-item p-2" style="width: 100%;">
            <?php
            if (count($sintak) > 0) :
                foreach ($sintak as $s) :
                    $member = $this->M_global->getData('member', ['kode_member' => $s->kode_member]);
                    if ($s->url == 'emr') {
                        $msg = '<i class="fa-solid fa-fw fa-user-doctor"></i> Emr Dokter | ' . $this->M_global->getData('m_prefix', ['kode_prefix' => $member->kode_prefix])->nama . '. ' . singkatTeks($member->nama) . ' | ' . $s->kode_member;
                        $par_url = 'Emr/dokter/' . $s->invoice;
                    } else if ($s->url == 'emr2') {
                        $msg = '<i class="fa-solid fa-fw fa-user-nurse"></i> Emr Perawat | ' . $this->M_global->getData('m_prefix', ['kode_prefix' => $member->kode_prefix])->nama . '. ' . singkatTeks($member->nama) . ' | ' . $s->kode_member;
                        $par_url = 'Emr/perawat/' . $s->invoice;
                    } else if ($s->url == 'kasir') {
                        $msg = '<i class="fa-solid fa-fw fa-file-invoice-dollar"></i> Kasir | ' . $s->invoice;
                        $par_url = 'Kasir/form_kasir/0/' . $s->invoice;
                    } else if ($s->url == 'pembayaran') {
                        $msg = '<i class="fa-solid fa-fw fa-file-invoice-dollar"></i> Pembayaran Kasir | ' . $s->invoice;
                        $par_url = 'Kasir/form_kasir/0?invoice=' . $s->invoice;
                    } else if ($s->url == 'mutasi_cabang') {
                        $msg = '<i class="fa-solid fa-fw fa-building-circle-check"></i> Mutasi Cabang';
                        $par_url = 'Transaksi/form_mutasi/0?invoice=' . $s->invoice;
                    } else if ($s->url == 'mutasi_gudang') {
                        $msg = '<i class="fa-solid fa-fw fa-warehouse"></i> Mutasi Gudang';
                        $par_url = 'Transaksi/form_mutasi/0?invoice=' . $s->invoice;
                    } else if ($s->url == 'pre_order') {
                        $msg = '<i class="fa-solid fa-fw fa-clipboard-check"></i> Terima Barang | ' . $s->invoice;
                        $par_url = 'Transaksi/form_barang_in/0?invoice=' . $s->invoice;
                    } else if ($s->url == 'jual') {
                        $msg = '<i class="fa-solid fa-fw fa-gift"></i> Orderan Dokter | ' . $s->invoice;
                        $par_url = 'Transaksi/form_barang_out/emr/' . $s->invoice;
                    } else {
                        $msg = ' None';
                        $par_url = '';
                    } ?>
                    <a type="button" href="<?= site_url($par_url) ?>" style="text-decoration: none; margin-bottom: 15px; color: #4c4c4c;">
                        <span class="font-weight-bold pl-3"><?= $msg ?></span>
                        <span class="float-right pr-3"><i class="fa-solid fa-chevron-right"></i></span>
                    </a>
                    <hr>
                <?php
                endforeach;
            else : ?>
                <span class="text-center" style="color: grey; margin-bottom: 10px; width: 100%;">Tidak Ada Notifikasi</span>
            <?php
            endif;
            echo '</a>';
        }

        // pesan
        public function body_psn()
        {
            $people = $this->M_global->getResult('user');
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row p-2">
                        <div class="col-md-4">
                            <div class="card shadow" style="height: 67vh; width: 9vw; position: fixed; background-color: #272a3f; color: white;">
                                <div class="card-body">
                                    <span class="text-center font-weight-bold">DAFTAR KONTAK</span>
                                    <hr style="background-color: white;">
                                    <?php
                                    $no_kontak = 1;
                                    foreach ($people as $p) :
                                    ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span><?= $p->nama ?></span>
                                            </div>
                                            <div class="col-md-12" style="margin-bottom: -5px; margin-top: -5px;">
                                                <span style="font-size: 8px; float: right">
                                                    <?= $this->M_global->getData('m_role', ['kode_role' => $p->kode_role])->keterangan ?>
                                                </span>
                                            </div>
                                        </div>
                                        <hr style="background-color: white;">
                                    <?php
                                        $no_kontak++;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row" style="height: 55vh;">
                                <div class="col-md-12">
                                    <div class="row mb-1">
                                        <label for="">Kepada</label>
                                        <div class="col-md-12">
                                            <textarea name="ke" id="ke" class="w-80" rows="2" style="border-radius: 10px;">Kepada</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-12">
                                            <div class="float-right">
                                                <textarea name="dari" id="dari" class="w-80" rows="2" style="border-radius: 10px;">Saya</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-10">
                                    <textarea name="pesanku" id="pesanku" class="form-control pesanku-no-border"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success w-100 h-100 paper-plane-no-border"><i class="fa-solid fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <?php
        }

        // regist page
        public function regist()
        {
            // website config
            $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
            $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

            $parameter = [
                'judul'         => 'Gabung Sekarang',
                'nama_apps'     => $web_setting->nama,
                'web_version'   => $web_version->version,
                'web'           => $web_setting,
            ];

            $this->template->load('Template/Auth', 'Auth/Regist', $parameter);
        }

        // cek email
        public function cek_email()
        {
            $email = $this->input->get('email');

            // cek email di table user
            $cek = $this->M_auth->jumRow('user', ['email' => $email]);
            if ($cek) {
                $cek = $cek;
            } else {
                $cek = $this->M_auth->jumRow('member', ['email' => $email]);
            }

            if ($cek < 1) { // jika tidak ada/ kurang dari 1
                // kirimkan status 1
                echo json_encode(['status' => 1]);
            } else { // selain itu
                // kirimkan status 0
                echo json_encode(['status' => 0]);
            }
        }

        // validasi email
        public function validasi_email($param, $email, $isi, $token)
        {
            // cek email di table member_token
            $cek_member_token = $this->M_auth->jumRow("member_token", ["email" => $email]);

            if ($cek_member_token < 1) { // jika tidak ada/ kurang dari 1
                // tambahkan token baru dan email ke table member_token
                $this->M_auth->insert("member_token", $isi);
            } else { // selain itu
                // update token baru dan email ke table member_token
                $this->M_auth->update("member_token", $isi, ["email" => $email]);
                $this->M_auth->update("user_token", $isi, ["email" => $email]);
            }

            // cek parameter dari view
            if ($param == 1) { // jika parameternya 1
                // maka notifikasi "penambahan akun"
                $judul = "KODE DAFTAR AKUN APOTEK *MYHERS";
                $pesan = 'Kode untuk daftar akun adalah : <br><b style="font-size: 24px">"' . $token . '"</b>';
            } else { // selain itu
                // maka notifikasi "atur ulang sandi"
                $judul = "KODE ATUR ULANG SANDI AKUN APOTEK *MYHERS";
                $pesan = 'Kode untuk atur ulang sandi akun adalah : <br><b style="font-size: 24px">"' . $token . '"</b>';
            }

            // kirimkan token via email
            _sendMail($email, $judul, $pesan);
        }

        // mendapatkan kode baru secara acak
        public function sendCode($param) // param merupakan lemparan dari view
        {
            // ambil value email dari get url
            $email = $this->input->get("email");

            // buat token random 6 digit
            $token = random_int(100000, 999999);

            // tampung value ke sebuah variable isi
            $isi = [
                'email' => $email,
                'token' => $token,
                'valid' => 0,
            ];

            // jika email tidak ada/ kurang dari 1 di table member
            if ($this->M_auth->jumRow("member", ["email" => $email]) < 1) {
                // jalankan fungsi validasi email
                $this->validasi_email($param, $email, $isi, $token);
            } else { // selain itu
                if ($param > 1) { // jika parameternya lebih dari 1
                    // jalankan fungsi validasi email
                    $this->validasi_email($param, $email, $isi, $token);
                } else { // selain itu
                    // kirimkan status 3 dan email ke view
                    echo json_encode(["status" => 3, "email" => $email]);
                }
            }
        }

        // proses registrasi akun
        public function regist_proses()
        {
            // variable
            $nama           = htmlspecialchars($this->input->post("nama"));
            $email          = htmlspecialchars($this->input->post("email"));
            $nohp           = htmlspecialchars($this->input->post("nohp"));
            $password       = htmlspecialchars($this->input->post("password"));
            $kode           = htmlspecialchars($this->input->post("kode"));
            $jkel           = htmlspecialchars($this->input->post("jkel"));
            // ambil kode member berdasarkan nama awal dan 5 digit kedepan secara berurutan
            $kode_member    = _codeMember($nama);

            if ($jkel == 'P') { // jika gender laki-laki
                // fotonya pria
                $foto = 'pria.png';
            } else { // selain itu
                // fotonya wanita
                $foto = 'wanita.png';
            }


            // cek email di table member_token
            $cek_member_token = $this->M_auth->jumRow("member_token", ["email" => $email]);

            if ($cek_member_token > 0) { // jika cek ada/ lebih dari 0
                if ($this->M_auth->jumRow("member", ["email" => $email]) < 1) { // jika email tidak ada di table member
                    if ($this->M_auth->getRow("member_token", ["email" => $email])->token == $kode) { // jika token yang dimasukan sama dengan token yang tersimpan di table member_token

                        // tampung value ke variable
                        $isi = [
                            'nama'          => $nama,
                            'email'         => $email,
                            'nohp'          => $nohp,
                            'password'      => md5($password),
                            'secondpass'    => $password,
                            'actived'       => 1,
                            'joined'        => date('Y-m-d H:i:s'),
                            'kode_role'     => 'R0005',
                            'kode_member'   => $kode_member,
                            'jkel'          => $jkel,
                            'foto'          => $foto,
                            'on_off'        => 0,
                        ];

                        // simpan value ke table user
                        $this->M_auth->insert("member", $isi);

                        // update valid menjadi 1 berdasarkan email di table member_token
                        $this->M_auth->update("member_token", ["valid" => 1], ["email" => $email]);

                        // kirimkan status 1 ke view
                        echo json_encode(["status" => 1]);
                    } else { // selain itu
                        // kirimkan status 2 ke view
                        echo json_encode(["status" => 2]);
                    }
                } else { // selain itu
                    // kirimkan sttus 3 ke view
                    echo json_encode(["status" => 3]);
                }
            } else { // selain itu 
                // kirimkan status 4 ke view
                echo json_encode(["status" => 4]);
            }
        }

        // fungsi cek user role
        public function cekRole()
        {
            // variable email
            $email    = $this->input->get('email');

            // ambil data user berdasarkan email
            $user     = $this->M_global->getData('user', ['email' => $email]);

            if ($user) {
                $user = $user;
            } else {
                $user = $this->M_global->getData('member', ['email' => $email]);
            }

            if ($user->kode_role == 'R0005') { // jika kode role adalah member
                // kirimkan status 1 ke view
                echo json_encode(['status' => 1, 'kode_role' => $user->kode_role]);
            } else { // selain itu
                // kirimkan status 0 ke view
                echo json_encode(['status' => 0, 'kode_role' => $user->kode_role]);
            }
        }

        // proses login/ masuk ke dalam sistem
        public function login_proses()
        {
            // variable
            $email        = htmlspecialchars($this->input->post("email"));
            $password     = htmlspecialchars($this->input->post("password"));
            $shift        = htmlspecialchars($this->input->post("shift"));
            $cabang       = htmlspecialchars($this->input->post("cabang"));

            // ambil data user berdasarkan email
            $cek          = $this->M_auth->getRow('user', ['email' => $email]);

            // cek ada usernya atau tidak
            if (empty($cek)) { // jika tidak ada
                $this->login_member($email, $password, $shift, $cabang);
            } else { // jika ada
                $this->login_user($email, $password, $shift, $cabang);
            }
        }

        // login member
        public function login_member($email, $password, $shift, $cabang)
        {
            // cek email di table member
            $cek_member = $this->M_auth->jumRow("member", ["email" => $email]);

            if ($cek_member < 1) { // jika email tidak ada/ kurang dari 1
                // kirimkan status 2 ke view
                echo json_encode(["status" => 2]);
            } else { // selain itu
                // ambil data email yang ada di member berdasarkan email, kemudian tampung ke variable $member
                $member = $this->M_auth->getRow("member", ["email" => $email]);

                if ($member->actived > 0) { // jika status akun aktif
                    if (md5($password) == $member->password) { // jika password yang di masukan sama dengan password yang ada pada $member

                        // update status on_off
                        $this->M_global->updateData('member', ['on_off' => 1], ['email' => $email]);

                        // cabang
                        $init_cabang = $this->M_global->getData('cabang', ['kode_cabang' => $cabang])->inisial_cabang;

                        // tampung value ke variable
                        $isi_session = [
                            'kode_member'   => $member->kode_member,
                            'nama'          => $member->nama,
                            'email'         => $member->email,
                            'kode_role'     => $member->kode_role,
                            'shift'         => $shift,
                            'cabang'        => $cabang,
                            'init_cabang'   => $init_cabang,
                        ];

                        // buatkan session baru untuk masuk ke sistem
                        $this->session->set_userdata($isi_session);

                        // kirimkan status 1 ke view
                        echo json_encode(["status" => 1, 'kode_role' => $member->kode_role]);
                    } else { // selain itu
                        // kirimkan status 3 ke view
                        echo json_encode(["status" => 3]);
                    }
                } else { // selain itu
                    // kirimkan status 4 ke view
                    echo json_encode(["status" => 4]);
                }
            }
        }

        // login user
        public function login_user($email, $password, $shift, $cabang)
        {
            // cek email di table user
            $cek_user   = $this->M_auth->jumRow("user", ["email" => $email]);

            $date       = date("Y-m-d");
            $jam        = date("H:i:s");

            if ($cek_user < 1) { // jika email tidak ada/ kurang dari 1
                // kirimkan status 2 ke view
                echo json_encode(["status" => 2]);
            } else { // selain itu
                // ambil data email yang ada di user berdasarkan email, kemudian tampung ke variable $user
                $user = $this->M_auth->getRow("user", ["email" => $email]);

                if ($user->actived > 0) { // jika status akun aktif
                    if (md5($password) == $user->password) { // jika password yang di masukan sama dengan password yang ada pada $user

                        // update status on_off
                        $this->M_global->updateData('user', ['on_off' => 1], ['email' => $email]);

                        // cabang
                        $init_cabang = $this->M_global->getData('cabang', ['kode_cabang' => $cabang])->inisial_cabang;

                        // tampung value ke variable
                        $isi_session = [
                            'kode_user'     => $user->kode_user,
                            'nama'          => $user->nama,
                            'email'         => $user->email,
                            'kode_role'     => $user->kode_role,
                            'shift'         => $shift,
                            'cabang'        => $cabang,
                            'init_cabang'   => $init_cabang,
                            'kode_cabang'   => $cabang,
                            'web_id'        => 1,
                        ];

                        // buatkan session baru untuk masuk ke sistem
                        $this->session->set_userdata($isi_session);

                        // aktifitas user
                        $cek_log = $this->db->query("SELECT * FROM activity_log WHERE kode = '$email'")->num_rows();
                        if ($cek_log > 0) {
                            $this->db->query("UPDATE activity_log SET tgl_masuk = '$date', jam_masuk = '$jam' WHERE kode = '$email'");
                        } else {
                            $data_pesan = [
                                'kode'          => $email,
                                'isi'           => "Login / Logout",
                                'tgl_masuk'     => $date,
                                'jam_masuk'     => $jam,
                            ];
                            $this->db->insert("activity_log", $data_pesan);
                        }

                        $aktifitas = [
                            'email'         => $email,
                            'kegiatan'      => $email . " <b>Masuk Sistem</b>",
                            'menu'          => "Login",
                            'waktu'         => date('Y-m-d H:i:s'),
                            'kode_cabang'   => $init_cabang,
                            'shift'         => $shift,
                        ];

                        $this->db->insert("activity_user", $aktifitas);
                        $this->db->query("UPDATE user SET on_off = '1' WHERE email = '$email'");

                        // kirimkan status 1 ke view
                        echo json_encode(["status" => 1, 'kode_role' => $user->kode_role]);
                    } else { // selain itu
                        // kirimkan status 3 ke view
                        echo json_encode(["status" => 3]);
                    }
                } else { // selain itu
                    // kirimkan status 4 ke view
                    echo json_encode(["status" => 4]);
                }
            }
        }

        // atur ulang sandi page
        public function repass()
        {
            // website config
            $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
            $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

            $parameter = [
                'judul'             => 'Atur Ulang Sandi',
                'nama_apps'         => $web_setting->nama,
                'web_version'       => $web_version->version,
                'web_version_all'   => $web_version,
                'web'               => $web_setting,
            ];

            $this->template->load('Template/Auth', 'Auth/Repass', $parameter);
        }

        // fungsi untuk atur ulang sandi
        public function atur_sandi()
        {
            // variable
            $email    = $this->input->post('email');
            $password = $this->input->post('password');
            $kode     = $this->input->post('kode');

            // ambil token yang ada pada user dengan email di table user_token
            $token    = $this->M_auth->getRow('user_token', ['email' => $email])->token;

            if ($token == $kode) { // jika token sesuai dengan kode yang di masukan
                // lakukan proses
                $cek = [
                    $this->M_auth->update('user', ['password' => md5($password), 'secondpass' => $password], ['email' => $email]), // update password (dengan md5) dan secondpass berdasarkan email
                    $this->M_auth->update('user_token', ['valid' => 1], ['email' => $email]), // update valid menjadi 1 berdasarkan email
                ];

                if ($cek) { // jika proses cek berjalan
                    // kirimkan status 1 ke view
                    echo json_encode(['status' => 1]);
                } else { // selain itu
                    // kirimkan status 0 ke view
                    echo json_encode(['status' => 0]);
                }
            } else { // selain itu
                // kirimkan status 0 ke view
                echo json_encode(['status' => 0]);
            }
        }

        // fungsi keluar sistem
        public function logout()
        {
            $date           = date('Y-m-d');
            $jam            = date('H:i:s');

            // session
            $sess           = $this->session->userdata('email');
            $init_cabang    = $this->session->userdata('init_cabang');
            $shift          = $this->session->userdata('shift');

            $aktifitas = [
                'email'         => $sess,
                'kegiatan'      => $sess . " <b>Meninggalkan Sistem</b>",
                'menu'          => "Logout",
                'waktu'         => date('Y-m-d H:i:s'),
                'kode_cabang'   => $init_cabang,
                'shift'         => $shift,
            ];

            // cek user/ member
            $cek = [
                $this->db->insert("activity_user", $aktifitas),
                $this->db->query("UPDATE activity_log SET tgl_keluar = '$date', jam_keluar = '$jam' WHERE kode = '$sess'"),
                $this->M_global->jumDataRow('user', ['email' => $sess]),
            ];

            if ($cek > 0) { // jika ini user
                // update user on_off
                $this->M_global->updateData('user', ['on_off' => 0], ['email' => $sess]);
            } else { // selain itu
                // update member on_off
                $this->M_global->updateData('member', ['on_off' => 0], ['email' => $sess]);
            }

            // hancurkan session
            $this->session->sess_destroy();

            // arahkan ke auth
            redirect('Auth');
        }

        public function ganti_shift()
        {
            $email    = $this->session->userdata('email');
            $shift    = $this->input->get('shift');
            $password = $this->input->get('password');

            // ambil data user berdasarkan email
            $cek      = $this->M_global->getData('user', ['email' => $email]);

            // cek password
            if ($cek->password == md5($password)) { // jika password sesuai
                aktifitas_user('Shift', 'mengubah shift ' . $this->session->userdata('shift') . ' ke shift ' . $shift, 'Cabang: ' . $this->session->userdata('cabang'), '');

                $this->session->unset_userdata('shift');
                $this->session->set_userdata('shift', $shift);

                echo json_encode(['status' => 1]);
            } else { // jika tidak sesuai
                echo json_encode(['status' => 0]);
            }
        }

        // fungsi kirim email
        public function email()
        {
            $email = $this->input->get('email');
            $judul = $this->input->get('param');

            $attched_file    = $_SERVER["DOCUMENT_ROOT"] . '/first_apps/assets/file/pdf/' . $judul . '.pdf';

            $ready_message   = "";
            $ready_message   .= "<table border=0>
                <tr>
                    <td style='width: 30%;'>Judul</td>
                    <td style='width: 10%;'> : </td>
                    <td style='width: 60%;'> $judul </td>
                </tr>
                <tr>
                    <td style='width: 30%;'>Tgl/Jam</td>
                    <td style='width: 10%;'> : </td>
                    <td style='width: 60%;'>" . date('d-m-Y') . " / " . date('H:i:s') . "</td>
                </tr>
                <tr>
                    <td style='width: 30%;'>Pengirim</td>
                    <td style='width: 10%;'> : </td>
                    <td style='width: 60%;'>" . $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama . "</td>
                </tr>
            </table>";

            if ($judul == 'Report Wilayah Kecamatan') {
                $this->email->send_my_email($email, $judul, $ready_message, $attched_file);
                echo json_encode(["status" => 1]);
            } else {
                if ($this->email->send_my_email($email, $judul, $ready_message, $attched_file)) {
                    echo json_encode(["status" => 1]);
                } else {
                    echo json_encode(["status" => 0]);
                }
            }
        }
    }
