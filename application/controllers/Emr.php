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
        $this->load->model("M_Emr");

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
            'list_data'     => '',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Emr/Daftar', $parameter);
    }

    // fungsi list daftar
    public function daftar_list($param1)
    {
        // Parameter untuk list table
        $kode_poli    = $this->input->get('kode_poli');
        $kode_dokter  = $this->input->get('kode_dokter');

        // Kondisi role
        $updated      = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted      = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        // Table server side tampung kedalam variable $list
        $dat          = explode("~", $param1);
        if ($dat[0] == 1) {
            $dari     = date('Y-m-d');
            $sampai   = date('Y-m-d');
            $tipe     = 1;
        } else {
            $dari     = date('Y-m-d', strtotime($dat[1])); // Extract month from date
            $sampai   = date('Y-m-d', strtotime($dat[2])); // Extract year from date
            $tipe     = 2;
        }

        $list         = $this->M_Emr->get_datatables($dari, $sampai, $kode_poli, $kode_dokter, $tipe);

        $data         = [];
        $no           = $_POST['start'] + 1;

        // Loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if (
                    $rd->status_trx == 2
                ) {
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

            if (
                $deleted > 0
            ) {
                if (
                    $rd->status_trx == 2
                ) {
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

            $cek_per = $this->M_global->getData('emr_per', ['no_trx' => $rd->no_trx]);
            if ($cek_per) {
                $status_per = '<span class="badge badge-sm badge-info">Diperiksa Perawat</span>';
            } else {
                $status_per = '';
            }

            $cek_dok = $this->M_global->getData('emr_dok', ['no_trx' => $rd->no_trx]);
            if ($cek_dok) {
                $status_dok = '<span class="badge badge-sm badge-primary">Diperiksa Dokter</span>';
            } else {
                $status_dok = '';
            }

            $row = [];
            $row[] = $no++;
            $row[] = $rd->no_trx . '<br>' . (($rd->status_trx == 0) ? '<span class="badge badge-sm badge-success">Buka</span>' : (($rd->status_trx == 2) ? '<span class="badge badge-sm badge-danger">Batal</span>' : '<span class="badge badge-sm badge-primary">Selesai</span>')) . '<br>' . $status_per . ' ' . $status_dok;
            $row[] = 'No. RM: <span class="float-right">' . $rd->kode_member . '</span><hr>Nama: <span class="float-right">' . $this->M_global->getData('member', ['kode_member' => $rd->kode_member])->nama . '</span>';
            $row[] = 'Datang: <span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_daftar)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_daftar)) . '</span><br>' .
                '<hr>Selesai: <span class="float-right">' . (($rd->status_trx < 1) ? '<i class="text-secondary">Null</i>' : (($rd->tgl_keluar == null) ? 'xx/xx/xxxx' : date('d/m/Y', strtotime($rd->tgl_keluar))) . ' ~ ' . (($rd->jam_keluar == null) ? 'xx:xx:xx' : date('H:i:s', strtotime($rd->jam_keluar)))) . '</span>';
            $row[] = 'Dr. ' . $this->M_global->getData('dokter', ['kode_dokter' => $rd->kode_dokter])->nama . '<hr>(Poli: ' . $this->M_global->getData('m_poli', ['kode_poli' => $rd->kode_poli])->keterangan . ')';
            $row[] = '<span>' . $this->M_global->getData('m_poli', ['kode_poli' => $rd->kode_poli])->keterangan . (($rd->kode_ruang == null) ? '' : ' (' . $this->M_global->getData('m_ruang', ['kode_ruang' => $rd->kode_ruang])->keterangan . ')</span>') . '<hr>No Urut <span class="float-right">' . $rd->no_antrian . '</span>';

            if ($rd->status_trx == 2) {
                $disabled = 'disabled';
            } else {
                $disabled = '';
            }

            if (
                $rd->kode_dokter == $this->data['kode_user']
            ) {
                $button = '<button ' . $disabled . ' type="button" style="margin-bottom: 5px; margin-right: 5px;" class="btn btn-primary" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" title="Dokter" onclick="getUrl(' . "'" . "Emr/dokter/" . $rd->no_trx . "'" . ')"><i class="fa-solid fa-user-doctor"></i> Doctor</button>';
            } else {
                $button = '<button ' . $disabled . ' type="button" style="margin-bottom: 5px; margin-right: 5px;" class="btn btn-success" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" title="Perawat" onclick="getUrl(' . "'" . "Emr/perawat/" . $rd->no_trx . "'" . ')"><i class="fa-solid fa-user-nurse"></i> Nurse</button>';
            }

            $row[] = '<div class="d-flex justify-content-center">
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

        // Hasil server side
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_Emr->count_all($dari, $sampai, $kode_poli, $kode_dokter, $tipe),
            "recordsFiltered" => $this->M_Emr->count_filtered($dari, $sampai, $kode_poli, $kode_dokter, $tipe),
            "data" => $data,
        ];

        // Kirimkan ke view
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

        if ($kode_dokter == '' || $kode_dokter == null || $kode_dokter == 'null') {
            $where_dokter = '';
        } else {
            $where_dokter = ' AND kode_dokter = "' . $kode_dokter . '"';
        }

        $pendaftaran = $this->db->query('SELECT *, ROW_NUMBER() OVER (ORDER BY id DESC) AS eps FROM pendaftaran WHERE kode_member = "' . $kode_member . '" ' . $where_dokter . '  ORDER BY id DESC')->result();

        $no_his = count($pendaftaran);
        foreach ($pendaftaran as $p) : ?>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card" style="background-color: <?= ($p->no_trx == $no_trx) ? '#272a3f; color: white;' : 'white' ?>; border: 1px solid grey;">
                        <div class="card-header">
                            <span class="h5">Kunj : <?= $no_his ?> <?= ($p->tipe_daftar == 1) ? '<span class="badge badge-sm badge-danger float-right">Jalan</span>' : '<span class="badge badge-sm badge-warning float-right">Inap</span>' ?></span>
                            <br>
                            <span style="font-size: 14px;"><?= (($p->status_trx == 0) ? '<span class="badge badge-sm badge-success">Buka</span>' : (($p->status_trx == 2) ? '<span class="badge badge-sm badge-danger">Batal</span>' : '<span class="badge badge-sm badge-primary">Selesai</span>')) ?></span>
                        </div>
                        <div class="card-footer text-center">
                            <button type="button" class="btn btn-sm btn-info" style="width: 49%;" <?= (($p->status_trx == 2) ? 'disabled' : '') ?> onclick="show_his('<?= $p->no_trx ?>', '<?= $no_his ?>', '<?= $p->kode_member ?>')"> Nurs &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-angles-right"></i></button>
                            <button type="button" class="btn btn-sm btn-primary" style="width: 49%;" <?= (($p->status_trx == 2) ? 'disabled' : '') ?> onclick="show_his2('<?= $p->no_trx ?>', '<?= $no_his ?>', '<?= $p->kode_member ?>')"> Doc &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-angles-right"></i></button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table style="font-size: 14px;">
                                    <tr>
                                        <td>Daftar</td>
                                        <td> : </td>
                                        <td><?= date('d M y', strtotime($p->tgl_daftar)) ?> / <?= date('H:i', strtotime($p->jam_daftar)) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Pulang</td>
                                        <td> : </td>
                                        <td><?= (!$p->tgl_keluar) ? 'xx-xx-xxxx' : date('d M y', strtotime($p->tgl_keluar)) ?> / <?= (!$p->jam_keluar) ? 'xx:xx' : date('H:i', strtotime($p->jam_keluar)) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Dokter</td>
                                        <td style="width: 5%;"> : </td>
                                        <td style="width: 65%;">Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $p->kode_dokter])->nama ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Poli</td>
                                        <td style="width: 5%;"> : </td>
                                        <td style="width: 65%;"><?= $this->M_global->getData('m_poli', ['kode_poli' => $p->kode_poli])->keterangan . ' (' . $this->M_global->getData('m_ruang', ['kode_ruang' => $p->kode_ruang])->keterangan . ')' ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Cabang</td>
                                        <td style="width: 5%;"> : </td>
                                        <td style="width: 65%;"><?= $this->M_global->getData('cabang', ['kode_cabang' => $p->kode_cabang])->cabang ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php $no_his--;
        endforeach;
    }

    // histori kunjungan px
    public function his_px($no_trx, $eps, $kode_member)
    {
        $pendaftaran    = $this->db->query('SELECT *, ROW_NUMBER() OVER (ORDER BY id DESC) AS eps FROM pendaftaran WHERE no_trx = "' . $no_trx . '"  ORDER BY id DESC')->result();

        $member         = $this->M_global->getData('member', ['kode_member' => $kode_member]);

        $prov           = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $member->provinsi])->provinsi;
        $kab            = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $member->kabupaten])->kabupaten;
        $kec            = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $member->kecamatan])->kecamatan;

        $alamat         = 'Prov. ' . $prov . ', ' . $kab . ', Kec. ' . $kec . ', Ds. ' . $member->desa . ', (POS: ' . $member->kodepos . '), RT.' . $member->rt . '/RW.' . $member->rw;

        $emr_per        = $this->M_global->getData('emr_per', ['no_trx' => $no_trx]);

        $cek_dokter     = $this->M_global->getData('dokter', ['kode_dokter' => $this->data['kode_user']]);

        $no_his         = count($pendaftaran);
        foreach ($pendaftaran as $p) : ?>
            <div class="card-header">
                <span class="h4">Kunj : <?= $eps ?> <?= ($p->tipe_daftar == 1) ? '<span class="badge badge-sm badge-danger float-right">Jalan</span>' : '<span class="badge badge-sm badge-warning float-right">Inap</span>' ?></span>
            </div>
            <div class="card-footer">
                <span class="h5">Status : <?= (($p->status_trx == 0) ? '<span class="badge badge-sm badge-success float-right">Buka</span>' : (($p->status_trx == 2) ? '<span class="badge badge-sm badge-danger float-right">Batal</span>' : '<span class="badge badge-sm badge-primary float-right">Selesai</span>')) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <tr>
                            <td style="width: 15%;" valign="top">No RM</td>
                            <td style="width: 5%;" valign="top"> : </td>
                            <td style="width: 80%;" valign="top"><?= $kode_member ?></td>
                        </tr>
                        <tr>
                            <td style="width: 15%;" valign="top">Nama</td>
                            <td style="width: 5%;" valign="top"> : </td>
                            <td style="width: 80%;" valign="top"><?= $member->nama ?></td>
                        </tr>
                        <tr>
                            <td style="width: 15%;" valign="top">Alamat</td>
                            <td style="width: 5%;" valign="top"> : </td>
                            <td style="width: 80%;" valign="top"><?= $alamat ?></td>
                        </tr>
                        <tr>
                            <td style="width: 15%;">Cabang</td>
                            <td style="width: 5%;"> : </td>
                            <td style="width: 80%;"><?= $this->M_global->getData('cabang', ['kode_cabang' => $p->kode_cabang])->cabang ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-footer card-outline card-primary">
                <div class="row mb-1">
                    <div class="col-dm-12">
                        <span class="font-weight-bold">Assesment
                            <?php if (!$cek_dokter) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyTextAssesment('sempoyongan_emr', 'berjalan_dgn_alat_emr', 'penompang_emr', 'keterangan_assesment_emr')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="implementAssesment(
                                                '<?= ((!empty($emr_per)) ? $emr_per->sempoyongan : '') ?>', 'sempoyongan',
                                                '<?= ((!empty($emr_per)) ? $emr_per->berjalan_dgn_alat : '') ?>', 'berjalan_dgn_alat',
                                                '<?= ((!empty($emr_per)) ? $emr_per->penompang : '') ?>', 'penompang',
                                                '<?= ((!empty($emr_per)) ? $emr_per->keterangan_assesment : '') ?>', 'keterangan_assesment',
                                            )"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table style="width: 100%; border-radius: 10px;" border="0" cellpadding="5px;">
                                <?php
                                $a1 = (!empty($emr_per) ? $emr_per->sempoyongan : 0);
                                $a2 = (!empty($emr_per) ? $emr_per->berjalan_dgn_alat : 0);
                                $b = (!empty($emr_per) ? $emr_per->penompang : 0);

                                if (($a1 == 1) || ($a2 == 1)) {
                                    $a = 1;
                                } else {
                                    $a = 0;
                                }

                                if (($a == 0) && ($b == 0)) {
                                    $hasil = 'Tidak Beresiko';
                                    $nilai = 'Tidak Ditemukan A & B';
                                } else if (($a == 1) || ($b == 1)) {
                                    $hasil = 'Beresiko Sedang';
                                    $nilai = 'Ditemukan Salah Satu Antara A & B';
                                } else {
                                    $hasil = 'Beresiko Tinggi';
                                    $nilai = 'Ditemukan A & B';
                                }
                                ?>
                                <tr>
                                    <td style="width: 20%;">Sempoyongan</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="sempoyongan_emr"><?= ((!empty($emr_per)) ? (($emr_per->sempoyongan == 1) ? 'Ya' : 'Tidak') : '') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Berjalan Dgn Alat</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="berjalan_dgn_alat_emr"><?= ((!empty($emr_per)) ? (($emr_per->berjalan_dgn_alat == 1) ? 'Ya' : 'Tidak') : '') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Penompang Duduk</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="penompang_emr"><?= ((!empty($emr_per)) ? (($emr_per->penompang == 1) ? 'Ya' : 'Tidak') : '') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Hasil</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="hasil_emr"><?= $hasil ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Nilai</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="nilai_emr"><?= $nilai ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Ket Lain</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="keterangan_assesment_emr"><?= ((!empty($emr_per)) ? $emr_per->keterangan_assesment : '-') ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Pemeriksaan Fisik
                            <?php if (!$cek_dokter) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            onclick="copyTextPemeriksaan('anamnesa_per_emr', 'diagnosa_per_emr', 'tekanan_darah_emr', 'nadi_emr', 'suhu_emr', 'bb_emr', 'tb_emr', 'pernapasan_emr', 'saturasi_emr', 'gizi_emr', 'hamil_emr', 'hpht_emr', 'keterangan_hamil_emr', 'scale_emr')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implementPemeriksaan(
                                                '<?= ((!empty($emr_per)) ? $emr_per->anamnesa_per : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->diagnosa_per : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->tekanan_darah : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->nadi : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->suhu : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->bb : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->tb : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->pernapasan : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->saturasi : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->gizi : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->hamil : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->hpht : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->keterangan_hamil : '') ?>',
                                                '<?= ((!empty($emr_per)) ? $emr_per->scale : '') ?>'
                                            )"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <input type="hidden" name="tekanan_darah_emr" id="tekanan_darah_emr" value="<?= (!empty($emr_per) ? $emr_per->tekanan_darah : '') ?>">
                            <input type="hidden" name="nadi_emr" id="nadi_emr" value="<?= (!empty($emr_per) ? $emr_per->nadi : '') ?>">
                            <input type="hidden" name="suhu_emr" id="suhu_emr" value="<?= (!empty($emr_per) ? $emr_per->nadi : '') ?>">
                            <input type="hidden" name="bb_emr" id="bb_emr" value="<?= (!empty($emr_per) ? $emr_per->nadi : '') ?>">
                            <input type="hidden" name="tb_emr" id="tb_emr" value="<?= (!empty($emr_per) ? $emr_per->nadi : '') ?>">
                            <input type="hidden" name="pernapasan_emr" id="pernapasan_emr" value="<?= (!empty($emr_per) ? $emr_per->nadi : '') ?>">
                            <input type="hidden" name="saturasi_emr" id="saturasi_emr" value="<?= (!empty($emr_per) ? $emr_per->nadi : '') ?>">
                            <input type="hidden" name="gizi_emr" id="gizi_emr" value="<?= (!empty($emr_per) ? $emr_per->nadi : '') ?>">
                            <table style="width: 100%; border-radius: 10px;" border="0" cellpadding="5px;">
                                <tr>
                                    <td style="width: 20%;">Anamnesa</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="anamnesa_per_emr"><?= ((!empty($emr_per)) ? $emr_per->anamnesa_per : '-') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Diagnosa</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="diagnosa_per_emr"><?= ((!empty($emr_per)) ? $emr_per->diagnosa_per : '-') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Pemeriksaan Fisik</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="his_pem_fisik"><?= ((!empty($emr_per)) ? ('Tekanan Darah : ' . $emr_per->tekanan_darah . ' (mmHg) | Nadi : ' . $emr_per->nadi . ' (x/mnt) | Suhu : ' . $emr_per->suhu . ' (°c) | Berat Badan : ' . $emr_per->bb . ' (kg) | Tinggi Badang : ' . $emr_per->tb . ' (cm) | Pernapasan : ' . $emr_per->pernapasan . ' (x/mnt) | Saturasi : ' . $emr_per->saturasi . ' (%) | Gizi : ' . (($emr_per->gizi == 0) ? 'Buruk' : (($emr_per->gizi == 1) ? 'Kurang' : (($emr_per->gizi == 2) ? 'Cukup' : ''))) . '') : '-') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Kehamilan</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="hamil_emr"><?= ((!empty($emr_per)) ? (($emr_per->hamil == 1) ? 'Ya' : 'Tidak') : 'Tidak') ?></span> / HPHT: <span id="hpht_emr"><?= ((!empty($emr_per)) ? (($emr_per->hpht != null) ? date('d-m-Y', strtotime($emr_per->hpht)) : '-') : '-') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;"></td>
                                    <td style="width: 5%;"></td>
                                    <td style="width: 75%;">
                                        Ket: <span id="keterangan_hamil_emr"><?= ((!empty($emr_per)) ? (($emr_per->keterangan_hamil == '') ? '-' : $emr_per->keterangan_hamil) : '-') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Skala Nyeri</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="scale_emr"><?= ((!empty($emr_per)) ? $emr_per->scale : '-') ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Psikologi & Spiritual
                            <?php if (!$cek_dokter) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyTextPsiko(
                                            '<?= (!empty($emr_per) ? $emr_per->bicara : '') ?>',
                                            '<?= (!empty($emr_per) ? $emr_per->emosi : '') ?>',
                                            '<?= (!empty($emr_per) ? $emr_per->spiritual : '') ?>',
                                            '<?= (!empty($emr_per) ? $emr_per->gangguan : '') ?>',
                                        )"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implementPsiko(
                                            '<?= (!empty($emr_per) ? $emr_per->bicara : '') ?>',
                                            '<?= (!empty($emr_per) ? $emr_per->emosi : '') ?>',
                                            '<?= (!empty($emr_per) ? $emr_per->spiritual : '') ?>',
                                            '<?= (!empty($emr_per) ? $emr_per->gangguan : '') ?>',
                                        )"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table style="width: 100%; border-radius: 10px;" border="0" cellpadding="5px;">
                                <tr>
                                    <td style="width: 20%;">Cara Bicara</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="bicara_emr"><?= (!empty($emr_per) ? ((($emr_per->bicara == 1) ? 'Bicara Normal' : 'Bicara Terganggu') . ', Ket: ' . (($emr_per->bicara == 2) ? $emr_per->gangguan : '')) : '') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Psikologi</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="psiko_emr"><?= (!empty($emr_per) ? (($emr_per->emosi == 1) ? 'Tenang' : (($emr_per->emosi == 2) ? 'Gelisah' : 'Emosional')) : '') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">Spiritual</td>
                                    <td style="width: 5%;"> : </td>
                                    <td style="width: 75%;">
                                        <span id="spiritual_emr"><?= (!empty($emr_per) ? (($emr_per->spiritual == 1) ? 'Berdiri' : (($emr_per->spiritual == 2) ? 'Duduk' : 'Berbaring')) : '') ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">E-Order
                            <?php if (!$cek_dokter) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <?php
                                        $tarif_text = '';
                                        $emr_tarif = $this->M_global->getDataResult('emr_tarif', ['no_trx' => $p->no_trx]);
                                        $emr_per_barang = $this->M_global->getDataResult('emr_per_barang', ['no_trx' => $p->no_trx]);
                                        if ($emr_tarif) {
                                            foreach ($emr_tarif as $et) {
                                                $tarif = $this->M_global->getData('m_tarif', ['kode_tarif' => $et->kode_tarif]);
                                                if ($tarif) {
                                                    $tarif_text .= '@' . $tarif->nama . ' | ' . $et->qty . ', ';
                                                } else {
                                                    $tarif_text .= '-';
                                                }
                                            }
                                        } else {
                                            $tarif_text .= '';
                                        }
                                        ?>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyTextOrder('<?= $tarif_text ?>')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implementOrder('<?= $no_trx ?>')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="text-primary"><b>Tarif / Tindakan</b></span>
                                <br>
                                <?php
                                if (empty($emr_tarif)) {
                                    echo '-';
                                } else {
                                    if (count($emr_tarif) > 1) {
                                        $br = '<br>';
                                    } else {
                                        $br = '';
                                    }

                                    foreach ($emr_tarif as $et) :
                                        $tarif = $this->M_global->getData('m_tarif', ['kode_tarif' => $et->kode_tarif]);
                                        if ($tarif) {
                                            echo '@' . $tarif->nama . ' | ' . $et->qty . $br;
                                        } else {
                                            echo '-';
                                        }
                                    endforeach;
                                }
                                ?>
                            </div>
                            <div class="col-md-6">
                                <span class="text-primary"><b>Resep / Racikan</b></span>
                                <br>
                                <?php
                                $emr_per_barang = $this->M_global->getDataResult('emr_per_barang', ['no_trx' => $p->no_trx]);
                                if (empty($emr_per_barang)) {
                                    echo '-';
                                } else {
                                    if (count($emr_per_barang) > 1) {
                                        $br = '<br>';
                                    } else {
                                        $br = '';
                                    }

                                    foreach ($emr_per_barang as $epb) :
                                        $barang = $this->M_global->getData('barang', ['kode_barang' => $epb->kode_barang]);
                                        $satuan = $this->M_global->getData('m_satuan', ['kode_satuan' => $epb->kode_satuan]);
                                        echo '@' . $barang->nama . ' | ' . $epb->qty . ' ' . $satuan->keterangan . ' | ' . $epb->signa . $br;
                                    endforeach;
                                }

                                if ($emr_per->eracikan != '') {
                                    echo '<br>' . $emr_per->eracikan;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php $no_his--;
        endforeach;
    }

    // histori kunjungan px2
    public function his_px2($no_trx, $eps, $kode_member)
    {
        $pendaftaran    = $this->db->query('SELECT *, ROW_NUMBER() OVER (ORDER BY id DESC) AS eps FROM pendaftaran WHERE no_trx = "' . $no_trx . '"  ORDER BY id DESC')->result();

        $member         = $this->M_global->getData('member', ['kode_member' => $kode_member]);

        $prov           = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $member->provinsi])->provinsi;
        $kab            = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $member->kabupaten])->kabupaten;
        $kec            = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $member->kecamatan])->kecamatan;

        $alamat         = 'Prov. ' . $prov . ', ' . $kab . ', Kec. ' . $kec . ', Ds. ' . $member->desa . ', (POS: ' . $member->kodepos . '), RT.' . $member->rt . '/RW.' . $member->rw;

        $emr_per        = $this->M_global->getData('emr_per', ['no_trx' => $no_trx]);
        $emr_dok        = $this->M_global->getData('emr_dok', ['no_trx' => $no_trx]);

        $cek_dokter     = $this->M_global->getData('dokter', ['kode_dokter' => $this->data['kode_user']]);

        $no_his         = count($pendaftaran);
        foreach ($pendaftaran as $p) : ?>
            <div class="card-header">
                <span class="h4">Kunj : <?= $eps ?> <?= ($p->tipe_daftar == 1) ? '<span class="badge badge-sm badge-danger float-right">Jalan</span>' : '<span class="badge badge-sm badge-warning float-right">Inap</span>' ?></span>
            </div>
            <div class="card-footer">
                <span class="h5">Status : <?= (($p->status_trx == 0) ? '<span class="badge badge-sm badge-success float-right">Buka</span>' : (($p->status_trx == 2) ? '<span class="badge badge-sm badge-danger float-right">Batal</span>' : '<span class="badge badge-sm badge-primary float-right">Selesai</span>')) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <tr>
                            <td style="width: 15%;" valign="top">No RM</td>
                            <td style="width: 5%;" valign="top"> : </td>
                            <td style="width: 80%;" valign="top"><?= $kode_member ?></td>
                        </tr>
                        <tr>
                            <td style="width: 15%;" valign="top">Nama</td>
                            <td style="width: 5%;" valign="top"> : </td>
                            <td style="width: 80%;" valign="top"><?= $member->nama ?></td>
                        </tr>
                        <tr>
                            <td style="width: 15%;" valign="top">Alamat</td>
                            <td style="width: 5%;" valign="top"> : </td>
                            <td style="width: 80%;" valign="top"><?= $alamat ?></td>
                        </tr>
                        <tr>
                            <td style="width: 15%;">Cabang</td>
                            <td style="width: 5%;"> : </td>
                            <td style="width: 80%;"><?= $this->M_global->getData('cabang', ['kode_cabang' => $p->kode_cabang])->cabang ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Anamnesa
                            <?php if (($cek_dokter) || ($this->session->userdata('kode_role') == 'R0001')) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_anamnesa')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement('<?= ((!empty($emr_dok)) ? $emr_dok->anamnesa_dok : '') ?>', 'anamnesa_dok')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_anamnesa"><?= ((!empty($emr_dok)) ? $emr_dok->anamnesa_dok : '-') ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Pemeriksaan Fisik
                            <?php if (($cek_dokter) || ($this->session->userdata('kode_role') == 'R0001')) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_pem_fisik')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement_fisik('<?= ((!empty($emr_dok)) ? $emr_dok->eracikan : '') ?>', 'eracikan', '<?= $p->no_trx ?>')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_pem_fisik">
                            <?php
                            $emr_dok_fisik = $this->M_global->getDataResult('emr_dok_fisik', ['no_trx' => $p->no_trx]);

                            if (empty($emr_dok_fisik)) {
                                echo '-';
                            } else {
                                if (count($emr_dok_fisik) > 1) {
                                    $br = '<br>';
                                } else {
                                    $br = '';
                                }

                                foreach ($emr_dok_fisik as $edf) :
                                    echo $edf->fisik . ' | ' . $edf->desc_fisik . $br;
                                endforeach;
                            }

                            ?>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Diagnosa
                            <?php if (($cek_dokter) || ($this->session->userdata('kode_role') == 'R0001')) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_diagnosa')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement('<?= ((!empty($emr_dok)) ? $emr_dok->diagnosa_dok : '') ?>', 'diagnosa_dok')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_diagnosa"><?= ((!empty($emr_dok)) ? $emr_dok->diagnosa_dok : '-') ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Terapi
                            <?php
                            if (($cek_dokter) || ($this->session->userdata('kode_role') == 'R0001')) :
                            ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_terapi')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement_err('<?= ((!empty($emr_dok)) ? $emr_dok->eracikan : '') ?>', 'eracikan', '<?= $p->no_trx ?>')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="text-primary"><b>Tarif / Tindakan</b></span>
                                <br>
                                <?php
                                $emr_tarif = $this->M_global->getDataResult('emr_tarif', ['no_trx' => $p->no_trx]);
                                if (empty($emr_tarif)) {
                                    echo '-';
                                } else {
                                    if (count($emr_tarif) > 1) {
                                        $br = '<br>';
                                    } else {
                                        $br = '';
                                    }

                                    foreach ($emr_tarif as $et) :
                                        $tarif = $this->M_global->getData('m_tarif', ['kode_tarif' => $et->kode_tarif]);
                                        if ($tarif) {
                                            echo '@' . $tarif->nama . ' | ' . number_format($et->qty) . $br;
                                        } else {
                                            echo '-';
                                        }
                                    endforeach;
                                }
                                ?>
                            </div>
                            <div class="col-md-6">
                                <span class="text-primary"><b>Resep / Racikan</b></span>
                                <br>
                                <?php
                                $emr_per_barang = $this->M_global->getDataResult('emr_per_barang', ['no_trx' => $p->no_trx]);
                                if (empty($emr_per_barang)) {
                                    echo '-';
                                } else {
                                    if (count($emr_per_barang) > 1) {
                                        $br = '<br>';
                                    } else {
                                        $br = '';
                                    }

                                    foreach ($emr_per_barang as $epb) :
                                        $barang = $this->M_global->getData('barang', ['kode_barang' => $epb->kode_barang]);
                                        $satuan = $this->M_global->getData('m_satuan', ['kode_satuan' => $epb->kode_satuan]);
                                        echo '@' . $barang->nama . ' | ' . $epb->qty . ' ' . $satuan->keterangan . ' | ' . $epb->signa . $br;
                                    endforeach;
                                }

                                if ($emr_dok->eracikan != '') {
                                    echo '<br>' . $emr_dok->eracikan;
                                }
                                ?>
                            </div>
                        </div>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Anjuran
                            <?php if (($cek_dokter) || ($this->session->userdata('kode_role') == 'R0001')) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_rencana')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement('<?= ((!empty($emr_dok)) ? $emr_dok->rencana_dok : '') ?>', 'rencana_dok')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_rencana">
                            <span id="his_rencana"><?= ((!empty($emr_dok)) ? $emr_dok->rencana_dok : '-') ?></span>
                        </span>
                    </div>
                </div>
            </div>
