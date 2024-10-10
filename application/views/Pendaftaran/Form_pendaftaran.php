<form method="post" id="form_pendaftaran">
    <div class="row">
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
                                        <input type="text" class="form-control" placeholder="Otomatis" id="no_trx" name="no_trx" value="<?= (!empty($data_pendaftaran) ? $data_pendaftaran->no_trx : '') ?>" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="" class="mt-3">Member <sup class="text-danger">**</sup></label>
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
                                        <label for="">Tgl/Jam Daftar <sup class="text-danger">**</sup></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="<?= (!empty($data_pendaftaran) ? date('Y-m-d', strtotime($data_pendaftaran->tgl_daftar)) : date('Y-m-d')) ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= (!empty($data_pendaftaran) ? date('H:i:s', strtotime($data_pendaftaran->jam_daftar)) : date('H:i:s')) ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">No. Antrian</label>
                                        <input type="text" class="form-control" placeholder="Otomatis" id="no_antrian" name="no_antrian" value="<?= (!empty($data_pendaftaran) ? $data_pendaftaran->no_antrian : '') ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Ruangan/Bed <sup class="text-danger">**</sup></label>
                                        <select name="kode_ruang" id="kode_ruang" class="form-control select2_ruang" data-placeholder="~ Pilih Ruang">
                                            <?php
                                            if (!empty($data_pendaftaran)) :
                                                $ruang = $this->M_global->getData('m_ruang', ['kode_ruang' => $data_pendaftaran->kode_ruang]);
                                                echo '<option value="' . $ruang->kode_ruang . '">' . $ruang->kode_ruang . ' ~ ' . $ruang->keterangan . '</option>';
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Poli <sup class="text-danger">**</sup></label>
                                        <select name="kode_poli" id="kode_poli" class="form-control select2_poli" data-placeholder="~ Pilih Poli" onchange="getDokter(this.value)">
                                            <?php
                                            if (!empty($data_pendaftaran)) :
                                                $poli = $this->M_global->getData('m_poli', ['kode_poli' => $data_pendaftaran->kode_poli]);
                                                echo '<option value="' . $poli->kode_poli . '">' . $poli->kode_poli . ' ~ ' . $poli->keterangan . '</option>';
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Dokter Poli <sup class="text-danger">**</sup></label>
                                        <select name="kode_dokter" id="kode_dokter" class="form-control select2_dokter_poli" data-placeholder="~ Pilih Dokter">
                                            <?php
                                            if (!empty($data_pendaftaran)) :
                                                $dokter = $this->M_global->getData('dokter', ['kode_dokter' => $data_pendaftaran->kode_dokter]);
                                                echo '<option value="' . $dokter->kode_dokter . '">' . $dokter->kode_dokter . ' ~ ' . $dokter->nama . '</option>';
                                            endif;
                                            ?>
                                        </select>
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
                                            <th>Dokter</th>
                                            <th width="10%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyRiwayat">
                                        <?php if (!empty($data_pendaftaran)) : ?>
                                            <?php $no = 1;
                                            foreach ($riwayat as $r) : ?>
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td>
                                                        <?= $this->M_global->getData('cabang', ['kode_cabang' => $r->kode_cabang])->cabang ?>
                                                        <?php
                                                        if ($r->status_trx == 0) {
                                                            $cek_status = 'success';
                                                            $message_status = 'Open';
                                                            $btndis = 'disabled';
                                                        } else if ($r->status_trx == 2) {
                                                            $cek_status = 'warning';
                                                            $message_status = 'Cancel';
                                                            $btndis = '';
                                                        } else {
                                                            $cek_status = 'danger';
                                                            $message_status = 'Close';
                                                            $btndis = '';
                                                        }
                                                        ?>
                                                        <span class="badge badge-<?= $cek_status ?>"><?= $message_status ?></span>
                                                    </td>
                                                    <td><?= $r->no_trx ?></td>
                                                    <td><?= date('Y-m-d', strtotime($r->tgl_daftar)) . ' ~ ' . date('H:i:s', strtotime($r->jam_daftar)) ?></td>
                                                    <td><?= '<span class="text-center">' . (($r->status_trx < 1) ? '-' : date('d/m/Y', strtotime($r->tgl_keluar)) . ' ~ ' . date('H:i:s', strtotime($r->jam_keluar))) . '</>' ?></td>
                                                    <td><?= $this->M_global->getData('m_poli', ['kode_poli' => $r->kode_poli])->keterangan ?></td>
                                                    <td><?= $this->M_global->getData('dokter', ['kode_dokter' => $r->kode_dokter])->nama ?></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Detail Transaksi" onclick="getHisPas('<?= $r->no_trx ?>')" <?= $btndis ?>><i class="fa-solid fa-circle-info"></i></button>
                                                    </td>
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

