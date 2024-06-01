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
                                    <label for="">Invoice <?= (($param2) ? 'Retur' : ''); ?> <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="token_pembayaran" id="token_pembayaran" value="<?= (!empty($data_pembayaran) ? $data_pembayaran->token_pembayaran : '') ?>">
                                        <input type="text" class="form-control" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Invoice" placeholder="Invoice (Otomatis)" id="invoice" name="invoice" value="<?= (!empty($data_pembayaran) ? $data_pembayaran->invoice : '') ?>" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="id-card-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Tgl/Jam Pembayaran <?= (($param2) ? 'Retur' : ''); ?> <sup class="text-danger">**</sup></label>
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
                                    <label for=""><?= (($param2) ? 'Returan' : 'Penjualan'); ?><sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <?php if ($param2) {
                                            $select2_penjualan = 'select2_penjualan_retur';
                                        } else {
                                            $select2_penjualan = 'select2_penjualan';
                                        } ?>
                                        <select name="inv_jual" id="inv_jual" class="form-control <?= $select2_penjualan ?>" data-placeholder="~ Pilih Penjualan" onchange="cekJual(this.value, '<?= (($param2) ? $param2 : '') ?>')">
                                            <?php
                                            if (!empty($data_pembayaran)) :
                                                $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $data_pembayaran->no_trx]);
                                                echo '<option value="' . $data_pembayaran->inv_jual . '">' . $data_pembayaran->inv_jual . ' ~ Kode Member: ' . $pendaftaran->kode_member . ' | Nama Member: ' . $this->M_global->getData('member', ['kode_member' => $pendaftaran->kode_member])->nama . '</option>';
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
                                                    <input type="checkbox" id="cek_cash" name="cek_cash" class="form-control" onclick="cek_cc(0)" <?= (!empty($data_pembayaran) ? (($data_pembayaran->jenis_pembayaran == 0) ? 'checked' : '') : '') ?> <?= (($param2) ? 'disabled' : '') ?>>
                                                </div>
                                                <div class="col-md-6 col-6">CASH</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <input type="checkbox" id="cek_card" name="cek_card" class="form-control" onclick="cek_cc(1)" <?= (!empty($data_pembayaran) ? (($data_pembayaran->jenis_pembayaran == 1) ? 'checked' : '') : '') ?> <?= (($param2) ? 'disabled' : '') ?>>
                                                </div>
                                                <div class="col-md-6 col-6">CARD</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <input type="checkbox" id="cek_cash_card" name="cek_cash_card" class="form-control" onclick="cek_cc(2)" <?= (!empty($data_pembayaran) ? (($data_pembayaran->jenis_pembayaran == 2) ? 'checked' : '') : '') ?> <?= (($param2) ? 'disabled' : '') ?>>
                                                </div>
                                                <div class="col-md-6 col-6">CASH + CARD</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Promo</label>
                                    <div class="input-group mb-3">
                                        <select name="kode_promo" id="kode_promo" class="form-control select2_promo" data-placeholder="~ Pilih Promo" onchange="cek_promo(this.value)">
                                            <?php
                                            if (!empty($data_pembayaran)) :
                                                $m_promo = $this->M_global->getData('m_promo', ['kode_promo' => $data_pembayaran->kode_promo]);
                                                echo '<option value="' . $data_pembayaran->kode_promo . '">' . $data_pembayaran->nama . '</option>';
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Potongan Promo (%)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-right" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Potongan Promo" title="Potongan Promo" placeholder="Potongan Promo" id="potongan_promo" name="potongan_promo" value="<?= (!empty($data_pembayaran) ? number_format($data_pembayaran->discpr_promo) : '0') ?>" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="balloon-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Total Yang Harus Dibayar</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-right font-weight-bold" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Total Harus Bayar" placeholder="Total Harus Bayar" id="total_jual" name="total_jual" value="<?= (!empty($data_pembayaran) ? number_format($data_pembayaran->total - $data_pembayaran->kembalian) : '0') ?>" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="cash-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Kekurangan Dibayar <?= (!empty($data_pembayaran) ? (($data_pembayaran->cek_um == 1) ? '<span class="badge badge-primary">Masuk Ke Uang Muka</span>' : '') : '') ?></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-right font-weight-bold" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Kekurangan" placeholder="Kekurangan" id="total_kurang" name="total_kurang" value="<?= (!empty($data_pembayaran) ? number_format($data_pembayaran->kembalian) : '0') ?>" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="cash-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card shadow" id="forJual">
                        <div class="card-header h5"># Pembayaran</div>
                        <div class="card-body">
                            <div class="row mb-3 not_umum">
                                <div class="col-md-6 col-6">
                                    <label for="" class="text-success font-weight-bold">Uang Muka Tersedia</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-right text-primary font-weight-bold" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Uang Muka Tersedia" placeholder="Uang Muka Tersedia" id="uang_sisa" name="uang_sisa" value="0" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="wallet-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <label for="" class="font-weight-bold">Uang Muka Pakai</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-right" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Uang Muka Pakai" placeholder="Uang Muka Pakai" id="um_keluar" name="um_keluar" value="<?= (!empty($data_pembayaran) ? number_format($data_pembayaran->um_keluar) : '0') ?>" onchange="pakai_um()">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="wallet-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="text-danger font-weight-bold">Total Pembayaran</label>
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
                                                            <table class="table table-striped table-hover table-bordered" id="tableBayarCard">
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
                            <hr>
                            <div class="row not_umum">
                                <div class="col-md-12">
                                    <label for="">Deposit Ke Uang Muka</label>
                                    <div class="row mb-3">
                                        <div class="col-md-1 col-2 mt-auto">
                                            <input type="checkbox" name="cek_umx" id="cek_umx" class="form-control" onclick="cek_um_in()" <?= (!empty($data_pembayaran) ? (($data_pembayaran->cek_um == 1) ? 'checked' : '') : '') ?>>
                                            <input type="hidden" name="cek_um" id="cek_um" class="form-control" value="<?= (!empty($data_pembayaran) ? $data_pembayaran->cek_um : '0') ?>">
                                        </div>
                                        <div class="col-md-11 col-10 ms-auto font-weight-bold">
                                            <span>Keterangan: </span>
                                            <br>
                                            <span class="text-danger">Hasil kembalian Pasien akan masuk kedalam uang muka, untuk di depositkan!</span>
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
    var token_pembayaran = $('#token_pembayaran');
    var invoice = $('#invoice');
    var tgl_pembayaran = $('#tgl_pembayaran');
    var jam_pembayaran = $('#jam_pembayaran');
    var inv_jual = $('#inv_jual');
    var total_jual = $('#total_jual');
    var kode_promo = $('#kode_promo');
    var potongan_promo = $('#potongan_promo');
    var fortableCard = $('#fortableCard');
    var fortableCash = $('#fortableCash');
    var bodyCard = $('#bodyBayarCard');

    const btnSimpan = $('#btnSimpan');
    const form = $('#form_pembayaran');
    const forJual = $('#forJual');

    <?php if ($param2) : ?>
        forJual.hide();
    <?php else : ?>
        forJual.show();
    <?php endif; ?>

    <?php if (!empty($data_pembayaran)) :  ?>
        kode_promo.attr('disabled', false);
        <?php if ($this->M_global->getData('barang_out_header', ['invoice' => $data_pembayaran->inv_jual])->kode_member == 'U00001') : ?>
            $('.not_umum').hide();
        <?php else : ?>
            $('.not_umum').show();
        <?php endif ?>

        <?php if (!empty($bayar_detail)) : ?>
            fortableCard.show();
        <?php else : ?>
            fortableCard.hide();
        <?php endif; ?>
    <?php else :  ?>
        $('.not_umum').hide();
        kode_promo.attr('disabled', true);
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

    // fungsi cek um
    function cek_um_in() {
        if (document.getElementById('cek_umx').checked == true) {
            $('#cek_um').val(1);
        } else {
            $('#cek_um').val(0);
        }
    }

    // fungsi cek promo
    function cek_promo(kopro) {
        if (kopro == '' || kopro == null) {
            return Swal.fire("Promo", "Form sudah dipilih?", "question");
        }

        $.ajax({
            url: siteUrl + 'Master_show/cek_promo/' + kopro,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) { // jika fungsi berjalan
                if (result.status == 0) { // jika mendapatkan respon 0
                    Swal.fire("Promo", "Tidak ditemukan!, coba lagi", "info");
                } else {
                    potongan_promo.val(result.discpr);

                    hitung_promo();
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    // fungsi hitung promo
    function hitung_promo() {
        var totju = parseFloat((total_jual.val()).replaceAll(',', ''));
        var discpr_prom = parseFloat((potongan_promo.val()).replaceAll(',', ''));

        var new_total_jual = totju - (totju * (discpr_prom / 100));

        total_jual.val(formatRpNoId(new_total_jual));
        $('#total_kurang').val(formatRpNoId((0 - new_total_jual)));
    }

    // fungsi cek jual
    function cekJual(x, cek_retur) {
        if (x == '' || x == null) { // jika x kosong/ null
            return Swal.fire("Data Penjualan", "Form sudah dipilih?", "question");
        }

        <?php if ($param2) : ?>
            var cek_retur = 1;
        <?php else : ?>
            var cek_retur = 0;
        <?php endif ?>

        // jalankan fungsi
        $.ajax({
            url: siteUrl + 'Kasir/getInfoJual/' + x + '/' + cek_retur,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) { // jika fungsi berjalan dengan baik
                if (result.status == 0) { // jika mendapatkan respon 0
                    if (result[1].kode_member == 'U00001') {} else {
                        Swal.fire("Total Penjualan", "Tidak ditemukan!, coba lagi", "info");
                    }
                } else { // selain itu
                    total_jual.val(formatRpNoId(result[0].total));
                    $('#total_kurang').val(formatRpNoId((0 - result[0].total)));

                    kode_promo.attr('disabled', false);

                    initailizeSelect2_promo(result[0].total);

                    if (result[1].kode_member == 'U00001') {
                        $('.not_umum').hide();
                    } else {
                        // ambil uangmuka
                        $('.not_umum').show();
                        get_um(result[0].kode_member, cek_retur);
                    }
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    // fungsi ambil uang muka
    function get_um(x, y) {
        if (y == 1) {
            return;
        }

        if (x == '' || x == null) { // jika x kosong/ null
            return Swal.fire("Member", "Form sudah dipilih?", "question");
        }

        $.ajax({
            url: siteUrl + 'Kasir/getInfoUM/' + x,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) { // jika fungsi berjalan dengan baik
                if (result.status == 0) { // jika mendapatkan respon 0
                    Swal.fire("Uang Muka", "Tidak tersedia", "info");
                } else { // selain itu
                    $('#uang_sisa').val(formatRpNoId(result.uang_sisa));
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
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

    // fungsi pakai um
    function pakai_um() {
        var uang_sisa = parseFloat(($('#uang_sisa').val()).replaceAll(',', ''));
        var um_keluar = parseFloat(($('#um_keluar').val()).replaceAll(',', ''));

        if (um_keluar > uang_sisa) {
            $('#um_keluar').val(formatRpNoId(uang_sisa));

            Swal.fire("Uang Muka Pakai", "Lebih besar dari Uang Muka Tersedia", "question");
        } else {
            $('#um_keluar').val(formatRpNoId(um_keluar));
        }

        hitung_bayar();
    }

    // fungsi hitung pembayaran
    function hitung_bayar() {
        var cash = parseFloat(($('#cash').val()).replaceAll(',', ''));
        var card = parseFloat(($('#card').val()).replaceAll(',', ''));
        var um_keluar = parseFloat(($('#um_keluar').val()).replaceAll(',', ''));

        var semua = cash + card + um_keluar;
        $('#cash').val(formatRpNoId(cash));
        $('#total').val(formatRpNoId(semua));

        hitung_kurang();
    }

    // fungsi hitung kurang
    function hitung_kurang() {
        var total_jual = parseFloat(($('#total_jual').val()).replaceAll(',', ''));
        var total = parseFloat(($('#total').val()).replaceAll(',', ''));

        var kurang = total - total_jual;

        $('#total_kurang').val(formatRpNoId(kurang));

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

        if (inv_jual.val() == '' || inv_jual.val() == null) { // jika inv_jual null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Penjualan", "Form sudah dipilih?", "question");
        }

        if (invoice.val() == '' || invoice.val() == null) { // jika invoice null/ kosong
            // isi param = 1
            var param = 1;
        } else { // selain itu
            // isi param = 2
            var param = 2;
        }

        // jalankan proses cek barang
        proses(param);
    }

    // fungsi proses dengan param
    function proses(param) {

        <?php if ($param2) : ?>
            var cek_retur = 1;
        <?php else : ?>
            var cek_retur = 0;
        <?php endif ?>

        if (param == 1) { // jika param 1 berarti insert/tambah
            var message = 'dibuat!';
        } else { // selain itu berarti update/ubah
            var message = 'diperbarui!';
        }

        // jalankan proses dengan param insert/update
        $.ajax({
            url: siteUrl + 'kasir/kasir_proses/' + param + '/' + cek_retur,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Pembayaran <?= (($param2) ? 'Retur' : ''); ?>", "Berhasil " + message, "success").then(() => {
                        question_cetak(result.token_pembayaran);
                    });
                } else { // selain itu

                    Swal.fire("Pembayaran <?= (($param2) ? 'Retur' : ''); ?>", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    function question_cetak(x) {
        Swal.fire({
            title: "Cetak Bukti?",
            text: 'Cetak bukti pembayaran!',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Cetak",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin
                window.open(siteUrl + 'Kasir/print_kwitansi/' + x, '_blank');
                getUrl('Kasir');
            } else {
                getUrl('Kasir');
            }
        });
    }

    // fungsi cek tombol simpan 
    function cek_button() {
        var kurang = parseFloat(($('#total_kurang').val()).replaceAll(',', ''));

        <?php if ($param2) : ?>
            btnSimpan.attr('disabled', false);
        <?php else : ?>
            if (kurang < 0) {
                btnSimpan.attr('disabled', true);
            } else {
                btnSimpan.attr('disabled', false);
            }
        <?php endif; ?>
    }
</script>