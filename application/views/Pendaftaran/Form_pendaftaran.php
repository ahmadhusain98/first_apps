<style>
    :root {
        --fc-border-color: #e9ecef;
        --fc-daygrid-event-dot-width: 5px;
        --fc-button-primary: #007bff;
    }
</style>

<form method="post" id="form_pendaftaran">
    <input type="hidden" name="ulang" id="ulang" value="<?= $ulang ?>">
    <div class="row" data-aos="fade-down"
        data-aos-easing="linear"
        data-aos-duration="1000">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="">No. Pendaftaran</label>
                                        <input type="text" class="form-control" placeholder="Otomatis" id="no_trx" name="no_trx" value="<?= (($ulang == 1) ? '' : (!empty($data_pendaftaran) ? $data_pendaftaran->no_trx : '')) ?>" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="kode_member" class="mt-3">Member <sup class="text-danger">**</sup></label>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <select name="kode_member" id="kode_member" class="form-control select2_member" data-placeholder="~ Cari Member" onchange="getRiwayat(this.value)">
                                                    <?php
                                                    if (!empty($data_pendaftaran)) :
                                                        $member = $this->M_global->getData('member', ['kode_member' => $data_pendaftaran->kode_member]);
                                                        echo '<option value="' . $member->kode_member . '">' . $member->kode_member . ' ~ ' . $member->nama . '</option>';
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-warning w-100" onclick="updateMember()" id="btnUMember"><i class="fa-regular fa-pen-to-square"></i>&nbsp;&nbsp;Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Tgl/Jam Daftar <sup class="text-danger">**</sup></label>
                                                <div class="row">
                                                    <div class="col-md-6 col-6">
                                                        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="<?= (($ulang == 1) ? ((!empty($daftar_ulang)) ? date('Y-m-d', strtotime($daftar_ulang->tgl_ulang)) : '') : ((!empty($data_pendaftaran) ? date('Y-m-d', strtotime($data_pendaftaran->tgl_daftar)) : date('Y-m-d')))) ?>" readonly>
                                                    </div>
                                                    <div class="col-md-6 col-6">
                                                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= (($ulang == 1) ? date('H:i:s') : ((!empty($data_pendaftaran) ? date('H:i:s', strtotime($data_pendaftaran->jam_daftar)) : date('H:i:s')))) ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Tipe Pendaftaran <sup class="text-danger">**</sup></label>
                                                <input type="hidden" id="tipe_daftar" name="tipe_daftar" value="1">
                                                <div class="row">
                                                    <div class="col-md-6 col-6">
                                                        <div class="row">
                                                            <div class="col-md-4 m-auto">
                                                                <input type="checkbox" name="rajal" id="rajal" class="form-control" onclick="changeType(1)">
                                                            </div>
                                                            <div class="col-md-8 m-auto">
                                                                <span for="">Rawat Jalan</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-6">
                                                        <div class="row">
                                                            <div class="col-md-4 m-auto">
                                                                <input type="checkbox" name="ranap" id="ranap" class="form-control" onclick="changeType(2)">
                                                            </div>
                                                            <div class="col-md-8 m-auto">
                                                                <span for="">Rawat Inap</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="no_antrian">No. Antrian</label>
                                        <input type="text" class="form-control" placeholder="Otomatis" id="no_antrian" name="no_antrian" value="<?= (($ulang == 1) ? '' : (!empty($data_pendaftaran) ? $data_pendaftaran->no_antrian : '')) ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="kode_poli">Poli <sup class="text-danger">**</sup></label>
                                                <select name="kode_poli" id="kode_poli" class="form-control select2_poli" data-placeholder="~ Pilih Poli" onchange="getDokter(this.value)">
                                                    <?php
                                                    if (!empty($data_pendaftaran)) :
                                                        $poli = $this->M_global->getData('m_poli', ['kode_poli' => $data_pendaftaran->kode_poli]);
                                                        echo '<option value="' . $poli->kode_poli . '">' . $poli->keterangan . '</option>';
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="kode_jenis_bayar">Jenis Bayar <sup class="text-danger">**</sup></label>
                                                <select name="kode_jenis_bayar" id="kode_jenis_bayar" class="form-control select2_jenis_bayar" data-placeholder="~ Pilih Jenis Bayar">
                                                    <?php
                                                    if (!empty($data_pendaftaran)) :
                                                        $jenis_bayar = $this->M_global->getData('m_jenis_bayar', ['kode_jenis_bayar' => $data_pendaftaran->kode_jenis_bayar]);
                                                        echo '<option value="' . $jenis_bayar->kode_jenis_bayar . '">' . $jenis_bayar->keterangan . '</option>';
                                                    else :
                                                        echo '<option value="JB00000001">Perorangan</option>';
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="kode_dokter">Dokter Poli <sup class="text-danger">**</sup></label>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <select name="kode_dokter" id="kode_dokter" class="form-control select2_dokter_poli" data-placeholder="~ Pilih Dokter">
                                                    <?php
                                                    if (!empty($data_pendaftaran)) :
                                                        $dokter = $this->M_global->getData('dokter', ['kode_dokter' => $data_pendaftaran->kode_dokter]);
                                                        echo '<option value="' . $dokter->kode_dokter . '">Dr. ' . $dokter->nama . '</option>';
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-info w-100" title="Jadwal Dokter" onclick="jadwal_dokter()"><i class="fa fa-info-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for_ranap">
                                        <label for="">Ruangan/Bed <sup class="text-danger">**</sup></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="kode_ruang" id="kode_ruang" class="form-control select2_ruang" data-placeholder="~ Pilih Ruang" onchange="getBed(this.value)">
                                                    <?php
                                                    if (!empty($data_pendaftaran)) :
                                                        $ruang = $this->M_global->getData('m_ruang', ['kode_ruang' => $data_pendaftaran->kode_ruang]);
                                                        echo '<option value="' . $ruang->kode_ruang . '">' . $ruang->keterangan . '</option>';
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="kode_bed" id="kode_bed" class="form-control select2_bed" data-placeholder="~ Pilih Bed">
                                                    <?php
                                                    if (!empty($data_pendaftaran)) :
                                                        $bed = $this->M_global->getData('bed', ['kode_bed' => $data_pendaftaran->kode_bed]);
                                                        echo '<option value="' . $bed->kode_bed . '">' . $bed->nama_bed . '</option>';
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="">Tarif Paket</label>
                            <div class="table-responsive">
                                <input type="hidden" name="jumPaket" id="jumPaket" value="<?= ((!empty($pasien_paket)) ? count($pasien_paket) : 0) ?>">
                                <table class="table table-striped table-bordered" id="tableTarifPaket" width="100%" style="border-raidus: 10px;">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">Hapus</th>
                                            <th width="85%">Tindakan</th>
                                            <th width="10%">Kunjungan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyTarifPaket">
                                        <?php if (!empty($pasien_paket)) : ?>
                                            <?php $no = 1;
                                            foreach ($pasien_paket as $pp) :
                                                $paket = $this->M_global->getData('m_tarif', ['kode_tarif' => $pp->kode_tarif]);
                                            ?>
                                                <tr id="rowPaket<?= $no ?>">
                                                    <td>
                                                        <button type="button" class="btn btn-danger" onclick="hapusTindakan('<?= $no ?>')">
                                                            <i class="fa-solid fa-delete-left"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <select name="kode_tarif[]" id="kode_tarif<?= $no ?>" class="form-control select2_tarif_paket" data-placeholder="~ Pilih Tindakan" onchange="getKunjungan(this.value, <?= $no ?>)">
                                                            <option value="<?= $pp->kode_tarif ?>"><?= $paket->nama; ?></option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="kunjungan[]" id="kunjungan<?= $no ?>" class="form-control text-center" readonly value="<?= $pp->kunjungan ?>">
                                                    </td>
                                                </tr>
                                            <?php $no++;
                                            endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="tambahTarifPaket()" id="btnTambahPaket" <?= ((!empty($pasien_paket) ? (((count($pasien_paket) > 0) ? '' : 'disabled')) : 'disabled')) ?>><i class="fa-solid fa-folder-plus"></i> Tambah Tarif Paket</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger" onclick="getUrl('Health/pendaftaran')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-right">
                                        <?php if (!empty($data_pendaftaran)) : ?>
                                            <button type="button" class="btn btn-info" onclick="getUrl('Health/form_pendaftaran/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                                        <?php else : ?>
                                            <button type="button" class="btn btn-info" onclick="reseting()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
                                        <?php endif ?>
                                        <button type="button" class="btn btn-success" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Riwayat Member</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tableRiwayat" width="100%" style="border-radius: 10px;">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                                            <th>Cabang</th>
                                            <th>No. Transaksi</th>
                                            <th>Tgl/Jam Daftar</th>
                                            <th>Tgl/Jam Keluar</th>
                                            <th>Poli</th>
                                            <th style="border-radius: 0px 10px 0px 0px;">Dokter</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyRiwayat">
                                        <?php if (!empty($data_pendaftaran)) : ?>
                                            <?php $no = 1;
                                            foreach ($riwayat as $r) : ?>
                                                <tr>
                                                    <td style="text-align: right;"><?= $no ?></td>
                                                    <td>
                                                        <?= $this->M_global->getData('cabang', ['kode_cabang' => $r->kode_cabang])->cabang ?>
                                                        <?php
                                                        if ($r->status_trx == 0) {
                                                            $cek_status = 'success';
                                                            $message_status = 'Proses';
                                                            $btndis = 'style="color: black;"';
                                                        } else if ($r->status_trx == 2) {
                                                            $cek_status = 'warning';
                                                            $message_status = 'Batal';
                                                            $btndis = 'style="color: black;"';
                                                        } else {
                                                            $cek_status = 'danger';
                                                            $message_status = 'Selesai';
                                                            $btndis = 'onclick="getHisPas(' . "'" . $r->no_trx . "'" . ')" style="color: blue;"';
                                                        }
                                                        ?>
                                                        <span class="float-right badge badge-<?= $cek_status ?>"><?= $message_status ?></span>
                                                    </td>
                                                    <td>
                                                        <a type="button" <?= $btndis ?>><?= $r->no_trx ?></a>
                                                    </td>
                                                    <td><?= date('Y-m-d', strtotime($r->tgl_daftar)) . ' ~ ' . date('H:i:s', strtotime($r->jam_daftar)) ?></td>
                                                    <td><?= '<span class="text-center">' . (($r->status_trx < 1) ? '-' : date('d/m/Y', strtotime($r->tgl_keluar)) . ' ~ ' . date('H:i:s', strtotime($r->jam_keluar))) . '</>' ?></td>
                                                    <td><?= $this->M_global->getData('m_poli', ['kode_poli' => $r->kode_poli])->keterangan ?></td>
                                                    <td>Dr. <?= $this->M_global->getData('dokter', ['kode_dokter' => $r->kode_dokter])->nama ?></td>
                                                </tr>
                                            <?php $no++;
                                            endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td style="border-radius: 0px 0px 10px 10px;" colspan="8" class="text-center">Belum Ada Riwayat</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- full calendar -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<script>
    // variable
    var table = $('#tableRiwayat');
    var body = $('#bodyRiwayat');
    var bodyPaket = $('#bodyTarifPaket');
    var no_trx = $('#no_trx');
    var kode_member = $('#kode_member');
    var kode_poli = $('#kode_poli');
    var kode_jenis_bayar = $('#kode_jenis_bayar');
    var tipe_daftar = $('#tipe_daftar');
    var kode_dokter = $('#kode_dokter');
    var kode_ruang = $('#kode_ruang');
    var kode_bed = $('#kode_bed');
    const btnTambahPaket = $('#btnTambahPaket');
    var modal_mg = $('#modal_mg');
    var for_ranap = $('.for_ranap');

    const form = $('#form_pendaftaran');
    const btnSimpan = $('#btnSimpan');

    $('#btnUMember').attr('disabled', true);
    changeType(1);

    <?php if ($ulang == 1) : ?>
        getRiwayat('<?= (!empty($daftar_ulang) ? $daftar_ulang->kode_member : '') ?>');

        <?php if (!empty($daftar_ulang)) : ?>
            Swal.fire("Pasien Appointment", "Pastikan jadwal dokter tersedia sebelum mendaftarkan ulang pasien!", "info");
        <?php endif; ?>
    <?php endif ?>

    function tambahTarifPaket() {
        var jum = Number($('#jumPaket').val());
        var row = jum + 1;

        $('#jumPaket').val(row);

        bodyPaket.append(`<tr id="rowPaket${row}">
            <td>
                <button type="button" class="btn btn-danger" onclick="hapusTindakan(${row})">
                    <i class="fa-solid fa-delete-left"></i>
                </button>
            </td>
            <td>
                <select name="kode_tarif[]" id="kode_tarif${row}" class="form-control select2_tarif_paket" data-placeholder="~ Pilih Tindakan" onchange="getKunjungan(this.value, ${row})"></select>
            </td>
            <td>
                <input type="text" name="kunjungan[]" id="kunjungan${row}" class="form-control text-center" readonly>
            </td>
        </tr>`);

        initailizeSelect2_tarif_paket();
    }

    function changeType(param) {
        var rajal = document.getElementById('rajal');
        var ranap = document.getElementById('ranap');

        if (param == 1) {
            rajal.checked = true;
            ranap.checked = false;
            tipe_daftar.val(1)
            for_ranap.hide(200)
        } else {
            // rajal.checked = false;
            // ranap.checked = true;
            // tipe_daftar.val(2)
            // for_ranap.show(200)
            changeType(1)

            Swal.fire("Rawat Inap", "Coming Soon", "info");
        }
    }

    function hapusTindakan(i) {
        $('#rowPaket' + i).remove();
    }

    function getKunjungan(kdtf, i) {
        if (!kdtf || kdtf === null) {
            return
        }

        var kdmbr = $('#kode_member').val();

        $.ajax({
            url: siteUrl + 'Health/getPaket/' + kdtf + '/' + kdmbr,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) {
                if (result.status == 1) {
                    $('#kunjungan' + i).val(result.kunjungan);
                }
            },
            error: function(result) {
                error_proccess();
            }
        });
    }

    // fungsi get dokter berdasarkan kode poli
    function getDokter(kode_poli) {
        kode_dokter.empty();

        if (kode_poli == '' || kode_poli == null) { // jika kode poli kosong/ null
            // tampilkan notif
            Swal.fire("Poli", "Sudah dipilih?", "question");
            // set param jadi kosong
            var param = '';
        } else {
            // set param menjadi kode poli
            var param = kode_poli;
        }

        // jalankan select2 berdasarkan param
        initailizeSelect2_dokter_poli(param);
    }

    // fungsi get bed berdasarkan ruang
    function getBed(param) {
        kode_bed.empty();

        if (param == '' || param == null) { // jika kode ruang kosong/ null
            // tampilkan notif
            Swal.fire("Poli", "Sudah dipilih?", "question");
            // set param jadi kosong
            var param = '';
        } else {
            // set param menjadi kode ruang
            var param = param;
        }

        // jalankan select2 berdasarkan param
        initailizeSelect2_bed(param);
    }

    // fungsi save/update
    function save() {
        btnSimpan.attr('disabled', true);

        if (kode_member.val() == '' || kode_member.val() == null) { // jika kode_member kosong/ null
            btnSimpan.attr('disabled', false);

            return Swal.fire("Member", "Sudah dipilih?", "question");
        }

        if (kode_dokter.val() == '' || kode_dokter.val() == null) { // jika kode_dokter kosong/ null
            btnSimpan.attr('disabled', false);

            return Swal.fire("Dokter", "Sudah dipilih?", "question");
        }

        if (kode_jenis_bayar.val() == '' || kode_jenis_bayar.val() == null) { // jika kode_jenis_bayar kosong/ null
            btnSimpan.attr('disabled', false);

            return Swal.fire("Jenis Bayar", "Sudah dipilih?", "question");
        }

        if (tipe_daftar.val() == 1) {
            if ($('#kode_poli').val() == '' || $('#kode_poli').val() == null) { // jika kode_poli kosong/ null
                btnSimpan.attr('disabled', false);

                return Swal.fire("Poli", "Sudah dipilih?", "question");
            }

        } else {
            if (kode_ruang.val() == '' || kode_ruang.val() == null) { // jika kode_ruang kosong/ null
                btnSimpan.attr('disabled', false);

                return Swal.fire("Ruang", "Sudah dipilih?", "question");
            }

            if (kode_bed.val() == '' || kode_bed.val() == null) { // jika kode_bed kosong/ null
                btnSimpan.attr('disabled', false);

                return Swal.fire("Ruang", "Sudah dipilih?", "question");
            }
        }

        if (no_trx.val() == '' || no_trx.val() == null) { // jika kode no_trx kosong/ null
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek logistik
        if (param == 1) {
            $.ajax({
                url: siteUrl + 'Health/cekStatusMember',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(result) { // jika fungsi berjalan dengan baik
                    if (result.status == 1) { // jika mendapatkan respon 1
                        // jalankan fungsi proses berdasarkan param
                        proses(param);
                    } else { // selain itu
                        btnSimpan.attr('disabled', false);

                        Swal.fire("Member " + result.kode_member, "Sudah terdaftar di cabang <b>" + result.cabang + "</b> pada tanggal <b>" + result.tgl + "</b><br>Silahkan <b>hubungi cabang terkait</b> untuk diselesaikan!", "info");
                    }
                },
                error: function(result) { // jika fungsi error
                    btnSimpan.attr('disabled', false);

                    error_proccess();
                }
            });
        } else {
            proses(param);
        }
    }

    // fungsi proses
    function proses(param) {
        if (param == 1) { // jika param 1 berarti insert/tambah
            var message = 'dibuat!';
        } else { // selain itu berarti update/ubah
            var message = 'diperbarui!';
        }

        // jalankan proses dengan param insert/update
        $.ajax({
            url: siteUrl + 'Health/pendaftaran_proses/' + param,
            type: "POST",
            data: $('#form_pendaftaran').serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 2) {
                    Swal.fire("Limit Pendaftaran " + result.limit + " Pasien", "Pasien Dr. " + result.dokter + " sudah penuh, mohon maaf silahkan lakukan diesok hari, Terima kasih!", "info");
                } else if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Pendaftaran", "Berhasil " + message, "success").then(() => {
                        // querstion(result.no_trx);
                        getUrl('Health/pendaftaran');
                    });
                } else { // selain itu

                    Swal.fire("Pendaftaran", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    function querstion(param) {
        Swal.fire({
            title: "Cetak Berkas?",
            text: "Berkas bukti pendaftaran pasien!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, cetak!",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin
                getUrl('Health/pendaftaran');
                getDetail(param);
            } else {
                getUrl('Health/pendaftaran');
            }
        });
    }

    // fungsi ambil riwayat
    function getRiwayat(kode_member) {
        if (kode_member == '' || kode_member == null) { // jika kode_member kosong/ null
            // kosongkan body
            $('#btnUMember').attr('disabled', true);
            return body.empty();
        }

        btnTambahPaket.attr('disabled', false);

        $('#btnUMember').attr('disabled', false);
        // kosongkan body
        body.empty();

        // jalankan fungsi
        $.ajax({
            url: '<?= site_url() ?>Health/getRiwayat/' + kode_member,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) { // jika fungsi berjalan
                var no = 1;

                // loop hasil
                $.each(result, function(index, value) {
                    if (value.tgl_keluar == null) { // jika tgl keluarnya null
                        // beri nilai minus/ strip
                        var keluar = "-";
                    } else { // selain itu
                        // beri nilai sesuai record db
                        var keluar = value.tgl_keluar + ' ~ ' + value.jam_keluar;
                    }

                    if (value.status_trx == 0) {
                        var cek_color = 'success';
                        var message = 'Proses';

                        var btndis = 'style="color: black;"';
                    } else if (value.status_trx == 2) {
                        var cek_color = 'warning';
                        var message = 'Batal';

                        var btndis = `style="color: black;"`;
                    } else {
                        var cek_color = 'danger';
                        var message = 'Selesai';

                        var btndis = ` onclick="getHisPas('${value.no_trx}')" style="color: blue;"`;
                    }

                    // tampilkan ke bodyRiwayat
                    $('#bodyRiwayat').append(`<tr>
                        <td style="text-align: right;">${no}</td>
                        <td>${value.cabang} <span class="float-right badge badge-${cek_color}">${message}</span></td>
                        <td>
                            <a type="button" ${btndis}>${value.no_trx}</a>
                        </td>
                        <td>${value.tgl_daftar} ~ ${value.jam_daftar}</td>
                        <td>${keluar}</td>
                        <td>${value.nama_poli}</td>
                        <td>Dr. ${value.nama_dokter}</td>
                    </tr>`);
                    no++;
                });
            },
            error: function(result) { // jika fungsi error

                // jalankan fungsi error
                error_proccess();
            }
        });
    }

    // fungsi update data member
    function updateMember() {
        var param = kode_member.val();
        if (param == '' || param == null) {
            return Swal.fire("Member", "Sudah dipilih?", "question");
        }

        getUrl('Health/form_daftar/' + param);
    }

    function reseting() {
        $('#no_trx').val('');
        $('#no_antrian').val('');
        $('#kode_member').val('').change();
        $('#kode_poli').val('').change();
        $('#kode_dokter').val('').change();
        $('#kode_ruang').val('').change();
        $('#kode_jenis_bayar').val('').change();
        bodyPaket.empty();
        body.empty();
    }

    // fungsi lihat detail
    function getDetail(param) {
        window.open(siteUrl + 'Health/print_pendaftaran/' + param + '/0', '_blank');
    }

    // fungsi lihat detail
    function getHisPas(param) {
        $.ajax({
            url: siteUrl + 'Health/getToken/' + param,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) {
                if (result.status == 1) {
                    window.open(siteUrl + 'Kasir/print_kwitansi/' + result.token + '/0', '_blank');
                } else {
                    Swal.fire("History Pasien", "Gagal diambil, silahkan dicoba kembali", "info");
                }
            },
            error: function(result) {
                error_proccess();
            }
        });
    }

    function showGuide() {
        // ubah ukuran modal
        $('.modal-dialog').removeClass('modal-lg')
        $('.modal-dialog').addClass('modal-xl')

        // clean text
        $('#modal_mgLabel').text(``);
        $('#modal-isi').text(``);

        $('#modal_mg').modal('show'); // show modal

        // isi text
        $('#modal_mgLabel').append(`Manual Guide Pendaftaran`);
        $('#modal-isi').append(`
            <ol>
                <li style="font-weight: bold;">Tambah Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Tambah</li>
                        <li>Selanjutnya isikan Form berikut:</li>
                        <p>
                            <ul>
                                <li>Cari Member yang akan didaftarkan<br>(jika ingin update data member, klik tombol Update di kanan form member)</li>
                                <li>Pilih Ruangan/Bed</li>
                                <li>Pilih Poli</li>
                                <li>Pilih Dokter, dan</li>
                                <li>Pilih Tarif Paket jika menggunakan Tarif Paket, jika tidak maka tidak perlu di pilih</li>
                            </ul>
                        </p>
                        <li>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
                <li style="font-weight: bold;">Ubah Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Ubah pada list data yang ingin di ubah</li>
                        <li>Selanjutnya ubah Form yang ingin diubah, diantaranya:</li>
                        <p>
                            <ul>
                                <li>Form Member yang akan didaftarkan<br>(jika ingin update data member, klik tombol Update di kanan form member)</li>
                                <li>Form Ruangan/Bed</li>
                                <li>Form Poli</li>
                                <li>Form Dokter, dan</li>
                                <li>Form Tarif Paket jika menggunakan Tarif Paket, jika tidak maka tidak perlu di pilih</li>
                            </ul>
                        </p>
                        <li>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
            </ol>
        `);
    }

    // modal jadwal dokter
    function jadwal_dokter() {
        // ubah ukuran modal
        $('.modal-dialog').removeClass('modal-lg')
        $('.modal-dialog').addClass('modal-xl')

        // clean text
        $('#modal_mgLabel').text(``);
        $('#modal-isi').text(``);

        setTimeout(function() {
            kalendar();
        }, 500); // Add delay to ensure modal is fully shown

        $('#modal_mg').modal('show'); // show modal

        $('#modal_mgLabel').text(`Jadwal Dokter`);
        $('#modal-isi').append("<div id='calendar' style='min-height: 700px;'></div>"); // Adjusted height

    }

    // fungsi kalendar
    function kalendar() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id', // ubah lokasi ke indonesia
            editable: true,
            headerToolbar: { // menampilkan button yang akan ditampilkan
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: { // merubah text button
                today: 'Hari ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            customButtons: { // merubah text button
                prev: {
                    text: 'Sebelumnya',
                    click: function() {
                        calendar.prev();
                    }
                },
                next: {
                    text: 'Berikutnya',
                    click: function() {
                        calendar.next();
                    }
                }
            },
            events: { // load data fullcalendar
                url: siteUrl + 'Health/jdokter_list',
                method: 'GET',
                failure: function() {
                    Swal.fire("Jadwal Dokter", "Gagal diload", "error");
                },
                allDay: false
            },
            eventContent: function(arg) { // mengubah tanda koma (,) menjadi <br>
                let title = arg.event.title.split(',').join('<br>');
                return {
                    html: title
                };
            },
            eventDidMount: function(info) {
                // Mengatur warna teks menjadi putih
                info.el.style.color = 'white';

                // Mengatur warna latar belakang berdasarkan status
                switch (info.event.extendedProps.status_dokter) {
                    case '1': // Hadir
                        info.el.style.backgroundColor = '#007bff'; // Biru
                        break;
                    case '2': // Izin
                        info.el.style.backgroundColor = '#ffd000'; // Kuning
                        break;
                    case '3': // Sakit
                        info.el.style.backgroundColor = '#ed1e32'; // Merah
                        break;
                    case '4': // Cuti
                        info.el.style.backgroundColor = '#2aae47'; // Hijau
                        break;
                    default:
                        info.el.style.backgroundColor = '#76818d'; // Warna default jika status tidak dikenali
                }
            },
            eventMouseEnter: function(info) {
                // Fungsi untuk memformat tanggal
                function formatDate(date) {
                    const yyyy = date.getFullYear();
                    let mm = date.getMonth() + 1;
                    let dd = date.getDate();

                    if (dd < 10) dd = '0' + dd;
                    if (mm < 10) mm = '0' + mm;

                    return dd + '-' + mm + '-' + yyyy;
                }

                const start_date = info.event.start || new Date(info.event.startStr); // fallback ke startStr jika start tidak ada
                const formattedStartDate = formatDateWithDay(start_date); // Memformat tanggal mulai

                const end_date = info.event.end || new Date(info.event.endStr); // fallback ke endStr jika end tidak ada
                const formattedEndDate = formatDateWithDay(end_date); // Memformat tanggal selesai

                const formattedStartTime = formatTime(info.event.extendedProps.time_start);
                const formattedEndTime = formatTime(info.event.extendedProps.time_end);

                if (info.event.extendedProps.limit_px == 0) {
                    var limit_px = 'Tidak Terbatas';
                } else {
                    var limit_px = info.event.extendedProps.limit_px + ' Pasien';
                }

                // Buat tooltip dengan informasi dokter, waktu mulai dan selesai, serta catatan
                $(info.el).tooltip({
                    title: 'Nama: ' + info.event.extendedProps.nama_dokter + '<br>Hari: ' + formattedStartDate + ' (' + formattedStartTime + ' - ' + formattedEndTime + ')<br>Limit Pasien: ' + limit_px + '<br>Catatan: ' + info.event.extendedProps.comment,
                    html: true,
                    placement: 'top'
                });

                // Tampilkan tooltip
                $(info.el).tooltip('show');
            },
            eventMouseLeave: function(info) { // saat tidak di hover
                // sembunyikan tooltip
                $(info.el).tooltip('hide');
            }
        });
        calendar.render();
    }
</script>