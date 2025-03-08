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

        // parameter
        $param = $this->input->get('param');

        $parameter = [
            $this->data,
            'judul'         => 'Sampah Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Sampah Master',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Sampah/sampah_list/',
            'param1'        => '',
            'menu'          => $this->M_global->getDataResult('m_menu', ['id < ' => '999', 'id > ' => '2']),
            'query_master'  => $this->M_global->getDataSampah($param),
            'check'         => $param,
        ];

        $this->template->load('Template/Content', 'Sampah', $parameter);
    }

    public function restore()
    {
        $cek        = $this->input->post('check_onex'); // Checkbox data
        $invoice    = $this->input->post('invoice'); // Invoice value
        $tabel      = $this->input->post('tabel'); // Table name
        $jum        = count($cek);
        $no         = 0; // Counter for successful updates

        // Validate inputs
        if (!$cek || !$invoice || !$tabel) {
            echo json_encode(['status' => 0]);
            return;
        }

        for ($x = 0; $x <= ($jum - 1); $x++) {
            $_cek       = $cek[$x];
            $_invoice   = $invoice[$x];
            $_tabel     = $tabel[$x];

            if ($_cek == 1) {
                // Define update parameters
                $updateData = ['hapus' => 0, 'tgl_hapus' => null, 'jam_hapus' => null];

                // Handle specific table updates
                if ($_tabel == 'm_satuan') {
                    $where = ['kode_satuan' => $_invoice];
                } else if ($_tabel == 'm_kategori') {
                    $where = ['kode_kategori' => $_invoice];
                } else if ($_tabel == 'm_jenis') {
                    $where = ['kode_jenis' => $_invoice];
                } else if ($_tabel == 'm_supplier') {
                    $where = ['kode_supplier' => $_invoice];
                } else if ($_tabel == 'm_bank') {
                    $where = ['kode_bank' => $_invoice];
                } else if ($_tabel == 'm_pekerjaan') {
                    $where = ['kode_pekerjaan' => $_invoice];
                } else if ($_tabel == 'm_agama') {
                    $where = ['kode_agama' => $_invoice];
                } else if ($_tabel == 'm_pendidikan') {
                    $where = ['kode_pendidikan' => $_invoice];
                } else if ($_tabel == 'm_poli') {
                    $where = ['kode_poli' => $_invoice];
                } else if ($_tabel == 'kas_bank') {
                    $where = ['kode_kas_bank' => $_invoice];
                } else if ($_tabel == 'm_pajak') {
                    $where = ['kode_pajak' => $_invoice];
                } else if ($_tabel == 'm_akun') {
                    $where = ['kode_akun' => $_invoice];
                } else if ($_tabel == 'tipe_bank') {
                    $where = ['kode_tipe' => $_invoice];
                } else if ($_tabel == 'm_gudang') {
                    $where = ['kode_gudang' => $_invoice];
                } else if ($_tabel == 'barang_cabang' || $_tabel == 'logistik_cabang') {
                    $where = ['kode_barang' => $_invoice, 'kode_cabang' => $this->session->userdata('cabang')];
                } else if ($_tabel == 'user') {
                    $where = ['kode_user' => $_invoice];
                } else if ($_tabel == 'dokter') {
                    $where = ['kode_dokter' => $_invoice];
                } else if ($_tabel == 'perawat') {
                    $where = ['kode_perawat' => $_invoice];
                } else if ($_tabel == 'tarif_jasa' || $_tabel == 'tarif_paket') {
                    $where = ['kode_tarif' => $_invoice, 'kode_cabang' => $this->session->userdata('cabang')];
                } else if ($_tabel == 'm_ruang') {
                    $where = ['kode_ruang' => $_invoice];
                } else if ($_tabel == 'm_prefix') {
                    $where = ['kode_prefix' => $_invoice];
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

    public function restore_one($id, $table)
    {
        $updateData = ['hapus' => 0, 'tgl_hapus' => null, 'jam_hapus' => null];

        // Handle specific table updates
        if ($table == 'm_satuan') {
            $where = ['kode_satuan' => $id];
        } else if ($table == 'm_kategori') {
            $where = ['kode_kategori' => $id];
        } else if ($table == 'm_jenis') {
            $where = ['kode_jenis' => $id];
        } else if ($table == 'm_supplier') {
            $where = ['kode_supplier' => $id];
        } else if ($table == 'm_bank') {
            $where = ['kode_bank' => $id];
        } else if ($table == 'm_pekerjaan') {
            $where = ['kode_pekerjaan' => $id];
        } else if ($table == 'm_agama') {
            $where = ['kode_agama' => $id];
        } else if ($table == 'm_pendidikan') {
            $where = ['kode_pendidikan' => $id];
        } else if ($table == 'm_poli') {
            $where = ['kode_poli' => $id];
        } else if ($table == 'kas_bank') {
            $where = ['kode_kas_bank' => $id];
        } else if ($table == 'm_pajak') {
            $where = ['kode_pajak' => $id];
        } else if ($table == 'm_akun') {
            $where = ['kode_akun' => $id];
        } else if ($table == 'tipe_bank') {
            $where = ['kode_tipe' => $id];
        } else if ($table == 'm_gudang') {
            $where = ['kode_gudang' => $id];
        } else if ($table == 'barang_cabang' || $table == 'logistik_cabang') {
            $where = ['kode_barang' => $id, 'kode_cabang' => $this->session->userdata('cabang')];
        } else if ($table == 'user') {
            $where = ['kode_user' => $id];
        } else if ($table == 'dokter') {
            $where = ['kode_dokter' => $id];
        } else if ($table == 'perawat') {
            $where = ['kode_perawat' => $id];
        } else if ($table == 'tarif_jasa' || $table == 'tarif_paket') {
            $where = ['kode_tarif' => $id, 'kode_cabang' => $this->session->userdata('cabang')];
        } else if ($table == 'm_ruang') {
            $where = ['kode_ruang' => $id];
        } else if ($table == 'm_prefix') {
            $where = ['kode_prefix' => $id];
        } else {
            echo json_encode(['status' => 0]);
            return;
        }

        $cek = $this->M_global->updateData($table, $updateData, $where);

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    public function deleted()
    {
        $cek        = $this->input->post('check_onex'); // Checkbox data
        $invoice    = $this->input->post('invoice'); // Invoice value
        $tabel      = $this->input->post('tabel'); // Table name
        $jum        = count($cek);
        $no         = 0; // Counter for successful updates

        // Validate inputs
        if (!$cek || !$invoice || !$tabel) {
            echo json_encode(['status' => 0]);
            return;
        }

        for ($x = 0; $x <= ($jum - 1); $x++) {
            $_cek       = $cek[$x];
            $_invoice   = $invoice[$x];
            $_tabel     = $tabel[$x];

            if ($_cek == 1) {
                // Handle specific table updates
                if ($_tabel == 'm_satuan') {
                    $where = ['kode_satuan' => $_invoice];
                } else if ($_tabel == 'm_kategori') {
                    $where = ['kode_kategori' => $_invoice];
                } else if ($_tabel == 'm_jenis') {
                    $where = ['kode_jenis' => $_invoice];
                } else if ($_tabel == 'm_supplier') {
                    $where = ['kode_supplier' => $_invoice];
                } else if ($_tabel == 'm_bank') {
                    $where = ['kode_bank' => $_invoice];
                } else if ($_tabel == 'm_pekerjaan') {
                    $where = ['kode_pekerjaan' => $_invoice];
                } else if ($_tabel == 'm_agama') {
                    $where = ['kode_agama' => $_invoice];
                } else if ($_tabel == 'm_pendidikan') {
                    $where = ['kode_pendidikan' => $_invoice];
                } else if ($_tabel == 'm_poli') {
                    $where = ['kode_poli' => $_invoice];
                } else if ($_tabel == 'kas_bank') {
                    $where = ['kode_kas_bank' => $_invoice];
                } else if ($_tabel == 'm_pajak') {
                    $where = ['kode_pajak' => $_invoice];
                } else if ($_tabel == 'm_akun') {
                    $where = ['kode_akun' => $_invoice];
                } else if ($_tabel == 'tipe_bank') {
                    $where = ['kode_tipe' => $_invoice];
                } else if ($_tabel == 'm_gudang') {
                    $where = ['kode_gudang' => $_invoice];
                } else if ($_tabel == 'barang_cabang') {
                    $where = ['kode_barang' => $_invoice];

                    $barang_cabang = $this->M_global->getDataResult('barang_cabang', ['kode_barang' => $_invoice, 'hapus' => 0]);

                    if (count($barang_cabang) < 1) {
                        $this->M_global->delData('barang_cabang', ['kode_barang' => $_invoice]);
                        $this->M_global->delData('barang_jenis', ['kode_barang' => $_invoice]);
                    }
                } else if ($_tabel == 'logistik_cabang') {
                    $where = ['kode_barang' => $_invoice, 'kode_cabang' => $this->session->userdata('cabang')];

                    $logistik_cabang = $this->M_global->getDataResult('logistik_cabang', ['kode_barang' => $_invoice, 'hapus' => 0]);

                    if (count($logistik_cabang) < 1) {
                        $this->M_global->delData('logistik', ['kode_logistik' => $_invoice]);
                    }
                } else if ($_tabel == 'user') { // Fixed the typo here from $table to $_tabel
                    $where = ['kode_user' => $_invoice];

                    $user = $this->M_global->getData('user', $where);

                    $this->M_global->delData('user_token', ['email' => $user->email]);
                } else if ($_tabel == 'dokter') {
                    $where = ['kode_dokter' => $_invoice];

                    $this->M_global->delData('dokter_poli', ['kode_dokter' => $_invoice]);
                    $this->M_global->delData('user', ['kode_user' => $_invoice]);
                } else if ($_tabel == 'perawat') {
                    $where = ['kode_perawat' => $_invoice];

                    $this->M_global->delData('perawat_poli', ['kode_perawat' => $_invoice]);
                    $this->M_global->delData('user', ['kode_user' => $_invoice]);
                } else if ($_tabel == 'tarif_jasa') {
                    $where = ['kode_tarif' => $_invoice, 'kode_cabang' => $this->session->userdata('cabang')];

                    $tarif_jasa = $this->M_global->getDataResult('tarif_jasa', ['kode_tarif' => $_invoice, 'hapus' => 0]);

                    if (count($tarif_jasa) < 1) {
                        $this->M_global->delData('tarif_single_bhp', ['kode_tarif' => $_invoice]);
                        $this->M_global->delData('m_tarif', ['kode_tarif' => $_invoice]);
                    }
                } else if ($_tabel == 'tarif_paket') {
                    $where = ['kode_tarif' => $_invoice, 'kode_cabang' => $this->session->userdata('cabang')];

                    $tarif_paket = $this->M_global->getDataResult('tarif_paket', ['kode_tarif' => $_invoice, 'hapus' => 0]);

                    if (count($tarif_paket) < 1) {
                        $this->M_global->delData('tarif_paket_bhp', ['kode_tarif' => $_invoice]);
                        $this->M_global->delData('m_tarif', ['kode_tarif' => $_invoice]);
                    }
                } else if ($_tabel == 'm_ruang') {
                    $where = ['kode_ruang' => $_invoice];

                    $this->M_global->delData('bed_cabang', ['kode_bed' => $_invoice, 'kode_cabang' => $this->session->userdata('cabang')]);
                } else if ($_tabel == 'm_prefix') {
                    $where = ['kode_prefix' => $_invoice];
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

    public function deleted_one($id, $table)
    {
        if ($table == 'm_satuan') {
            $where = ['kode_satuan' => $id];
        } else if ($table == 'm_kategori') {
            $where = ['kode_kategori' => $id];
        } else if ($table == 'm_jenis') {
            $where = ['kode_jenis' => $id];
        } else if ($table == 'm_supplier') {
            $where = ['kode_supplier' => $id];
        } else if ($table == 'm_bank') {
            $where = ['kode_bank' => $id];
        } else if ($table == 'm_pekerjaan') {
            $where = ['kode_pekerjaan' => $id];
        } else if ($table == 'm_agama') {
            $where = ['kode_agama' => $id];
        } else if ($table == 'm_pendidikan') {
            $where = ['kode_pendidikan' => $id];
        } else if ($table == 'm_poli') {
            $where = ['kode_poli' => $id];
        } else if ($table == 'kas_bank') {
            $where = ['kode_kas_bank' => $id];
        } else if ($table == 'm_pajak') {
            $where = ['kode_pajak' => $id];
        } else if ($table == 'm_akun') {
            $where = ['kode_akun' => $id];
        } else if ($table == 'tipe_bank') {
            $where = ['kode_tipe' => $id];
        } else if ($table == 'm_gudang') {
            $where = ['kode_gudang' => $id];
        } else if ($table == 'barang_cabang') {
            $where = ['kode_barang' => $id, 'kode_cabang' => $this->session->userdata('cabang')];

            $barang_cabang = $this->M_global->getDataResult('barang_cabang', ['kode_barang' => $id, 'hapus' => 0]);

            if (count($barang_cabang) < 1) {
                $this->M_global->delData('barang_jenis', ['kode_barang' => $id]);
                $this->M_global->delData('barang', ['kode_barang' => $id]);
            }
        } else if ($table == 'logistik_cabang') {
            $where = ['kode_barang' => $id, 'kode_cabang' => $this->session->userdata('cabang')];

            $logistik_cabang = $this->M_global->getDataResult('logistik_cabang', ['kode_barang' => $id, 'hapus' => 0]);

            if (count($logistik_cabang) < 1) {
                $this->M_global->delData('logistik', ['kode_logistik' => $id]);
            }
        } else if ($table == 'user') {
            $where = ['kode_user' => $id];

            $user = $this->M_global->getData('user', $where);

            $this->M_global->delData('user_token', ['email' => $user->email]);
        } else if ($table == 'dokter') {
            $where = ['kode_dokter' => $id];

            $this->M_global->delData('dokter_poli', ['kode_dokter' => $id]);
            $this->M_global->delData('user', ['kode_user' => $id]);
        } else if ($table == 'perawat') {
            $where = ['kode_perawat' => $id];

            $this->M_global->delData('perawat_poli', ['kode_perawat' => $id]);
            $this->M_global->delData('user', ['kode_user' => $id]);
        } else if ($table == 'tarif_jasa') {
            $where = ['kode_tarif' => $id, 'kode_cabang' => $this->session->userdata('cabang')];

            $tarif_jasa = $this->M_global->getDataResult('tarif_jasa', ['kode_tarif' => $id, 'hapus' => 0]);

            if (count($tarif_jasa) < 1) {
                $this->M_global->delData('tarif_single_bhp', ['kode_tarif' => $id]);
                $this->M_global->delData('m_tarif', ['kode_tarif' => $id]);
            }
        } else if ($table == 'tarif_paket') {
            $where = ['kode_tarif' => $id, 'kode_cabang' => $this->session->userdata('cabang')];

            $tarif_paket = $this->M_global->getDataResult('tarif_paket', ['kode_tarif' => $id, 'hapus' => 0]);

            if (count($tarif_paket) < 1) {
                $this->M_global->delData('tarif_paket_bhp', ['kode_tarif' => $id]);
                $this->M_global->delData('m_tarif', ['kode_tarif' => $id]);
            }
        } else if ($table == 'm_ruang') {
            $where = ['kode_ruang' => $id];

            $this->M_global->delData('bed_cabang', ['kode_bed' => $id, 'kode_cabang' => $this->session->userdata('cabang')]);
        } else if ($table == 'm_prefix') {
            $where = ['kode_prefix' => $id];
        } else {
            echo json_encode(['status' => 0]);
            return;
        }

        $cek = $this->M_global->delData($table, $where);

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }
}
