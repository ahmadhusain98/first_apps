<form method="post" id="form_tarif">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="id" class="control-label">Kode Tarif</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="kodeTarif" name="kodeTarif" placeholder="Otomatis" value="<?= (!empty($tarif) ? $tarif->kode_tarif : '') ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nama">Nama Tarif <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" onkeyup="ubah_nama(this.value, 'nama')" value="<?= (!empty($tarif) ? $tarif->nama : '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="id" class="control-label">Kategori Tarif <sup class="text-danger">**</sup></label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <select name="kategori" id="kategori" class="form-control select2_kategori_tarif" data-placeholder="~ Pilih Kategori">
                                            <?php if (!empty($tarif)) :
                                                $kategori = $this->M_global->getData('kategori_tarif', ['kode_kategori' => $tarif->kategori]); ?>
                                                <option value="<?= $tarif->kategori; ?>"><?= $kategori->keterangan ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="width: 100%;">
                                            <i class="fa-solid fa-circle-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nama">Jenis Tarif</label>
                                <input type="hidden" class="form-control" id="jenis" name="jenis" placeholder="Jenis Tarif" value="1">
                                <input type="text" class="form-control" id="jenisx" name="jenisx" placeholder="Jenis Tarif" value="Paket" readonly>
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
            <div class="form-group">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tableDetail" width="100%" style="border-radius: 10px;">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" style="width: 5%;">Hapus</th>
                                        <th rowspan="2" style="width: 25%;">Cabang</th>
                                        <th rowspan="2" style="width: 10%;">Paket Kunjungan</th>
                                        <th colspan="4" style="width: 60%;">Jasa</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th style="width: 15%;">RS</th>
                                        <th style="width: 15%;">Dokter</th>
                                        <th style="width: 15%;">Pelayanan</th>
                                        <th style="width: 15%;">Poli</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyDetail">
                                    <?php if (!empty($paket_jasa)) : ?>
                                        <?php $no = 1;
                                        foreach ($paket_jasa as $sj) : ?>
                                            <tr id="rowJasa<?= $no ?>">
                                                <td>
                                                    <button type="button" class="btn btn-dark" onclick="hapusBaris(<?= $no ?>)"><i class="fa-solid fa-delete-left"></i></button>
                                                </td>
                                                <td>
                                                    <select name="kode_cabang[]" id="kode_cabang<?= $no ?>" class="select2_all_cabang" data-placeholder="~ Pilih Cabang">
                                                        <?php $cabang = $this->M_global->getData('cabang', ['kode_cabang' => $sj->kode_cabang]); ?>
                                                        <option value="<?= $sj->kode_cabang ?>"><?= $cabang->cabang ?></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="kunjungan[]" id="kunjungan<?= $no ?>" class="form-control text-right" value="<?= number_format($sj->kunjungan) ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="jasa_rs[]" id="jasa_rs<?= $no ?>" class="form-control text-right" value="<?= number_format($sj->jasa_rs) ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="jasa_dokter[]" id="jasa_dokter<?= $no ?>" class="form-control text-right" value="<?= number_format($sj->jasa_dokter) ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="jasa_pelayanan[]" id="jasa_pelayanan<?= $no ?>" class="form-control text-right" value="<?= number_format($sj->jasa_pelayanan) ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="jasa_poli[]" id="jasa_poli<?= $no ?>" class="form-control text-right" value="<?= number_format($sj->jasa_poli) ?>">
                                                </td>
                                            </tr>
                                        <?php $no++;
                                        endforeach; ?>
                                    <?php else : ?>
                                        <tr id="rowJasa1">
                                            <td>
                                                <button type="button" class="btn btn-dark" onclick="hapusBaris(1)"><i class="fa-solid fa-delete-left"></i></button>
                                            </td>
                                            <td>
                                                <select name="kode_cabang[]" id="kode_cabang1" class="select2_all_cabang" data-placeholder="~ Pilih Cabang"></select>
                                            </td>
                                            <td>
                                                <input type="text" name="kunjungan[]" id="kunjungan1" class="form-control text-right" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="jasa_rs[]" id="jasa_rs1" class="form-control text-right" value="0">
                                            </td>
                                            <td>
                                                <input type="text" name="jasa_dokter[]" id="jasa_dokter1" class="form-control text-right" value="0">
                                            </td>
                                            <td>
                                                <input type="text" name="jasa_pelayanan[]" id="jasa_pelayanan1" class="form-control text-right" value="0">
                                            </td>
                                            <td>
                                                <input type="text" name="jasa_poli[]" id="jasa_poli1" class="form-control text-right" value="0">
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
                        <input type="hidden" class="form-control" id="jumJasa" value="<?= (!empty($paket_jasa) ? count($paket_jasa) : '1') ?>">
                        <button type="button" class="btn btn-primary" onclick="tambah_jasa()"><i class="fa-solid fa-folder-plus"></i> Tambah Jasa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger" onclick="getUrl('Master/tin_paket')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
            <button type="button" class="btn btn-success float-right ml-2" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
            <?php if (!empty($tarif)) : ?>
                <button type="button" class="btn btn-info float-right" onclick="getUrl('Master/form_tin_paket/0')" id="btnBaru"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            <?php else : ?>
                <button type="button" class="btn btn-info float-right" onclick="reseting()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
            <?php endif ?>
        </div>
    </div>
</form>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori Tarif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="form_kategori">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="keterangan_kategori">Keterangan <sup class="text-danger">**</sup></label>
                            <input type="text" class="form-control" id="keterangan_kategori" name="keterangan_kategori" placeholder="Keterangan..." onkeyup="ubah_nama(this.value, 'keterangan_kategori')">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-success float-right" onclick="proses_kategori()"><i class="fa fa-server"></i> Proses</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const bodyDetail = $('#bodyDetail');
    const form_tarif = $('#form_tarif');
    const btnSimpan = $('#btnSimpan');
    var kodeTarif = $('#kodeTarif');
    var nama = $('#nama');
    var kategori = $('#kategori');

    function proses_kategori() {
        $('#exampleModal').modal('hide');

        if ($('#keterangan_kategori').val() == '' || $('#keterangan_kategori').val() == null) {
            Swal.fire("Keterangan Kategori", "Form sudah diisi?", "question");

            $('#exampleModal').modal('show');

            return
        }

        $.ajax({
            url: siteUrl + 'Master/add_kategori_tarif',
            type: 'POST',
            dataType: 'JSON',
            data: $('#form_kategori').serialize(),
            success: function(result) {
                $('#exampleModal').modal('hide');

                if (result.status == 1) {
                    Swal.fire("Kategori Tarif", "Berhasil dibuat, silahkan dipilih!", "success");
                } else {
                    Swal.fire("Kategori Tarif", "Gagal dibuat, silahkan coba lagi!", "info");
                }
            },
            error: function(result) {
                error_proccess();

                $('#exampleModal').modal('show');
            }
        })
    }

    function tambah_jasa() {
        var jum = Number($('#jumJasa').val());
        var row = jum + 1;

        $('#jumJasa').val(row);
        bodyDetail.append(`<tr id="rowJasa${row}">
            <td>
                <button type="button" class="btn btn-dark" onclick="hapusBaris(${row})"><i class="fa-solid fa-delete-left"></i></button>
            </td>
            <td>
                <select name="kode_cabang[]" id="kode_cabang${row}" class="select2_all_cabang" data-placeholder="~ Pilih Cabang"></select>
            </td>
            <td>
                <input type="text" name="kunjungan[]" id="kunjungan${row}" class="form-control text-right" value="">
            </td>
            <td>
                <input type="text" name="jasa_rs[]" id="jasa_rs${row}" class="form-control text-right" value="0">
            </td>
            <td>
                <input type="text" name="jasa_dokter[]" id="jasa_dokter${row}" class="form-control text-right" value="0">
            </td>
            <td>
                <input type="text" name="jasa_pelayanan[]" id="jasa_pelayanan${row}" class="form-control text-right" value="0">
            </td>
            <td>
                <input type="text" name="jasa_poli[]" id="jasa_poli${row}" class="form-control text-right" value="0">
            </td>
        </tr>`);

        initailizeSelect2_all_cabang();
    }

    // fungsi hapus baris card
    function hapusBaris(row) {
        $('#rowJasa' + row).remove();
    }

    function reseting() {
        kodeTarif.val('');
        nama.val('');
        kategori.html(`<option value="">~ Pilih Kategori</option>`);
        $('#jumJasa').val(0);
        bodyDetail.empty();
        tambah_jasa();
    }

    function save() {
        btnSimpan.attr('disabled', true);

        if (kodeTarif.val() == '' || kodeTarif.val() == null) {
            var param = 1;
        } else {
            var param = 2;
        }

        if (nama.val() == '' || nama.val() == null) {
            btnSimpan.attr('disabled', false);

            Swal.fire("Nama Tarif", "Form sudah diisi?", "question");

            return
        }

        if (kategori.val() == '' || kategori.val() == null) {
            btnSimpan.attr('disabled', false);

            Swal.fire("Kategori Tarif", "Form sudah dipilih?", "question");

            return
        }

        proses(param);
    }

    function proses(param) {
        if (param == 1) {
            var message = 'buat';
        } else {
            var message = 'perbarui';
        }

        $.ajax({
            url: siteUrl + 'Master/tin_paket_proses/' + param,
            type: 'POST',
            dataType: 'JSON',
            data: form_tarif.serialize(),
            success: function(result) {
                btnSimpan.attr('disabled', false);

                if (result.status == 1) {
                    Swal.fire("Tarif Paket", "Berhasil di" + message + "!", "success").then(() => {
                        getUrl('Master/tin_paket');
                    });
                } else {
                    Swal.fire("Tarif Paket", "Gagal di" + message + "!, silahkan dicoba lagi", "info");
                }
            },
            error: function(result) {
                btnSimpan.attr('disabled', false);
                error_proccess();
            }
        })
    }
</script>