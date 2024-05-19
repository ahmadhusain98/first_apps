<form method="post" id="form_pembayaran">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header font-weight-bold h4"># Form Pembayaran <?= (($param2) ? 'Retur' : ''); ?></div>
                <div class="card-body">
                    <div class="card shadow">
                        <div class="card-header h5"># Form</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Invoice <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Invoice" placeholder="Invoice (Otomatis)" id="invoice" name="invoice" value="<?= (!empty($data_pembayaran) ? $data_pembayaran->invoice : '') ?>" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="id-card-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Tgl/Jam Pembayaran <sup class="text-danger">**</sup></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <input type="date" class="form-control" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Tgl Pembayaran" placeholder="Tgl Pembayaran" id="tgl_pembayaran" name="tgl_pembayaran" value="<?= (!empty($data_pembayaran) ? date('Y-m-d', strtotime($data_pembayaran->tgl_pembayaran)) : date('Y-m-d')) ?>" readonly>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <ion-icon name="calendar-number-outline"></ion-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <input type="time" class="form-control" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Jam Pembayaran" placeholder="Jam Pembayaran" id="jam_pembayaran" name="jam_pembayaran" value="<?= (!empty($data_pembayaran) ? date('H:i:s', strtotime($data_pembayaran->jam_pembayaran)) : date('H:i:s')) ?>" readonly>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <ion-icon name="time-outline"></ion-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Member<sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <select name="kode_member" id="kode_member" class="form-control select2_member" data-placeholder="~ Pilih Member">
                                            <?php
                                            if (!empty($data_pembayaran)) :
                                                $member = $this->M_global->getData('member', ['kode_member' => $data_pembayaran->kode_member]);
                                                echo '<option value="' . $data_pembayaran->kode_member . '">' . $data_pembayaran->kode_member . ' ~ Kode Member: ' . $member->kode_member . ' | Nama Member: ' . $this->M_global->getData('member', ['kode_member' => $member->kode_member])->nama . '</option>';
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Jenis Pembayaran <sup class="text-danger">**</sup></label>
                                    <input type="hidden" name="jenis_pembayaran" id="jenis_pembayaran" value="<?= (!empty($data_pembayaran) ? $data_pembayaran->jenis_pembayaran : 0) ?>">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <input type="checkbox" id="cek_cash" name="cek_cash" class="form-control" onclick="cek_cc(0)" <?= (!empty($data_pembayaran) ? (($data_pembayaran->jenis_pembayaran == 0) ? 'checked' : '') : '') ?>>
                                                </div>
                                                <div class="col-md-6 col-6">CASH</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <input type="checkbox" id="cek_card" name="cek_card" class="form-control" onclick="cek_cc(1)" <?= (!empty($data_pembayaran) ? (($data_pembayaran->jenis_pembayaran == 1) ? 'checked' : '') : '') ?>>
                                                </div>
                                                <div class="col-md-6 col-6">CARD</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <input type="checkbox" id="cek_cash_card" name="cek_cash_card" class="form-control" onclick="cek_cc(2)" <?= (!empty($data_pembayaran) ? (($data_pembayaran->jenis_pembayaran == 2) ? 'checked' : '') : '') ?>>
                                                </div>
                                                <div class="col-md-6 col-6">CASH + CARD</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card shadow">
                        <div class="card-header h5"># Deposit</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="text-danger font-weight-bold">Total Deposit</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-right text-primary font-weight-bold" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Pembayaran Total" placeholder="Pembayaran Total" id="total" name="total" value="<?= (!empty($data_pembayaran) ? number_format($data_pembayaran->total) : '0') ?>" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="wallet-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="fortableCash">
                                    <label for="">Cash</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-right" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Pembayaran Cash" placeholder="Pembayaran Cash" id="cash" name="cash" value="<?= (!empty($data_pembayaran) ? number_format($data_pembayaran->cash) : '0') ?>" onchange="hitung_bayar()">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="cash-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="fortableCard">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group mb-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover table-bordered" id="tableBayarCard" style="width: 100%;">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <th style="width: 5%;">Hapus</th>
                                                                        <th style="width: 15%;">Bank</th>
                                                                        <th style="width: 10%;">Tipe</th>
                                                                        <th style="width: 20%;">No. Kartu</th>
                                                                        <th style="width: 20%;">Approval</th>
                                                                        <th style="width: 20%;">Pembayaran</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="bodyBayarCard">
                                                                    <?php if (!empty($bayar_detail)) : ?>
                                                                        <?php $no = 1;
                                                                        foreach ($bayar_detail as $bd) : ?>
                                                                            <tr id="rowCard<?= $no ?>">
                                                                                <td>
                                                                                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Hapus" onclick="hapusBaris(<?= $no ?>)"><ion-icon name="ban-outline"></ion-icon></button>
                                                                                </td>
                                                                                <td>
                                                                                    <select name="kode_bank[]" id="kode_bank<?= $no ?>" class="select2_bank" data-placeholder="~ Pilih Bank">
                                                                                        <option value="<?= $bd->kode_bank ?>"><?= $this->M_global->getData('m_bank', ['kode_bank' => $bd->kode_bank])->keterangan; ?></option>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <select name="tipe_bank[]" id="tipe_bank<?= $no ?>" class="select2_tipe_bank" data-placeholder="~ Pilih Tipe Bank">
                                                                                        <option value="<?= $bd->kode_tipe ?>"><?= $this->M_global->getData('tipe_bank', ['kode_tipe' => $bd->kode_tipe])->keterangan; ?></option>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="no_card[]" id="no_card<?= $no ?>" class="form-control" maxlength="16" value="<?= $bd->no_card ?>">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="approval[]" id="approval<?= $no ?>" class="form-control" maxlength="6" value="<?= $bd->approval ?>">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="jumlah_card[]" id="jumlah_card<?= $no ?>" class="form-control text-right" value="<?= number_format($bd->jumlah) ?>" onchange="hitung_card(<?= $no ?>); formatRp(this.value, 'jumlah_card1')">
                                                                                </td>
                                                                            </tr>
                                                                        <?php $no++;
                                                                        endforeach; ?>
                                                                    <?php else : ?>
                                                                        <tr id="rowCard1">
                                                                            <td>
                                                                                <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Hapus" onclick="hapusBaris(1)"><ion-icon name="ban-outline"></ion-icon></button>
                                                                            </td>
                                                                            <td>
                                                                                <select name="kode_bank[]" id="kode_bank1" class="select2_bank" data-placeholder="~ Pilih Bank"></select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="tipe_bank[]" id="tipe_bank1" class="select2_tipe_bank" data-placeholder="~ Pilih Tipe Bank"></select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="no_card[]" id="no_card1" class="form-control" maxlength="16">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="approval[]" id="approval1" class="form-control" maxlength="6">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="jumlah_card[]" id="jumlah_card1" class="form-control text-right" value="0" onchange="hitung_card(1); formatRp(this.value, 'jumlah_card1')">
                                                                            </td>
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" class="form-control" id="jumCard" value="<?= (!empty($bayar_detail) ? count($bayar_detail) : '1') ?>">
                                            <button type="button" class="btn btn-primary" onclick="tambah_card()"><ion-icon name="add-circle-outline"></ion-icon> Tambah Card</button>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <div class="row">
                                                <label for="" class="control-label col-md-3 my-auto">Total Card</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control text-right" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Total Card" placeholder="Total Card" id="card" name="card" value="<?= (!empty($data_pembayaran) ? number_format($data_pembayaran->card) : '0') ?>" readonly>
                                                </div>
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
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger btn-sm" onclick="getUrl('Kasir')" id="btnKembali"><ion-icon name="play-back-outline"></ion-icon> Kembali</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan"><ion-icon name="save-outline"></ion-icon> <?= (!empty($data_pembayaran) ? 'Perbarui' : 'Simpan') ?></button>
                            <?php if (!empty($data_pembayaran)) : ?>
                                <button type="button" class="btn btn-success float-right btn-sm" onclick="getUrl('Kasir/form_kasir/0')" id="btnBaru"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
                            <?php else : ?>
                                <button type="button" class="btn btn-info float-right btn-sm" onclick="reset()" id="btnReset"><ion-icon name="refresh-outline"></ion-icon> Reset</button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var invoice = $('#invoice');
    var tgl_pembayaran = $('#tgl_pembayaran');
    var jam_pembayaran = $('#jam_pembayaran');
    var kode_member = $('#kode_member');
    var total_jual = $('#total_jual');
    var fortableCard = $('#fortableCard');
    var fortableCash = $('#fortableCash');
    var bodyCard = $('#bodyBayarCard');

    const btnSimpan = $('#btnSimpan');
    const form = $('#form_pembayaran');

    <?php if (!empty($data_pembayaran)) :  ?>
        <?php if (!empty($bayar_detail)) : ?>
            fortableCard.show();
        <?php else : ?>
            fortableCard.hide();
        <?php endif; ?>
    <?php else :  ?>
        document.getElementById('cek_cash').checked = true;
        fortableCard.hide();
        <?php if (!$param2) : ?>
            btnSimpan.attr('disabled', true);
        <?php endif;  ?>
    <?php endif;  ?>

    function cek_cc(isi) {

        if (isi == 0) {
            document.getElementById('cek_card').checked = false;
            document.getElementById('cek_cash').checked = true;
            document.getElementById('cek_cash_card').checked = false;

            fortableCash.show(200);
            fortableCard.hide(200);
        } else if (isi == 1) {
            document.getElementById('cek_card').checked = true;
            document.getElementById('cek_cash').checked = false;
            document.getElementById('cek_cash_card').checked = false;

            fortableCash.hide(200);
            fortableCard.show(200);
        } else {
            document.getElementById('cek_card').checked = false;
            document.getElementById('cek_cash').checked = false;
            document.getElementById('cek_cash_card').checked = true;

            fortableCash.show(200);
            fortableCard.show(200);
        }

        $('#jenis_pembayaran').val(isi);
    }

    // fungsi format Rupiah NoId
    function formatRpNoId(num) {
        num = num.toString().replace(/\$|\,/g, '');

        num = Math.ceil(num);

        if (isNaN(num)) num = "0";

        sign = (num == (num = Math.abs(num)));
        num = Math.floor(num * 100 + 0.50000000001);
        cents = num % 100;
        num = Math.floor(num / 100).toString();

        if (cents < 10) cents = "0" + cents;

        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) {
            num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
        }

        return (((sign) ? '' : '-') + '' + num);
    }

    // fungsi tambah baris card
    function tambah_card() {
        var jum = Number($('#jumCard').val());
        var row = jum + 1;

        $('#jumCard').val(row);
        bodyCard.append(`<tr id="rowCard${row}">
            <td>
                <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Hapus" onclick="hapusBaris(${row})"><ion-icon name="ban-outline"></ion-icon></button>
            </td>
            <td>
                <select name="kode_bank[]" id="kode_bank${row}" class="select2_bank" data-placeholder="~ Pilih Bank"></select>
            </td>
            <td>
                <select name="tipe_bank[]" id="tipe_bank${row}" class="select2_tipe_bank" data-placeholder="~ Pilih Tipe Bank"></select>
            </td>
            <td>
                <input type="text" name="no_card[]" id="no_card${row}" class="form-control" maxlength="16">
            </td>
            <td>
                <input type="text" name="approval[]" id="approval${row}" class="form-control" maxlength="6">
            </td>
            <td>
                <input type="text" name="jumlah_card[]" id="jumlah_card${row}" class="form-control text-right" value="0" onchange="hitung_card(${row}); formatRp(this.value, 'jumlah_card${row}')">
            </td>
        </tr>`);


        initailizeSelect2_bank();
        initailizeSelect2_tipe_bank();
    }

    // fungsi hapus baris card
    function hapusBaris(row) {
        $('#rowCard' + row).remove();

        hitung_card_all();
    }

    // fungsi hitung pembayaran
    function hitung_bayar() {
        var cash = parseFloat(($('#cash').val()).replaceAll(',', ''));
        var card = parseFloat(($('#card').val()).replaceAll(',', ''));

        var semua = cash + card;
        $('#cash').val(formatRpNoId(cash));
        $('#total').val(formatRpNoId(semua));
        cek_button();
    }

    // fungsi hitung row card
    function hitung_card(x) {
        var jumlah = ($('#jumlah_card' + x).val()).replaceAll(',', '');

        hitung_card_all();
    }

    // fungsi hitung seluruh card
    function hitung_card_all() {
        var tableBayarCard = document.getElementById('tableBayarCard'); // ambil id table detail
        var rowCount = tableBayarCard.rows.length; // hitung jumlah rownya

        // buat variable untuk di sum
        var tjumlah = 0;
        for (var i = 1; i < rowCount; i++) {
            var row = tableBayarCard.rows[i];

            var jumlah1 = Number((row.cells[5].children[0].value).replace(/[^0-9\.]+/g, ""));

            // lakukan rumus sum
            tjumlah += jumlah1;
        }

        $('#card').val(formatRpNoId(tjumlah));

        hitung_bayar();
    }

    // fungsi save
    function save() {
        btnSimpan.attr('disabled', true);

        if (kode_member.val() == '' || kode_member.val() == null) { // jika kode_member null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Member", "Form sudah dipilih?", "question");
        }

        if (invoice.val() == '' || invoice.val() == null) { // jika invoice null/ kosong
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        $("#loading").modal("show");

        // jalankan proses cek barang
        proses(param);
    }

    // fungsi proses dengan param
    function proses(param) {

        if (param == 1) { // jika param 1 berarti insert/tambah
            var message = 'dibuat!';
        } else { // selain itu berarti update/ubah
            var message = 'diperbarui!';
        }

        // jalankan proses dengan param insert/update
        $.ajax({
            url: siteUrl + 'kasir/um_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1
                    $("#loading").modal("hide");

                    Swal.fire("Deposit", "Berhasil " + message, "success").then(() => {
                        getUrl('Kasir/deposit_um');
                    });
                } else { // selain itu
                    $("#loading").modal("hide");

                    Swal.fire("Deposit", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                $("#loading").modal("hide");

                error_proccess();
            }
        });
    }

    // fungsi cek tombol simpan 
    function cek_button() {
        var total = parseFloat(($('#total').val()).replaceAll(',', ''));

        if (total < 0) {
            btnSimpan.attr('disabled', true);
        } else {
            btnSimpan.attr('disabled', false);
        }
    }
</script>