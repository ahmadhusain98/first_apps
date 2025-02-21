<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emr extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada

            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Emr'])->id;

            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            $cek_akses_menu = $this->M_global->getData('akses_menu', ['id_menu' => $id_menu, 'kode_role' => $user->kode_role]);
            if ($cek_akses_menu) {
                // tampung data ke variable data public
                $this->data = [
                    'kode_user' => $user->kode_user,
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
            'judul'         => 'Electrical Medical Record',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'EMR Rawat Jalan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Emr/daftar_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Emr/Daftar', $parameter);
    }

    // fungsi list daftar
    public function daftar_list($param1 = 1, $param2 = '', $param3 = '')
    {
        // parameter untuk list table
        $table            = 'pendaftaran';
        $colum            = ['id', 'no_trx', 'tgl_daftar', 'jam_daftar', 'kode_member', 'kode_poli', 'kode_ruang', 'kode_dokter', 'no_antrian', 'tgl_keluar', 'jam_keluar', 'status_trx', 'kode_user', 'shift'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = 'tgl_daftar';
        $kondisi_param2   = 'kode_poli';
        $kondisi_param3   = 'kode_dokter';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan   = date('m');
            $tahun   = date('Y');
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2, $param3, $kondisi_param3);
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
            $row[]  = $rd->no_trx . '<br>' . (($rd->status_trx == 0) ? '<span class="badge badge-success">Buka</span>' : (($rd->status_trx == 2) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-primary">Selesai</span>'));
            $row[]  = 'No. RM: <span class="float-right">' . $rd->kode_member . '</span><hr>Nama: <span class="float-right">' . $this->M_global->getData('member', ['kode_member' => $rd->kode_member])->nama . '</span>';
            $row[]  = 'Datang: <span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_daftar)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_daftar)) . '</span><br>' .
                '<hr>Selesai: <span class="float-right">' . (($rd->status_trx < 1) ? '<i class="text-secondary">Null</i>' : (($rd->tgl_keluar == null) ? 'xx/xx/xxxx' : date('d/m/Y', strtotime($rd->tgl_keluar))) . ' ~ ' . (($rd->jam_keluar == null) ? 'xx:xx:xx' : date('H:i:s', strtotime($rd->jam_keluar)))) . '</span>';
            $row[]  = 'Dr. ' . $this->M_global->getData('dokter', ['kode_dokter' => $rd->kode_dokter])->nama . '<hr>(Poli: ' . $this->M_global->getData('m_poli', ['kode_poli' => $rd->kode_poli])->keterangan . ')';
            $row[]  = '<span>' . $this->M_global->getData('m_poli', ['kode_poli' => $rd->kode_poli])->keterangan . (($rd->kode_ruang == null) ? '' : ' (' . $this->M_global->getData('m_ruang', ['kode_ruang' => $rd->kode_ruang])->keterangan . ')</span>') . '<hr>No Urut <span class="float-right">' . $rd->no_antrian . '</span>';

            if ($rd->kode_dokter == $this->data['kode_user']) {
                $button = '<button type="button" style="margin-bottom: 5px; margin-right: 5px;" class="btn btn-primary" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" title="Dokter" onclick="getUrl(' . "'" . "Emr/dokter/" . $rd->no_trx . "'" . ')"><i class="fa-solid fa-user-doctor"></i> Doctor</button>';
            } else {
                $button = '<button type="button" style="margin-bottom: 5px; margin-right: 5px;" class="btn btn-success" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" title="Perawat" onclick="getUrl(' . "'" . "Emr/perawat/" . $rd->no_trx . "'" . ')"><i class="fa-solid fa-user-nurse"></i> Nurse</button>';
            }

            $row[]  = '<div class="d-flex justify-content-center">
                ' . $button . '
                <div class="btn-group dropstart" style="margin-bottom: 5px;">
                    <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-envelope-open-text"></i> Surat
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Surat Keterangan Sakit</a></li>
                        <li><a class="dropdown-item" href="#">Surat Keterangan Dokter</a></li>
                        <li><a class="dropdown-item" href="#">Surat Keterangan Diagnosa</a></li>
                        <li><a class="dropdown-item" href="#">Surat Keterangan Dalam Perawatan</a></li>
                    </ul>
                </div>
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

    // get satuan
    public function getSatuan($kode_barang)
    {
        $barang = $this->M_global->getData('barang', ['kode_barang' => $kode_barang]);

        if (!$barang) {
            echo json_encode(['error' => 'Barang not found']);
            return;
        }

        $kode_satuan_keys = [
            'kode_satuan',
            'kode_satuan2',
            'kode_satuan3'
        ];

        $satuan = [];

        foreach ($kode_satuan_keys as $key) {
            $kode_satuan = $barang->$key;
            $nama_satuan = $kode_satuan ? $this->M_global->getData('m_satuan', ['kode_satuan' => $kode_satuan])->keterangan : $kode_satuan;

            $satuan[] = [
                'kode_satuan' => $kode_satuan,
                'nama_satuan' => $nama_satuan
            ];
        }

        echo json_encode($satuan);
    }

    // histori px
    public function histori_px($no_trx)
    {
        $kode_member = $this->input->get('kode_member');
        $kode_dokter = $this->input->get('kode_dokter');

        if ($kode_dokter) {
            $where_dokter = ' AND kode_dokter = "' . $kode_dokter . '"';
        } else {
            $where_dokter = '';
        }

        $pendaftaran = $this->db->query('SELECT *, ROW_NUMBER() OVER (ORDER BY id DESC) AS eps FROM pendaftaran WHERE kode_member = "' . $kode_member . '" ' . $where_dokter . '  ORDER BY id DESC')->result();

        $no_his = count($pendaftaran);
        foreach ($pendaftaran as $p) : ?>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card" style="background-color: <?= ($p->no_trx == $no_trx) ? '#272a3f; color: white;' : 'white' ?>; border: 1px solid grey;">
                        <div class="card-header">
                            <span class="h4">Episode : <?= $no_his ?> <?= ($p->tipe_daftar == 1) ? '<span class="badge badge-danger float-right">Rawat Jalan</span>' : '<span class="badge badge-warning float-right">Rawat Inap</span>' ?></span>
                        </div>
                        <div class="card-footer">
                            <span class="h5">Status Pemeriksaan : <?= (($p->status_trx == 0) ? '<span class="badge badge-success">Buka</span>' : (($p->status_trx == 2) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-primary">Selesai</span>')) ?></span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td> : </td>
                                        <td>Masuk : <?= date('d-m-Y', strtotime($p->tgl_daftar)) ?> / <?= date('H:i', strtotime($p->jam_daftar)) ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>Keluar : <?= (!$p->tgl_keluar) ? 'xx-xx-xxxx' : date('d M y', strtotime($p->tgl_keluar)) ?> / <?= (!$p->jam_keluar) ? 'xx:xx' : date('H:i', strtotime($p->jam_keluar)) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Dokter</td>
                                        <td style="width: 5%;"> : </td>
                                        <td style="width: 65%;">Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $p->kode_dokter])->nama ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Poli</td>
                                        <td style="width: 5%;"> : </td>
                                        <td style="width: 65%;"><?= $this->M_global->getData('m_poli', ['kode_poli' => $p->kode_poli])->keterangan ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span class="font-weight-bold">Anamnesa</span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span>Isi Anamnesa</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span class="font-weight-bold">Pemerisaan Fisik</span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span>Isi Pemerisaan Fisik</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span class="font-weight-bold">Diagnosa</span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span>Isi Diagnosa</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span class="font-weight-bold">Terapi</span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span>Isi Terapi</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span class="font-weight-bold">Rencana</span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <span>Isi Rencana</span>
                                </div>
                            </div>
                        </div>
                        <?php if ($p->no_trx != $no_trx) : ?>
                            <div class="card-footer">
                                <button type="button" class="btn btn-info w-100" onclick="show_his('<?= $p->no_trx ?>')">Informasi Lanjutan &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-angles-right"></i></button>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
<?php $no_his--;
        endforeach;
    }


    // perawat page
    public function perawat($no_trx)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);


        $kode_dokter = $this->input->get('kode_dokter');
        if (!$kode_dokter) {
            $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
        } else {
            $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx, 'kode_dokter' => $kode_dokter]);
        }

        $parameter = [
            $this->data,
            'judul'         => 'EMR',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Perawat',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'param1'        => '',
            'pendaftaran'   => $pendaftaran,
            'no_trx'        => $no_trx,
            'kode_dokter'   => $kode_dokter,
        ];

        $this->template->load('Template/Content', 'Emr/Perawat', $parameter);
    }


    // dokter page
    public function dokter($no_trx)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);


        $kode_dokter = $this->input->get('kode_dokter');
        if (!$kode_dokter) {
            $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
        } else {
            $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx, 'kode_dokter' => $kode_dokter]);
        }

        $parameter = [
            $this->data,
            'judul'         => 'EMR',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Dokter',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'param1'        => '',
            'pendaftaran'   => $pendaftaran,
            'no_trx'        => $no_trx,
            'kode_dokter'   => $kode_dokter,
        ];

        $this->template->load('Template/Content', 'Emr/Dokter', $parameter);
    }
}
