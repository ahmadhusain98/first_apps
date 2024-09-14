<form method="post" id="form_user">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <label for="no_jurnal" class="control-label">No. Jurnal</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Otomatis" id="no_jurnal" name="no_jurnal" value="">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="">Tgl/Jam <sup class="text-danger">**</sup></label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" placeholder="Tgl" id="tgl" name="tgl" value="<?= date('Y-m-d') ?>" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="time" class="form-control" placeholder="Jam" id="jam" name="jam" value="<?= date('H:i:s') ?>" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="keterangan" class="control-label">Keterangan</label>
                    <div class="input-group mb-3">
                        <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-note-sticky"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="kode_cabang" class="control-label">Cabang</label>
                    <div class="input-group mb-3">
                        <?php $cab = $this->M_global->getData('cabang', ['kode_cabang' => $this->session->userdata('cabang')]); ?>
                        <input type="hidden" class="form-control" placeholder="Otomatis" id="kode_cabang" name="kode_cabang" value="<?= $this->session->userdata('cabang') ?>">
                        <textarea name="kode_cabangx" id="kode_cabangx" class="form-control" disabled><?= '(' . $cab->inisial_cabang . ') ' . $cab->cabang ?></textarea>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-building"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Detail</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <input type="hidden" name="jumDetail" id="jumDetail" value="1">
                <table class="table table-striped table-bordered" id="tableDetail" style="width: 100%; border-radius: 10px;">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 5%;">Hapus</th>
                            <th style="width: 55%;">Akun</th>
                            <th style="width: 20%;">Cash</th>
                            <th style="width: 20%;">Card</th>
                        </tr>
                    </thead>
                    <tbody id="bodyDetail">
                        <tr id="rowDetail1">
                            <td>
                                <button type="button" class="btn btn-danger" onclick="hapusBaris('1')">
                                    <i class="fa-solid fa-delete-left"></i>
                                </button>
                            </td>
                            <td>
                                <select name="kode_akun[]" id="kode_akun1" class="form-control select2_akun_sel" data-placeholder="~ Pilih Akun"></select>
                            </td>
                            <td>
                                <input type="text" name="cash[]" id="cash1" class="form-control text-right" value="0" onkeyup="hitung_t(); formatRpNoId(this.value)">
                            </td>
                            <td>
                                <input type="text" name="card[]" id="card1" class="form-control text-right" value="0" onkeyup="hitung_t(); formatRpNoId(this.value)">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 col-12">
            <button type="button" class="btn btn-primary" onclick="tambahDetail()" id="btnTambahDetail"><i class="fa-solid fa-folder-plus"></i> Tambah</button>
        </div>
        <div class="col-md-5 col-12">
            <div class="card">
                <div class="card-footer">
                    <div class="row mb-1">
                        <label for="total_cash" class="control-label col-md-4 col-12 my-auto">Total Cash <span class="float-right">Rp</span></label>
                        <div class="col-md-8 col-12">
                            <input type="text" name="total_cash" id="total_cash" class="form-control text-right" value="0" readonly>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="total_card" class="control-label col-md-4 col-12 my-auto">Total Card <span class="float-right">Rp</span></label>
                        <div class="col-md-8 col-12">
                            <input type="text" name="total_card" id="total_card" class="form-control text-right" value="0" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger" onclick="getUrl('Master/user')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
            <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
            <?php if (!empty($data_user)) : ?>
                <button type="button" class="btn btn-info float-right" onclick="getUrl('Master/form_user/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            <?php else : ?>
                <button type="button" class="btn btn-info float-right" onclick="reset()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
            <?php endif ?>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        initailizeSelect2_akun_sel('K0021');
    });

    const body = $('#bodyDetail');

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
            num = num.substring(0, num.length - (4 * i + 3)) + ',' +
                num.substring(num.length - (4 * i + 3));
        }

        return (((sign) ? '' : '-') + '' + num);
    }

    function tambahDetail() {
        var jum = Number($('#jumDetail').val());
        var row = jum + 1;

        $('#jumDetail').val(row);

        body.append(`<tr id="rowDetail${row}">
            <td>
                <button type="button" class="btn btn-danger" onclick="hapusBaris('${row}')">
                    <i class="fa-solid fa-delete-left"></i>
                </button>
            </td>
            <td>
                <select name="kode_akun[]" id="kode_akun${row}" class="form-control select2_akun_sel" data-placeholder="~ Pilih Akun"></select>
            </td>
            <td>
                <input type="text" name="cash[]" id="cash${row}" class="form-control text-right" value="0" onkeyup="hitung_t(); formatRpNoId(this.value)">
            </td>
            <td>
                <input type="text" name="card[]" id="card${row}" class="form-control text-right" value="0" onkeyup="hitung_t(); formatRpNoId(this.value)">
            </td>
        </tr>`);

        initailizeSelect2_akun_sel('K0021');
    }

    function hapusBaris(i) {
        var jum = Number($('#jumDetail').val());
        var row = jum - 1;

        $('#jumDetail').val(row);

        $('#rowDetail' + i).remove();

        hitung_t();
    }

    function hitung_t() {
        var tableDetail = document.getElementById('tableDetail'); // ambil id table detail
        var rowCount = tableDetail.rows.length; // hitung jumlah rownya

        // buat variable untuk di sum
        var tcash = 0;
        var tcard = 0;

        // lakukan loop
        for (var i = 1; i < rowCount; i++) {
            var row = tableDetail.rows[i];

            // Ambil data berdasarkan loop dengan safety checks
            var cash1 = Number((row.cells[2].children[0].value).replace(/[^0-9\.]+/g, ""));
            var card1 = Number((row.cells[3].children[0].value).replace(/[^0-9\.]+/g, ""));

            // Lakukan rumus sum
            tcash += cash1;
            tcard += card1;

            $('#cash' + i).val(formatRpNoId(cash1));
            $('#card' + i).val(formatRpNoId(card1));
        }

        // Tampilkan hasil ke dalam format koma
        $('#total_cash').val(formatRpNoId(tcash));
        $('#total_card').val(formatRpNoId(tcard));
    }
</script>