<?php

use PharIo\Manifest\Url;

defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller
{
    // variable open public untuk controller Member
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

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
            'judul'         => 'Member',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Daftar',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
        ];

        $this->template->load('Template/Content', 'Member/Daftar', $parameter);
    }

    public function getProvinsi($kode_provinsi)
    {
        $prov = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $kode_provinsi]);

        echo json_encode(["provinsi" => $prov->provinsi]);
    }
}
