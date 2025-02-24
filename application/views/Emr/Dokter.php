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

// Use query binding to prevent SQL injection
$last_notrx = $this->db->query('SELECT * FROM pendaftaran WHERE kode_member = ? ORDER BY id DESC LIMIT 1', [$kode_memberx])->row();

// Check if last_notrx exists to avoid errors when accessing its properties
if ($last_notrx) {
    $riwayat = $this->db->query('SELECT * FROM emr_dok WHERE kode_member = ? AND no_trx <> ? ORDER BY id DESC', [$kode_memberx, $last_notrx->no_trx])->result();

    if (!empty($riwayat)) {
        $p_kel = [];
        $alr = [];
        foreach ($riwayat as $rwt) {
            // Check if $rwt has the necessary properties
            $p_kel[] = $rwt->penyakit_keluarga ?? ''; // Use null coalescing operator to handle missing fields
            $alr[] = $rwt->alergi ?? ''; // Use null coalescing operator
        }
    } else {
        // Return empty values if no records are found
        $p_kel = '';
        $alr = '';
    }
} else {
    // Handle the case when $last_notrx is null (no records found)
    $p_kel = '';
    $alr = '';
}

if (is_array($p_kel) && !empty($p_kel)) {
    $p_kel = implode(", ", $p_kel);  // Join array elements into a string separated by commas
    $alr = implode(", ", $alr);  // Join array elements into a string separated by commas
} else {
    $p_kel = $p_kel;  // In case it's not an array, just print the string
    $alr = $alr;  // In case it's not an array, just print the string
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
    <form id="form_emr_dokter">
        <input type="hidden" name="no_trx" id="no_trx" value="<?= $no_trx ?>">
        <input type="hidden" name="kode_member" id="kode_member" value="<?= $pendaftaran->kode_member ?>">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-outline card-primary" style="position: fixed; width: 20.5%;">
                    <div class="card-header">
                        <span class="font-weight-bold h4 text-primary">History Pasien</span>
                    </div>
                    <div class="card-body">
                        <select name="filter_dokter" id="filter_dokter" class="form-control select2_dokter_all" data-placeholder="~ Pilih Dokter" onchange="history_px()">
                            <?php if (!empty($kode_dokter)) : ?>
                                <option value="<?= $kode_dokter ?>">Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $kode_dokter])->nama ?></option>
                            <?php else : ?>
                                <option value="<?= ((!empty($pendaftaran)) ? $pendaftaran->kode_dokter : '') ?>"><?= ((!empty($pendaftaran)) ? 'Dr. ' . $this->M_global->getData('dokter', ['kode_dokter' => $pendaftaran->kode_dokter])->nama : '') ?></option>
                            <?php endif ?>
                        </select>
                        <hr>
                        <div id="body_history" style="overflow-y: scroll; overflow-x: hidden; height: 64vh; width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> EMR Dokter</span>
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
                                        <textarea name="alamat" id="alamat" class="form-control" readonly><?= $alamat ?></textarea>
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
                                                <input type="text" class="form-control" id="kode_ruang" name="kode_ruang" value="<?= ($pendaftaran) ? $pendaftaran->kode_ruang : '' ?>" readonly>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <input type="text" class="form-control" id="kode_bed" name="kode_bed" value="<?= ($pendaftaran) ? $pendaftaran->kode_bed : '' ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> Tanda Vital</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Tekanan Darah (mmHg)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="tekanan_darah" name="tekanan_darah" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->tekanan_darah : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Nadi (x/mnt)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="nadi" name="nadi" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->nadi : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Suhu (°c)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="suhu" name="suhu" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->suhu : '') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Berat Badan (kg)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="bb" name="bb" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->bb : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Tinggi Badan (cm)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="tb" name="tb" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->tb : '') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Pernapasan (x/mnt)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="pernapasan" name="pernapasan" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->pernapasan : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Saturasi O2 (%)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="saturasi" name="saturasi" class="form-control" value="<?= ((!empty($emr_per)) ? $emr_per->saturasi : '') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> Anamnesa **</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Perawat</label>
                                    <div class="col-md-9">
                                        <textarea name="anamnesa_per" id="anamnesa_per" class="form-control" rows="5" readonly><?= ((!empty($emr_per)) ? $emr_per->anamnesa_per : '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Dokter</label>
                                    <div class="col-md-9">
                                        <textarea name="anamnesa_dok" id="anamnesa_dok" class="form-control" rows="5"><?= ((!empty($emr_dok)) ? $emr_dok->anamnesa_dok : '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> Riwayat Kesehatan</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Penyakit Keluarga</label>
                                    <div class="col-md-9">
                                        <input type="text" id="penyakit_keluarga_his" name="penyakit_keluarga_his" class="form-control mb-3" readonly value="<?= $p_kel ?>">
                                        <textarea name="penyakit_keluarga" id="penyakit_keluarga" class="form-control" rows="3" placeholder="Penyakit Baru..."><?= ((!empty($emr_per)) ? $emr_per->penyakit_keluarga : '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Alergi</label>
                                    <div class="col-md-9">
                                        <input type="text" id="alergi_his" name="alergi_his" class="form-control mb-3" readonly value="<?= $alr ?>">
                                        <textarea name="alergi" id="alergi" class="form-control" rows="3" placeholder="Alergi Baru..."><?= ((!empty($emr_per)) ? $emr_per->alergi : '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> Pemeriksaan Fisik</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered" id="table_fisik">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">Hapus</th>
                                                <th style="width: 15%;">Bagian Tubuh</th>
                                                <th style="width: 80%;">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="body_fisik">
                                            <?php if (!empty($emr_dok_fisik)) : ?>
                                                <?php $nof = 1;
                                                foreach ($emr_dok_fisik as $edf) : ?>
                                                    <tr id="row_fisik<?= $nof ?>">
                                                        <td>
                                                            <button class="btn btn-sm btn-danger" type="button" id="btnHapus<?= $nof ?>" onclick="hapusFisik('<?= $nof ?>')"><i class="fa-solid fa-delete-left"></i></button>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="fisik[]" id="fisik<?= $nof ?>" class="form-control" value="<?= $edf->fisik ?>">
                                                        </td>
                                                        <td>
                                                            <textarea name="desc_fisik[]" id="desc_fisik<?= $nof ?>" class="form-control"><?= $edf->desc_fisik ?></textarea>
                                                        </td>
                                                    </tr>
                                                <?php $nof++;
                                                endforeach ?>
                                            <?php else : ?>
                                                <tr id="row_fisik1">
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" type="button" id="btnHapus1" onclick="hapusFisik('1')"><i class="fa-solid fa-delete-left"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="fisik[]" id="fisik1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <textarea name="desc_fisik[]" id="desc_fisik1" class="form-control"></textarea>
                                                    </td>
                                                </tr>
                                            <?php endif ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="addFisik()"><i class="fa fa-circle-plus"></i> Tambah</button>
                                <button type="button" class="btn btn-danger float-right" onclick="emptyFisik()"><i class="fa fa-trash"></i> Hapus Semua</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> Diagnosa Dokter & Rencana **</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Diagnosa</label>
                                    <div class="col-md-9">
                                        <textarea name="diagnosa_dok" id="diagnosa_dok" class="form-control" rows="5" placeholder="Diagnosa Dokter..."><?= ((!empty($emr_dok)) ? $emr_dok->diagnosa_dok : '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Rencana</label>
                                    <div class="col-md-9">
                                        <textarea name="rencana_dok" id="rencana_dok" class="form-control" rows="5" placeholder="Rencana Dokter..."><?= ((!empty($emr_dok)) ? $emr_dok->rencana_dok : '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> E-Order</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <button class="btn btn-primary w-100 mb-1" type="button" onclick="sel_tab(0)" id="btn_etarif">E-Tarif / Tindakan</button>
                                        <button class="btn btn-light w-100 mb-1" type="button" onclick="sel_tab(1)" id="btn_eresep">E-Resep</button>
                                        <button class="btn btn-light w-100 mb-1" type="button" onclick="sel_tab(2)" id="btn_elab">E-Laboratorium</button>
                                        <button class="btn btn-light w-100 mb-1" type="button" onclick="sel_tab(3)" id="btn_erad">E-Radiologi</button>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card w-100 h-100">
                                            <div class="card-header">
                                                <span class="h4" id="title_tab">E-Resep / Racik</span>
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
    const form = $('#form_emr_dokter')
    var no_trx = $("#no_trx");
    var kode_member = $("#kode_member");
    var diagnosa_per = $("#diagnosa_per");
    var diagnosa_dok = $("#diagnosa_dok");
    var rencana_dok = $("#rencana_dok");
    var anamnesa_per = $("#anamnesa_per");
    var anamnesa_dok = $("#anamnesa_dok");
    var filter_dokter = $("#filter_dokter");
    const btn_etarif = $('#btn_etarif');
    const btn_eresep = $('#btn_eresep');
    const btn_elab = $('#btn_elab');
    const btn_erad = $('#btn_erad');
    const tab_etarif = $('#tab_etarif');
    const tab_eresep = $('#tab_eresep');
    const tab_elab = $('#tab_elab');
    const tab_erad = $('#tab_erad');
    let title_tab = $('#title_tab');

    history_px();
    sel_tab(0);

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

            title_tab.text('E-Tarif / Tindakan');
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

            title_tab.text('E-Resep / Racik');
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

            title_tab.text('E-Laboratorium');
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

            title_tab.text('E-Radiologi');
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
                console.table(result)
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

    function addFisik() {
        var tableFisikIn = document.getElementById('table_fisik'); // ambil id table detail
        var jum = tableFisikIn.rows.length; // hitung jumlah rownya
        var x = Number(jum) + 1;
        var tbody = $('#body_fisik')

        tbody.append(`<tr id="row_fisik${x}">
            <td>
                <button class="btn btn-sm btn-danger" type="button" id="btnHapus${x}" onclick="hapusFisik('${x}')"><i class="fa-solid fa-delete-left"></i></button>
            </td>
            <td>
                <input type="text" name="fisik[]" id="fisik${x}" class="form-control">
            </td>
            <td>
                <textarea name="desc_fisik[]" id="desc_fisik${x}" class="form-control"></textarea>
            </td>
        </tr>`);
    }

    function emptyFisik() {
        var tbody = $('#body_fisik');

        tbody.empty();
        addFisik();
    }

    function hapusFisik(no) {
        $('#row_fisik' + no).remove();
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

    function copyText(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent;
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

    function implement(param, x) {
        $('#' + x).val(param);
    }

    function implement_err(param, x, notrx) {
        $('#' + x).val(param);
        var tbody = $('#body_eresep');
        var tbody2 = $('#body_etarif');
        tbody.empty();
        tbody2.empty();
        var no = 1;
        var no2 = 1;

        if (!notrx) {
            return;
        }

        tbody.empty();

        $.ajax({
            url: `${siteUrl}Emr/emr_per_barang/${notrx}`,
            type: `POST`,
            dataType: `JSON`,
            success: function(result) {
                $.each(result, function(index, value) {
                    tbody.append(`<tr id="row_eresep${no}">
                        <td width="5%" class="text-center">
                            <button class="btn btn-sm btn-danger" type="button" id="btnHapus${no}" onclick="hapusBarang('${no}')" <?= $btn_diss ?>><i class="fa-solid fa-delete-left"></i></button>
                        </td>
                        <td width="30%">
                            <select name="kode_barang[]" id="kode_barang${no}" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '${no}')">
                                <option value="${value.kode_barang}">${value.nama}</option>
                            </select>
                        </td>
                        <td width="15%">
                            <select name="kode_satuan[]" id="kode_satuan${no}" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                                <option value="${value.kode_satuan}">${value.satuan}</option>
                            </select>
                        </td>
                        <td width="15%">
                            <input type="text" id="qty${no}" name="qty[]" value="${value.qty}" min="1" class="form-control text-right" onchange="hitung_st('${no}'); formatRp(this.value, 'qty${no}')" <?= $readonly ?>>
                        </td>
                        <td width="35%">
                            <textarea name="signa[]" id="signa${no}" class="form-control" <?= $readonly ?>>${value.signa}</textarea>
                        </td>
                    </tr>`);

                    initailizeSelect2_barang_stok();

                    $(".select2_global").select2({
                        placeholder: $(this).data('placeholder'),
                        width: '100%',
                        allowClear: true,
                    });

                    no++
                });
            },
            error: function(error) {
                error_proccess();
            }
        });

        $.ajax({
            url: `${siteUrl}Emr/emr_tarif/${notrx}`,
            type: `POST`,
            dataType: `JSON`,
            success: function(result) {
                $.each(result, function(index, value) {
                    tbody2.append(`<tr id="row_etarif${no2}">
                        <td class="text-center">
                            <button class="btn btn-sm btn-danger" type="button" id="btnHapusT${no2}" onclick="hapusTarif('${no2}')"><i class="fa-solid fa-delete-left"></i></button>
                        </td>
                        <td>
                            <select name="kode_tarif[]" id="kode_tarif${no2}" class="form-control select2_tarif_single" data-placeholder="~ Pilih Tindakan">
                                <option value="${value.kode_tarif}">${value.nama}</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" id="qty_tarif${no2}" name="qty_tarif[]" value="${value.qty}" min="1" class="form-control text-right" onchange="formatRp(this.value, 'qty_tarif${no2}')">
                        </td>
                    </tr>`);

                    initailizeSelect2_tarif_single();

                    no2++
                });
            },
            error: function(error) {
                error_proccess();
            }
        });
    }

    function implement_fisik(param, x, notrx) {
        $('#' + x).val(param);
        var tbody = $('#body_fisik');
        var no = 1;

        if (!notrx) {
            return;
        }

        tbody.empty();

        $.ajax({
            url: `${siteUrl}Emr/emr_dok_fisik/${notrx}`,
            type: `POST`,
            dataType: `JSON`,
            success: function(result) {
                $.each(result, function(index, value) {
                    tbody.append(`<tr id="row_fisik${no}">
                        <td>
                            <button class="btn btn-sm btn-danger" type="button" id="btnHapus${no}" onclick="hapusFisik('${no}')"><i class="fa-solid fa-delete-left"></i></button>
                        </td>
                        <td>
                            <input type="text" name="fisik[]" id="fisik${no}" class="form-control" value="${value.fisik}">
                        </td>
                        <td>
                            <textarea name="desc_fisik[]" id="desc_fisik${no}" class="form-control">${value.desc_fisik}</textarea>
                        </td>
                    </tr>`);
                    no++
                });
            },
            error: function(error) {
                error_proccess();
            }
        });
    }
</script>

<script>
    function save() {
        if (diagnosa_dok.val() == '' || diagnosa_dok.val() == null) {
            return Swal.fire("Diagnosa Dokter", "Form sudah diisi?", "question");
        }

        if (rencana_dok.val() == '' || rencana_dok.val() == null) {
            return Swal.fire("Rencana Dokter", "Form sudah diisi?", "question");
        }

        if (anamnesa_dok.val() == '' || anamnesa_dok.val() == null) {
            return Swal.fire("Anamnesa Dokter", "Form sudah diisi?", "question");
        }

        $.ajax({
            url: `${siteUrl}Emr/proses_dok`,
            type: `POST`,
            dataType: `JSON`,
            data: form.serialize(),
            success: function(result) {
                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("EMR Dokter", "Berhasil diproses", "success").then(() => {
                        location.href = siteUrl + 'Emr';
                    });
                } else { // selain itu

                    Swal.fire("EMR Dokter", "Gagal diproses, silahkan dicoba kembali", "info");
                }
            },
            error: function(error) {
                error_proccess();
            }
        });
    }
</script>