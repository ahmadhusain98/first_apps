<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting_apps extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada

            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Setting_apps'])->id;

            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            $cek_akses_menu = $this->M_global->getData('akses_menu', ['id_menu' => $id_menu, 'kode_role' => $user->kode_role]);
            if ($cek_akses_menu) {
                // tampung data ke variable data public
                $this->data = [
                    'nama'      => $user->nama,
                    'email'     => $user->email,
                    'kode_role' => $user->kode_role,
                    'actived'   => $user->actived,
                    'foto'      => $user->foto,
                    'shift'     => $this->session->userdata('shift'),
                    'menu'      => 'Setting_apps',
                ];
            } else {
                // kirimkan kembali ke Auth
                redirect('Where');
            }
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
            'judul'         => 'Pengaturan Web',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pengaturan Web',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
        ];

        $this->template->load('Template/Content', 'Setting/Web', $parameter);
    }

    // fungsi update profile website
    public function proses()
    {
        // variable
        $id                       = $this->input->post('id_web');
        $nohp                     = $this->input->post('nohp_web');
        $email                    = $this->input->post('email_web');
        $kode_email               = $this->input->post('kode_email');
        $nama                     = $this->input->post('nama_web');
        $bg_theme                 = $this->input->post('bg_theme');
        $alamat                   = $this->input->post('alamat_web');

        // configurasi upload file
        $config['upload_path']    = 'assets/img/web/';
        $config['allowed_types']  = 'jpg|png|jpeg';
        $config['max_size']       = '2048';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $web = $this->M_global->getData('web_setting', ['id' => 1]);

        if ($_FILES['filefoto']['name']) { // jika file didapatkan nama filenya
            // upload file
            $this->upload->do_upload('filefoto');

            // ambil namanya berdasarkan nama file upload
            $gambar = $this->upload->data('file_name');
        } else { // selain itu
            // beri nilai default
            if ($web->logo == 'AdminLTELogo.png') {
                $gambar = 'AdminLTELogo.png';
            } else {
                $gambar = $web->logo;
            }
        }

        if ($_FILES['watermark']['name']) { // jika file didapatkan nama filenya
            // upload file
            $this->upload->do_upload('watermark');

            // ambil namanya berdasarkan nama file upload
            $theme = $this->upload->data('file_name');
        } else { // selain itu
            // beri nilai default
            if ($web->watermark == 'My_Logo_4_2.png') {
                $theme = 'My_Logo_4_2.png';
            } else {
                $theme = $web->watermark;
            }
        }

        // masukan variable ke dalam variable $isi untuk di update
        $isi = [
            'nama'          => $nama,
            'email'         => $email,
            'kode_email'    => $kode_email,
            'nohp'          => $nohp,
            'alamat'        => $alamat,
            'logo'          => $gambar,
            'bg_theme'      => $bg_theme,
            'watermark'     => $theme,
        ];

        // jalankan fungsi update berdasarkan id
        $cek = $this->M_global->updateData('web_setting', $isi, ['id' => $id]);

        if ($cek) { // jika proses berhasil beri nilai 1
            echo json_encode(['status' => 1]);
        } else { // selain itu beri nilai 0
            echo json_encode(['status' => 0]);
        }
    }
}
