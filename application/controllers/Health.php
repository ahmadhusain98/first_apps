<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Health extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada
            
            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Health'])->id;

            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            $cek_akses_menu = $this->M_global->getData('akses_menu', ['id_menu' => $id_menu, 'kode_role' => $user->kode_role]);
            if($cek_akses_menu) {
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
            } else {
                // kirimkan kembali ke Auth
                redirect('Home');
            }

        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    // daftar page
    public function daftar()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Healt Management',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pendaftaran',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Health/daftar_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Pendaftaran/Daftar', $parameter);
    }

    // fungsi list member
    public function daftar_list($param1 = 'R0005')
    {
        // parameter untuk list table
        $table            = 'member';
        $colum            = ['id', 'kode_member', 'nama', 'email', 'password', 'secondpass', 'jkel', 'foto', 'kode_role', 'actived', 'joined', 'on_off', 'nohp', 'tmp_lahir', 'tgl_lahir', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'kodepos', 'nik', 'last_regist', 'status_regist', 'rt', 'rw'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = 'kode_role';

        // kondisi role
        $updated    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        // table server side tampung kedalam variable $list
        $list         = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data         = [];
        $no           = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->on_off < 1) {
                    if ($rd->kode_member == 'U00001') {
                        $upd_diss = 'disabled';
                    } else {
                        $upd_diss = '';
                    }
                } else {
                    $upd_diss = 'disabled';
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->on_off < 1) {
                    if ($rd->kode_member == 'U00001') {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss = '';
                    }
                } else {
                    $del_diss = 'disabled';
                }
            } else {
                $del_diss = 'disabled';
            }

            $prov   = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $rd->provinsi])->provinsi;
            $kab    = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $rd->kabupaten])->kabupaten;
            $kec    = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $rd->kecamatan])->kecamatan;

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_member . (($rd->actived == 1) ? '<br><span class="badge badge-success">Aktif</span>' : '<br><span class="badge badge-dark">Non-aktif</span>');
            $row[]  = $rd->nik;
            $row[]  = $rd->nama . '<br><span class="badge badge-info">' . hitung_umur($rd->tgl_lahir) . '</span>';
            $row[]  = 'Prov. ' . $prov . ',<br>' . $kab . ',<br>Kec. ' . $kec . ',<br>Ds. ' . $rd->desa . ',<br>(POS: ' . $rd->kodepos . '), RT.' . $rd->rt . '/RW.' . $rd->rw;
            $row[]  = $rd->last_regist . (($rd->status_regist == 1) ? '<span class="badge badge-primary float-right">Buka</span>' : '<span class="badge badge-danger float-right">Tutup</span>');

            if ($rd->actived > 0) {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_member . "', 0" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-xmark"></i></button>';
            } else {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_member . "', 1" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-check"></i></button>';
            }

            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_member . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_member . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
                <br>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-primary" onclick="info(' . "'" . $rd->kode_member . "'" . ')"><i class="fa-solid fa-circle-info"></i></button>
                <a type="button" style="margin-bottom: 5px;" target="_blank" class="btn btn-dark" href="' . site_url("Health/print_card/") . $rd->kode_member . '"><i class="fa-solid fa-id-badge"></i></a>
            </div>';

            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables->count_all($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1),
            "recordsFiltered" => $this->M_datatables->count_filtered($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // fungsi cetak kartu member
    function print_card($kode_member)
    {
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;

        $member = $this->M_global->getData('member', ['kode_member' => $kode_member]);

        $prov   = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $member->provinsi])->provinsi;
        $kab    = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $member->kabupaten])->kabupaten;
        $kec    = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $member->kecamatan])->kecamatan;

        $judul = 'Kartu Member ' . $kode_member;
        $filename = $judul;

        $body .= '<table style="width: 100%; font-size: 9px;" cellpadding="2px">';

        $body .= '<tr>
            <td style="width: 23%;">RM</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $kode_member . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">NIK</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $member->nik . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Nama</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $member->nama . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Tmp Lahir</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $member->tmp_lahir . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Tgl Lahir</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $member->tgl_lahir . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Umur</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . hitung_umur($member->tgl_lahir) . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Alamat</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">Prov. ' . $prov . ', ' . $kab . ', Kec.' . $kec . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">&nbsp;</td>
            <td style="width: 2%;">&nbsp;</td>
            <td style="width: 75%;">Ds. ' . $member->desa . ' (' . $member->kodepos . ')</td>
        </tr>
        ';
        $body .= '</table>';

        cetak_pdf_small($judul, $body, 1, $position, $filename, $web_setting);
    }

    // fungsi form daftar
    public function form_daftar($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $member = $this->M_global->getData('member', ['kode_member' => $param]);
        } else {
            $member = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Health Management',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pendaftaran',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'data_member'   => $member,
            'role'          => $this->M_global->getResult('m_role'),
        ];

        $this->template->load('Template/Content', 'Pendaftaran/Form_member', $parameter);
    }

    // fungsi cek nik
    public function cekNik()
    {
        // ambil nik inputan
        $nik = $this->input->post('nik');

        // cek nik pada table member
        $cek = $this->M_global->jumDataRow('member', ['nik' => $nik]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi save/update member
    function member_proses($param)
    {
        $nik                = htmlspecialchars($this->input->post('nik'));
        $nama               = htmlspecialchars($this->input->post('nama'));
        if ($param == 1) {
            $kode_member    = _codeMember($nama);
        } else {
            $kode_member    = htmlspecialchars($this->input->post('kodeMember'));
        }
        $email              = htmlspecialchars($this->input->post('email'));
        $secondpass         = htmlspecialchars($this->input->post('password'));
        $password           = md5($secondpass);
        $provinsi           = htmlspecialchars($this->input->post('provinsi'));
        $kabupaten          = htmlspecialchars($this->input->post('kabupaten'));
        $kecamatan          = htmlspecialchars($this->input->post('kecamatan'));
        $desa               = htmlspecialchars($this->input->post('desa'));
        $kodepos            = htmlspecialchars($this->input->post('kodepos'));
        $nohp               = htmlspecialchars($this->input->post('nohp'));
        $tmp_lahir          = htmlspecialchars($this->input->post('tmp_lahir'));
        $tgl_lahir          = htmlspecialchars($this->input->post('tgl_lahir'));
        $pekerjaan          = htmlspecialchars($this->input->post('pekerjaan'));
        $agama              = htmlspecialchars($this->input->post('agama'));
        $pendidikan         = htmlspecialchars($this->input->post('pendidikan'));
        $rt                 = htmlspecialchars($this->input->post('rt'));
        $rw                 = htmlspecialchars($this->input->post('rw'));
        $jkel               = htmlspecialchars($this->input->post('jkel'));
        if ($jkel == 'P') {
            $foto           = 'pria.png';
        } else {
            $foto           = 'wanit.png';
        }
        $kode_role          = 'R0005';
        $joined             = date('Y-m-d H:i:s');
        $actived            = 1;
        $on_off             = 0;
        $last_regist        = 0;
        $status_regist      = 0;

        if ($param == 1) { // jika parameternya 1

            $isi = [
                'kode_member'   => $kode_member,
                'nama'          => $nama,
                'email'         => $email,
                'password'      => $password,
                'secondpass'    => $secondpass,
                'nohp'          => $nohp,
                'tmp_lahir'     => $tmp_lahir,
                'tgl_lahir'     => $tgl_lahir,
                'pekerjaan'     => $pekerjaan,
                'agama'         => $agama,
                'pendidikan'    => $pendidikan,
                'provinsi'      => $provinsi,
                'kabupaten'     => $kabupaten,
                'kecamatan'     => $kecamatan,
                'desa'          => $desa,
                'kodepos'       => $kodepos,
                'rt'            => $rt,
                'rw'            => $rw,
                'nik'           => $nik,
                'jkel'          => $jkel,
                'foto'          => $foto,
                'kode_role'     => $kode_role,
                'joined'        => $joined,
                'on_off'        => $on_off,
                'last_regist'   => $last_regist,
                'status_regist' => $status_regist,
                'actived'       => $actived,
            ];
            // jalankan fungsi simpan
            $cek = $this->M_global->insertData('member', $isi);
        } else { // selain itu

            $isi = [
                'kode_member'   => $kode_member,
                'nama'          => $nama,
                'email'         => $email,
                'password'      => $password,
                'secondpass'    => $secondpass,
                'nohp'          => $nohp,
                'tmp_lahir'     => $tmp_lahir,
                'tgl_lahir'     => $tgl_lahir,
                'pekerjaan'     => $pekerjaan,
                'agama'         => $agama,
                'pendidikan'    => $pendidikan,
                'provinsi'      => $provinsi,
                'kabupaten'     => $kabupaten,
                'kecamatan'     => $kecamatan,
                'desa'          => $desa,
                'kodepos'       => $kodepos,
                'rt'            => $rt,
                'rw'            => $rw,
                'nik'           => $nik,
                'jkel'          => $jkel,
                'foto'          => $foto,
                'kode_role'     => $kode_role,
                'actived'       => $actived,
            ];
            // jalankan fungsi update
            $cek = $this->M_global->updateData('member', $isi, ['kode_member' => $kode_member]);
        }

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi aktif/nonaktif member
    public function activeddaftar($kode_member, $param)
    {
        // jalankan fungsi update actived member
        $cek = $this->M_global->updateData('member', ['actived' => $param], ['kode_member' => $kode_member]);

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil info member
    public function getInfoMember($kode_member)
    {
        $data = $this->db->query("SELECT m.*, p.keterangan AS pendidikan, pk.keterangan AS pekerjaan, a.keterangan AS agama FROM member m JOIN m_pendidikan p ON m.pendidikan = p.kode_pendidikan JOIN m_pekerjaan pk ON m.pekerjaan = pk.kode_pekerjaan JOIN m_agama a ON m.agama = a.kode_agama WHERE m.kode_member =  '$kode_member'")->row();

        echo json_encode($data);
    }

    // fungsi hapus member
    public function delMember($kode_member)
    {
        // jalankan fungsi hapus member berdasarkan kode_member
        $cek = $this->M_global->delData('member', ['kode_member' => $kode_member]);

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // pendaftaran page
    public function pendaftaran()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Healt Management',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pendaftaran',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Health/pendaftaran_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Pendaftaran/Pendaftaran', $parameter);
    }

    // fungsi list pendaftaran
    public function pendaftaran_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'pendaftaran';
        $colum            = ['id', 'no_trx', 'tgl_daftar', 'jam_daftar', 'kode_member', 'kode_poli', 'kode_dokter', 'no_antrian', 'tgl_keluar', 'jam_keluar', 'status_trx'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = 'kode_poli';
        $kondisi_param1   = 'tgl_daftar';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan   = date('m');
            $tahun   = date('Y');
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan   = date('Y-m-d', strtotime($dat[1]));
            $tahun   = date('Y-m-d', strtotime($dat[2]));
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->status_trx == 2) {
                    $upd_diss = 'disabled';
                } else {
                    if ($rd->status_trx == 1) {
                        $upd_diss = 'disabled';
                    } else {
                        $upd_diss = '';
                    }
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->status_trx == 2) {
                    $del_diss = 'disabled';
                } else {
                    if ($rd->status_trx == 1) {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss = '';
                    }
                }
            } else {
                $del_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->no_trx;
            $row[]  = $rd->kode_member . ' ~ ' . $this->M_global->getData('member', ['kode_member' => $rd->kode_member])->nama;
            $row[]  = date('d/m/Y', strtotime($rd->tgl_daftar)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_daftar));
            $row[]  = '<span class="text-center">' . (($rd->status_trx < 1) ? '-' : (($rd->tgl_keluar == null) ? '' : date('d/m/Y', strtotime($rd->tgl_keluar))) . ' ~ ' . (($rd->jam_keluar == null) ? '' : date('H:i:s', strtotime($rd->jam_keluar)))) . '</>';
            $row[]  = $this->M_global->getData('m_poli', ['kode_poli' => $rd->kode_poli])->keterangan;
            $row[]  = $this->M_global->getData('dokter', ['kode_dokter' => $rd->kode_dokter])->nama;
            $row[]  = '<div class="text-center">' . (($rd->status_trx == 0) ? '<span class="badge badge-success">Buka</span>' : (($rd->status_trx == 2) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-primary">Selesai</span>')) . '</div>';

            if ($rd->status_trx < 1) {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->no_trx . "', 0" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-xmark"></i></button>';
            } else {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" disabled><i class="fa-solid fa-user-check"></i></button>';
            }

            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->no_trx . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->no_trx . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
                <br>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="email(' . "'" . $rd->no_trx . "'" . ')"><i class="fa-solid fa-envelope-open-text"></i></button>
                <a type="button" target="_blank" style="margin-bottom: 5px;" class="btn btn-dark" href="' . site_url("Health/print_pendaftaran/") . $rd->no_trx . '"><i class="fa-solid fa-id-badge"></i></a>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // fungsi kirim email barang in
    public function email($no_trx)
    {
        $email = $this->input->get('email');

        $header = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);

        $judul = 'Pendaftaran ' . $header->no_trx;

        // $attched_file    = base_url() . 'assets/file/pdf/' . $judul . '.pdf';ahmad.ummgl@gmail.com
        $attched_file    = $_SERVER["DOCUMENT_ROOT"] . '/first_apps/assets/file/pdf/' . $judul . '.pdf';

        $ready_message   = "";
        $ready_message   .= "<table border=0>
            <tr>
                <td style='width: 30%;'>No Pendaftaran</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'> $header->no_trx </td>
            </tr>
            <tr>
                <td style='width: 30%;'>Tgl/Jam Daftar</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . date('d-m-Y', strtotime($header->tgl_daftar)) . " / " . date('H:i:s', strtotime($header->jam_daftar)) . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Pembeli</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . $this->M_global->getData('member', ['kode_member' => $header->kode_member])->nama . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Poli/Dokter</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . $this->M_global->getData('m_poli', ['kode_poli' => $header->kode_poli])->keterangan . ' / Dr. ' . $this->M_global->getData('dokter', ['kode_dokter' => $header->kode_dokter])->nama . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Status</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . (($header->status_trx == 0) ? 'Open' : (($header->status_trx == 2) ? 'Cancel' : 'Close')) . " </td>
            </tr>
        </table>";

        $server_subject = $judul;

        if ($this->email->send_my_email($email, $server_subject, $ready_message, $attched_file)) {
            echo json_encode(["status" => 1, 'result' => $attched_file]);
        } else {
            echo json_encode(["status" => 0]);
        }

        // echo json_encode($attched_file);
    }

    // fungsi cetak histori
    public function print_hispas($no_trx)
    {
        $web_setting          = $this->M_global->getData('web_setting', ['id' => 1]);

        $position             = 'L'; // cek posisi l/p

        // body cetakan
        $body                 = '';
        $body                 .= '<br><br>'; // beri jarak antara kop dengan body

        $pendaftaran          = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
        $pembayaran           = $this->M_global->getData('pembayaran', ['no_trx' => $no_trx]);
        $barang_out_header    = $this->M_global->getData('barang_out_header', ['invoice' => $pembayaran->inv_jual]);
        $barang_out_detail    = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $pembayaran->inv_jual]);
        $member               = $this->M_global->getData('member', ['kode_member' => $pendaftaran->kode_member]);

        $judul                = 'Riwayat ~ ' . $no_trx;
        $filename             = $judul;

        if ($pendaftaran->status_trx == 1) {
            $open = '<input type="checkbox" style="width: 80px;" checked="checked"> Terbayar';
            $close = '<input type="checkbox" style="width: 80px;"> Belum Bayar';
        } else {
            $open = '<input type="checkbox" style="width: 80px;"> Terbayar';
            $close = '<input type="checkbox" style="width: 80px;" checked="checked"> Belum Bayar';
        }

        $body .= '<table style="width: 100%; font-size: 14px;" cellpadding="2px" autosize="1">
            <tr>
                <td>(Masuk: ' . date('d/m/Y', strtotime($pendaftaran->tgl_daftar)) . ' - ' . date('H:i:s', strtotime($pendaftaran->jam_daftar)) . ') ~ (Keluar: ' . date('d/m/Y', strtotime($pendaftaran->tgl_keluar)) . ' - ' . date('H:i:s', strtotime($pendaftaran->jam_keluar)) . ')</td>
                <td colspan="2" style="text-align: right; color: white;"><span style="border: 1px solid #0e1d2e; background-color: #0e1d2e;">NO: #' . $no_trx . '</span></td>
            </tr>
        </table>';

        $body .= '<br>';

        $body .= '<table style="width: 100%; font-size: 14px;" cellpadding="2px" autosize="1">
            <tr>
                <td style="width: 10%;">Poli</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . (($pendaftaran->kode_poli != 'UMUM') ? $this->M_global->getData('m_poli', ['kode_poli' => $pendaftaran->kode_poli])->keterangan : 'UMUM') . '</td>
                <td style="width: 10%;">RM</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . $member->kode_member . '</td>
            </tr>
            <tr>
                <td style="width: 10%;">Dr. Poli</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . $this->M_global->getData('dokter', ['kode_dokter' => $pendaftaran->kode_dokter])->nama . '</td>
                <td style="width: 10%;">Nama</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . $member->nama . '</td>
            </tr>
            <tr>
                <td style="width: 10%;">Ruangan</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . $this->M_global->getData('m_ruang', ['kode_ruang' => $pendaftaran->kode_ruang])->keterangan . '</td>
                <td style="width: 10%;">Umur</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . hitung_umur($member->tgl_lahir) . '</td>
            </tr>
            <tr>
                <td style="width: 10%;">Antrian</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . $pendaftaran->no_antrian . '</td>
                <td style="width: 10%;">Status</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . (($pendaftaran->status_trx == 0) ? 'Open' : (($pendaftaran->status_trx == 2) ? 'Cancel' : 'Close')) . '</td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 2%;"></td>
                <td style="width: 38%;"></td>
                <td style="width: 10%;">Status Bayar</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">' . $open . '&nbsp;&nbsp;' . $close . '</td>
            </tr>
        </table>';

        $body .= '<br>';

        $body .= '<table style="width: 100%; font-size: 18px;" autosize="1">
            <tr>
                <td><span style="background-color: #0e1d2e; color: white; margin: 10px 10px; text-align: center; border-radius: 5px;">~ Pembelian #' . $pembayaran->inv_jual . ' / ' . $this->M_global->getData('user', ['kode_user' => $barang_out_header->kode_user])->nama . ' / ' . date('d-m-Y', strtotime($barang_out_header->tgl_jual)) . ' - ' . date('H:i:s', strtotime($barang_out_header->jam_jual)) . '</span></td>
            </tr>
        </table>';

        $body .= '<table style="width: 100%; font-size: 14px;" autosize="1" cellpadding="5px">
            <thead>
                <tr style="background-color: #0e1d2e;">
                    <th style="color: white; width: 5%;">No</th>
                    <th style="color: white; width: 35%;">Barang</th>
                    <th style="color: white; width: 10%;">Satuan</th>
                    <th style="color: white; width: 10%;">Harga</th>
                    <th style="color: white; width: 10%;">Qty</th>
                    <th style="color: white; width: 10%;">Diskon</th>
                    <th style="color: white; width: 10%;">Pajak</th>
                    <th style="color: white; width: 10%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>';
        $no = 1;
        foreach ($barang_out_detail as $bd) {
            $body .= '<tr>
                <td style="border: 1px solid black; text-align: right;">' . $no++ . '</td>
                <td style="border: 1px solid black; ">' . $bd->kode_barang . ' ~ ' . $this->M_global->getData('barang', ['kode_barang' => $bd->kode_barang])->nama . '</td>
                <td style="border: 1px solid black; ">' . $this->M_global->getData('m_satuan', ['kode_satuan' => $bd->kode_satuan])->keterangan . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($bd->harga) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($bd->qty) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($bd->discrp) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($bd->pajakrp) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($bd->jumlah) . '</td>
            </tr>';
        }
        $body .= '</tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right;">Subtotal: Rp. </td>
                <td style="text-align: right;">' . number_format($barang_out_header->subtotal) . '</td>
            </tr>
            <tr>
                <td colspan="7" style="text-align: right;">Diskon: Rp. </td>
                <td style="text-align: right;">' . number_format($barang_out_header->diskon) . '</td>
            </tr>
            <tr>
                <td colspan="7" style="text-align: right;">Pajak: Rp. </td>
                <td style="text-align: right;">' . number_format($barang_out_header->pajak) . '</td>
            </tr>
            <tr>
                <td colspan="7" style="text-align: right;">Total: Rp. </td>
                <td style="text-align: right;">' . number_format($barang_out_header->total) . '</td>
            </tr>
        </tfoot>
        </table>';

        $body .= '<br>';

        $body .= '<table style="width: 100%; font-size: 18px;" autosize="1">
            <tr>
                <td><span style="background-color: #0e1d2e; color: white; margin: 10px 10px; text-align: center; border-radius: 5px;">~ Pembayaran #' . $pembayaran->invoice . ' / ' . $this->M_global->getData('user', ['kode_user' => $pembayaran->kode_user])->nama . ' / ' . date('d-m-Y', strtotime($pembayaran->tgl_pembayaran)) . ' - ' . date('H:i:s', strtotime($pembayaran->jam_pembayaran)) . '</span></td>
            </tr>
        </table>';

        $body .= '<table style="width: 100%; font-size: 14px;" autosize="1" cellpadding="5px">
            <thead>
                <tr>
                    <th style="background-color: #0e1d2e; color: white; width: 48%;" colspan="3">Pembayaran</th>
                    <th style="width: 4%;"></th>
                    <th style="background-color: #0e1d2e; color: white; width: 48%;" colspan="3">Kembalian</th>
                </tr>
                <tr>
                    <th style="background-color: #0e1d2e; color: white; width: 16%;">Uang Muka</th>
                    <th style="background-color: #0e1d2e; color: white; width: 16%;">Cash</th>
                    <th style="background-color: #0e1d2e; color: white; width: 16%;">Card</th>
                    <th style="width: 4%;"></th>
                    <th style="background-color: #0e1d2e; color: white; width: 24%;">Uang Muka</th>
                    <th style="background-color: #0e1d2e; color: white; width: 24%;">Cash</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; width: 16%; text-align: right;">' . number_format($pembayaran->um_keluar) . '</td>
                    <td style="border: 1px solid black; width: 16%; text-align: right;">' . number_format($pembayaran->cash) . '</td>
                    <td style="border: 1px solid black; width: 16%; text-align: right;">' . number_format($pembayaran->card) . '</td>
                    <td style="width: 4%;"></td>
                    <td style="border: 1px solid black; width: 24%; text-align: right;">' . number_format($pembayaran->um_masuk) . '</td>
                    <td style="border: 1px solid black; width: 24%; text-align: right;">' . (($pembayaran->cek_um > 0) ? 0 : number_format($pembayaran->kembalian)) . '</td>
                </tr>
            </tbody>
        </table>';

        cetak_pdf($judul, $body, 1, $position, $filename, $web_setting);
    }

    // fungsi cetak pendaftaran member
    public function print_pendaftaran($no_trx)
    {
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
        $member = $this->M_global->getData('member', ['kode_member' => $pendaftaran->kode_member]);

        $judul = 'Pendaftaran ' . $no_trx;
        $filename = $judul;

        if ($pendaftaran->status_trx == 1) {
            $open = '<input type="checkbox" style="width: 80px;" checked="checked"> Terbayar';
            $close = '<input type="checkbox" style="width: 80px;"> Belum Bayar';
        } else {
            $open = '<input type="checkbox" style="width: 80px;"> Terbayar';
            $close = '<input type="checkbox" style="width: 80px;" checked="checked"> Belum Bayar';
        }

        $body .= '<table style="width: 100%; font-size: 9px;" cellpadding="2px">';

        $body .= '<tr>
            <td colspan="3" style="text-align: right; color: white;"><span style="border: 1px solid #0e1d2e; background-color: #0e1d2e;">NO: #' . $no_trx . '</span></td>
        </tr>
        <tr>
            <td style="width: 23%;">RM</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $member->kode_member . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Nama</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $member->nama . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Umur</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . hitung_umur($member->tgl_lahir) . '</td>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 23%;">Poli</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . (($pendaftaran->kode_poli != 'UMUM') ? $this->M_global->getData('m_poli', ['kode_poli' => $pendaftaran->kode_poli])->keterangan : 'UMUM') . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Dr. Poli</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $this->M_global->getData('dokter', ['kode_dokter' => $pendaftaran->kode_dokter])->nama . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Ruangan</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $this->M_global->getData('m_ruang', ['kode_ruang' => $pendaftaran->kode_ruang])->keterangan . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Antrian</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $pendaftaran->no_antrian . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Status</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . (($pendaftaran->status_trx == 0) ? 'Open' : (($pendaftaran->status_trx == 2) ? 'Cancel' : 'Close')) . '</td>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 23%;">Tgl Masuk</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . date('d/m/Y', strtotime($pendaftaran->tgl_daftar)) . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Jam Masuk</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . date('H:i:s', strtotime($pendaftaran->jam_daftar)) . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Tgl Keluar</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . (isset($pendaftaran->tgl_keluar) ? date('d/m/Y', strtotime($pendaftaran->tgl_keluar)) : '-')  . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Jam Keluar</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . (isset($pendaftaran->jam_keluar) ? date('H:i:s', strtotime($pendaftaran->jam_keluar)) : '-')  . '</td>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 23%;">Status</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $open . '&nbsp;&nbsp;' . $close . '</td>
        </tr>
        ';
        $body .= '</table>';

        cetak_pdf_small($judul, $body, 1, $position, $filename, $web_setting);
    }

    // fungsi ambil riwayat
    public function getRiwayat($kode_member)
    {
        $data = $this->db->query('SELECT 
            p.no_trx, p.tgl_daftar, p.jam_daftar, p.tgl_keluar, p.jam_keluar, pol.keterangan AS nama_poli, dok.nama AS nama_dokter,
            c.cabang, p.status_trx
        FROM pendaftaran p 
        JOIN cabang c ON c.kode_cabang = p.kode_cabang
        JOIN m_poli pol ON pol.kode_poli = p.kode_poli 
        JOIN dokter dok ON dok.kode_dokter = p.kode_dokter 
        WHERE p.kode_member = "' . $kode_member . '"')->result();
        echo json_encode($data);
    }

    // form pendaftaran page
    public function form_pendaftaran($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $pendaftaran    = $this->M_global->getData('pendaftaran', ['no_trx' => $param]);
            $kode_member    = $pendaftaran->kode_member;

            $riwayat        = $this->M_global->getDataResult('pendaftaran', ['kode_member' => $kode_member]);
        } else {
            $pendaftaran    = null;
            $riwayat        = null;
        }

        $parameter = [
            $this->data,
            'judul'             => 'Health Management',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Pendaftaran',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'list_data'         => '',
            'data_pendaftaran'  => $pendaftaran,
            'riwayat'           => $riwayat,
            'role'              => $this->M_global->getResult('m_role'),
        ];

        $this->template->load('Template/Content', 'Pendaftaran/Form_pendaftaran', $parameter);
    }

    // fungsi cek member terdaftar/ tidak
    public function cekStatusMember()
    {
        $kode_member = $this->input->post('kode_member');

        $member = $this->M_global->getData('member', ['kode_member' => $kode_member]);

        if ($member->status_regist < 1) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0, "kode_member" => $kode_member]);
        }
    }

    // fungsi pendaftara proses
    public function pendaftaran_proses($param)
    {
        // variable
        $kode_user    = $this->session->userdata('kode_user');
        $shift        = $this->session->userdata('shift');
        $kode_cabang  = $this->session->userdata('cabang');

        $kode_poli    = $this->input->post('kode_poli');
        $tgl_daftar   = date('Y-m-d');

        if ($param == 1) { // jika param = 1
            // buat kode baru
            $no_trx         = _kodeTrx($kode_poli, $kode_cabang);
            $no_antrian     = _noAntrian($kode_poli, $kode_cabang, $tgl_daftar);
        } else { // selain itu
            // ambil dari inputan
            $no_trx         = $this->input->post('no_trx');
            $no_antrian     = $this->input->post('no_antrian');
        }

        $jam_daftar   = date('H:i:s');
        $kode_member  = $this->input->post('kode_member');
        $kode_dokter  = $this->input->post('kode_dokter');
        $kode_ruang   = $this->input->post('kode_ruang');

        // jika ada last antrian + 1, jika tidak ada 0 + 1

        // masukan kedalam variable $isi
        $isi = [
            'kode_cabang'   => $kode_cabang,
            'no_trx'        => $no_trx,
            'tgl_daftar'    => $tgl_daftar,
            'jam_daftar'    => $jam_daftar,
            'kode_member'   => $kode_member,
            'no_antrian'    => $no_antrian,
            'kode_poli'     => $kode_poli,
            'kode_dokter'   => $kode_dokter,
            'tgl_keluar'    => null,
            'jam_keluar'    => null,
            'status_trx'    => 0,
            'kode_ruang'    => $kode_ruang,
            'kode_user'     => $kode_user,
            'shift'         => $shift,
        ];

        if ($param == 1) { // jika param = 1
            aktifitas_user_transaksi('Pendaftaran', 'mendaftarkan Member ' . $kode_member, $no_trx);

            // lakukan fungsi tambah ke table pendaftaran
            $cek = [
                $this->M_global->insertData('pendaftaran', $isi),
                $this->M_global->updateData('member', ['status_regist' => 1, 'last_regist' => $no_trx], ['kode_member' => $kode_member]),
            ];
        } else { // selain itu
            aktifitas_user_transaksi('Pendaftaran', 'mengubah Pendaftaran Member ' . $kode_member, $no_trx);
            // lakukan fungsi ubah ke table pendaftaran
            $cek = $this->M_global->updateData('pendaftaran', $isi, ['no_trx' => $no_trx]);
        }

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1, 'no_trx' => $no_trx]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi aktif/nonaktif pendaftaran
    public function activedpendaftaran($no_trx)
    {
        // jalankan fungsi update actived pendaftaran
        $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
        aktifitas_user_transaksi('Pendaftaran', 'membatalkan Pendaftaran Member ' . $pendaftaran->kode_member, $no_trx);

        $cek = [
            $this->M_global->updateData('pendaftaran', ['status_trx' => 2, 'tgl_keluar' => date('Y-m-d'), 'jam_keluar' => date('H:i:s')], ['no_trx' => $no_trx]),
            $this->M_global->updateData('member', ['status_regist' => 0], ['last_regist' => $no_trx]),
        ];

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus pendaftaran
    public function delPendaftaran($no_trx)
    {
        // jalankan fungsi hapus pendaftaran berdasarkan no_trx
        $member = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);

        aktifitas_user_transaksi('Pendaftaran', 'menghapus Pendaftaran Member ' . $member->kode_member, $no_trx);

        $cek = $this->M_global->delData('pendaftaran', ['no_trx' => $no_trx]);

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);

            $last = $this->db->query("SELECT * FROM pendaftaran WHERE kode_member = '$member->kode_member' AND no_trx <> '$no_trx' ORDER BY id DESC LIMIT 1")->row();

            if ($last) {
                $this->M_global->updateData('member', ['last_regist' => $last->no_trx, 'status_regist' => 0], ['kode_member' => $member->kode_member]);
            } else {
                $this->M_global->updateData('member', ['last_regist' => null, 'status_regist' => 0], ['kode_member' => $member->kode_member]);
            }
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }
}
