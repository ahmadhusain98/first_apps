<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        $this->db->query("SET SESSION sql_mode = REPLACE(
            REPLACE(
                REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY,', ''),
            ',ONLY_FULL_GROUP_BY', ''),
        'ONLY_FULL_GROUP_BY', '')");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada
            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            // tampung data ke variable data public
            $this->data = [
                'nama'      => $user->nama,
                'email'     => $user->email,
                'kode_role' => $user->kode_role,
                'actived'   => $user->actived,
                'foto'      => $user->foto,
                'shift'     => $this->session->userdata('shift'),
                'menu'      => 'Home',
            ];
        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    // home page
    public function index()
    {
        $sess_cabang = $this->session->userdata('cabang');
        $sess_web = $this->session->userdata('web_id');

        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $sess_web]);

        $now = date('Y-m-d');

        $header_out = $this->db->query("SELECT * FROM barang_out_header WHERE kode_cabang = '$sess_cabang' AND tgl_jual LIKE '%$now%' AND status_jual = 1")->result();
        $header_bayar = $this->db->query("SELECT * FROM pembayaran WHERE kode_cabang = '$sess_cabang' AND tgl_pembayaran LIKE '%$now%' AND approved = 1")->result();
        $header_daftar = $this->db->query("SELECT * FROM pendaftaran WHERE kode_cabang = '$sess_cabang' AND tgl_daftar LIKE '%$now%' AND status_trx != 2")->result();

        $saldo_utama = $this->M_global->getData('kas_utama', ['kode_cabang' => $sess_cabang]);
        $saldo_second = $this->db->query("SELECT SUM(sisa) AS saldo FROM kas_second WHERE kode_cabang = '$sess_cabang'")->row();

        $saldo = ((!empty($saldo_utama)) ? $saldo_utama->sisa : 0) + ((!empty($saldo_second)) ? $saldo_second->saldo : 0);

        $parameter = [
            $this->data,
            'judul'             => 'Selamat Datang',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Beranda',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'kunjungan_poli'    => $this->db->query("SELECT p.keterangan AS poli, COUNT(boh.kode_poli) AS jumlah FROM pembayaran buy JOIN barang_out_header boh ON buy.inv_jual = boh.invoice JOIN m_poli p ON boh.kode_poli = p.kode_poli WHERE buy.kode_cabang = '$sess_cabang' AND buy.tgl_pembayaran LIKE '%$now%' AND buy.approved = 1 GROUP BY boh.kode_poli")->result(),
            'jumlah_beli'       => count($header_out),
            'jumlah_bayar'      => count($header_bayar),
            'saldo_kas'         => $saldo,
            'jumlah_daftar'     => count($header_daftar),
            'hutang'            => $this->db->query("SELECT SUM(jumlah) AS hutang FROM piutang WHERE kode_cabang = '$sess_cabang' AND jumlah > 0")->row(),
            'piutang'           => $this->db->query("SELECT SUM(jumlah) AS piutang FROM piutang WHERE kode_cabang = '$sess_cabang' AND jumlah < 0")->row(),
        ];

        $this->template->load('Template/Content', 'Home/Dashboard', $parameter);
    }
}
