<?php
if (!$pendaftaran) {
    $member = '';
    $histori = $this->db->query('SELECT *, ROW_NUMBER() OVER (ORDER BY id DESC) AS eps FROM pendaftaran ORDER BY id DESC')->result();
} else {
    $member = $this->M_global->getData('member', ['kode_member' => $pendaftaran->kode_member]);
    $histori = $this->db->query('SELECT *, ROW_NUMBER() OVER (ORDER BY id DESC) AS eps FROM pendaftaran WHERE kode_member = "' . $pendaftaran->kode_member . '" ORDER BY id DESC')->result();
}
?>

<form id="form_emr_dokter">
    <input type="hidden" name="no_trx" id="no_trx" value="<?= $no_trx ?>">
    <div class="row">
        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4 text-primary">History Pasien</span>
                </div>
                <div class="card-body">
                    <select name="filter_dokter" id="filter_dokter" class="form-control select2_dokter_all" data-placeholder="~ Pilih Dokter" onchange="getHistDokter(this.value)">
                        <option value="<?= $kode_dokter ?>">Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $kode_dokter])->nama ?></option>
                    </select>
                    <hr>
                    <?php if ($pendaftaran) : ?>
                        <?php $no_his = count($histori);
                        foreach ($histori as $h) : ?>
                            <div class="card shadow mb-3" style="background-color: <?= ($h->no_trx == $no_trx) ? '#272a3f; color: white;' : 'white' ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span class="h4">Episode : <?= $no_his ?> <?= ($h->tipe_daftar == 1) ? '<span class="badge badge-danger float-right">Rawat Jalan</span>' : '<span class="badge badge-warning float-right">Rawat Inap</span>' ?></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <table>
                                                <tr>
                                                    <td>Tanggal</td>
                                                    <td> : </td>
                                                    <td>Masuk : <?= date('d-m-Y', strtotime($h->tgl_daftar)) ?> / Jam : <?= date('H:i', strtotime($h->jam_daftar)) ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Keluar : <?= (!$h->tgl_keluar) ? 'xx-xx-xxxx' : date('d M y', strtotime($h->tgl_keluar)) ?> / Jam : <?= (!$h->jam_keluar) ? 'xx:xx' : date('H:i', strtotime($h->jam_keluar)) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Dokter</td>
                                                    <td> : </td>
                                                    <td>Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $h->kode_dokter])->nama ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Poli</td>
                                                    <td> : </td>
                                                    <td><?= $this->M_global->getData('m_poli', ['kode_poli' => $h->kode_poli])->keterangan ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td> : </td>
                                                    <td><?= (($h->status_trx == 0) ? '<span class="badge badge-success">Buka</span>' : (($h->status_trx == 2) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-primary">Selesai</span>')) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <?php if ($h->no_trx != $no_trx) : ?>
                                            <div class="col-md-3 m-auto">
                                                <div class="float-right">
                                                    <i type="button" style="background-color: transparent; border: none;" class="fa-solid fa-angles-down text-primary fa-2x" id="btn_down" onclick="show_his('<?= $h->no_trx ?>', 1)"></i>
                                                    <i type="button" style="background-color: transparent; border: none;" class="fa-solid fa-angles-up text-primary fa-2x" id="btn_up" onclick="show_his('<?= $h->no_trx ?>', 2)"></i>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($h->no_trx != $no_trx) : ?>
                                        <div class="row mt-3" id="historipass">
                                            <div class="col-md-12">
                                                <div class="card" style="background-color: <?= ($h->no_trx == $no_trx) ? '#272a3f; color: white;' : 'white' ?>">
                                                    <div class="card-body">
                                                        asdas
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php $no_his--;
                        endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir Pasien <?= (($pendaftaran) ? (($pendaftaran->tipe_daftar == 1) ? 'Rawat Jalan' : 'Rawat Inap') : '') ?></span>
                        </div>
                        <div class="card-footer">
                            <span class="font-weight-bold h4"><i class="fa-solid fa-play text-primary"></i> Data Pasien</span>
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
                            <span class="font-weight-bold h4"><i class="fa-solid fa-play text-primary"></i> E-Order</span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills nav-fill">
                                        <li class="nav-item">
                                            <a type="button" id="for_resep" class="btn btn-light" style="width: 100%;" href="#" onclick="change_tab(1)">Resep</a>
                                        </li>
                                        <li class="nav-item">
                                            <a type="button" id="for_lab" class="btn btn-light" style="width: 100%;" href="#" onclick="change_tab(2)">Laboratorium</a>
                                        </li>
                                        <li class="nav-item">
                                            <a type="button" id="for_rad" class="btn btn-light" style="width: 100%;" href="#" onclick="change_tab(3)">Radiologi</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="for_tab_resep">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <span class="font-weight-bold h4">E-Resep</span>&nbsp;&nbsp;&nbsp;
                                                                <button type="button" class="btn btn-secondary"><i class="fa fa-copy"></i> Resep Sebelumnya</button>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
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
                                                                                        <td class="text-center">
                                                                                            <button class="btn btn-sm btn-danger" type="button" id="btnHapus<?= $no_eresep ?>" onclick="hapusBarang('1')"><i class="fa-solid fa-delete-left"></i></button>
                                                                                        </td>
                                                                                        <td>
                                                                                            <select name="kode_barang[]" id="kode_barang<?= $no_eresep ?>" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '<?= $no_eresep ?>')">
                                                                                                <option value="">~ Pilih Barang</option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <select name="kode_satuan[]" id="kode_satuan<?= $no_eresep ?>" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                                                                                                <option value="">~ Pilih Satuan</option>
                                                                                                <?php foreach ($satuan as $s) : ?>
                                                                                                    <option value="<?= $s['kode_satuan'] ?>" <?= (($er->kode_satuan == $s['kode_satuan']) ? 'selected' : '') ?>><?= $s['keterangan'] ?></option>
                                                                                                <?php endforeach; ?>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="text" id="qty<?= $no_eresep ?>" name="qty[]" value="<?= $no_eresep ?>" min="<?= $no_eresep ?>" class="form-control text-right" onchange="hitung_st('<?= $no_eresep ?>'); formatRp(this.value, 'qty<?= $no_eresep ?>')">
                                                                                        </td>
                                                                                        <td>
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
                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-primary" onclick="addBarang()" id="btnCari"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <span class="font-weight-bold h4">E-Racik</span>&nbsp;&nbsp;&nbsp;
                                                                <button type="button" class="btn btn-secondary"><i class="fa fa-copy"></i> Racikan Sebelumnya</button>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <textarea name="eracik" id="eracik" class="form-control" rows="10"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="for_tab_lab">
                                        lab
                                    </div>
                                    <div id="for_tab_rad">
                                        rad
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
        </div>
    </div>
</form>

<script>
    var no_trx = $('#no_trx');
    const form = $('#form_emr_dokter');
    var for_resep = $('#for_resep');
    var for_lab = $('#for_lab');
    var for_rad = $('#for_rad');
    var for_tab_resep = $('#for_tab_resep');
    var for_tab_lab = $('#for_tab_lab');
    var for_tab_rad = $('#for_tab_rad');
    const btn_up = $('#btn_up');
    const btn_down = $('#btn_down');

    change_tab(1);

    show_his('<?= $no_trx ?>', 1);

    function show_his(no, param) {
        alert(param)
        if (param == 1) {
            btn_up.hide();
            btn_down.show();
        } else {
            btn_up.show();
            btn_down.hide();
        }
    }

    function change_tab(param) {
        if (param == 1) {
            for_resep.removeClass('btn-light');
            for_resep.addClass('btn-primary')
            for_tab_resep.show();

            for_lab.removeClass('btn-primary');
            for_lab.addClass('btn-light');
            for_tab_lab.hide();

            for_rad.removeClass('btn-primary');
            for_rad.addClass('btn-light');
            for_tab_rad.hide();
        } else if (param == 2) {
            for_lab.addClass('btn-primary');
            for_lab.removeClass('btn-light');
            for_tab_lab.show();

            for_resep.addClass('btn-light');
            for_resep.removeClass('btn-primary')
            for_tab_resep.hide();

            for_rad.removeClass('btn-primary');
            for_rad.addClass('btn-light');
            for_tab_rad.hide();
        } else {
            for_rad.removeClass('btn-light');
            for_rad.addClass('btn-primary')
            for_tab_rad.show();

            for_lab.removeClass('btn-primary');
            for_lab.addClass('btn-light');
            for_tab_lab.hide();

            for_resep.removeClass('btn-primary');
            for_resep.addClass('btn-light');
            for_tab_resep.hide();
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
            <td class="text-center">
                <button class="btn btn-sm btn-danger" type="button" id="btnHapus${x}" onclick="hapusBarang('${x}')"><i class="fa-solid fa-delete-left"></i></button>
            </td>
            <td>
                <select name="kode_barang[]" id="kode_barang${x}" class="form-control select2_barang_stok" data-placeholder="~ Pilih Barang" onchange="getSatuan(this.value, '${x}')">
                    <option value="">~ Pilih Barang</option>
                </select>
            </td>
            <td>
                <select name="kode_satuan[]" id="kode_satuan${x}" class="form-control select2_global" data-placeholder="~ Pilih Satuan">
                    <option value="">~ Pilih Satuan</option>
                </select>
            </td>
            <td>
                <input type="text" id="qty${x}" name="qty[]" value="1" min="1" class="form-control text-right" onchange="hitung_st('${x}'); formatRp(this.value, 'qty${x}')">
            </td>
            <td>
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

    function getHistDokter(param) {
        location.href = siteUrl + 'Emr/dokter/' + no_trx.val() + '?kode_dokter=' + param;
    }
</script>