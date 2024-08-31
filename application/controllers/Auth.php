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
        $nama       = htmlspecialchars($this->input->post("nama"));
        $email      = htmlspecialchars($this->input->post("email"));
        $nohp       = htmlspecialchars($this->input->post("nohp"));
        $password   = htmlspecialchars($this->input->post("password"));
        $kode       = htmlspecialchars($this->input->post("kode"));
        $jkel       = htmlspecialchars($this->input->post("jkel"));

        if ($jkel == 'P') { // jika gender laki-laki
            // fotonya pria
            $foto = 'pria.png';
        } else { // selain itu
            // fotonya wanita
            $foto = 'wanita.png';
        }

        // ambil kode member berdasarkan nama awal dan 5 digit kedepan secara berurutan
        $kode_member  = _codeMember($nama);

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
        $email = $this->input->get('email');

        // ambil data user berdasarkan email
        $user = $this->M_global->getData('user', ['email' => $email]);

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

        $cek          = $this->M_auth->getRow('user', ['email' => $email]);

        if (empty($cek)) {
            $this->login_member($email, $password, $shift, $cabang);
        } else {
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
        $cek_user = $this->M_auth->jumRow("user", ["email" => $email]);

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
                    ];

                    // buatkan session baru untuk masuk ke sistem
                    $this->session->set_userdata($isi_session);

                    // aktifitas user
                    $cek_log = $this->db->query("SELECT * FROM activity_log WHERE kode = '$email'")->num_rows();
                    if ($cek_log > 0) {
                        $this->db->query("UPDATE activity_log SET tgl_masuk = '$date', jam_masuk = '$jam' WHERE kode = '$email'");
                    } else {
                        $data_pesan = [
                            'kode'      => $email,
                            'isi'       => "Login / Logout",
                            'tgl_masuk' => $date,
                            'jam_masuk' => $jam,
                        ];
                        $this->db->insert("activity_log", $data_pesan);
                    }

                    $aktifitas = [
                        'email'     => $email,
                        'kegiatan'  => $email . " masuk di Cabang " . $init_cabang . ', Shift: ' . $shift,
                        'menu'      => "Login",
                        'waktu'     => date('Y-m-d H:i:s'),
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
            'judul'         => 'Atur Ulang Sandi',
            'nama_apps'     => $web_setting->nama,
            'web_version'   => $web_version->version,
            'web'           => $web_setting,
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
        $token = $this->M_auth->getRow('user_token', ['email' => $email])->token;

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
        // session
        $sess = $this->session->userdata('email');
        $init_cabang = $this->session->userdata('init_cabang');
        $shift = $this->session->userdata('shift');

        $aktifitas = [
            'email'     => $sess,
            'kegiatan'  => $sess . " keluar di Cabang " . $init_cabang . ', Shift: ' . $shift,
            'menu'      => "Logout",
            'waktu'     => date('Y-m-d H:i:s'),
        ];

        $this->db->insert("activity_user", $aktifitas);

        // cek user/ member
        $cek = $this->M_global->jumDataRow('user', ['email' => $sess]);

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

    // celan db
    public function clean_db()
    {
        $sintak = [
            $this->db->query("TRUNCATE TABLE barang_in_header"),
            $this->db->query("TRUNCATE TABLE barang_in_detail"),
            $this->db->query("TRUNCATE TABLE barang_in_retur_header"),
            $this->db->query("TRUNCATE TABLE barang_in_retur_detail"),
            $this->db->query("TRUNCATE TABLE barang_out_header"),
            $this->db->query("TRUNCATE TABLE barang_out_detail"),
            $this->db->query("TRUNCATE TABLE barang_out_retur_header"),
            $this->db->query("TRUNCATE TABLE barang_out_retur_detail"),
            $this->db->query("TRUNCATE TABLE barang_stok"),
            $this->db->query("TRUNCATE TABLE bayar_card_detail"),
            $this->db->query("TRUNCATE TABLE bayar_um_card_detail"),
            $this->db->query("TRUNCATE TABLE cart_header"),
            $this->db->query("TRUNCATE TABLE cart_detail"),
            $this->db->query("TRUNCATE TABLE cart_promo"),
            $this->db->query("TRUNCATE TABLE pembayaran"),
            $this->db->query("TRUNCATE TABLE pembayaran_uangmuka"),
            $this->db->query("TRUNCATE TABLE pendaftaran"),
            $this->db->query("TRUNCATE TABLE uang_muka"),
        ];

        if ($sintak) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }
}
