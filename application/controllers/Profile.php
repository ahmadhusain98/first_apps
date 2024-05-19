<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada
            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            if ($user) {
                $user = $user;
            } else {
                $user = $this->M_global->getData("member", ["email" => $this->session->userdata("email")]);
            }

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

        $cek         = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')]);

        if ($cek) {
            $data = $cek;
        } else {
            $data = $this->db->query("SELECT m.*, m.kode_member AS kode_user FROM member m WHERE m.kode_member = '" . $this->session->userdata('kode_user') . "'");
        }

        $parameter = [
            $this->data,
            'judul'         => 'Profile',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Akun Pengguna',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'data_user'     => $data,
        ];

        $this->template->load('Template/Content', 'Pengaturan/Profile', $parameter);
    }

    // fungsi nonaktifkan akun
    public function nonaktif($kode_user)
    {
        // jalankan fungsi nonaktif akun
        $cek = $this->M_global->updateData('user', ['actived' => 0], ['kode_user' => $kode_user]);

        if ($cek) { // jika fungsi berjalan
            // kembalikan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kembalikan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi update akun user
    public function updateAkun($kode_user)
    {
        // variable
        $nama         = $this->input->post('nama');
        $email        = $this->input->post('email');
        $jkel         = $this->input->post('jkel');
        $secondpass   = $this->input->post('secondpass');
        $password     = md5($secondpass);

        // configurasi upload file
        $config['upload_path']    = 'assets/user/';
        $config['allowed_types']  = 'jpg|png|jpeg';
        $config['max_size']       = '2048';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($_FILES['filefoto']['name']) { // jika file didapatkan nama filenya
            // upload file
            $this->upload->do_upload('filefoto');

            // ambil namanya berdasarkan nama file upload
            $gambar = $this->upload->data('file_name');
        } else { // selain itu
            $cek_user = $this->M_global->getData('user', ['email' => $email]);

            if ($cek_user) {
                if ($cek_user->foto == '' || $cek_user->foto == null) {
                    // beri nilai default
                    if ($jkel == 'P') { // jika pria
                        $gambar = 'pria.png';
                    } else { // selain itu
                        $gambar = 'wanita.png';
                    }
                } else {
                    $gambar = $cek_user->foto;
                }
            } else {
                $cek_member = $this->M_global->getData('member', ['email' => $email]);

                if ($cek_member) {
                    if ($cek_member->foto == '' || $cek_member->foto == null) {
                        // beri nilai default
                        if ($jkel == 'P') { // jika pria
                            $gambar = 'pria.png';
                        } else { // selain itu
                            $gambar = 'wanita.png';
                        }
                    } else {
                        $cek_member->foto;
                    }
                    $gambar = $cek_member->foto;
                } else {
                    // beri nilai default
                    if ($jkel == 'P') { // jika pria
                        $gambar = 'pria.png';
                    } else { // selain itu
                        $gambar = 'wanita.png';
                    }
                }
            }
        }

        // masukan variable ke dalam variable $isi untuk di update
        $isi = [
            'nama'          => $nama,
            'email'         => $email,
            'jkel'          => $jkel,
            'secondpass'    => $secondpass,
            'password'      => $password,
            'foto'          => $gambar,
        ];

        // jalankan fungsi update berdasarkan id
        $cek = $this->M_global->updateData('user', $isi, ['kode_user' => $kode_user]);

        if ($cek) { // jika proses berhasil beri nilai 1
            echo json_encode(['status' => 1]);
        } else { // selain itu beri nilai 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi update akun member
    public function updateAkunMember($kode_member)
    {
        // variable
        $nama         = $this->input->post('nama');
        $email        = $this->input->post('email');
        $jkel         = $this->input->post('jkel');
        $secondpass   = $this->input->post('secondpass');
        $password     = md5($secondpass);

        // configurasi upload file
        $config['upload_path']    = 'assets/member/';
        $config['allowed_types']  = 'jpg|png|jpeg';
        $config['max_size']       = '2048';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($_FILES['filefoto']['name']) { // jika file didapatkan nama filenya
            // upload file
            $this->upload->do_upload('filefoto');

            // ambil namanya berdasarkan nama file upload
            $gambar = $this->upload->data('file_name');
        } else { // selain itu
            $cek_member = $this->M_global->getData('member', ['email' => $email]);

            if ($cek_member) {
                if ($cek_member->foto == '' || $cek_member->foto == null) {
                    // beri nilai default
                    if ($jkel == 'P') { // jika pria
                        $gambar = 'pria.png';
                    } else { // selain itu
                        $gambar = 'wanita.png';
                    }
                } else {
                    $gambar = $cek_member->foto;
                }
            } else {
                $cek_member = $this->M_global->getData('member', ['email' => $email]);

                if ($cek_member) {
                    if ($cek_member->foto == '' || $cek_member->foto == null) {
                        // beri nilai default
                        if ($jkel == 'P') { // jika pria
                            $gambar = 'pria.png';
                        } else { // selain itu
                            $gambar = 'wanita.png';
                        }
                    } else {
                        $cek_member->foto;
                    }
                    $gambar = $cek_member->foto;
                } else {
                    // beri nilai default
                    if ($jkel == 'P') { // jika pria
                        $gambar = 'pria.png';
                    } else { // selain itu
                        $gambar = 'wanita.png';
                    }
                }
            }
        }

        // masukan variable ke dalam variable $isi untuk di update
        $isi = [
            'nama'          => $nama,
            'email'         => $email,
            'jkel'          => $jkel,
            'secondpass'    => $secondpass,
            'password'      => $password,
            'foto'          => $gambar,
        ];

        // jalankan fungsi update berdasarkan id
        $cek = $this->M_global->updateData('member', $isi, ['kode_member' => $kode_member]);

        if ($cek) { // jika proses berhasil beri nilai 1
            echo json_encode(['status' => 1]);
        } else { // selain itu beri nilai 0
            echo json_encode(['status' => 0]);
        }
    }

    // profile member page
    public function profile_member()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Profile',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Akun Pengguna',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'data_user'     => $this->db->query("SELECT m.* FROM member m WHERE m.kode_member = '" . $this->session->userdata('kode_member') . "'")->row(),
        ];

        $this->template->load('Template/App', 'Pengaturan/Profile_member', $parameter);
    }
}
