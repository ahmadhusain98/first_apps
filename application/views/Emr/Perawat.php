<?php
$member = $this->M_global->getData('member', ['kode_member' => $pendaftaran->kode_member]);

$cek_session = $this->session->userdata('kode_user');
$cek_sess_dokter = $this->M_global->getData('dokter', ['kode_dokter' => $cek_session]);

$cek_jual = $this->M_global->getData('barang_out_header', ['no_trx' => $no_trx]);
if ($cek_jual) {
    $btn_diss = 'disabled';
    $readonly = 'readonly';
} else {
    $btn_diss = '';
    $readonly = '';
}

if ($pendaftaran->status_trx == 1) {
    $btn_sv = 'disabled';
} else {
    $btn_sv = '';
}

$kode_memberx = $pendaftaran->kode_member;

$last_notrx = $this->db->query('SELECT * FROM pendaftaran WHERE kode_member = ? ORDER BY id DESC LIMIT 1', [$kode_memberx])->row();

if ($last_notrx) {
    $riwayat = $this->db->query('SELECT * FROM emr_dok WHERE kode_member = ? AND no_trx <> ? ORDER BY id DESC', [$kode_memberx, $last_notrx->no_trx])->result();

    if (!empty($riwayat)) {
        $p_kel = [];
        $alr = [];
        foreach ($riwayat as $rwt) {
            if (!empty($rwt->penyakit_keluarga)) {
                $p_kel[] = $rwt->penyakit_keluarga;
            }
            if (!empty($rwt->alergi)) {
                $alr[] = $rwt->alergi;
            }
        }
    } else {
        $p_kel = '';
        $alr = '';
    }
} else {
    $p_kel = '';
    $alr = '';
}

if (is_array($p_kel) && !empty($p_kel)) {
    $p_kel = implode(", ", $p_kel);
} else {
    $p_kel = empty($p_kel) ? '' : $p_kel;
}

if (is_array($alr) && !empty($alr)) {
    $alr = implode(", ", $alr);
} else {
    $alr = empty($alr) ? '' : $alr;
}

?>

<div id="popup">
    <div class="card shadow card-lg" style="border: 1px solid grey;">
        <div class="card-header card-draggable">
            <span class="h4">
                History Pasien - Perawat
                <i type="button" class="fa fa-times float-right" onclick="close_popup()"></i>
            </span>
        </div>
        <div id="body_hispx" style="overflow-y: scroll; overflow-x: hidden; height: 70vh; width: 100%;"></div>
    </div>
</div>

<div id="popup2">
    <div class="card shadow card-lg" style="border: 1px solid grey;">
        <div class="card-header card-draggable2">
            <span class="h4">
                History Pasien - Dokter
                <i type="button" class="fa fa-times float-right" onclick="close_popup2()"></i>
            </span>
        </div>
        <div id="body_hispx2" style="overflow-y: scroll; overflow-x: hidden; height: 70vh; width: 100%;"></div>
    </div>
</div>

