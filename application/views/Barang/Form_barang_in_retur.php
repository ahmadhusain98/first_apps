<form method="post" id="form_barang_in">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="h4 font-weight-bold"># Form Retur Pembelian</div>
                </div>
                <div class="card-body">
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="h4"># Form</div>
                        </div>
                        <div class="card-body">
                            <label for="">Pembelian <sup class="text-danger">**</sup></label>
                            <div class="row">
                                <div class="col-md-1 col-1">
                                    <div class="input-group mb-3">
                                        <input type="checkbox" name="cek_beli" id="cek_beli" class="form-control" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Menggunakan Pembelian" onclick="cekBeli()" <?= (!empty($data_barang_in_retur) ? (($data_barang_in_retur->invoice_in == '' || $data_barang_in_retur->invoice_in == null) ? '' : 'checked') : '') ?>>
                                    </div>
                                </div>
                                <div class="col-md-11 col-11">
                                    <div class="input-group mb-3">
                                        <select name="invoice_in" id="invoice_in" class="form-control select2_global" data-placeholder="~ Pilih Invoice Pembelian" onchange="getBarangIn(this.value)">
                                            <option value="">~ Pilih Invoice Pembelian</option>
                                            <?php foreach ($pembelian as $p) : ?>
                                                <option value="<?= $p->invoice ?>" <?= ((!empty($data_barang_in_retur) ? (($p->invoice == $data_barang_in_retur->invoice_in) ? 'selected' : '') : '')) ?>><?= $p->invoice . ' ~ Pemasok: ' . $this->M_global->getData('m_supplier', ['kode_supplier' => $p->kode_supplier])->nama . ' | Gudang: ' . $this->M_global->getData('m_gudang', ['kode_gudang' => $p->kode_gudang])->nama . ' | Tanggal: ' . date('d/m/Y', strtotime($p->tgl_beli)) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Invoice <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Invoice (Otomatis)" id="invoice" name="invoice" value="<?= (!empty($data_barang_in_retur) ? $data_barang_in_retur->invoice : '') ?>" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="id-card-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Tgl/Jam Retur <sup class="text-danger">**</sup></label>
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <div class="input-group mb-3">
                                                <input type="date" title="Tgl Beli" class="form-control" placeholder="Tgl Beli" id="tgl_beli" name="tgl_beli" value="<?= (!empty($data_barang_in_retur) ? date('Y-m-d', strtotime($data_barang_in_retur->tgl_beli)) : date('Y-m-d')) ?>" readonly>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <ion-icon name="today-outline"></ion-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <div class="input-group mb-3">
                                                <input type="time" title="Jam Beli" class="form-control" placeholder="Jam Beli" id="jam_beli" name="jam_beli" value="<?= (!empty($data_barang_in_retur) ? date('H:i:s', strtotime($data_barang_in_retur->jam_beli)) : date('H:i:s')) ?>" readonly>
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
                                    <label for="">Pemasok <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <select name="kode_supplier" id="kode_supplier" class="form-control select2_supplier" data-placeholder="~ Pilih Pemasok">
                                            <?php
                                            if (!empty($data_barang_in_retur)) :
                                                $supplier = $this->M_global->getData('m_supplier', ['kode_supplier' => $data_barang_in_retur->kode_supplier])->nama;
                                                echo '<option value="' . $data_barang_in_retur->kode_supplier . '">' . $data_barang_in_retur->kode_supplier . ' ~ ' . $supplier . '</option>';
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Gudang <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <select name="kode_gudang" id="kode_gudang" class="form-control select2_gudang_int" data-placeholder="~ Pilih Gudang">
                                            <?php
                                            if (!empty($data_barang_in_retur)) :
                                                $gudang = $this->M_global->getData('m_gudang', ['kode_gudang' => $data_barang_in_retur->kode_gudang])->nama;
                                                echo '<option value="' . $data_barang_in_retur->kode_gudang . '">' . $data_barang_in_retur->kode_gudang . ' ~ ' . $gudang . '</option>';
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Surat Jalan <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Surat Jalan" id="surat_jalan" name="surat_jalan" value="<?= (!empty($data_barang_in_retur) ? $data_barang_in_retur->surat_jalan : '') ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="barcode-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">No. Faktur <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="No. Faktur" id="no_faktur" name="no_faktur" value="<?= (!empty($data_barang_in_retur) ? $data_barang_in_retur->no_faktur : '') ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="pricetag-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Alasan <sup class="text-danger">**</sup></label>
                                    <div class="input-group mb-3">
                                        <textarea name="alasan" id="alasan" class="form-control"><?= (!empty($data_barang_in_retur) ? $data_barang_in_retur->alasan : '') ?></textarea>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <ion-icon name="barcode-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="h4"># Detail Barang</div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <input type="hidden" name="jumlahBarisBarang" id="jumlahBarisBarang" value="<?= (!empty($barang_detail) ? count($barang_detail) : '0') ?>">
                                        <table class="table table-striped table-hover table-bordered" id="tableDetailBarangIn">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="5%">Hapus</th>
                                                    <th rowspan="2">Barang</th>
                                                    <th width="14%">Harga</th>
                                                    <th width="14%">Qty</th>
                                                    <th width="14%">Disc (%)</th>
                                                    <th width="14%">Disc (Rp)</th>
                                                    <th width="5%">Pajak</th>
                                                    <th width="14%">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyBarangIn">
                                                <?php if (!empty($barang_detail)) : ?>
                                                    <?php $no = 1;
                                                    foreach ($barang_detail as $bd) : ?>
                                                        <tr id="rowBarangIn<?= $no ?>">
                                                            <td class="text-center"><button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Hapus" type="button" id="btnHapus<?= $no ?>" onclick="hapusBarang('<?= $no ?>')"><ion-icon name="ban-outline"></ion-icon></button></td>
                                                            <td>
                                                                <input type="hidden" id="kode_barang_in<?= $no ?>" name="kode_barang_in[]" value="<?= $bd->kode_barang ?>">
                                                                <span><?= $bd->kode_barang ?> ~ <?= $this->M_global->getData('barang', ['kode_barang' => $bd->kode_barang])->nama ?></span>
                                                            </td>
                                                            <td>
                                                                <input type="text" id="harga_in<?= $no ?>" name="harga_in[]" value="<?= number_format($bd->harga) ?>" class="form-control text-right" onchange="hitung_st('<?= $no ?>'); formatRp(this.value, 'harga_in<?= $no ?>'); cekHarga(this.value, <?= $no ?>)" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" id="qty_in<?= $no ?>" name="qty_in[]" value="<?= number_format($bd->qty) ?>" class="form-control text-right" onchange="hitung_qty('<?= $no ?>'); formatRp(this.value, 'qty_in<?= $no ?>')">
                                                            </td>
                                                            <td>
                                                                <input type="text" id="discpr_in<?= $no ?>" name="discpr_in[]" value="<?= number_format($bd->discpr) ?>" class="form-control text-right" onchange="hitung_dpr(<?= $no ?>); formatRp(this.value, 'discpr_in<?= $no ?>')">
                                                            </td>
                                                            <td>
                                                                <input type="text" id="discrp_in<?= $no ?>" name="discrp_in[]" value="<?= number_format($bd->discrp) ?>" class="form-control text-right" onchange="hitung_drp(<?= $no ?>); formatRp(this.value, 'discrp_in<?= $no ?>')">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" id="pajak_in<?= $no ?>" name="pajak_in[]" class="form-control" onclick="hitung_st('<?= $no ?>')" <?= (((int)$bd->pajak > 0) ? 'checked' : '') ?>>
                                                                <input type="hidden" id="pajakrp_in<?= $no ?>" name="pajakrp_in[]" value="<?= number_format($bd->pajakrp) ?>">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="hidden" id="jumlah_in<?= $no ?>" name="jumlah_in[]" value="<?= number_format($bd->jumlah) ?>" class="form-control text-right" readonly>
                                                                <span id="jumlah2_in<?= $no ?>"><?= number_format($bd->jumlah) ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php $no++;
                                                    endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-7 col-12">
                                    <div class="row" id="forInvoiceIn">
                                        <div class="col-md-8 col-6">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Masukan Kode/Nama Barang" id="kode_barang" name="kode_barang">
                                                <div class="input-group-append" onclick="showBarang()">
                                                    <div class="input-group-text">
                                                        <ion-icon name="search-outline"></ion-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <button type="button" class="btn btn-sm btn-secondary float-right" onclick="searchBarang()" id="btnCari"><ion-icon name="add-circle-outline"></ion-icon> Tambah Barang</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12">
                                    <div class="card">
                                        <div class="card-footer">
                                            <div class="row mb-1">
                                                <label for="subtotal" class="control-label col-md-4 col-12 my-auto">Subtotal <span class="float-right">Rp</span></label>
                                                <div class="col-md-8 col-12">
                                                    <input type="text" name="subtotal" id="subtotal" class="form-control text-right" value="<?= ((!empty($data_barang_in_retur)) ? number_format($data_barang_in_retur->subtotal) : '0') ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="diskon" class="control-label col-md-4 col-12 my-auto">Diskon <span class="float-right">Rp</span></label>
                                                <div class="col-md-8 col-12">
                                                    <input type="text" name="diskon" id="diskon" class="form-control text-right" value="<?= ((!empty($data_barang_in_retur)) ? number_format($data_barang_in_retur->diskon) : '0') ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="pajak" class="control-label col-md-4 col-12 my-auto">Pajak <span class="float-right">Rp</span></label>
                                                <div class="col-md-8 col-12">
                                                    <input type="text" name="pajak" id="pajak" class="form-control text-right" value="<?= ((!empty($data_barang_in_retur)) ? number_format($data_barang_in_retur->pajak) : '0') ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="total" class="control-label col-md-4 col-12 my-auto">Total <span class="float-right">Rp</span></label>
                                                <div class="col-md-8 col-12">
                                                    <input type="text" name="total" id="total" class="form-control text-right" value="<?= ((!empty($data_barang_in_retur)) ? number_format($data_barang_in_retur->total) : '0') ?>" readonly>
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
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm" onclick="getUrl('Transaksi/barang_in_retur')" id="btnKembali"><ion-icon name="play-back-outline"></ion-icon> Kembali</button>
                            <button type="button" class="btn btn-dark float-right btn-sm ml-2" onclick="save()" id="btnSimpan"><ion-icon name="save-outline"></ion-icon> <?= (!empty($data_barang_in_retur) ? 'Perbarui' : 'Simpan') ?></button>
                            <?php if (!empty($data_barang_in_retur)) : ?>
                                <button type="button" class="btn btn-success float-right btn-sm" onclick="getUrl('Transaksi/form_barang_in_retur/0')" id="btnBaru"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
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

