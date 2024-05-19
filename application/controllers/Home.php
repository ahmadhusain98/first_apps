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
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'             => 'Selamat Datang',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Beranda',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'kunjungan_poli'    => $this->db->query("SELECT p.keterangan AS poli, COUNT(boh.kode_poli) AS jumlah FROM pembayaran buy JOIN barang_out_header boh ON buy.inv_jual = boh.invoice JOIN m_poli p ON boh.kode_poli = p.kode_poli GROUP BY boh.kode_poli")->result(),
            'jumlah_beli'       => count($this->M_global->getResult('barang_out_header')),
            'jumlah_member'     => count($this->M_global->getResult('member')),
        ];

        $this->template->load('Template/Content', 'Home/Dashboard', $parameter);
    }
}