<div class="form-container">
    <form id="form_emr_perawat">
        <input type="hidden" name="no_trx" id="no_trx" value="<?= $no_trx ?>">
        <input type="hidden" name="kode_member" id="kode_member" value="<?= $pendaftaran->kode_member ?>">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-outline card-primary" style="position: fixed; width: 19%;">
                    <div class="card-header">
                        <span class="font-weight-bold h4 text-primary">History Pasien</span>
                    </div>
                    <div class="card-body">
                        <select name="filter_dokter" id="filter_dokter" class="form-control select2_dokter_all" data-placeholder="~ Pilih Dokter" onchange="history_px()">
                            <?php if ($cek_sess_dokter) : ?>
                                <?php if (!empty($kode_dokter)) : ?>
                                    <option value="<?= $kode_dokter ?>">Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $kode_dokter])->nama ?></option>
                                <?php else : ?>
                                    <option value="<?= ((!empty($pendaftaran)) ? $pendaftaran->kode_dokter : '') ?>"><?= ((!empty($pendaftaran)) ? 'Dr. ' . $this->M_global->getData('dokter', ['kode_dokter' => $pendaftaran->kode_dokter])->nama : '') ?></option>
                                <?php endif ?>
                            <?php endif ?>
                        </select>
                        <hr>
                        <div id="body_history" style="overflow-y: scroll; overflow-x: hidden; height: 45vh; width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> EMR Perawat</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Nomor RM</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="kode_member2" name="kode_member2" value="<?= (($pendaftaran) ? (($member) ? $member->kode_member : '') : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Nama</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="name_member" name="name_member" value="<?= (($pendaftaran) ? (($member) ? $member->nama : '') : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Tempat / Tgl Lahir</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir" value="<?= (($pendaftaran) ? (($member) ? $member->tmp_lahir : '') : '') ?>" readonly>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= (($pendaftaran) ? (($member) ? $member->tgl_lahir : '') : '')  ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Gender / Umur</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="hidden" class="form-control" id="jkel" name="jkel" value="<?= (($pendaftaran) ? (($member) ? $member->jkel : '') : '') ?>" readonly>
                                                <input type="text" class="form-control" id="jkel1" name="jkel1" value="<?= (($pendaftaran) ? (($member) ? (($member->jkel == 'P') ? 'Pria' : 'Wanita') : '') : '') ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="umur" name="umur" value="<?= (($pendaftaran) ? (($member) ? hitung_umur($member->tgl_lahir) : '0 Tahun') : '0 Tahun') ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Alamat</label>
                                    <div class="col-md-9">
                                        <?php
                                        $prov           = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $member->provinsi])->provinsi;
                                        $kab            = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $member->kabupaten])->kabupaten;
                                        $kec            = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $member->kecamatan])->kecamatan;

                                        $alamat         = 'Prov. ' . $prov . ', ' . $kab . ', Kec. ' . $kec . ', Ds. ' . $member->desa . ', (POS: ' . $member->kodepos . '), RT.' . $member->rt . '/RW.' . $member->rw;
                                        ?>
                                        <textarea name="alamat" id="alamat" class="form-control" readonly rows="5"><?= $alamat ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Poli</label>
                                    <div class="col-md-9">
                                        <input type="hidden" class="form-control" id="kode_poli" name="kode_poli" value="<?= ($pendaftaran) ? $pendaftaran->kode_poli : '' ?>" readonly>
                                        <input type="text" class="form-control" id="kode_poli1" name="kode_poli1" value="<?= ($pendaftaran) ? $this->M_global->getData('m_poli', ['kode_poli' => $pendaftaran->kode_poli])->keterangan : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Dokter</label>
                                    <div class="col-md-9">
                                        <input type="hidden" class="form-control" id="kode_dokter" name="kode_dokter" value="<?= ($pendaftaran) ? $pendaftaran->kode_dokter : '' ?>" readonly>
                                        <input type="text" class="form-control" id="kode_dokter1" name="kode_dokter1" value="Dr. <?= ($pendaftaran) ? $this->M_global->getData('dokter', ['kode_dokter' => $pendaftaran->kode_dokter])->nama : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Perawat</label>
                                    <?php
                                    if (!empty($emr_per)) {
                                        $kode_per = $emr_per->kode_user;
                                    } else {
                                        $kode_per = $this->session->userdata('kode_user');
                                    }
                                    ?>
                                    <div class="col-md-9">
                                        <input type="hidden" class="form-control" id="kode_perawat" name="kode_perawat" value="<?= $kode_per ?>" readonly>
                                        <input type="text" class="form-control" id="kode_dokter1" name="kode_dokter1" value="<?= $this->M_global->getData('user', ['kode_user' => $kode_per])->nama ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Episode</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="episode" name="episode" value="<?= ($pendaftaran) ? count($this->M_global->getDataResult('pendaftaran', ['kode_member' => $pendaftaran->kode_member])) : '0' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Ruang / Bed</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <input type="text" class="form-control" id="kode_ruang" name="kode_ruang" value="<?= ($pendaftaran) ? $this->M_global->getData('m_ruang', ['kode_ruang' => $pendaftaran->kode_ruang])->keterangan : '' ?>" readonly>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <input type="text" class="form-control" id="kode_bed" name="kode_bed" value="<?= ($pendaftaran) ? (($pendaftaran->kode_bed != '') ? $this->M_global->getData('bed', ['kode_bed' => $pendaftaran->kode_bed])->nama_bed : '-') : '-' ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center card-outline card-primary">
                        <button type="button" id="btn_assesment" class="btn btn-primary" onclick="sel_tab_emr(1)">Assesment</button>
                        <button type="button" id="btn_pemeriksaan" class="btn" onclick="sel_tab_emr(2)">Pemeriksaan</button>
                        <button type="button" id="btn_psiko" class="btn" onclick="sel_tab_emr(3)">Psikologi & Spiritual</button>
                        <button type="button" id="btn_order" class="btn" onclick="sel_tab_emr(4)">E-Order</button>
                    </div>
                    <div class="card-body">
                        <div id="assesment_emr">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="sempoyongan" class="form-label col-md-3">Sempoyongan</label>
                                        <div class="col-md-9">
                                            <select name="sempoyongan" id="sempoyongan" class="form-control select2_global" data-placeholder="~ Pilih Cara Berjalan" onchange="cek_resiko()">
                                                <option value="">~ Pilih Cara Berjalan</option>
                                                <option value="0" <?= (!empty($emr_per) ? (($emr_per->sempoyongan == 0) ? 'selected' : '') : 'selected') ?>>Tidak</option>
                                                <option value="1" <?= (!empty($emr_per) ? (($emr_per->sempoyongan == 1) ? 'selected' : '') : '') ?>>Ya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="berjalan_dgn_alat" class="form-label col-md-3">Berjalan dgn Alat</label>
                                        <div class="col-md-9">
                                            <select name="berjalan_dgn_alat" id="berjalan_dgn_alat" class="form-control select2_global" data-placeholder="~ Pilih Cara Berjalan" onchange="cek_resiko()">
                                                <option value="">~ Pilih Cara Berjalan</option>
                                                <option value="0" <?= (!empty($emr_per) ? (($emr_per->berjalan_dgn_alat == 0) ? 'selected' : '') : 'selected') ?>>Tidak</option>
                                                <option value="1" <?= (!empty($emr_per) ? (($emr_per->berjalan_dgn_alat == 1) ? 'selected' : '') : '') ?>>Ya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="penompang" class="form-label col-md-3">Penompang duduk</label>
                                        <div class="col-md-9">
                                            <select name="penompang" id="penompang" class="form-control select2_global" data-placeholder="~ Pilih Penompang" onchange="cek_resiko()">
                                                <option value="">~ Pilih Cara Berjalan</option>
                                                <option value="0" <?= (!empty($emr_per) ? (($emr_per->penompang == 0) ? 'selected' : '') : 'selected') ?>>Tidak</option>
                                                <option value="1" <?= (!empty($emr_per) ? (($emr_per->penompang == 1) ? 'selected' : '') : '') ?>>Ya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="hasil" class="form-label col-md-3">Hasil</label>
                                        <div class="col-md-9">
                                            <input type="text" name="hasil" id="hasil" class="form-control" readonly value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="nilai" class="form-label col-md-3">Nilai</label>
                                        <div class="col-md-9">
                                            <input type="text" name="nilai" id="nilai" class="form-control" readonly value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <textarea name="keterangan_assesmnet" id="keterangan_assesmnet" class="form-control" rows="3" placeholder="Keterangan Lain"><?= (!empty($emr_per) ? $emr_per->keterangan_assesment : '') ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div id="pemeriksaan_emr">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="anamnesa_per" class="form-label col-md-3">Anamnesa **</label>
                                        <div class="col-md-9">
                                            <textarea name="anamnesa_per" id="anamnesa_per" class="form-control" rows="3" placeholder="Anamnesa Perawat..."><?= ((!empty($emr_per)) ? $emr_per->anamnesa_per : '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="diagnosa_per" class="form-label col-md-3">Diagnosa **</label>
                                        <div class="col-md-9">
                                            <textarea name="diagnosa_per" id="diagnosa_per" class="form-control" rows="3" placeholder="Diagnosa Perawat..."><?= ((!empty($emr_per)) ? $emr_per->diagnosa_per : '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="penyakit_keluarga_his" class="form-label col-md-3">Penyakit Keluarga</label>
                                        <div class="col-md-9">
                                            <input type="text" id="penyakit_keluarga_his" name="penyakit_keluarga_his" class="form-control mb-3" readonly value="<?= $p_kel ?>">
                                            <textarea name="penyakit_keluarga" id="penyakit_keluarga" class="form-control" rows="3" placeholder="Penyakit Baru..."><?= ((!empty($emr_per)) ? $emr_per->penyakit_keluarga : '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="alergi_his" class="form-label col-md-3">Alergi</label>
                                        <div class="col-md-9">
                                            <input type="text" id="alergi_his" name="alergi_his" class="form-control mb-3" readonly value="<?= $alr ?>">
                                            <textarea name="alergi" id="alergi" class="form-control" rows="3" placeholder="Alergi Baru..."><?= ((!empty($emr_per)) ? $emr_per->alergi : '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="tekanan_darah" class="form-label col-md-3">Tekanan Darah</label>
                                        <div class="col-md-9">
                                            <input type="text" id="tekanan_darah" name="tekanan_darah" class="form-control" placeholder="mmHg" value="<?= ((!empty($emr_per)) ? $emr_per->tekanan_darah : '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="nadi" class="form-label col-md-3">Nadi</label>
                                        <div class="col-md-9">
                                            <input type="text" id="nadi" name="nadi" class="form-control" placeholder="x/mnt" value="<?= ((!empty($emr_per)) ? $emr_per->nadi : '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="suhu" class="form-label col-md-3">Suhu</label>
                                        <div class="col-md-9">
                                            <input type="text" id="suhu" name="suhu" class="form-control" placeholder="°c" value="<?= ((!empty($emr_per)) ? $emr_per->suhu : '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="bb" class="form-label col-md-3">Berat Badan</label>
                                        <div class="col-md-9">
                                            <input type="text" id="bb" name="bb" class="form-control" placeholder="kg" value="<?= ((!empty($emr_per)) ? $emr_per->bb : '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="tb" class="form-label col-md-3">Tinggi Badan</label>
                                        <div class="col-md-9">
                                            <input type="text" id="tb" name="tb" class="form-control" placeholder="cm" value="<?= ((!empty($emr_per)) ? $emr_per->tb : '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="pernapasan" class="form-label col-md-3">Pernapasan</label>
                                        <div class="col-md-9">
                                            <input type="text" id="pernapasan" name="pernapasan" class="form-control" placeholder="x/mnt" value="<?= ((!empty($emr_per)) ? $emr_per->pernapasan : '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="saturasi" class="form-label col-md-3">Saturasi O2</label>
                                        <div class="col-md-9">
                                            <input type="text" id="saturasi" name="saturasi" class="form-control" placeholder="%" value="<?= ((!empty($emr_per)) ? $emr_per->saturasi : '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="gizi" class="form-label col-md-3">Status Gizi</label>
                                        <div class="col-md-9">
                                            <select name="gizi" id="gizi" class="form-control select2_global" data-placeholder="~ Pilih Status Gizi">
                                                <option value="">~ Pilih Status Gizi</option>
                                                <option value="0" <?= (!empty($emr_per) ? (($emr_per->gizi == 0) ? 'selected' : '') : '') ?>>Gizi Buruk</option>
                                                <option value="1" <?= (!empty($emr_per) ? (($emr_per->gizi == 1) ? 'selected' : '') : '') ?>>Gizi Kurang</option>
                                                <option value="2" <?= (!empty($emr_per) ? (($emr_per->gizi == 2) ? 'selected' : '') : 'selected') ?>>Gizi Cukup</option>
                                                <option value="3" <?= (!empty($emr_per) ? (($emr_per->gizi == 3) ? 'selected' : '') : '') ?>>Gizi Lebih</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="hamil" class="form-label col-md-3">Status Hamil</label>
                                        <div class="col-md-9">
                                            <select name="hamil" id="hamil" class="form-control select2_global" data-placeholder="~ Pilih Status">
                                                <option value="">~ Pilih Status</option>
                                                <option value="0" <?= (!empty($emr_per) ? (($emr_per->hamil == 0) ? 'selected' : '') : 'selected') ?>>Tidak</option>
                                                <option value="1" <?= (!empty($emr_per) ? (($emr_per->hamil == 1) ? 'selected' : '') : '') ?>>Ya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="hpht" class="form-label col-md-3">HPHT</label>
                                        <div class="col-md-9">
                                            <input type="date" name="hpht" id="hpht" class="form-control" value="<?= (!empty($emr_per) ? (($emr_per->hpht != null) ? date('Y-m-d', strtotime($emr_per->hpht)) : '') : '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <textarea name="keterangan_hamil" id="keterangan_hamil" class="form-control" rows="3" placeholder="Keterangan Hamil..."><?= (!empty($emr_per) ? $emr_per->keterangan_hamil : '') ?></textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <img src="<?= base_url() ?>assets/img/emr/scale.jpg" width="100%">
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <span class="h5 font-weight-bold">Skala Nyeri</span>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <input type="hidden" id="scale" name="scale" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->scale : '1') ?>">
                                                <table>
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <input type="checkbox" id="scale1" class="form-control" onclick="cek_scale('1')">
                                                        </td>
                                                        <td style="width: 80%;">
                                                            <label for="">&nbsp; (Skala Nyeri 0-1) Tidak Ada Rasa Sakit</label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <input type="checkbox" id="scale2" class="form-control" onclick="cek_scale('2')">
                                                        </td>
                                                        <td style="width: 80%;">
                                                            <label for="">&nbsp; (Skala Nyeri 2-3) Nyeri Ringan</label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <input type="checkbox" id="scale3" class="form-control" onclick="cek_scale('3')">
                                                        </td>
                                                        <td style="width: 80%;">
                                                            <label for="">&nbsp; (Skala Nyeri 4-5) Nyeri Sedang</label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <input type="checkbox" id="scale4" class="form-control" onclick="cek_scale('4')">
                                                        </td>
                                                        <td style="width: 80%;">
                                                            <label for="">&nbsp; (Skala Nyeri 6-7) Nyeri Parah</label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <input type="checkbox" id="scale5" class="form-control" onclick="cek_scale('5')">
                                                        </td>
                                                        <td style="width: 80%;">
                                                            <label for="">&nbsp; (Skala Nyeri 8-9) Nyeri Sangat Parah</label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <input type="checkbox" id="scale6" class="form-control" onclick="cek_scale('6')">
                                                        </td>
                                                        <td style="width: 80%;">
                                                            <label for="">&nbsp; (Skala Nyeri 10 >) Nyeri Sangat Buruk</label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="psiko_emr">
                            <div class="row mb-3">
                                <div class="col-md-2 my-auto">
                                    <label for="" class="form-label">Cara Bicara</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="table-responsive">
                                        <input type="hidden" id="bicara" name="bicara" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->bicara : '1') ?>">
                                        <table cellpadding="10px" width="100%">
                                            <tr>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="bicara1" class="form-control" onclick="cek_bcr(1)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Bicara Normal</span>
                                                </td>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="bicara2" class="form-control" onclick="cek_bcr(2)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Bicara Terganggu</span>
                                                </td>
                                                <td style="width: 40%;">
                                                    <textarea name="gangguan_bcr" id="gangguan_bcr" class="form-control" rows="1" placeholder="Keterangan Gangguan Bicara..."><?= ((!empty($emr_per)) ? $emr_per->gangguan : '') ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2 my-auto">
                                    <label for="" class="form-label">Psikologi</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="table-responsive">
                                        <input type="hidden" id="emosi" name="emosi" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->emosi : '1') ?>">
                                        <table cellpadding="10px" width="100%">
                                            <tr>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="emosi1" class="form-control" onclick="cek_emosi(1)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Tenang</span>
                                                </td>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="emosi2" class="form-control" onclick="cek_emosi(2)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Gelisah</span>
                                                </td>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="emosi3" class="form-control" onclick="cek_emosi(3)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Emosional</span>
                                                </td>
                                                <td style="width: 15%;"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2 my-auto">
                                    <label for="" class="form-label">Spiritual</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="table-responsive">
                                        <input type="hidden" id="spiritual" name="spiritual" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->spiritual : '1') ?>">
                                        <table cellpadding="10px" width="100%">
                                            <tr>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="spiritual1" class="form-control" onclick="cek_spiritual(1)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Berdiri</span>
                                                </td>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="spiritual2" class="form-control" onclick="cek_spiritual(2)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Duduk</span>
                                                </td>
                                                <td style="width: 5%;">
                                                    <input type="radio" id="spiritual3" class="form-control" onclick="cek_spiritual(3)">
                                                </td>
                                                <td style="width: 25%;">
                                                    <span for="">&nbsp;&nbsp;&nbsp; Berbaring</span>
                                                </td>
                                                <td style="width: 15%;"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="order_emr">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <button class="btn btn-primary w-100 mb-1" type="button" onclick="sel_tab(0)" id="btn_etarif">Tindakan</button>
                                            <button class="btn btn-light w-100 mb-1" type="button" onclick="sel_tab(1)" id="btn_eresep">Resep</button>
                                            <!-- <button class="btn btn-light w-100 mb-1" type="button" onclick="sel_tab(2)" id="btn_elab">E-Laboratorium</button>
                                        <button class="btn btn-light w-100 mb-1" type="button" onclick="sel_tab(3)" id="btn_erad">E-Radiologi</button> -->
                                        </div>
                                        <div class="col-md-10">
                                            <div class="card w-100 h-100">
                                                <div class="card-header">
                                                    <span class="h4" id="title_tab">Tindakan</span>
                                                </div>
                                                <div class="card-body">
                                                    <div id="tab_etarif">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover table-bordered" id="table_etarif" width="100%" style="border-radius: 10px;">
                                                                        <thead>
                                                                            <tr class="text-center">
                                                                                <th width="5%" style="border-radius: 10px 0px 0px 0px;">Hapus</th>
                                                                                <th width="85%">Tindakan</th>
                                                                                <th width="10%">Qty</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="body_etarif">
                                                                            <?php if (!empty($eresep)) : ?>
                                                                                <?php $no_etarif = 1;
                                                                                foreach ($etarif as $et) : ?>
                                                                                    <tr id="row_etarif<?= $no_etarif ?>">
                                                                                        <td class="text-center">
                                                                                            <button class="btn btn-sm btn-danger" type="button" id="btnHapusT<?= $no_etarif ?>" onclick="hapusTarif('<?= $no_etarif ?>')"><i class="fa-solid fa-delete-left"></i></button>
                                                                                        </td>
                                                                                        <td>
                                                                                            <select name="kode_tarif[]" id="kode_tarif<?= $no_etarif ?>" class="form-control select2_tarif_single" data-placeholder="~ Pilih Tindakan">
                                                                                                <?php $tarif = $this->M_global->getData('m_tarif', ['kode_tarif' => $et->kode_tarif]); ?>
                                                                                                <option value="<?= $et->kode_tarif ?>"><?= $tarif->nama ?></option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="text" id="qty_tarif<?= $no_etarif ?>" name="qty_tarif[]" value="<?= number_format($et->qty) ?>" min="1" class="form-control text-right" onchange="formatRp(this.value, 'qty_tarif<?= $no_etarif ?>')">
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php $no_etarif++;
                                                                                endforeach ?>
                                                                            <?php else : ?>
                                                                                <tr id="row_etarif1">
                                                                                    <td class="text-center">
                                                                                        <button class="btn btn-sm btn-danger" type="button" id="btnHapusT1" onclick="hapusTarif('1')"><i class="fa-solid fa-delete-left"></i></button>
                                                                                    </td>
                                                                                    <td>
                                                                                        <select name="kode_tarif[]" id="kode_tarif1" class="form-control select2_tarif_single" data-placeholder="~ Pilih Tindakan">
                                                                                            <option value="">~ Pilih Tindakan</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" id="qty_tarif1" name="qty_tarif[]" value="1" min="1" class="form-control text-right" onchange="formatRp(this.value, 'qty_tarif1')">
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-primary" onclick="addTarif()" id="btnCari" <?= $btn_diss ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                                                                        <button type="button" class="btn btn-danger float-right" onclick="emptyTarif()" id="btnEmpty" <?= $btn_diss ?>><i class="fa-solid fa-trash"></i>&nbsp;&nbsp;Hapus Semua</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="tab_eresep">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <span class="h4">Resep</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover table-bordered" id="table_eresep" width="100%" style="border-radius: 10px;">
                                                                                <thead>
                                                                                    <tr class="text-center">
                                                                                        <th width="5%" style="border-radius: 10px 0px 0px 0px;">Hapus</th>
                                                                                        <th width="30%">Barang</th>
                                                                                        <th width="15%">Satuan</th>
                                                                                        <th width="15%">Qty</th>
                                                                                        <th width="35%" style="border-radius: 0px 10px 0px 0px;">Signa</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="body_eresep">
                                                                                    <?php if (!empty($eresep)) : ?>
                                                                                        <?php $no_eresep = 1;
                                                                                        foreach ($eresep as $er) : ?>
                                                                                            <tr id="row_eresep<?= $no_eresep ?>">
                                                                                                <td width="5%" class="text-center">
                                                                                                    <button class="btn btn-sm btn-danger" type="button" id="btnHapus<?= $no_eresep ?>" onclick="hapusBarang('<?= $no_eresep ?>')" <?= $btn_diss ?>><i class="fa-solid fa-delete-left"></i></button>
                                                                                                </td>
                                                                                                <td width="30%">
                                                                                                    <select name="kode_barang[]" id="kode_barang<?= $no_eresep ?>" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '<?= $no_eresep ?>')">
                                                                                                        <?php
                                                                                                        $barang = $this->M_global->getData('barang', ['kode_barang' => $er->kode_barang]);
                                                                                                        ?>
                                                                                                        <option value="<?= $er->kode_barang ?>"><?= $barang->nama ?></option>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td width="15%">
                                                                                                    <select name="kode_satuan[]" id="kode_satuan<?= $no_eresep ?>" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                                                                                                        <?php
                                                                                                        $barang = $this->M_global->getData('barang', ['kode_barang' => $er->kode_barang]);

                                                                                                        $satuan = [];
                                                                                                        foreach ([$barang->kode_satuan, $barang->kode_satuan2, $barang->kode_satuan3] as $satuanCode) {
                                                                                                            $satuanDetail = $this->M_global->getData('m_satuan', ['kode_satuan' => $satuanCode]);
                                                                                                            if ($satuanDetail) {
                                                                                                                $satuan[] = [
                                                                                                                    'kode_satuan' => $satuanCode,
                                                                                                                    'keterangan' => $satuanDetail->keterangan,
                                                                                                                ];
                                                                                                            }
                                                                                                        }
                                                                                                        ?>
                                                                                                        <?php foreach ($satuan as $s) : ?>
                                                                                                            <option value="<?= $s['kode_satuan'] ?>" <?= (($er->kode_satuan == $s['kode_satuan']) ? 'selected' : '') ?>><?= $s['keterangan'] ?></option>
                                                                                                        <?php endforeach; ?>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td width="15%">
                                                                                                    <input type="text" id="qty<?= $no_eresep ?>" name="qty[]" value="<?= $er->qty ?>" min="<?= $no_eresep ?>" class="form-control text-right" onchange="hitung_st('<?= $no_eresep ?>'); formatRp(this.value, 'qty<?= $no_eresep ?>')" <?= $readonly ?>>
                                                                                                </td>
                                                                                                <td width="35%">
                                                                                                    <textarea name="signa[]" id="signa<?= $no_eresep ?>" class="form-control" <?= $readonly ?>><?= $er->signa ?></textarea>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php $no_eresep++;
                                                                                        endforeach; ?>
                                                                                    <?php else : ?>
                                                                                        <tr id="row_eresep1">
                                                                                            <td class="text-center">
                                                                                                <button class="btn btn-sm btn-danger" type="button" id="btnHapus1" onclick="hapusBarang('1')"><i class="fa-solid fa-delete-left"></i></button>
                                                                                            </td>
                                                                                            <td>
                                                                                                <select name="kode_barang[]" id="kode_barang1" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '1')">
                                                                                                    <option value="">~ Pilih Barang</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td>
                                                                                                <select name="kode_satuan[]" id="kode_satuan1" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                                                                                                    <option value="">~ Pilih Satuan</option>
                                                                                                    <?php foreach ($satuan as $s) : ?>
                                                                                                        <option value="<?= $s['kode_satuan'] ?>" <?= (($er->kode_satuan == $s['kode_satuan']) ? 'selected' : '') ?>><?= $s['keterangan'] ?></option>
                                                                                                    <?php endforeach; ?>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text" id="qty1" name="qty[]" value="1" min="1" class="form-control text-right" onchange="hitung_st('1'); formatRp(this.value, 'qty1')">
                                                                                            </td>
                                                                                            <td>
                                                                                                <textarea name="signa[]" id="signa1" class="form-control"></textarea>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endif; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-primary" onclick="addBarang()" id="btnCari" <?= $btn_diss ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                                                                        <button type="button" class="btn btn-danger float-right" onclick="emptyBarang()" id="btnEmpty" <?= $btn_diss ?>><i class="fa-solid fa-trash"></i>&nbsp;&nbsp;Hapus Semua</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <span class="h4">Racikan</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <textarea name="eracikan" id="eracikan" class="form-control" rows="5" <?= $readonly ?>><?= ((!empty($emr_per)) ? $emr_per->eracikan : '') ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="tab_elab">Lab Coming Soon</div>
                                                    <div id="tab_erad">Rad Coming Soon</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" onclick="getUrl('Emr')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
                                <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan" <?= $btn_sv ?>><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
                                <button type="button" class="btn btn-info float-right" onclick="reseting()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
    const form = $('#form_emr_perawat')
    var no_trx = $("#no_trx");
    var kode_member = $("#kode_member");
    var diagnosa_per = $("#diagnosa_per");
    var anamnesa_per = $("#anamnesa_per");
    var filter_dokter = $("#filter_dokter");
    const btn_assesment = $('#btn_assesment');
    const btn_pemeriksaan = $('#btn_pemeriksaan');
    const btn_psiko = $('#btn_psiko');
    const btn_order = $('#btn_order');
    const assesment_emr = $('#assesment_emr');
    const pemeriksaan_emr = $('#pemeriksaan_emr');
    const psiko_emr = $('#psiko_emr');
    const order_emr = $('#order_emr');
    const btn_etarif = $('#btn_etarif');
    const btn_eresep = $('#btn_eresep');
    const btn_elab = $('#btn_elab');
    const btn_erad = $('#btn_erad');
    const tab_etarif = $('#tab_etarif');
    const tab_eresep = $('#tab_eresep');
    const tab_elab = $('#tab_elab');
    const tab_erad = $('#tab_erad');
    let title_tab = $('#title_tab');
    var sempoyongan = $('#sempoyongan');
    var berjalan_dgn_alat = $('#berjalan_dgn_alat');
    var penompang = $('#penompang');
    var hasil = $('#hasil');
    var nilai = $('#nilai');
    var scale = $('#scale');
    var bicara = $('#bicara');
    var gangguan_bcr = $('#gangguan_bcr');
    var emosi = $('#emosi');
    var spiritual = $('#spiritual');

    history_px();
    cek_scale('<?= ((!empty($emr_per)) ? $emr_per->scale : '1') ?>');
    sel_tab(0);
    cek_bcr(<?= ((!empty($emr_per)) ? $emr_per->bicara : '1') ?>);
    cek_emosi(<?= ((!empty($emr_per)) ? $emr_per->emosi : '1') ?>);
    cek_spiritual(<?= ((!empty($emr_per)) ? $emr_per->spiritual : '1') ?>);
    sel_tab_emr(1);
    cek_resiko();

    function history_px() {
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("body_history").innerHTML = this.responseText;
            }
        };
        var param = `/${no_trx.val()}?kode_member=${kode_member.val()}&kode_dokter=${filter_dokter.val()}`;
        xhttp.open("GET", "<?= base_url('Emr/histori_px'); ?>" + param, true);
        xhttp.send();
    }

    function sel_tab_emr(param) {
        if (param == 1) {
            btn_assesment.addClass('btn-primary');
            btn_pemeriksaan.removeClass('btn-primary');
            btn_psiko.removeClass('btn-primary');
            btn_order.removeClass('btn-primary');

            assesment_emr.show(200);
            pemeriksaan_emr.hide(200);
            psiko_emr.hide(200);
            order_emr.hide(200);
        } else if (param == 2) {
            btn_assesment.removeClass('btn-primary');
            btn_pemeriksaan.addClass('btn-primary');
            btn_psiko.removeClass('btn-primary');
            btn_order.removeClass('btn-primary');

            assesment_emr.hide(200);
            pemeriksaan_emr.show(200);
            psiko_emr.hide(200);
            order_emr.hide(200);
        } else if (param == 3) {
            btn_assesment.removeClass('btn-primary');
            btn_pemeriksaan.removeClass('btn-primary');
            btn_psiko.addClass('btn-primary');
            btn_order.removeClass('btn-primary');

            assesment_emr.hide(200);
            pemeriksaan_emr.hide(200);
            psiko_emr.show(200);
            order_emr.hide(200);
        } else {
            btn_assesment.removeClass('btn-primary');
            btn_pemeriksaan.removeClass('btn-primary');
            btn_psiko.removeClass('btn-primary');
            btn_order.addClass('btn-primary');

            assesment_emr.hide(200);
            pemeriksaan_emr.hide(200);
            psiko_emr.hide(200);
            order_emr.show(200);
        }
    }

    function cek_resiko() {
        var a1 = sempoyongan.val();
        var a2 = berjalan_dgn_alat.val();
        var b = penompang.val();

        if ((a1 == 1) || (a2 == 1)) {
            var a = 1;
        } else {
            var a = 0;
        }

        if ((a == 0) && (b == 0)) {
            hasil.val('Tidak Beresiko');
            nilai.val('Tidak Ditemukan A & B');
        } else if ((a == 1) || (b == 1)) {
            hasil.val('Beresiko Sedang');
            nilai.val('Ditemukan Salah Satu Antara A & B');
        } else {
            hasil.val('Beresiko Tinggi');
            nilai.val('Ditemukan A & B');
        }
    }

    function cek_scale(param) {
        scale.val(param);
        if (param == 1) {
            document.getElementById('scale1').checked = true;
            document.getElementById('scale2').checked = false;
            document.getElementById('scale3').checked = false;
            document.getElementById('scale4').checked = false;
            document.getElementById('scale5').checked = false;
            document.getElementById('scale6').checked = false;
        } else if (param == 2) {
            document.getElementById('scale1').checked = false;
            document.getElementById('scale2').checked = true;
            document.getElementById('scale3').checked = false;
            document.getElementById('scale4').checked = false;
            document.getElementById('scale5').checked = false;
            document.getElementById('scale6').checked = false;
        } else if (param == 3) {
            document.getElementById('scale1').checked = false;
            document.getElementById('scale2').checked = false;
            document.getElementById('scale3').checked = true;
            document.getElementById('scale4').checked = false;
            document.getElementById('scale5').checked = false;
            document.getElementById('scale6').checked = false;
        } else if (param == 4) {
            document.getElementById('scale1').checked = false;
            document.getElementById('scale2').checked = false;
            document.getElementById('scale3').checked = false;
            document.getElementById('scale4').checked = true;
            document.getElementById('scale5').checked = false;
            document.getElementById('scale6').checked = false;
        } else if (param == 5) {
            document.getElementById('scale1').checked = false;
            document.getElementById('scale2').checked = false;
            document.getElementById('scale3').checked = false;
            document.getElementById('scale4').checked = false;
            document.getElementById('scale5').checked = true;
            document.getElementById('scale6').checked = false;
        } else {
            document.getElementById('scale1').checked = false;
            document.getElementById('scale2').checked = false;
            document.getElementById('scale3').checked = false;
            document.getElementById('scale4').checked = false;
            document.getElementById('scale5').checked = false;
            document.getElementById('scale6').checked = true;
        }
    }

    function cek_bcr(param) {
        bicara.val(param);
        if (param == 1) {
            document.getElementById('bicara1').checked = true;
            document.getElementById('bicara2').checked = false;
            gangguan_bcr.hide();
        } else {
            document.getElementById('bicara1').checked = false;
            document.getElementById('bicara2').checked = true;
            gangguan_bcr.show();
        }
    }

    function cek_emosi(param) {
        emosi.val(param);
        if (param == 1) {
            document.getElementById('emosi1').checked = true;
            document.getElementById('emosi2').checked = false;
            document.getElementById('emosi3').checked = false;
        } else if (param == 2) {
            document.getElementById('emosi1').checked = false;
            document.getElementById('emosi2').checked = true;
            document.getElementById('emosi3').checked = false;
        } else {
            document.getElementById('emosi1').checked = false;
            document.getElementById('emosi2').checked = false;
            document.getElementById('emosi3').checked = true;
        }
    }

    function cek_spiritual(param) {
        spiritual.val(param);
        if (param == 1) {
            document.getElementById('spiritual1').checked = true;
            document.getElementById('spiritual2').checked = false;
            document.getElementById('spiritual3').checked = false;
        } else if (param == 2) {
            document.getElementById('spiritual1').checked = false;
            document.getElementById('spiritual2').checked = true;
            document.getElementById('spiritual3').checked = false;
        } else {
            document.getElementById('spiritual1').checked = false;
            document.getElementById('spiritual2').checked = false;
            document.getElementById('spiritual3').checked = true;
        }
    }

    function sel_tab(param) {
        if (param == 0) {
            btn_etarif.addClass('btn-primary');
            btn_etarif.removeClass('btn-light');

            btn_eresep.removeClass('btn-primary');
            btn_eresep.addClass('btn-light');
            btn_elab.removeClass('btn-primary');
            btn_elab.addClass('btn-light');
            btn_erad.removeClass('btn-primary');
            btn_erad.addClass('btn-light');

            tab_etarif.show(200);
            tab_eresep.hide(200);
            tab_elab.hide(200);
            tab_erad.hide(200);

            title_tab.text('Tindakan');
        } else if (param == 1) {
            btn_eresep.addClass('btn-primary');
            btn_eresep.removeClass('btn-light');

            btn_etarif.removeClass('btn-primary');
            btn_etarif.addClass('btn-light');
            btn_elab.removeClass('btn-primary');
            btn_elab.addClass('btn-light');
            btn_erad.removeClass('btn-primary');
            btn_erad.addClass('btn-light');

            tab_eresep.show(200);
            tab_etarif.hide(200);
            tab_elab.hide(200);
            tab_erad.hide(200);

            title_tab.text('Resep / Racik');
        } else if (param == 2) {
            btn_elab.addClass('btn-primary');
            btn_elab.removeClass('btn-light');

            btn_eresep.removeClass('btn-primary');
            btn_eresep.addClass('btn-light');
            btn_etarif.removeClass('btn-primary');
            btn_etarif.addClass('btn-light');
            btn_erad.removeClass('btn-primary');
            btn_erad.addClass('btn-light');

            tab_elab.show(200);
            tab_eresep.hide(200);
            tab_etarif.hide(200);
            tab_erad.hide(200);

            title_tab.text('Laboratorium');
        } else {
            btn_erad.addClass('btn-primary');
            btn_erad.removeClass('btn-light');

            btn_eresep.removeClass('btn-primary');
            btn_eresep.addClass('btn-light');
            btn_elab.removeClass('btn-primary');
            btn_elab.addClass('btn-light');
            btn_etarif.removeClass('btn-primary');
            btn_etarif.addClass('btn-light');

            tab_erad.show(200);
            tab_eresep.hide(200);
            tab_elab.hide(200);
            tab_etarif.hide(200);

            title_tab.text('Radiologi');
        }
    }

    function getSatuan(param, no) {
        if (!param) {
            return Swal.fire("Barang", "Form sudah dipilih?", "question");
        }

        $('#kode_satuan' + no).empty();

        $.ajax({
            url: siteUrl + 'Emr/getSatuan/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                $.each(result, function(index, value) {
                    $('#kode_satuan' + no).append('<option value="' + value.kode_satuan + '">' + value.nama_satuan + '</option>')
                });
            },
            error: function(result) { // jika fungsi error
                error_proccess();
            }
        });
    }

    function addTarif() {
        var tableBarangIn = document.getElementById('table_etarif'); // ambil id table detail
        var jum = tableBarangIn.rows.length; // hitung jumlah rownya
        var x = Number(jum) + 1;
        var tbody = $('#body_etarif')

        tbody.append(`<tr id="row_etarif${x}">
            <td class="text-center">
                <button class="btn btn-sm btn-danger" type="button" id="btnHapusT${x}" onclick="hapusTarif('${x}')"><i class="fa-solid fa-delete-left"></i></button>
            </td>
            <td>
                <select name="kode_tarif[]" id="kode_tarif${x}" class="form-control select2_tarif_single" data-placeholder="~ Pilih Tindakan">
                    <option value="">~ Pilih Tindakan</option>
                </select>
            </td>
            <td>
                <input type="text" id="qty_tarif${x}" name="qty_tarif[]" value="1" min="1" class="form-control text-right" onchange="formatRp(this.value, 'qty_tarif${x}')">
            </td>
        </tr>`);

        initailizeSelect2_tarif_single();
    }

    function emptyTarif() {
        var tbody = $('#body_etarif');

        tbody.empty();
        addTarif();
    }

    function hapusTarif(no) {
        $('#row_etarif' + no).remove();
    }

    function addBarang() {
        var tableBarangIn = document.getElementById('table_eresep'); // ambil id table detail
        var jum = tableBarangIn.rows.length; // hitung jumlah rownya
        var x = Number(jum) + 1;
        var tbody = $('#body_eresep')

        tbody.append(`<tr id="row_eresep${x}">
            <td width="5%" class="text-center">
                <button class="btn btn-sm btn-danger" type="button" id="btnHapus${x}" onclick="hapusBarang('${x}')"><i class="fa-solid fa-delete-left"></i></button>
            </td>
            <td width="30%">
                <select name="kode_barang[]" id="kode_barang${x}" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '${x}')">
                    <option value="">~ Pilih Barang</option>
                </select>
            </td>
            <td width="15%">
                <select name="kode_satuan[]" id="kode_satuan${x}" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                    <option value="">~ Pilih Satuan</option>
                </select>
            </td>
            <td width="15%">
                <input type="text" id="qty${x}" name="qty[]" value="1" min="1" class="form-control text-right" onchange="hitung_st('${x}'); formatRp(this.value, 'qty${x}')">
            </td>
            <td width="35%">
                <textarea name="signa[]" id="signa${x}" class="form-control"></textarea>
            </td>
        </tr>`);

        initailizeSelect2_barang_stok();

        $(".select2_global").select2({
            placeholder: $(this).data('placeholder'),
            width: '100%',
            allowClear: true,
        });
    }

    function emptyBarang() {
        var tbody = $('#body_eresep');

        tbody.empty();
        addBarang();
    }

    function hapusBarang(no) {
        $('#row_eresep' + no).remove();
    }

    function reseting() {
        window.location.reload();
    }

    const popup = document.getElementById('popup');
    const header = document.querySelector('.card-draggable');
    let offsetX, offsetY, isDragging = false;

    header.addEventListener('mousedown', (e) => {
        isDragging = true;
        offsetX = e.clientX - popup.offsetLeft;
        offsetY = e.clientY - popup.offsetTop;
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        popup.style.left = e.clientX - offsetX + 'px';
        popup.style.top = e.clientY - offsetY + 'px';
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
    });

    function show_his(param, nohis, km) {
        $('#body_hispx').text('');

        if (!param) {
            return Swal.fire("Invoice", "Form sudah dipilih?", "question");
        }

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("body_hispx").innerHTML = this.responseText;
            }
        };
        var param = `/${param}/${nohis}/${km}`;
        xhttp.open("GET", "<?= base_url('Emr/his_px'); ?>" + param, true);
        xhttp.send();

        popup.style.display = 'block';
    }

    // dokter

    const popup2 = document.getElementById('popup2');
    const header2 = document.querySelector('.card-draggable2');
    let offsetX2, offsetY2, isDragging2 = false;

    header2.addEventListener('mousedown', (e) => {
        isDragging2 = true;
        offsetX2 = e.clientX - popup2.offsetLeft;
        offsetY2 = e.clientY - popup2.offsetTop;
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging2) return;
        popup2.style.left = e.clientX - offsetX2 + 'px';
        popup2.style.top = e.clientY - offsetY2 + 'px';
    });

    document.addEventListener('mouseup', () => {
        isDragging2 = false;
    });

    function show_his2(param, nohis, km) {
        $('#body_hispx2').text('');

        if (!param) {
            return Swal.fire("Invoice", "Form sudah dipilih?", "question");
        }

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("body_hispx2").innerHTML = this.responseText;
            }
        };
        var param = `/${param}/${nohis}/${km}`;
        xhttp.open("GET", "<?= base_url('Emr/his_px2'); ?>" + param, true);
        xhttp.send();

        popup2.style.display = 'block';
    }

    function copyTextAssesment(sempoyongan_emr, berjalan_dgn_alat_emr, penompang_emr, keterangan_assesment_emr) {
        const element = document.getElementById(sempoyongan_emr);
        const element1 = document.getElementById(berjalan_dgn_alat_emr);
        const element2 = document.getElementById(penompang_emr);
        const element3 = document.getElementById(keterangan_assesment_emr);
        const text = 'Sempoyongan: ' + element.textContent + ', Berjalan dengan alat: ' + element1.textContent + ', Penompang: ' + element2.textContent + ', Keterangan lain: ' + element3.textContent;
        navigator.clipboard.writeText(text)
            .then(() => {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Teks Berhasil Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            })
            .catch(err => {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Teks Gagal Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            });
    }

    function implementAssesment(param1, x1, param2, x2, param3, x3, param4, x4) {
        $('#' + x1).val(param1).change();
        $('#' + x2).val(param2).change();
        $('#' + x3).val(param3).change();
        $('#' + x4).val(param4);
    }

    function copyTextPemeriksaan(anamnesa_per_emr, diagnosa_per_emr, tekanan_darah_emr, nadi_emr, suhu_emr, bb_emr, tb_emr, pernapasan_emr, saturasi_emr, gizi_emr, hamil_emr, hpht_emr, keterangan_hamil_emr, scale_emr) {
        const element = document.getElementById(anamnesa_per_emr);
        const element1 = document.getElementById(diagnosa_per_emr);
        const element2 = document.getElementById(tekanan_darah_emr);
        const element3 = document.getElementById(nadi_emr);
        const element4 = document.getElementById(suhu_emr);
        const element5 = document.getElementById(bb_emr);
        const element6 = document.getElementById(tb_emr);
        const element7 = document.getElementById(pernapasan_emr);
        const element8 = document.getElementById(saturasi_emr);
        const element9 = document.getElementById(gizi_emr);
        const element10 = document.getElementById(hamil_emr);
        const element11 = document.getElementById(hpht_emr);
        const element12 = document.getElementById(keterangan_hamil_emr);
        const element13 = document.getElementById(scale_emr);
        const text = 'Anamnesa: ' + element.textContent + ', Diagnosa: ' + element1.textContent + ', Tekanan Darah: ' + element2.textContent + ', Nadi: ' + element3.textContent + ', Suhu: ' + element4.textContent + ', Berat Badan: ' + element5.textContent + ', Tinggi Badan: ' + element6.textContent + ', Pernapasan: ' + element7.textContent + ', Saturasi: ' + element8.textContent + ', Gizi: ' + element9.textContent + ', Hamil: ' + element10.textContent + ', HPHT: ' + element11.textContent + ', Keterangan Hamil: ' + element12.textContent + ', Skala Nyeri: ' + element13.textContent;
        navigator.clipboard.writeText(text)
            .then(() => {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Teks Berhasil Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            })
            .catch(err => {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Teks Gagal Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            });
    }

    function implementPemeriksaan(x1, x2, x3, x4, x5, x6, x7, x8, x9, x10, x11, x12, x13, x14) {
        $('#anamnesa_per').val(x1);
        $('#diagnosa_per').val(x2);
        $('#tekanan_darah').val(x3);
        $('#nadi').val(x4);
        $('#suhu').val(x5);
        $('#bb').val(x6);
        $('#tb').val(x7);
        $('#pernapasan').val(x8);
        $('#saturasi').val(x9);
        $('#gizi').val(x10).change();
        $('#hamil').val(x11).change();
        $('#hpht').val(x12);
        $('#keterangan_hamil').val(x13);
        $('#scale').val(x14);
        document.getElementById('scale' + x14).checked = true;
        cek_scale(x14);
        console.log(x14)
    }

    function copyTextPsiko(x1, x2, x3, x4) {
        const text = 'Bicara: ' + ((x1 == 1) ? 'Normal' : 'Terganggu') + ', Psikologi: ' + ((x2 == 1) ? 'Tenang' : ((x2 == 2) ? 'Gelisah' : 'Emosional')) + ', Spiritual: ' + ((x3 == 1) ? 'Berdiri' : ((x3 == 2) ? 'Duduk' : 'Berbaring')) + ', Gangguan Bicara: ' + x3;
        navigator.clipboard.writeText(text)
            .then(() => {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Teks Berhasil Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            })
            .catch(err => {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Teks Gagal Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            });
    }

    function implementPsiko(x1, x2, x3, x4) {
        $('#gangguan').val(x4);
        $('#bicara').val(x1);
        document.getElementById('bicara' + x1).checed = true;
        cek_bcr(x1);
        $('#emosi').val(x2);
        document.getElementById('emosi' + x2).checed = true;
        cek_emosi(x2);
        $('#spiritual').val(x3);
        document.getElementById('spiritual' + x3).checed = true;
        cek_spiritual(x3);
    }

    function copyTextOrder(x1) {
        const text = x1;
        navigator.clipboard.writeText(text)
            .then(() => {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Teks Berhasil Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            })
            .catch(err => {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Teks Gagal Disalin",
                    showConfirmButton: false,
                    timer: 500
                });
            });
    }

    function implementOrder(param) {
        //etarif
        $.ajax({
            url: `${siteUrl}Emr/emr_tarif/${param}`,
            type: `POST`,
            dataType: `JSON`,
            success: function(result) {
                if (result.length > 0) {
                    $('#body_etarif').empty();

                    var notar = 1;

                    $.each(result, function(index, value) {
                        $('#body_etarif').append(`<tr id="row_etarif${notar}">
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger" type="button" id="btnHapusT${notar}" onclick="hapusTarif('${notar}')"><i class="fa-solid fa-delete-left"></i></button>
                            </td>
                            <td>
                                <select name="kode_tarif[]" id="kode_tarif${notar}" class="form-control select2_tarif_single" data-placeholder="~ Pilih Tindakan">
                                    <option value="${value.kode_tarif}">${value.kode_tarif}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" id="qty_tarif${notar}" name="qty_tarif[]" value="${value.qty}" min="1" class="form-control text-right" onchange="formatRp(this.value, 'qty_tarif${notar}')">
                            </td>
                        </tr>`);

                        initailizeSelect2_tarif_single();

                        notar++;
                    });

                    return;
                }
            },
            error: function(error) {
                error_proccess();
            }
        })

        //eresep
        $.ajax({
            url: `${siteUrl}Emr/emr_per_barang/${param}`,
            type: `POST`,
            dataType: `JSON`,
            success: function(result) {
                if (result.length > 0) {
                    $('#body_eresep').empty();

                    var norsp = 1;

                    $.each(result, function(index, value) {
                        $('#body_eresep').append(`<tr id="row_eresep${norsp}">
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger" type="button" id="btnHapus${norsp}" onclick="hapusBarang('${norsp}')">
                                    <i class="fa-solid fa-delete-left"></i>
                                </button>
                            </td>
                            <td>
                                <select name="kode_barang[]" id="kode_barang${norsp}" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '${norsp}')">
                                    <option value="${value.kode_barang}">${value.nama}</option>
                                </select>
                            </td>
                            <td>
                                <select name="kode_satuan[]" id="kode_satuan${norsp}" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                                    <option value="">~ Pilih Satuan</option>
                                    <?php foreach ($satuan as $s) : ?>
                                        <option value="<?= $s['kode_satuan'] ?>" ${value.kode_satuan == <?= $s['kode_satuan'] ?> ? 'selected' : '' }><?= $s['keterangan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" id="qty${norsp}" name="qty[]" value="${value.qty}" min="1" class="form-control text-right" onchange="hitung_st('${norsp}'); formatRp(this.value, 'qty${norsp}')">
                            </td>
                            <td>
                                <textarea name="signa[]" id="signa${norsp}" class="form-control">${value.signa}</textarea>
                            </td>
                        </tr>`)

                        initailizeSelect2_barang_stok();

                        $(".select2_global").select2({
                            placeholder: $(this).data('placeholder'),
                            width: '100%',
                            allowClear: true,
                        });

                        norsp++;
                    });

                    return;
                }
            },
            error: function(error) {
                error_proccess();
            }
        })
    }
</script>

<script>
    function save() {
        if (diagnosa_per.val() == '' || diagnosa_per.val() == null) {
            return Swal.fire("Diagnosa Perawat", "Form sudah diisi?", "question");
        }

        if (anamnesa_per.val() == '' || anamnesa_per.val() == null) {
            return Swal.fire("Anamnesa Perawat", "Form sudah diisi?", "question");
        }

        $.ajax({
            url: `${siteUrl}Emr/proses_per`,
            type: `POST`,
            dataType: `JSON`,
            data: form.serialize(),
            success: function(result) {
                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("EMR Perawat", "Berhasil diproses", "success").then(() => {
                        location.href = siteUrl + 'Emr';
                    });
                } else { // selain itu

                    Swal.fire("EMR Perawat", "Gagal diproses, silahkan dicoba kembali", "info");
                }
            },
            error: function(error) {
                error_proccess();
            }
        });
    }
</script>