<!-- modal semua barang -->
<div class="modal fade" id="modal_barang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"># List Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="tutupModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div style="height: 400px; overflow: auto;">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableSederhanaObat" style="width: 100%;">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="90%">Obat</th>
                                            <th width="5%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $nolb = 1;
                                        foreach ($list_barang as $lb) : ?>
                                            <tr>
                                                <td width="5%"><?= $nolb ?></td>
                                                <td width="90%">
                                                    <?= $lb->kode_barang . ' ~ ' . $lb->nama . ' ~ Satuan: ' . $this->M_global->getData('m_satuan', ['kode_satuan' => $lb->kode_satuan])->keterangan . ' ~ Kategori: ' . $this->M_global->getData('m_kategori', ['kode_kategori' => $lb->kode_kategori])->keterangan . ' ~ HNA: Rp. ' . number_format($lb->hna) ?>
                                                    <input type="hidden" name="selobat[]" id="selobat<?= $nolb ?>" value="<?= $lb->kode_barang ?>">
                                                </td>
                                                <td width="5%" class="text-center">
                                                    <input type="hidden" class="form-control" name="select_barang[]" id="select_barang<?= $nolb ?>" value="0">
                                                    <input type="checkbox" class="form-control" name="select_barangx[]" id="select_barangx<?= $nolb ?>" onclick="selbar('<?= $nolb ?>')">
                                                    <!-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Pilih" onclick="selectBarang('<?= $lb->kode_barang ?>')"><ion-icon name="checkmark-circle-outline"></ion-icon></button> -->
                                                </td>
                                            </tr>
                                        <?php $nolb++;
                                        endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right" onclick="selbarfunc()"><ion-icon name="file-tray-full-outline"></ion-icon> Pilih Obat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var kode_barang = $('#kode_barang');
    const form = $('#form_barang_in');
    const btnCari = $('#btnCari');
    const btnSimpan = $('#btnSimpan');

    // header
    var invoice = $('#invoice');
    var tgl_beli = $('#tgl_beli');
    var jam_beli = $('#jam_beli');
    var kode_supplier = $('#kode_supplier');
    var kode_gudang = $('#kode_gudang');
    var surat_jalan = $('#surat_jalan');
    var no_faktur = $('#no_faktur');
    var alasan = $('#alasan');

    // detail
    var tableBarangIn = $('#tableDetailBarangIn');
    var bodyBarangIn = $('#bodyBarangIn');
    var rowBarangIn = $('#rowBarangIn');
    var jumlahBarisBarang = $('#jumlahBarisBarang');

    $('#tableSederhanaObat').DataTable({
        "destroy": true,
        "processing": true,
        "responsive": true,
        "serverSide": false,
        "scrollCollapse": false,
        "paging": false,
        "oLanguage": {
            "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
            "sInfoEmpty": "",
            "sInfoFiltered": "",
            "sSearch": "",
            "sSearchPlaceholder": "Cari data...",
            "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
            "sLengthMenu": "_MENU_ Baris",
            "sZeroRecords": "<div class='text-center'>Data Kosong</div>",
            "oPaginate": {
                "sPrevious": "Sebelumnya",
                "sNext": "Berikutnya"
            }
        },
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "Semua"]
        ],
        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],
    });

    // onload
    if (invoice.val() == '' || invoice.val() == null) {
        btnSimpan.attr('disabled', true);
        $('#invoice_in').attr('disabled', true);
    } else {
        hitung_t();

        if (document.getElementById('cek_beli').checked == true) {
            $('#invoice_in').attr('disabled', false);

            $('#forInvoiceIn').hide();
        } else {
            $('#invoice_in').attr('disabled', true);

            $('#forInvoiceIn').show();
        }
    }

    // fungsi tampil modal list barang
    function showBarang() {
        $('#modal_barang').modal('show');
    }

    // fungsi tutup modal list barang
    function tutupModal() {
        $('#modal_barang').modal('hide');
    }

    // fungsi pencarian by input dan enter
    kode_barang.keypress(function(e) {
        if (e.which == 13) { // jika di enter
            // jalankan fungsi
            return searchBarang();
        }
    });

    // fungsi select barang on check
    function selbar(x) {
        if (document.getElementById('select_barangx' + x).checked == true) {
            $('#select_barang' + x).val(1);
        } else {
            $('#select_barang' + x).val(0);
        }
    }

    // tampilkan fungsi select barang
    function selbarfunc() {
        var tableBarang = $('#tableSederhanaObat').dataTable(); // ambil id table detail
        var rowCount = tableBarang.fnGetData().length; // hitung jumlah rownya
        var tableBarangIn = document.getElementById('tableDetailBarangIn'); // ambil id table detail
        var no = tableBarangIn.rows.length; // hitung jumlah rownya

        // var no = 0;
        // lakukan loop
        for (var i = 1; i <= rowCount; i++) {
            if ($('#select_barang' + i).val() == 1) {
                $('#select_barang' + i).val(0);
                document.getElementById('select_barangx' + i).checked = false;
                var obat = $('#selobat' + i).val();
                $('#modal_barang').modal('hide');
                tampilList2(obat, i);
                no += 1;
                jumlahBarisBarang.val(no);
            }
        }
    }

    // fungsi tampilList2
    function tampilList2(brg, i) {
        // jalankan fungsi
        $.ajax({
            url: siteUrl + 'Transaksi/getBarang/' + brg,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) { // jika fungsi berjalan
                // reset inputan pencarian barang
                kode_barang.val('');

                if (result.status == 0) { // jika mendapatkan status 0
                    // munculkan notifikasi
                    return Swal.fire("Barang", "Tidak ditemukan!", "info");
                } else { // selain itu
                    // tambahkan jumlah row
                    var x = i;

                    // masukan ke body table barang in detail
                    bodyBarangIn.append(`<tr id="rowBarangIn${x}">
                        <td class="text-center"><button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Hapus" type="button" id="btnHapus${x}" onclick="hapusBarang('${x}')"><ion-icon name="ban-outline"></ion-icon></button></td>
                        <td>
                            <input type="hidden" id="kode_barang_in${x}" name="kode_barang_in[]" value="${result.kode_barang}">
                            <span>${result.kode_barang} ~ ${result.nama}</span>
                        </td>
                        <td>
                            <input type="text" id="harga_in${x}" name="harga_in[]" value="${formatRpNoId(result.nilai_persediaan)}" class="form-control text-right" onchange="hitung_st('${x}'); formatRp(this.value, 'harga_in${x}'); cekHarga(this.value, ${x})" readonly>
                        </td>
                        <td>
                            <input type="text" id="qty_in${x}" name="qty_in[]" value="1" class="form-control text-right" onchange="hitung_qty('${x}'); formatRp(this.value, 'qty_in${x}')">
                        </td>
                        <td>
                            <input type="text" id="discpr_in${x}" name="discpr_in[]" value="0" class="form-control text-right" onchange="hitung_dpr(${x}); formatRp(this.value, 'discpr_in${x}')">
                        </td>
                        <td>
                            <input type="text" id="discrp_in${x}" name="discrp_in[]" value="0" class="form-control text-right" onchange="hitung_drp(${x}); formatRp(this.value, 'discrp_in${x}')">
                        </td>
                        <td class="text-center">
                            <input type="checkbox" id="pajak_in${x}" name="pajak_in[]" class="form-control" onclick="hitung_st('${x}')">
                            <input type="hidden" id="pajakrp_in${x}" name="pajakrp_in[]" value="0">
                        </td>
                        <td class="text-right">
                            <input type="hidden" id="jumlah_in${x}" name="jumlah_in[]" value="${formatRpNoId(result.nilai_persediaan)}" class="form-control text-right" readonly>
                            <span id="jumlah2_in${x}">${formatRpNoId(result.nilai_persediaan)}</span>
                        </td>
                    </tr>`);

                    // jalankan fungsi
                    hitung_st(x);
                }
            },
            error: function(result) { // jika fungsi error

                // jalankan notifikasi error
                error_proccess();
            }
        });
    }

    // fungsi pilih barang dari modal
    function selectBarang(x) {
        // ambil angka row terakhir
        var jum = Number(jumlahBarisBarang.val());

        if (x == '' || x == null) { // jika x kosong/ null
        } else { // selain itu

            // jalankan fungsi
            $('#modal_barang').modal('hide');
            tampilList(x, jum);
        }
    }

    // fungsi pencarian barang
    function searchBarang() {
        // ambil angka row terakhir
        var jum = Number(jumlahBarisBarang.val());

        if (kode_barang.val() == '' || kode_barang.val() == null) { // jika kode_barang kosong/ null
        } else { // selain itu

            // jalankan fungsi
            tampilList(kode_barang.val(), jum);
        }
    }

    // fungsi tampilList
    function tampilList(brg, jum) {

        // jalankan fungsi
        $.ajax({
            url: siteUrl + 'Transaksi/getBarang/' + brg,
            type: 'POST',
            dataType: 'JSON',
            success: function(result) { // jika fungsi berjalan
                // reset inputan pencarian barang
                kode_barang.val('');

                if (result.status == 0) { // jika mendapatkan status 0
                    // munculkan notifikasi
                    return Swal.fire("Barang", "Tidak ditemukan!", "info");
                } else { // selain itu
                    // tambahkan jumlah row
                    var x = jum + 1;
                    jumlahBarisBarang.val(x);

                    // masukan ke body table barang in detail
                    bodyBarangIn.append(`<tr id="rowBarangIn${x}">
                            <td class="text-center"><button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Hapus" type="button" id="btnHapus${x}" onclick="hapusBarang('${x}')"><ion-icon name="ban-outline"></ion-icon></button></td>
                            <td>
                                <input type="hidden" id="kode_barang_in${x}" name="kode_barang_in[]" value="${result.kode_barang}">
                                <span>${result.kode_barang} ~ ${result.nama}</span>
                            </td>
                            <td>
                                <input type="text" id="harga_in${x}" name="harga_in[]" value="${formatRpNoId(result.nilai_persediaan)}" class="form-control text-right" onchange="hitung_st('${x}'); formatRp(this.value, 'harga_in${x}'); cekHarga(this.value, ${x})" readonly>
                            </td>
                            <td>
                                <input type="text" id="qty_in${x}" name="qty_in[]" value="1" class="form-control text-right" onchange="hitung_qty('${x}'); formatRp(this.value, 'qty_in${x}')">
                            </td>
                            <td>
                                <input type="text" id="discpr_in${x}" name="discpr_in[]" value="0" class="form-control text-right" onchange="hitung_dpr(${x}); formatRp(this.value, 'discpr_in${x}')">
                            </td>
                            <td>
                                <input type="text" id="discrp_in${x}" name="discrp_in[]" value="0" class="form-control text-right" onchange="hitung_drp(${x}); formatRp(this.value, 'discrp_in${x}')">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" id="pajak_in${x}" name="pajak_in[]" class="form-control" onclick="hitung_st('${x}')">
                                <input type="hidden" id="pajakrp_in${x}" name="pajakrp_in[]" value="0">
                            </td>
                            <td class="text-right">
                                <input type="hidden" id="jumlah_in${x}" name="jumlah_in[]" value="${formatRpNoId(result.nilai_persediaan)}" class="form-control text-right" readonly>
                                <span id="jumlah2_in${x}">${formatRpNoId(result.nilai_persediaan)}</span>
                            </td>
                        </tr>`);

                    // jalankan fungsi
                    hitung_st(x);
                }
            },
            error: function(result) { // jika fungsi error

                // jalankan notifikasi error
                error_proccess();
            }
        });
    }

    // fungsi hapus baris barang detail
    function hapusBarang(x) {
        // hapus baris barang detail dengan id tr table
        $('#rowBarangIn' + x).remove();
        // jalankan fungsi
        hitung_t();

    }

    // perhitungan diskon % row
    function hitung_dpr(x) {
        var harga = ($('#harga_in' + x).val()).replaceAll(',', '');
        var qty = ($('#qty_in' + x).val()).replaceAll(',', '');
        var discpr = ($('#discpr_in' + x).val()).replaceAll(',', '');

        if (Number(discpr) > 100) { // jika disc pr > 100
            // munculkan notifikasi
            Swal.fire("Diskon (%)", "Maksimal 100%!", "info");

            // identifikasi x = 100
            var a = 100;
        } else { // selain itu
            // identifikasi x = discpr
            var a = discpr;
        }

        // buat rumus diskon rp
        var discrp = (harga * qty) * (a / 100);

        // tampilkan hasil ke dalam format koma
        $('#discpr_in' + x).val(formatRpNoId(a));
        $('#discrp_in' + x).val(formatRpNoId(discrp));

        // jalankan fungsi
        hitung_st(x);
    }

    // perhitungan diskon rp row
    function hitung_drp(x) {
        var harga = ($('#harga_in' + x).val()).replaceAll(',', '');
        var qty = ($('#qty_in' + x).val()).replaceAll(',', '');
        var discrp = ($('#discrp_in' + x).val()).replaceAll(',', '');

        // buat rumus jumlah
        var st_awal = (harga * qty) - discrp;

        // tampilkan hasil ke dalam format koma
        $('#discrp_in' + x).val(formatRpNoId(discrp));
        $('#discpr_in' + x).val('0');
        $('#jumlah_in' + x).val(formatRpNoId(st_awal));
        $('#jumlah2_in' + x).text(formatRpNoId(st_awal));

        // jalankan fungsi
        hitung_st(x);
    }

    // fungsi ubah qty row
    function hitung_qty(x) {
        if (Number($('#discpr_in' + x).val().replaceAll(',', '')) > 0) {
            hitung_dpr(x);
        } else {
            hitung_drp(x);
        }
    }

    // perhitungan row
    function hitung_st(x) {
        var harga = ($('#harga_in' + x).val()).replaceAll(',', '');
        var qty = ($('#qty_in' + x).val()).replaceAll(',', '');
        var discrp = ($('#discrp_in' + x).val()).replaceAll(',', '');

        // buat rumus jumlah
        var st_awal = (harga * qty) - discrp;

        if (document.getElementById('pajak_in' + x).checked == true) { // jika pajak checked true
            // buat rumus pajak
            var pajakrp = formatRpNoId(st_awal * (Number(<?= $pajak ?>) / 100));
        } else { // selain itu
            // pajak dibuat 0
            var pajakrp = '0';
        }

        // tampilkan hasil ke dalam format koma
        $('#pajakrp_in' + x).val(pajakrp);
        $('#jumlah_in' + x).val(formatRpNoId(st_awal));
        $('#jumlah2_in' + x).text(formatRpNoId(st_awal));

        // jalankan rumus
        hitung_t();
    }

    // perhitungan total;
    function hitung_t() {
        var tableBarang = document.getElementById('tableDetailBarangIn'); // ambil id table detail
        var rowCount = tableBarang.rows.length; // hitung jumlah rownya

        // buat variable untuk di sum
        var tjumlah = 0;
        var tdiskon = 0;
        var tppn = 0;

        // lakukan loop
        for (var i = 1; i < rowCount; i++) {
            var row = tableBarang.rows[i];

            // ambil data berdasarkan loop
            var harga1 = Number((row.cells[2].children[0].value).replace(/[^0-9\.]+/g, ""));
            var qty1 = Number((row.cells[3].children[0].value).replace(/[^0-9\.]+/g, ""));
            var discrp1 = Number((row.cells[5].children[0].value).replace(/[^0-9\.]+/g, ""));
            var pajak1 = Number((row.cells[6].children[1].value).replace(/[^0-9\.]+/g, ""));
            var jumlah1 = Number((row.cells[7].children[0].value).replace(/[^0-9\.]+/g, ""));

            // lakukan rumus sum
            tjumlah += jumlah1 + discrp1;
            tdiskon += discrp1;
            tppn += pajak1;
        }

        // buat rumus total
        var ttotal = tjumlah + tppn;

        // tampilkan hasil ke dalam format koma
        $('#subtotal').val(formatRpNoId(tjumlah));
        $('#diskon').val(formatRpNoId(tdiskon));
        $('#pajak').val(formatRpNoId(tppn));
        $('#total').val(formatRpNoId(ttotal));

        // jalankan fungsi
        cekButtonSave();
    }

    // fungsi cek tombol simpan
    function cekButtonSave() {
        if (($('#total').val()).replaceAll(',', '') < 1 || $('#total').val() == '0') {
            btnSimpan.attr('disabled', true);
        } else {
            btnSimpan.attr('disabled', false);
        }
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
            num = num.substring(0, num.length - (4 * i + 3)) + ',' +
                num.substring(num.length - (4 * i + 3));
        }

        return (((sign) ? '' : '-') + '' + num);
    }

    // fungsi cek ubah harga
    function cekHarga(num, x) {
        // munculkan notifikasi
        Swal.fire("Harga Barang", "Akan diubah?, harga master akan mangikuti harga terakhir!", "question");

        // format harga
        $('#harga_in' + x).val(formatRpNoId(num));

        // ambil discpr untuk pengecekan
        var discpr = Number(($('#discpr_in' + x).val()).replaceAll(',', ''));
        if (discpr > 0) { // jika discpr lebih dari 0
            // jalankan fungsi
            hitung_dpr(x);
        } else { // selain itu
            // jalankan fungsi
            hitung_drp(x);
        }
    }

    // fungsi simpan
    function save() {
        btnSimpan.attr('disabled', true);

        var tableBarang = document.getElementById('tableDetailBarangIn'); // ambil id table detail
        var rowCount = tableBarang.rows.length; // hitung jumlah rownya

        if (rowCount < 1) { // jika jumlah baris detail kurang dari 1
            btnSimpan.attr('disabled', false);

            return Swal.fire("Detail Barang Pembelian", "Form sudah diisi?", "question");
        }

        if (tgl_beli.val() == '' || tgl_beli.val() == null) { // jika tgl_beli null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Tgl Beli", "Form sudah diisi?", "question");
        }

        if (jam_beli.val() == '' || jam_beli.val() == null) { // jika jam_beli null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Jam Beli", "Form sudah diisi?", "question");
        }

        if (kode_supplier.val() == '' || kode_supplier.val() == null) { // jika kode_supplier null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Pemasok", "Form sudah dipilih?", "question");
        }

        if (kode_gudang.val() == '' || kode_gudang.val() == null) { // jika kode_gudang null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Gudang", "Form sudah dipilih?", "question");
        }

        if (surat_jalan.val() == '' || surat_jalan.val() == null) { // jika surat_jalan null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Surat Jalan", "Form sudah diisi?", "question");
        }

        if (alasan.val() == '' || alasan.val() == null) { // jika alasan null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("Alasan", "Form sudah diisi?", "question");
        }

        if (no_faktur.val() == '' || no_faktur.val() == null) { // jika no_faktur null/ kosong
            btnSimpan.attr('disabled', false);

            return Swal.fire("No. Faktur", "Form sudah diisi?", "question");
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

        if (param == 1) { // jika param 1 berarti insert/tambah
            var message = 'dibuat!';
        } else { // selain itu berarti update/ubah
            var message = 'diperbarui!';
        }

        // jalankan proses dengan param insert/update
        $.ajax({
            url: siteUrl + 'Transaksi/barang_in_retur_proses/' + param,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function(result) { // jika fungsi berjalan dengan baik
                btnSimpan.attr('disabled', false);

                if (result.status == 1) { // jika mendapatkan respon 1

                    Swal.fire("Retur Pembelian", "Berhasil " + message, "success").then(() => {
                        getUrl('Transaksi/barang_in_retur');
                    });
                } else { // selain itu

                    Swal.fire("Retur Pembelian", "Gagal " + message + ", silahkan dicoba kembali", "info");
                }
            },
            error: function(result) { // jika fungsi error
                btnSimpan.attr('disabled', false);

                error_proccess();
            }
        });
    }

    // fungsi cek beli
    function cekBeli() {
        bodyBarangIn.empty();

        $('#invoice_in').val('').change();
        $('#kode_supplier').html(`<option value="">~ Pilih Pemasok</option>`);
        $('#kode_gudang').html(`<option value="">~ Pilih Gudang</option>`);
        surat_jalan.val('');
        no_faktur.val('');

        jumlahBarisBarang.val(0);

        hitung_t();

        if (document.getElementById('cek_beli').checked == true) {
            $('#invoice_in').attr('disabled', false);

            $('#forInvoiceIn').hide();
        } else {
            $('#invoice_in').attr('disabled', true);

            $('#forInvoiceIn').show();
        }
    }

    // fungsi ambil pembelian
    function getBarangIn(invoice_in) {
        bodyBarangIn.empty();
        if (invoice_in == '' || invoice_in == null) {
            kode_supplier.append(`<option value="">~ Pilih Pemasok</option>`);
            kode_gudang.append(`<option value="">~ Pilih Gudang</option>`);
            surat_jalan.val('');
            no_faktur.val('');
        } else {

            // jalankan fungsi
            $.ajax({
                url: siteUrl + 'Transaksi/getBarangIn/' + invoice_in,
                type: 'POST',
                dataType: 'JSON',
                success: function(result) {
                    if (result[0].status == 1) {

                        // isi header
                        kode_supplier.html(`<option value="${result[1].kode_supplier}">${result[1].kode_supplier} ~ ${result[1].nama_supplier}</option>`);
                        kode_gudang.html(`<option value="${result[1].kode_gudang}">${result[1].kode_gudang} ~ ${result[1].nama_gudang}</option>`);
                        surat_jalan.val(result[1].surat_jalan);
                        no_faktur.val(result[1].no_faktur);

                        // Detail
                        // tambahkan jumlah row
                        var x = 1;
                        var jum = Number(jumlahBarisBarang.val()) + Number(result[2].length);
                        jumlahBarisBarang.val(jum);

                        $.each(result[2], function(index, value) {
                            if (value.pajak > 0) {
                                var cekpajak = 'checked';
                            } else {
                                var cekpajak = '';
                            }

                            bodyBarangIn.append(`<tr id="rowBarangIn${x}">
                                <td class="text-center"><button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom" title="Hapus" type="button" id="btnHapus${x}" onclick="hapusBarang('${x}')"><ion-icon name="ban-outline"></ion-icon></button></td>
                                <td>
                                    <input type="hidden" id="kode_barang_in${x}" name="kode_barang_in[]" value="${value.kode_barang}">
                                    <span>${value.kode_barang} ~ ${value.nama_barang}</span>
                                </td>
                                <td>
                                    <input type="text" id="harga_in${x}" name="harga_in[]" value="${formatRpNoId(value.harga)}" class="form-control text-right" onchange="hitung_st('${x}'); formatRp(this.value, 'harga_in${x}'); cekHarga(this.value, ${x})" readonly>
                                </td>
                                <td>
                                    <input type="text" id="qty_in${x}" name="qty_in[]" value="${formatRpNoId(value.qty)}" class="form-control text-right" onchange="hitung_qty('${x}'); formatRp(this.value, 'qty_in${x}')">
                                </td>
                                <td>
                                    <input type="text" id="discpr_in${x}" name="discpr_in[]" value="${formatRpNoId(value.discpr)}" class="form-control text-right" onchange="hitung_dpr(${x}); formatRp(this.value, 'discpr_in${x}')">
                                </td>
                                <td>
                                    <input type="text" id="discrp_in${x}" name="discrp_in[]" value="${formatRpNoId(value.discrp)}" class="form-control text-right" onchange="hitung_drp(${x}); formatRp(this.value, 'discrp_in${x}')">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" id="pajak_in${x}" name="pajak_in[]" class="form-control" onclick="hitung_st('${x}')" ${cekpajak}>
                                    <input type="hidden" id="pajakrp_in${x}" name="pajakrp_in[]" value="${formatRpNoId(value.pajakrp)}">
                                </td>
                                <td class="text-right">
                                    <input type="hidden" id="jumlah_in${x}" name="jumlah_in[]" value="${formatRpNoId(value.jumlah)}" class="form-control text-right" readonly>
                                    <span id="jumlah2_in${x}">${formatRpNoId(value.jumlah)}</span>
                                </td>
                            </tr>`);

                            x++;
                        });

                        hitung_t();
                    }
                },
                error: function(result) {
                    error_proccess();
                }
            });
        }
    }

    // fungsi reset
    $('#btnReset').click('on', () => {
        bodyBarangIn.empty();

        $('#invoice_in').val('').change();
        $('#kode_supplier').html(`<option value="">~ Pilih Pemasok</option>`);
        $('#kode_gudang').html(`<option value="">~ Pilih Gudang</option>`);
        surat_jalan.val('');
        no_faktur.val('');

        hitung_t();
    })
</script>