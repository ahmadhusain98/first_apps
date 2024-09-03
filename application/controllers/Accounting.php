<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounting extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada

            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Accounting'])->id;

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
                    'menu'      => 'Accounting',
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

    // piutang page
    public function piutang()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Accounting',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Accounting',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'piutang_num'   => $this->M_global->getDataResult('piutang', ['kode_cabang' => $this->session->userdata('cabang')]),
            'list_data'     => 'Accounting/piutang_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Accounting/Piutang', $parameter);
    }

    // fungsi list piutang_list
    public function piutang_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'piutang';
        $colum            = ['id', 'kode_cabang', 'piutang_no', 'tanggal', 'jam', 'referensi', 'jumlah', 'status', 'tanggal_bayar', 'jam_bayar'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = '';
        $kondisi_param1   = 'tanggal';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);

        if ($dat[0] == 1) {
            $bulan        = date('m');
            $tahun        = date('Y');
            $type         = 1;
        } else {
            $bulan        = date('Y-m-d', strtotime($dat[1]));
            $tahun        = date('Y-m-d', strtotime($dat[2]));
            $type         = 2;
        }

        $list             = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2);

        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                $upd_diss = _lock_button();
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                $del_diss = _lock_button();
            } else {
                $del_diss = 'disabled';
            }

            if ($rd->status > 0) {
                $confirm_diss =  'disabled';
            } else {
                $confirm_diss = '';
            }

            $jual = $this->M_global->getData('barang_in_header', ['invoice' => $rd->referensi]);

            if ($jual) {
                $supplier   = $jual->kode_supplier;
                $gudang     = $jual->kode_gudang;
            } else {
                $supplier   = $this->M_global->getData('barang_in_retur_header', ['invoice' => $rd->referensi])->kode_supplier;
                $gudang     = $this->M_global->getData('barang_in_retur_header', ['invoice' => $rd->referensi])->kode_gudang;
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->referensi;
            $row[]  = date('d/m/Y', strtotime($rd->tanggal_bayar)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_bayar));
            $row[]  = $this->M_global->getData('m_supplier', ['kode_supplier' => $supplier])->nama;
            $row[]  = $this->M_global->getData('m_gudang', ['kode_gudang' => $gudang])->nama;
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->jumlah) . '</span>';
            $row[]  = '<div class="text-center">' . (($rd->status > 0) ? '<span class="badge badge-success">Terbayarkan</span>' : '<span class="badge badge-danger">Belum dibayar</span>') . '</div>';
            $row[]  = '<div class="text-center">
                <button class="btn btn-success" type="button" ' . $confirm_diss . ' title="Bayar #' . $rd->referensi . '" onclick="bayar(' . "'" . $rd->piutang_no . "', '" . $rd->referensi . "'" . ')"><i class="fa-solid fa-circle-dollar-to-slot"></i></button>
            </div>';

            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    public function piutang_bayar()
    {
        $piutang_no = $this->input->get('inv');

        $cek = $this->M_global->updateData('piutang', ['status' => 1, "tanggal_bayar" => date('Y-m-d'), "jam_bayar" => date('H:i:s')], ['piutang_no' => $piutang_no]);

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // deposit_kas page
    public function deposit_kas()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Accounting',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Deposit',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'piutang_num'   => $this->M_global->getDataResult('piutang', ['kode_cabang' => $this->session->userdata('cabang')]),
            'list_data'     => 'Accounting/deposit_kas_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Accounting/Deposit_kas', $parameter);
    }

    // fungsi list deposit_kas_list
    public function deposit_kas_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'deposit_kas';
        $colum            = ['id', 'kode_cabang', 'token', 'cash', 'card', 'jenis_pembayaran', 'tgl_masuk', 'jam_masuk', 'kode_user', 'total'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['tgl_masuk' => 'desc'];
        $kondisi_param2   = '';
        $kondisi_param1   = 'tgl_masuk';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);

        if ($dat[0] == 1) {
            $bulan        = date('m');
            $tahun        = date('Y');
            $type         = 1;
        } else {
            $bulan        = date('Y-m-d', strtotime($dat[1]));
            $tahun        = date('Y-m-d', strtotime($dat[2]));
            $type         = 2;
        }

        $list             = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2);

        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                $upd_diss = _lock_button();
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                $del_diss = _lock_button();
            } else {
                $del_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = date('d/m/Y', strtotime($rd->tgl_masuk)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_masuk));
            $row[]  = $this->M_global->getData('user', ['kode_user' => $rd->kode_user])->nama;
            $row[]  = (($rd->jenis_pembayaran == 0) ? 'Cash' : (($rd->jenis_pembayaran == 1) ? 'Card' : 'Cash + Card'));
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->cash) . '</span>';
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->card) . '</span>';
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->total) . '</span>';
            $row[]  = '<div class="text-center">
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->token . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->token . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';

            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // form form_deposit_kas page
    public function form_deposit_kas($param, $param2 = '')
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param == '0') {
            $pembayaran     = null;
            $bayar_detail   = null;
        } else {
            $bayar_detail   = $this->M_global->getDataResult('bayar_kas_card', ['token_deposit' => $param]);
            $pembayaran     = $this->M_global->getData('deposit_kas', ['token' => $param]);
        }

        $parameter = [
            $this->data,
            'judul'             => 'Accounting',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Kas/Bank Deposit',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'list_data'         => '',
            'data_pembayaran'   => $pembayaran,
            'bayar_detail'      => $bayar_detail,
            'param2'            => $param2,
        ];

        $this->template->load('Template/Content', 'Accounting/Form_deposit', $parameter);
    }

    public function delDepositKas($token) {
        $kas_utama      = $this->M_global->getData('kas_utama', ['id' => 1]);
        $deposit_kas    = $this->M_global->getData('deposit_kas', ['token' => $token]);
        $total          = $deposit_kas->total;

        $this->M_global->updateData('kas_utama', ['masuk' => ($kas_utama->masuk - $deposit_kas->total)], ['id' => 1]);
            
        $cek = [
            $this->M_global->delData('deposit_kas', ['token' => $token]),
            $this->M_global->delData('bayar_kas_card', ['token_deposit' => $token]),
        ];

        if($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    public function deposit_kas_proses($param)
    {
        $cabang = $this->session->userdata('cabang');
        if ($param == 2) {
            $token = $this->input->post('token');
        } else {
            $token = tokenKasir(30);
        }

        $tgl_masuk = $this->input->post('tgl_masuk');
        $jam_masuk = $this->input->post('jam_masuk');
        $jenis_pembayaran = $this->input->post('jenis_pembayaran');
        $total = str_replace(',', '', $this->input->post('total'));
        $cash = str_replace(',', '', $this->input->post('cash'));
        $card = str_replace(',', '', $this->input->post('card'));
        $kode_bank = $this->input->post('kode_bank');
        $tipe_bank = $this->input->post('tipe_bank');
        $no_card = $this->input->post('no_card');
        $approval = $this->input->post('approval');
        $jumlah = $this->input->post('jumlah_card');

        $isi = [
            'kode_cabang' => $cabang,
            'token' => $token,
            'tgl_masuk' => $tgl_masuk,
            'jam_masuk' => $jam_masuk,
            'total' => $total,
            'cash' => $cash,
            'card' => $card,
            'jenis_pembayaran' => $jenis_pembayaran,
            'kode_user' => $this->session->userdata('kode_user'),
        ];

        $kas_utama = $this->M_global->getData('kas_utama', ['id' => 1]);

        if ($param == 2) {
            $depo_kas = $this->M_global->getData('deposit_kas', ['token' => $token]);
            
            // update1
            $this->M_global->updateData('kas_utama', ['masuk' => ($kas_utama->masuk - $depo_kas->total)], ['id' => 1]);
            
            // update2
            $kas_utama2 = $this->M_global->getData('kas_utama', ['id' => 1]);
            $this->M_global->updateData('kas_utama', ['masuk' => ($kas_utama2->masuk + $total)], ['id' => 1]);

            $cek = [
                $this->M_global->updateData('deposit_kas', $isi, ['token' => $token]),
                $this->M_global->delData('bayar_kas_card', ['token_deposit' => $token]),
            ];
        } else {
            $cek = [
                $this->M_global->insertData('deposit_kas', $isi),
            ];

            $this->M_global->updateData('kas_utama', ['masuk' => ($kas_utama->masuk + $total), 'last_no' => $token], ['id' => 1]);
        }
        

        if ($cek) {
            if ($jenis_pembayaran > 0) {
                // detail card
                if (!empty($kode_bank)) {
                    $jum = count($kode_bank);

                    // lakukan loop dengan for
                    for ($x = 0; $x <= ($jum - 1); $x++) {
                        $_kode_bank   = $kode_bank[$x];
                        $_tipe_bank   = $tipe_bank[$x];
                        $_no_card     = $no_card[$x];
                        $_approval    = $approval[$x];
                        $_jumlah      = str_replace(',', '', $jumlah[$x]);

                        // isi detail card
                        $isi_card = [
                            'token_deposit'     => $token,
                            'kode_bank'         => $_kode_bank,
                            'kode_tipe'         => $_tipe_bank,
                            'no_card'           => $_no_card,
                            'approval'          => $_approval,
                            'jumlah'            => $_jumlah,
                        ];

                        // insert ke bayar_kas_card
                        $this->M_global->insertData('bayar_kas_card', $isi_card);
                    }
                }
            }

            if ($param == 1) {
                aktifitas_user_transaksi('Accounting', 'deposit Kas/Bank', $token);
            } else {
                aktifitas_user_transaksi('Accounting', 'mengubah deposit Kas/Bank', $token);
            }

            echo json_encode(['status' => 1, 'token' => $token]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }
}