<script>
    // variable
    var table = $('#tableRiwayat');
    var body = $('#bodyRiwayat');
    var bodyPaket = $('#bodyTarifPaket');
    var no_trx = $('#no_trx');
    var kode_member = $('#kode_member');
    var kode_poli = $('#kode_poli');
    var kode_dokter = $('#kode_dokter');
    var kode_ruang = $('#kode_ruang');
    const btnTambahPaket = $('#btnTambahPaket');

    const form = $('#form_pendaftaran');
    const btnSimpan = $('#btnSimpan');

    $('#btnUMember').attr('disabled', true);

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

    // fungsi save/update
    function save() {
        btnSimpan.attr('disabled', true);

        if (kode_member.val() == '' || kode_member.val() == null) { // jika kode_member kosong/ null
            btnSimpan.attr('disabled', false);

            return Swal.fire("Member", "Sudah dipilih?", "question");
        }

        if (kode_poli.val() == '' || kode_poli.val() == null) { // jika kode_poli kosong/ null
            btnSimpan.attr('disabled', false);

            return Swal.fire("Poli", "Sudah dipilih?", "question");
        }

        if (kode_dokter.val() == '' || kode_dokter.val() == null) { // jika kode_dokter kosong/ null
            btnSimpan.attr('disabled', false);

            return Swal.fire("Dokter", "Sudah dipilih?", "question");
        }

        if (kode_ruang.val() == '' || kode_ruang.val() == null) { // jika kode_ruang kosong/ null
            btnSimpan.attr('disabled', false);

            return Swal.fire("Ruang", "Sudah dipilih?", "question");
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

                if (result.status == 1) { // jika mendapatkan respon 1

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
            url: siteUrl + 'Health/getRiwayat/' + kode_member,
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
                        var message = 'Open';

                        var btndis = 'disabled';
                    } else if (value.status_trx == 2) {
                        var cek_color = 'warning';
                        var message = 'Cancel';

                        var btndis = '';
                    } else {
                        var cek_color = 'danger';
                        var message = 'Close';

                        var btndis = '';
                    }

                    // tampilkan ke bodyRiwayat
                    $('#bodyRiwayat').append(`<tr>
                        <td>${no}</td>
                        <td>${value.cabang} <span class="badge badge-${cek_color}">${message}</span></td>
                        <td>${value.no_trx}</td>
                        <td>${value.tgl_daftar} ~ ${value.jam_daftar}</td>
                        <td>${keluar}</td>
                        <td>${value.nama_poli}</td>
                        <td>${value.nama_dokter}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Detail Transaksi" onclick="getHisPas('${value.no_trx}')" ${btndis}><i class="fa-solid fa-circle-info"></i></button>
                        </td>
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
                        <li>Selanjutnya isikan Form yang tersedia<br>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
                <li style="font-weight: bold;">Ubah Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Ubah pada list data yang ingin di ubah</li>
                        <li>Ubah isi Form yang akan di ubah<br>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
                <li style="font-weight: bold;">Hapus Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Hapus pada list data yang ingin di hapus</li>
                        <li>Saat Muncul Pop Up, klik "Ya, Hapus"</li>
                    </ul>
                </p>
            </ol>
        `);
    }
</script>