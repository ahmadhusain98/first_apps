<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sampah extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada

            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Transaksi'])->id;

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
                    'menu'      => 'Transaksi',
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

    public function index() 
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Sampah',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Sampah',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Sampah/sampah_list/',
            'param1'        => '',
            'menu'          => $this->M_global->getDataResult('m_menu', ['id < ' => '999', 'id > ' => '2']),
            'query_master'  => $this->M_global->getDataSampah(),
        ];

        $this->template->load('Template/Content', 'Sampah', $parameter);
    }

    public function restore()
    {
        $cek = $this->input->post('check_onex'); // Checkbox data
        $invoice = $this->input->post('invoice'); // Invoice value
        $tabel = $this->input->post('tabel'); // Table name
        $jum = count($cek);
        $no = 0; // Counter for successful updates

        // Validate inputs
        if (!$cek || !$invoice || !$tabel) {
            echo json_encode(['status' => 0]);
            return;
        }

        for($x = 0; $x <= ($jum - 1); $x++) {
            $_cek = $cek[$x];
            $_invoice = $invoice[$x];
            $_tabel = $tabel[$x];

            if ($_cek == 1) {
                // Define update parameters
                $updateData = ['hapus' => 0, 'tgl_hapus' => null, 'jam_hapus' => null];
    
                // Handle specific table updates
                if ($_tabel == 'm_satuan') {
                    $where = ['kode_satuan' => $_invoice];
                } else if ($_tabel == 'm_kategori') {
                    $where = ['kode_kategori' => $_invoice];
                } else {
                    echo json_encode(['status' => 0]);
                    return;
                }

                $this->M_global->updateData($_tabel, $updateData, $where);
    
                $no++;
            }
        }

        // Return response
        if ($no === 0) {
            echo json_encode(['status' => 0]);
        } else {
            echo json_encode(['status' => 1]);
        }
    }

    public function deleted()
    {
        $cek = $this->input->post('check_onex'); // Checkbox data
        $invoice = $this->input->post('invoice'); // Invoice value
        $tabel = $this->input->post('tabel'); // Table name
        $jum = count($cek);
        $no = 0; // Counter for successful updates

        // Validate inputs
        if (!$cek || !$invoice || !$tabel) {
            echo json_encode(['status' => 0]);
            return;
        }

        for($x = 0; $x <= ($jum - 1); $x++) {
            $_cek = $cek[$x];
            $_invoice = $invoice[$x];
            $_tabel = $tabel[$x];

            if ($_cek == 1) {
                // Handle specific table updates
                if ($_tabel == 'm_satuan') {
                    $where = ['kode_satuan' => $_invoice];
                } else if ($_tabel == 'm_kategori') {
                    $where = ['kode_kategori' => $_invoice];
                } else {
                    echo json_encode(['status' => 0]);
                    return;
                }

                $this->M_global->delData($_tabel, $where);
    
                $no++;
            }
        }

        // Return response
        if ($no === 0) {
            echo json_encode(['status' => 0]);
        } else {
            echo json_encode(['status' => 1]);
        }
    }

}