<?php $no_his--;
        endforeach;
    }

    // emr barang
    public function emr_per_barang($no_trx)
    {
        $emr_per_barang = $this->db->query('SELECT eb.*, (SELECT nama FROM barang WHERE kode_barang = eb.kode_barang) AS nama, (SELECT keterangan FROM m_satuan WHERE kode_satuan = eb.kode_satuan) AS satuan FROM emr_per_barang eb WHERE eb.no_trx = "' . $no_trx . '"')->result();

        echo json_encode($emr_per_barang);
    }

    // emr_tarif
    public function emr_tarif($no_trx)
    {
        $emr_tarif = $this->db->query('SELECT et.*, t.nama FROM emr_tarif et JOIN m_tarif t ON et.kode_tarif = t.kode_tarif WHERE et.no_trx = "' . $no_trx . '"')->result();

        echo json_encode($emr_tarif);
    }

    // emr fisik
    public function emr_dok_fisik($no_trx)
    {
        $emr_dok_fisik = $this->M_global->getDataResult('emr_dok_fisik', ['no_trx' => $no_trx]);

        echo json_encode($emr_dok_fisik);
    }

    // perawat page
    public function perawat($no_trx)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $cek_session     = $this->session->userdata('kode_user');
        $cek_sess_dokter = $this->M_global->getData('dokter', ['kode_dokter' => $cek_session]);

        if ($cek_sess_dokter) {
            redirect('Where');
        } else {
            $kode_dokter = $this->input->get('kode_dokter');
            if (!$kode_dokter) {
                $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
            } else {
                $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx, 'kode_dokter' => $kode_dokter]);
            }

            $parameter = [
                $this->data,
                'judul'             => 'EMR',
                'nama_apps'         => $web_setting->nama,
                'page'              => 'Perawat',
                'web'               => $web_setting,
                'web_version'       => $web_version->version,
                'list_data'         => '',
                'param1'            => '',
                'pendaftaran'       => $pendaftaran,
                'no_trx'            => $no_trx,
                'kode_dokter'       => $kode_dokter,
                'emr_per'           => $this->M_global->getData('emr_per', ['no_trx' => $no_trx]),
                'eresep'            => $this->M_global->getDataResult('emr_per_barang', ['no_trx' => $no_trx]),
                'etarif'            => $this->M_global->getDataResult('emr_tarif', ['no_trx' => $no_trx]),
            ];

            $this->template->load('Template/Content', 'Emr/Perawat', $parameter);
        }
    }

    // proses simpan/update perawat
    public function proses_per()
    {
        // ambil data dari view
        $no_trx               = htmlspecialchars($this->input->post('no_trx'));
        $kode_member          = htmlspecialchars($this->input->post('kode_member'));
        $umur                 = htmlspecialchars($this->input->post('umur'));
        $penyakit_keluarga    = htmlspecialchars($this->input->post('penyakit_keluarga'));
        $alergi               = htmlspecialchars($this->input->post('alergi'));
        $tekanan_darah        = htmlspecialchars($this->input->post('tekanan_darah'));
        $nadi                 = htmlspecialchars($this->input->post('nadi'));
        $suhu                 = htmlspecialchars($this->input->post('suhu'));
        $bb                   = htmlspecialchars($this->input->post('bb'));
        $tb                   = htmlspecialchars($this->input->post('tb'));
        $pernapasan           = htmlspecialchars($this->input->post('pernapasan'));
        $saturasi             = htmlspecialchars($this->input->post('saturasi'));
        $gizi                 = $this->input->post('gizi');
        $hamil                = $this->input->post('hamil');
        $hpht                 = $this->input->post('hpht');
        $keterangan_hamil     = htmlspecialchars($this->input->post('keterangan_hamil'));
        $scale                = htmlspecialchars($this->input->post('scale'));
        $bicara               = htmlspecialchars($this->input->post('bicara'));
        $gangguan             = htmlspecialchars($this->input->post('gangguan_bcr'));
        $emosi                = htmlspecialchars($this->input->post('emosi'));
        $spiritual            = htmlspecialchars($this->input->post('spiritual'));
        $diagnosa_per         = htmlspecialchars($this->input->post('diagnosa_per'));
        $anamnesa_per         = htmlspecialchars($this->input->post('anamnesa_per'));
        $eracikan             = htmlspecialchars($this->input->post('eracikan'));
        $date_per             = date('Y-m-d');
        $time_per             = date('H:i:s');
        $sempoyongan          = $this->input->post('sempoyongan');
        $berjalan_dgn_alat    = $this->input->post('berjalan_dgn_alat');
        $penompang            = $this->input->post('penompang');
        $keterangan_assesment = $this->input->post('keterangan_assesment');

        $kode_barang          = $this->input->post('kode_barang');
        $kode_satuan          = $this->input->post('kode_satuan');
        $qty                  = $this->input->post('qty');
        $signa                = $this->input->post('signa');

        $kode_tarif           = $this->input->post('kode_tarif');
        $qty_tarif            = $this->input->post('qty_tarif');

        // tampung dalam array
        $data = [
            'no_trx'                => $no_trx,
            'kode_member'           => $kode_member,
            'umur'                  => $umur,
            'date_per'              => $date_per,
            'time_per'              => $time_per,
            'sempoyongan'           => $sempoyongan,
            'berjalan_dgn_alat'     => $berjalan_dgn_alat,
            'penompang'             => $penompang,
            'keterangan_assesment'  => $keterangan_assesment,
            'penyakit_keluarga'     => $penyakit_keluarga,
            'alergi'                => $alergi,
            'tekanan_darah'         => (($tekanan_darah) ? $tekanan_darah : '-'),
            'nadi'                  => (($nadi) ? $nadi : '-'),
            'suhu'                  => (($suhu) ? $suhu : '-'),
            'bb'                    => (($bb) ? $bb : '-'),
            'tb'                    => (($tb) ? $tb : '-'),
            'pernapasan'            => (($pernapasan) ? $pernapasan : '-'),
            'saturasi'              => (($saturasi) ? $saturasi : '-'),
            'gizi'                  => (($gizi) ? $gizi : '-'),
            'hamil'                 => $hamil,
            'hpht'                  => $hpht,
            'keterangan_hamil'      => $keterangan_hamil,
            'scale'                 => $scale,
            'bicara'                => $bicara,
            'gangguan'              => $gangguan,
            'emosi'                 => $emosi,
            'spiritual'             => $spiritual,
            'diagnosa_per'          => $diagnosa_per,
            'anamnesa_per'          => $anamnesa_per,
            'eracikan'              => $eracikan,
            'kode_user'             => $this->data['kode_user'],
        ];

        // pengecekan data emr perawat
        $cek_emr_per = $this->M_global->getData('emr_per', ['no_trx' => $no_trx]);

        if ($cek_emr_per) { // jika ada data, maka update
            $cek = [
                $this->M_global->updateData('emr_per', $data, ['no_trx' => $no_trx]),
                $this->M_global->updateData('emr_dok', ['penyakit_keluarga' => $penyakit_keluarga, 'alergi' => $alergi, 'eracikan' => $eracikan], ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_per_barang', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_tarif', ['no_trx' => $no_trx]),
            ];

            aktifitas_user_transaksi('EMR', 'Mengubah Emr Perawat ' . $kode_member, $no_trx);
        } else { // selain itu maka tambah
            $cek = [
                $this->M_global->insertData('emr_per', $data),
                $this->M_global->delData('emr_per_barang', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_tarif', ['no_trx' => $no_trx]),
            ];

            aktifitas_user_transaksi('EMR', 'Menambahkan Emr Perawat ' . $kode_member, $no_trx);
        }

        $loop = 0;
        if (isset($kode_barang)) {
            foreach ($kode_barang as $k) {
                if ($k) {
                    $kode_barang_   = $k;
                    $kode_satuan_   = $kode_satuan[$loop];
                    $qty_           = $qty[$loop];
                    $signa_         = $signa[$loop];

                    $loop++;

                    $data_barang = [
                        'no_trx'        => $no_trx,
                        'kode_barang'   => $kode_barang_,
                        'kode_satuan'   => $kode_satuan_,
                        'qty'           => $qty_,
                        'signa'         => $signa_,
                    ];

                    $this->M_global->insertData('emr_per_barang', $data_barang);
                }
            }
        }

        $loop2 = 0;
        if (isset($kode_tarif)) {
            foreach ($kode_tarif as $kt) {
                if ($k) {
                    $kode_tarif_   = $kt;
                    $qty_tarif_    = $qty_tarif[$loop2];

                    $loop2++;

                    $data_tarif = [
                        'no_trx'        => $no_trx,
                        'kode_tarif'    => $kode_tarif_,
                        'qty'           => $qty_tarif_,
                    ];

                    $this->M_global->insertData('emr_tarif', $data_tarif);
                }
            }
        }

        if ($cek) { // jika fungsi cek berjalan, maka status 1

            echo json_encode(['status' => 1]);
        } else { // selain itu status 0
            echo json_encode(['status' => 0]);
        }
    }

    // dokter page
    public function dokter($no_trx)
    {
        $cek_session     = $this->session->userdata('kode_user');
        $cek_sess_dokter = $this->M_global->getData('dokter', ['kode_dokter' => $cek_session]);

        // cek apakah dia dokter ?
        if (($cek_sess_dokter) || ($this->session->userdata('kode_role') == 'R0001')) { // jika dokter
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
                'judul'             => 'EMR',
                'nama_apps'         => $web_setting->nama,
                'page'              => 'Dokter',
                'web'               => $web_setting,
                'web_version'       => $web_version->version,
                'list_data'         => '',
                'param1'            => '',
                'pendaftaran'       => $pendaftaran,
                'no_trx'            => $no_trx,
                'kode_dokter'       => $kode_dokter,
                'emr_per'           => $this->M_global->getData('emr_per', ['no_trx' => $no_trx]),
                'emr_dok'           => $this->M_global->getData('emr_dok', ['no_trx' => $no_trx]),
                'emr_dok_fisik'     => $this->M_global->getDataResult('emr_dok_fisik', ['no_trx' => $no_trx]),
                'eresep'            => $this->M_global->getDataResult('emr_per_barang', ['no_trx' => $no_trx]),
                'etarif'            => $this->M_global->getDataResult('emr_tarif', ['no_trx' => $no_trx]),
                'icd9'              => $this->M_global->getDataResult('emr_dok_icd9', ['no_trx' => $no_trx]),
                'icd10'             => $this->M_global->getDataResult('emr_dok_icd10', ['no_trx' => $no_trx]),
            ];

            $this->template->load('Template/Content', 'Emr/Dokter', $parameter);
        } else { // namun jika bukan dokter, arahkan ke page dokter
            redirect('Where'); // lempar ke url where
        }
    }

    // proses simpan/update dokter
    public function proses_dok()
    {
        // ambil data dari view
        $no_trx               = htmlspecialchars($this->input->post('no_trx'));
        $kode_member          = htmlspecialchars($this->input->post('kode_member'));
        $umur                 = htmlspecialchars($this->input->post('umur'));
        $penyakit_keluarga    = htmlspecialchars($this->input->post('penyakit_keluarga'));
        $alergi               = htmlspecialchars($this->input->post('alergi'));
        $diagnosa_dok         = htmlspecialchars($this->input->post('diagnosa_dok'));
        $anamnesa_dok         = htmlspecialchars($this->input->post('anamnesa_dok'));
        $rencana_dok          = htmlspecialchars($this->input->post('rencana_dok'));
        $eracikan             = htmlspecialchars($this->input->post('eracikan'));
        $date_dok             = date('Y-m-d');
        $time_dok             = date('H:i:s');

        $kode_barang          = $this->input->post('kode_barang');
        $kode_satuan          = $this->input->post('kode_satuan');
        $qty                  = $this->input->post('qty');
        $signa                = $this->input->post('signa');

        $fisik                = $this->input->post('fisik');
        $desc_fisik           = $this->input->post('desc_fisik');

        $kode_tarif           = $this->input->post('kode_tarif');
        $qty_tarif            = $this->input->post('qty_tarif');

        $icd9                 = $this->input->post('icd9');
        $icd10                = $this->input->post('icd10');

        // tampung dalam array
        $data = [
            'no_trx'            => $no_trx,
            'kode_member'       => $kode_member,
            'umur'              => $umur,
            'date_dok'          => $date_dok,
            'time_dok'          => $time_dok,
            'penyakit_keluarga' => $penyakit_keluarga,
            'alergi'            => $alergi,
            'diagnosa_dok'      => $diagnosa_dok,
            'anamnesa_dok'      => $anamnesa_dok,
            'rencana_dok'       => $rencana_dok,
            'eracikan'          => $eracikan,
            'kode_user'         => $this->data['kode_user'],
        ];

        // pengecekan data emr perawat
        $cek_emr_dok = $this->M_global->getData('emr_dok', ['no_trx' => $no_trx]);

        if ($cek_emr_dok) { // jika ada data, maka update
            $cek = [
                $this->M_global->updateData('emr_dok', $data, ['no_trx' => $no_trx]),
                $this->M_global->updateData('emr_per', ['penyakit_keluarga' => $penyakit_keluarga, 'alergi' => $alergi, 'eracikan' => $eracikan], ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_per_barang', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_dok_fisik', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_tarif', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_dok_icd9', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_dok_icd10', ['no_trx' => $no_trx]),
            ];

            aktifitas_user_transaksi('EMR', 'Mengubah Emr Dokter ' . $kode_member, $no_trx);
        } else { // selain itu maka tambah
            $cek = [
                $this->M_global->insertData('emr_dok', $data),
                $this->M_global->delData('emr_per_barang', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_dok_fisik', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_tarif', ['no_trx' => $no_trx]),
            ];

            aktifitas_user_transaksi('EMR', 'Menambahkan Emr Dokter ' . $kode_member, $no_trx);
        }

        $loop = 0;
        if (isset($kode_barang)) {
            foreach ($kode_barang as $k) {
                if ($k) {
                    $kode_barang_   = $k;
                    $kode_satuan_   = $kode_satuan[$loop];
                    $qty_           = $qty[$loop];
                    $signa_         = $signa[$loop];

                    $loop++;

                    $data_barang = [
                        'no_trx'        => $no_trx,
                        'kode_barang'   => $kode_barang_,
                        'kode_satuan'   => $kode_satuan_,
                        'qty'           => $qty_,
                        'signa'         => $signa_,
                    ];

                    $this->M_global->insertData('emr_per_barang', $data_barang);
                }
                $loop++;
            }
        }

        $loop2 = 0;
        if (isset($fisik)) {
            foreach ($fisik as $f) {
                if ($f) {
                    $fisik_        = $f;
                    $desc_fisik_   = $desc_fisik[$loop2];

                    $loop2++;

                    $data_fisik = [
                        'no_trx'        => $no_trx,
                        'fisik'         => $fisik_,
                        'desc_fisik'    => $desc_fisik_,
                    ];

                    $this->M_global->insertData('emr_dok_fisik', $data_fisik);
                }
            }
        }

        $loop3 = 0;
        if (isset($kode_tarif)) {
            foreach ($kode_tarif as $kt) {
                if ($f) {
                    $kode_tarif_  = $kt;
                    $qty_tarif_   = $qty_tarif[$loop3];

                    $loop3++;

                    $data_tarif = [
                        'no_trx'        => $no_trx,
                        'kode_tarif'    => $kode_tarif_,
                        'qty'           => $qty_tarif_,
                    ];

                    $this->M_global->insertData('emr_tarif', $data_tarif);
                }
            }
        }

        $loop4 = 0;
        if (isset($icd9)) {
            foreach ($icd9 as $i9) {
                if ($f) {
                    $kode_  = $i9;

                    $loop4++;

                    $data_icd9 = [
                        'no_trx'      => $no_trx,
                        'kode_icd'    => $kode_,
                    ];

                    $this->M_global->insertData('emr_dok_icd9', $data_icd9);
                }
            }
        }

        $loop5 = 0;
        if (isset($icd10)) {
            foreach ($icd10 as $i10) {
                if ($f) {
                    $kode_  = $i10;

                    $loop5++;

                    $data_icd10 = [
                        'no_trx'      => $no_trx,
                        'kode_icd'    => $kode_,
                    ];

                    $this->M_global->insertData('emr_dok_icd10', $data_icd10);
                }
            }
        }

        if ($cek) { // jika fungsi cek berjalan, maka status 1

            echo json_encode(['status' => 1]);
        } else { // selain itu status 0
            echo json_encode(['status' => 0]);
        }
    }

    public function getIcd($param, $key)
    {
        if ($param == 9) {
            $sintak = $this->db->query("SELECT kode AS id, CONCAT(kode, ', ', keterangan) AS text FROM icd9 WHERE kode LIKE '%$key%' OR keterangan LIKE '%$key%'")->row();
        } else {
            $sintak = $this->db->query("SELECT kode AS id, CONCAT(kode, ', ', keterangan) AS text FROM icd10 WHERE kode LIKE '%$key%' OR keterangan LIKE '%$key%'")->row();
        }

        echo json_encode($sintak);
    }
}
