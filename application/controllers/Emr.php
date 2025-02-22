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
                $status_per = '<span class="badge badge-info">Diperiksa Perawat</span>';
            } else {
                $status_per = '';
            }

            $cek_dok = $this->M_global->getData('emr_dok', ['no_trx' => $rd->no_trx]);
            if ($cek_dok) {
                $status_dok = '<span class="badge badge-primary">Diperiksa Dokter</span>';
            } else {
                $status_dok = '';
            }

            $row = [];
            $row[] = $no++;
            $row[] = $rd->no_trx . '<br>' . (($rd->status_trx == 0) ? '<span class="badge badge-success">Buka</span>' : (($rd->status_trx == 2) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-primary">Selesai</span>')) . '<br>' . $status_per . ' ' . $status_dok;
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
                        <div class="card-footer text-center">
                            <button type="button" class="btn btn-info" style="width: 49%;" <?= (($p->status_trx == 2) ? 'disabled' : '') ?> onclick="show_his('<?= $p->no_trx ?>', '<?= $no_his ?>', '<?= $p->kode_member ?>')">EMR Perawat &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-angles-right"></i></button>
                            <button type="button" class="btn btn-primary" style="width: 49%;" <?= (($p->status_trx == 2) ? 'disabled' : '') ?> onclick="show_his2('<?= $p->no_trx ?>', '<?= $no_his ?>', '<?= $p->kode_member ?>')">EMR Dokter &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-angles-right"></i></button>
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
                <span class="h4">Episode : <?= $eps ?> <?= ($p->tipe_daftar == 1) ? '<span class="badge badge-danger float-right">Rawat Jalan</span>' : '<span class="badge badge-warning float-right">Rawat Inap</span>' ?></span>
            </div>
            <div class="card-footer">
                <span class="h5">Status Pemeriksaan : <?= (($p->status_trx == 0) ? '<span class="badge badge-success">Buka</span>' : (($p->status_trx == 2) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-primary">Selesai</span>')) ?></span>
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
                            <?php if (!$cek_dokter) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_anamnesa')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement('<?= ((!empty($emr_per)) ? $emr_per->anamnesa_per : '') ?>', 'anamnesa_per')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_anamnesa"><?= ((!empty($emr_per)) ? $emr_per->anamnesa_per : '-') ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Pemeriksaan Fisik
                            <?php if (!$cek_dokter) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_pem_fisik')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement_fisik(
                                        '<?= ((!empty($emr_per)) ? $emr_per->tekanan_darah : '') ?>', 'tekanan_darah',
                                        '<?= ((!empty($emr_per)) ? $emr_per->nadi : '') ?>', 'nadi',
                                        '<?= ((!empty($emr_per)) ? $emr_per->suhu : '') ?>', 'suhu',
                                        '<?= ((!empty($emr_per)) ? $emr_per->bb : '') ?>', 'bb',
                                        '<?= ((!empty($emr_per)) ? $emr_per->tb : '') ?>', 'tb',
                                        '<?= ((!empty($emr_per)) ? $emr_per->pernapasan : '') ?>', 'pernapasan',
                                        '<?= ((!empty($emr_per)) ? $emr_per->saturasi : '') ?>', 'saturasi'
                                        )"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_pem_fisik"><?= ((!empty($emr_per)) ? ('Tekanan Darah : ' . $emr_per->tekanan_darah . ' (mmHg) | Nadi : ' . $emr_per->nadi . ' (x/mnt) | Suhu : ' . $emr_per->suhu . ' (°c) | Berat Badan : ' . $emr_per->bb . ' (kg) | Tinggi Badang : ' . $emr_per->tb . ' (cm) | Pernapasan : ' . $emr_per->pernapasan . ' (x/mnt) | Saturasi : ' . $emr_per->saturasi . ' (%)') : '-') ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Diagnosa
                            <?php if (!$cek_dokter) : ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_diagnosa')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement('<?= ((!empty($emr_per)) ? $emr_per->diagnosa_per : '') ?>', 'diagnosa_per')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_diagnosa"><?= ((!empty($emr_per)) ? $emr_per->diagnosa_per : '-') ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Terapi
                            <?php
                            if (!$cek_dokter) :
                            ?>
                                <div class="float-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="copyText('his_terapi')"><i class="fa fa-copy"></i> Copy</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="implement_err('<?= ((!empty($emr_per)) ? $emr_per->eracikan : '') ?>', 'eracikan', '<?= $p->no_trx ?>')"><i class="fa-solid fa-clone"></i> Apply</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_terapi">
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
                                            echo '@' . $tarif->nama . ' | ' . $et->qty . $br;
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

                                        if ($emr_per->eracikan != '') {
                                            echo '<br>' . $emr_per->eracikan;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </span>
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
                <span class="h4">Episode : <?= $eps ?> <?= ($p->tipe_daftar == 1) ? '<span class="badge badge-danger float-right">Rawat Jalan</span>' : '<span class="badge badge-warning float-right">Rawat Inap</span>' ?></span>
            </div>
            <div class="card-footer">
                <span class="h5">Status Pemeriksaan : <?= (($p->status_trx == 0) ? '<span class="badge badge-success">Buka</span>' : (($p->status_trx == 2) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-primary">Selesai</span>')) ?></span>
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
                            <?php if ($cek_dokter) : ?>
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
                            <?php if ($cek_dokter) : ?>
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
                            <?php if ($cek_dokter) : ?>
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
                                            echo '@' . $tarif->nama . ' | ' . $et->qty . $br;
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

                                        if ($emr_per->eracikan != '') {
                                            echo '<br>' . $emr_per->eracikan;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span id="his_terapi">
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
                                    echo $barang->nama . ' | ' . $epb->qty . ' ' . $satuan->keterangan . ' | ' . $epb->signa . $br;
                                endforeach;

                                if ($emr_per->eracikan != '') {
                                    echo '<br>' . $emr_per->eracikan;
                                }
                            }

                            ?>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Rencana
                            <?php if ($cek_dokter) : ?>
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

        $kode_barang          = $this->input->post('kode_barang');
        $kode_satuan          = $this->input->post('kode_satuan');
        $qty                  = $this->input->post('qty');
        $signa                = $this->input->post('signa');

        $kode_tarif           = $this->input->post('kode_tarif');
        $qty_tarif            = $this->input->post('qty_tarif');

        // tampung dalam array
        $data = [
            'no_trx'            => $no_trx,
            'kode_member'       => $kode_member,
            'umur'              => $umur,
            'date_per'          => $date_per,
            'time_per'          => $time_per,
            'penyakit_keluarga' => $penyakit_keluarga,
            'alergi'            => $alergi,
            'tekanan_darah'     => (($tekanan_darah) ? $tekanan_darah : '-'),
            'nadi'              => (($nadi) ? $nadi : '-'),
            'suhu'              => (($suhu) ? $suhu : '-'),
            'bb'                => (($bb) ? $bb : '-'),
            'tb'                => (($tb) ? $tb : '-'),
            'pernapasan'        => (($pernapasan) ? $pernapasan : '-'),
            'saturasi'          => (($saturasi) ? $saturasi : '-'),
            'scale'             => $scale,
            'bicara'            => $bicara,
            'gangguan'          => $gangguan,
            'emosi'             => $emosi,
            'spiritual'         => $spiritual,
            'diagnosa_per'      => $diagnosa_per,
            'anamnesa_per'      => $anamnesa_per,
            'eracikan'          => $eracikan,
            'kode_user'         => $this->data['kode_user'],
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
        } else { // selain itu maka tambah
            $cek = [
                $this->M_global->insertData('emr_per', $data),
                $this->M_global->delData('emr_per_barang', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_tarif', ['no_trx' => $no_trx]),
            ];
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
        ];

        $this->template->load('Template/Content', 'Emr/Dokter', $parameter);
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
            ];
        } else { // selain itu maka tambah
            $cek = [
                $this->M_global->insertData('emr_dok', $data),
                $this->M_global->delData('emr_per_barang', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_dok_fisik', ['no_trx' => $no_trx]),
                $this->M_global->delData('emr_tarif', ['no_trx' => $no_trx]),
            ];
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

        if ($cek) { // jika fungsi cek berjalan, maka status 1

            echo json_encode(['status' => 1]);
        } else { // selain itu status 0
            echo json_encode(['status' => 0]);
        }
    }
}
