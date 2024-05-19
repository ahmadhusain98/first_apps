<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marketing extends CI_Controller
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
        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    // promo page
    public function promo()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Marketing',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Promo',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Marketing/promo_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Marketing/Promo', $parameter);
    }

    // form promo page
    public function form_promo($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != 0) {
            $promo = $this->M_global->getData('m_promo', ['kode_promo' => $param]);
        } else {
            $promo = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Barang',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'promo'         => $promo,
        ];

        $this->template->load('Template/Content', 'Marketing/Form_promo', $parameter);
    }

    // cek promo
    public function cekProm()
    {
        // ambil nama inputan
        $nama = $this->input->post('nama');

        // cek nama pada table barang
        $cek = $this->M_global->jumDataRow('m_promo', ['nama' => $nama]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses ismpan/update
    public function promo_proses($param)
    {
        // header
        if ($param == 1) { // jika param = 1
            $kode_promo = _code_promo();
        } else {
            $kode_promo = $this->input->post('kodePromo');
        }
        $nama         = $this->input->post('nama');
        $tgl_mulai    = $this->input->post('tgl_mulai');
        $tgl_selesai  = $this->input->post('tgl_selesai');
        $keterangan   = $this->input->post('keterangan');

        $min_buy      = str_replace(',', '', $this->input->post('min_buy'));
        $discpr       = str_replace(',', '', $this->input->post('discpr'));

        $isi = [
            'kode_promo'    => $kode_promo,
            'nama'          => $nama,
            'tgl_mulai'     => $tgl_mulai,
            'tgl_selesai'   => $tgl_selesai,
            'keterangan'    => $keterangan,
            'min_buy'       => $min_buy,
            'discpr'        => $discpr,
        ];

        if ($param == 1) {
            $cek = $this->M_global->insertData('m_promo', $isi);
        } else {
            $cek = $this->M_global->updateData('m_promo', $isi, ['kode_promo' => $kode_promo]);
        }

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi list promo
    public function promo_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'm_promo';
        $colum            = ['id', 'kode_promo', 'nama', 'tgl_mulai', 'tgl_selesai', 'keterangan', 'min_buy', 'discpr'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = '';
        $kondisi_param1   = 'tgl_mulai';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat              = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan        = date('m');
            $tahun        = date('Y');
            $list         = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan        = date('Y-m-d', strtotime($dat[1]));
            $tahun        = date('Y-m-d', strtotime($dat[2]));
            $list         = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }
        $data             = [];
        $no               = $_POST['start'] + 1;

        $now              = date('Y-m-d');

        // loop $list
        foreach ($list as $rd) {
            $cek_guna = $this->M_global->jumDataRow('pembayaran', ['kode_promo' => $rd->kode_promo]);

            if ($updated > 0) {
                if ($rd->tgl_selesai < $now) {
                    $upd_diss = 'disabled';
                } else {
                    if ($cek_guna < 1) {
                        $upd_diss = '';
                    } else {
                        $upd_diss = 'disabled';
                    }
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->tgl_selesai < $now) {
                    $del_diss = 'disabled';
                } else {
                    if ($cek_guna < 1) {
                        $del_diss = '';
                    } else {
                        $del_diss = 'disabled';
                    }
                }
            } else {
                $del_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = 'Mulai: <br><span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_mulai)) . '</span><br>Berakhir: <br><span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_selesai)) . '</span>';
            $row[]  = $rd->nama;
            $row[]  = $rd->keterangan;
            $row[]  = 'Min Pembelian: <br><span class="float-right">Rp.' . number_format($rd->min_buy) . '</span>';
            $row[]  = '<span class="float-right">' . number_format($rd->discpr) . ' %</span>';
            $row[]  = (($rd->tgl_selesai < $now) ? '<span class="badge badge-danger">Promo Berakhir</span>' : '<span class="badge badge-success">Promo Berjalan</span>');
            $row[]  = '<div class="text-center">
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-secondary" title="Ubah" onclick="ubah(' . "'" . $rd->kode_promo . "'" . ')" ' . $upd_diss . '>
                    <ion-icon name="create-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->kode_promo . "'" . ')" ' . $del_diss . '>
                    <ion-icon name="close-circle-outline"></ion-icon>
                </button>
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

    // fungsi hapus promo
    public function delPromo($kode_promo)
    {
        // jalankan fungsi hapus promo berdasarkan kode_promo
        $cek = $this->M_global->delData('m_promo', ['kode_promo' => $kode_promo]);

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }
}
