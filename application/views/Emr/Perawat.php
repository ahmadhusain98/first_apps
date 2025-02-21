<?php
$member = $this->M_global->getData('member', ['kode_member' => $pendaftaran->kode_member]);
?>

<style>
    .modal {
        z-index: 1050;
        /* display: none !important; */
    }

    .modal-backdrop {
        display: none;
    }

    .modal-static {
        z-index: -1;
    }

    .modal-dialog {
        cursor: move;
    }

    .modal-header {
        cursor: move;
    }
</style>

<button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#myModal">
    Buka Modal
</button>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="draggableModal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel">Modal Static</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Ini adalah modal static yang dapat di-drag.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="form-container">
    <form id="form_emr_perawat">
        <input type="hidden" name="no_trx" id="no_trx" value="<?= $no_trx ?>">
        <input type="hidden" name="kode_member" id="kode_member" value="<?= $pendaftaran->kode_member ?>">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-outline card-primary" style="position: fixed; width: 20%;">
                    <div class="card-header">
                        <span class="font-weight-bold h4 text-primary">History Pasien</span>
                    </div>
                    <div class="card-body">
                        <select name="filter_dokter" id="filter_dokter" class="form-control select2_dokter_all" data-placeholder="~ Pilih Dokter" onchange="history_px()">
                            <option value="<?= $kode_dokter ?>">Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $kode_dokter])->nama ?></option>
                        </select>
                        <hr>
                        <div id="body_history" style="overflow-y: scroll; overflow-x: hidden; height: 65vh; width: 100%;"></div>
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
                                        <input type="text" class="form-control" id="kode_member" name="kode_member" value="<?= (($pendaftaran) ? (($member) ? $member->kode_member : '') : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Nama Pasien</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="name_member" name="name_member" value="<?= (($pendaftaran) ? (($member) ? $member->nama : '') : '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Tempat & Tanggal Lahir</label>
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
                                    <label for="" class="form-label col-md-3">Umur Saat Ini</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="umur" name="umur" value="<?= (($pendaftaran) ? (($member) ? hitung_umur($member->tgl_lahir) : '0 Tahun') : '0 Tahun') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-3">Jenis Kelamin</label>
                                    <div class="col-md-9">
                                        <input type="hidden" class="form-control" id="jkel" name="jkel" value="<?= (($pendaftaran) ? (($member) ? $member->jkel : '') : '') ?>" readonly>
                                        <input type="text" class="form-control" id="jkel1" name="jkel1" value="<?= (($pendaftaran) ? (($member) ? (($member->jkel == 'P') ? 'Pria' : 'Wanita') : '') : '') ?>" readonly>
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
                                        <input type="text" class="form-control" id="kode_dokter1" name="kode_dokter1" value="<?= ($pendaftaran) ? $this->M_global->getData('dokter', ['kode_dokter' => $pendaftaran->kode_dokter])->nama : '' ?>" readonly>
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
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> Riwayat Kesehatan</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Penyakit Keluarga</label>
                                    <div class="col-md-9">
                                        <input type="text" id="penyakit_keluarga_his" name="penyakit_keluarga_his" class="form-control mb-3" readonly>
                                        <textarea name="penyakit_keluarga" id="penyakit_keluarga" class="form-control" rows="3" placeholder="Penyakit Baru..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="form-label col-md-3">Alergi</label>
                                    <div class="col-md-9">
                                        <input type="text" id="alergi_his" name="alergi_his" class="form-control mb-3" readonly>
                                        <textarea name="alergi" id="alergi" class="form-control" rows="3" placeholder="Alergi Baru..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold h4 text-primary"><i class="fa-solid fa-bookmark text-primary"></i> Pemeriksaan Fisik</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Tekanan Darah (mmHg)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="tekanan_darah" name="tekanan_darah" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Nadi (x/mnt)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="nadi" name="nadi" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Suhu (°c)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="suhu" name="suhu" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Berat Badan (kg)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="bb" name="bb" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Tinggi Badan (cm)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="tb" name="tb" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Pernapasan (x/mnt)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="pernapasan" name="pernapasan" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="form-label col-md-6 m-auto">Saturasi O2 (%)</label>
                                    <div class="col-md-6">
                                        <input type="text" id="saturasi" name="saturasi" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <img src="<?= base_url() ?>assets/img/emr/scale.jpg" width="100%">
                            </div>
                            <div class="col-md-6">
                                <div class="card-footer">
                                    <span class="h5 font-weight-bold">Skala Nyeri</span>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table>
                                                <tr>
                                                    <td style="width: 20%;">
                                                        <input type="checkbox" id="scale1" name="scale[]" class="form-control">
                                                        <input type="hidden" id="scale_i1" name="scale_i[]" class="form-control" value="0">
                                                    </td>
                                                    <td style="width: 80%;">
                                                        <label for="">&nbsp;&nbsp;&nbsp; (Skala Nyeri 0-1) Tidak Ada Rasa Sakit</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%;">
                                                        <input type="checkbox" id="scale2" name="scale[]" class="form-control">
                                                        <input type="hidden" id="scale_i2" name="scale_i[]" class="form-control" value="0">
                                                    </td>
                                                    <td style="width: 80%;">
                                                        <label for="">&nbsp;&nbsp;&nbsp; (Skala Nyeri 2-3) Nyeri Ringan</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%;">
                                                        <input type="checkbox" id="scale3" name="scale[]" class="form-control">
                                                        <input type="hidden" id="scale_i3" name="scale_i[]" class="form-control" value="0">
                                                    </td>
                                                    <td style="width: 80%;">
                                                        <label for="">&nbsp;&nbsp;&nbsp; (Skala Nyeri 4-5) Nyeri Sedang</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%;">
                                                        <input type="checkbox" id="scale4" name="scale[]" class="form-control">
                                                        <input type="hidden" id="scale_i4" name="scale_i[]" class="form-control" value="0">
                                                    </td>
                                                    <td style="width: 80%;">
                                                        <label for="">&nbsp;&nbsp;&nbsp; (Skala Nyeri 6-7) Nyeri Parah</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%;">
                                                        <input type="checkbox" id="scale5" name="scale[]" class="form-control">
                                                        <input type="hidden" id="scale_i5" name="scale_i[]" class="form-control" value="0">
                                                    </td>
                                                    <td style="width: 80%;">
                                                        <label for="">&nbsp;&nbsp;&nbsp; (Skala Nyeri 8-9) Nyeri Sangat Parah</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%;">
                                                        <input type="checkbox" id="scale6" name="scale[]" class="form-control">
                                                        <input type="hidden" id="scale_i6" name="scale_i[]" class="form-control" value="0">
                                                    </td>
                                                    <td style="width: 80%;">
                                                        <label for="">&nbsp;&nbsp;&nbsp; (Skala Nyeri 10 >) Nyeri Sangat Buruk</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
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
                                        <button class="btn btn-primary w-100" type="button" onclick="sel_tab(1)" id="btn_eresep">E-Resep</button>
                                        <button class="btn btn-light w-100 mt-3 mb-3" type="button" onclick="sel_tab(2)" id="btn_elab">E-Laboratorium</button>
                                        <button class="btn btn-light w-100" type="button" onclick="sel_tab(3)" id="btn_erad">E-Radiologi</button>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card w-100 h-100">
                                            <div class="card-header">
                                                <span class="h4" id="title_tab">E-Resep / Racik</span>
                                                <button type="button" class="btn btn-primary float-right"><i class="fa fa-copy"></i> Copy Resep/Racik</button>
                                            </div>
                                            <div class="card-body">
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
                                                                                                <button class="btn btn-sm btn-danger" type="button" id="btnHapus<?= $no_eresep ?>" onclick="hapusBarang('1')"><i class="fa-solid fa-delete-left"></i></button>
                                                                                            </td>
                                                                                            <td width="30%">
                                                                                                <select name="kode_barang[]" id="kode_barang<?= $no_eresep ?>" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '<?= $no_eresep ?>')">
                                                                                                    <option value="">~ Pilih Barang</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td width="15%">
                                                                                                <select name="kode_satuan[]" id="kode_satuan<?= $no_eresep ?>" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                                                                                                    <option value="">~ Pilih Satuan</option>
                                                                                                    <?php foreach ($satuan as $s) : ?>
                                                                                                        <option value="<?= $s['kode_satuan'] ?>" <?= (($er->kode_satuan == $s['kode_satuan']) ? 'selected' : '') ?>><?= $s['keterangan'] ?></option>
                                                                                                    <?php endforeach; ?>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td width="15%">
                                                                                                <input type="text" id="qty<?= $no_eresep ?>" name="qty[]" value="<?= $no_eresep ?>" min="<?= $no_eresep ?>" class="form-control text-right" onchange="hitung_st('<?= $no_eresep ?>'); formatRp(this.value, 'qty<?= $no_eresep ?>')">
                                                                                            </td>
                                                                                            <td width="35%">
                                                                                                <textarea name="signa[]" id="signa<?= $no_eresep ?>" class="form-control"></textarea>
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
                                                                    <button type="button" class="btn btn-primary" onclick="addBarang()" id="btnCari"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
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
                                                                    <textarea name="eracikan" id="eracikan" class="form-control" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="tab_elab">Lab</div>
                                                <div id="tab_erad">Rad</div>
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
                                <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
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
    var filter_dokter = $("#filter_dokter");
    const btn_eresep = $('#btn_eresep');
    const btn_elab = $('#btn_elab');
    const btn_erad = $('#btn_erad');
    const tab_eresep = $('#tab_eresep');
    const tab_elab = $('#tab_elab');
    const tab_erad = $('#tab_erad');
    let title_tab = $('#title_tab')

    history_px();
    sel_tab(1);

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

    function show_his(param) {
        var params = `/${param}?kode_member=${kode_member.val()}&kode_dokter=${filter_dokter.val()}`
        window.open(`${siteUrl}Emr/perawat/${params}`, '_blank');
    }

    function sel_tab(param) {
        if (param == 1) {
            btn_eresep.addClass('btn-primary');
            btn_eresep.removeClass('btn-light');

            btn_elab.removeClass('btn-primary');
            btn_elab.addClass('btn-light');
            btn_erad.removeClass('btn-primary');
            btn_erad.addClass('btn-light');

            tab_eresep.show(200);
            tab_elab.hide(200);
            tab_erad.hide(200);

            title_tab.text('E-Resep / Racik');
        } else if (param == 2) {
            btn_elab.addClass('btn-primary');
            btn_elab.removeClass('btn-light');

            btn_eresep.removeClass('btn-primary');
            btn_eresep.addClass('btn-light');
            btn_erad.removeClass('btn-primary');
            btn_erad.addClass('btn-light');

            tab_elab.show(200);
            tab_eresep.hide(200);
            tab_erad.hide(200);

            title_tab.text('E-Laboratorium');
        } else {
            btn_erad.addClass('btn-primary');
            btn_erad.removeClass('btn-light');

            btn_elab.removeClass('btn-primary');
            btn_elab.addClass('btn-light');
            btn_eresep.removeClass('btn-primary');
            btn_eresep.addClass('btn-light');

            tab_erad.show(200);
            tab_elab.hide(200);
            tab_eresep.hide(200);

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

    function hapusBarang(no) {
        $('#row_eresep' + no).remove();
    }

    function reseting() {
        window.location.reload();
    }

    $(document).ready(function() {
        $('#myModal').on('shown.bs.modal', function() {
            $(this).find('.modal-dialog').draggable({
                handle: '.modal-header',
            });
        });
    });
</script>