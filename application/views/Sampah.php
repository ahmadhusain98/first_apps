<form method="post" id="form_sampah">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Sampah</span>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary" onclick="getUrl('Sampah')"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                    </div>
                </div>
                <div class="card-footer">
                    <div style="overflow-x: auto; white-space: nowrap; display: flex; text-decoration: none; text-align: center; max-width: 100%;">
                        <?php foreach ($menu as $m) : ?>
                            <button class="btn btn-danger m-1" id="id_checkbox<?= $m->id ?>" onclick="check_on('<?= $m->id ?>')" type="button">
                                <input type="checkbox" name="id_menu[]" id="id_menu<?= $m->id ?>" <?= ($check == $m->id) ? 'checked' : '' ?>> <?= $m->nama ?>
                                <input type="hidden" name="id_menu2[]" id="id_menu2<?= $m->id ?>" value="<?= ($check != '') ? '1' : '0' ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <!-- <select name="id_menu[]" id="id_menu" class="form-control select2_global" data-placeholder="~ Pilih Menu" multiple="multiple">
                        <option value="">~ Pilih Menu</option>
                        <?php if (!empty($menu)) :
                            $me_arr = [];
                            foreach ($menu as $me) :
                                $me_arr[] = $me->id;
                            endforeach;
                        endif;
                        ?>
                        <?php foreach ($menu as $m) : ?>
                            <option value="<?= $m->id ?>" <?= (!empty($menu) ? (in_array($m->id, $me_arr) ? '' : 'selected') : '') ?>><?= $m->nama ?></option>
                        <?php endforeach; ?>
                    </select> -->
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="h6">Jumlah Sampah: <span style="color: <?= (count($query_master) < 501) ? 'green' : ((count($query_master) < 1000) ? 'yellow' : 'red') ?>; font-weight: bold;"><?= count($query_master) ?></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                <button type="button" class="btn btn-success" onclick="sel_pulihkan()"><i class="fa fa-trash-restore-alt"></i> Pulihkan</button>
                                <button type="button" class="btn btn-dark" onclick="sel_hapus()"><i class="fa fa-trash-alt"></i> Hapus</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tableSampah" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 5%;"><input type="checkbox" class="form-control" name="check_all" id="check_all" onclick="sel_all()"></th>
                                            <th style="width: 5%;">#</th>
                                            <th>Menu</th>
                                            <th>Waktu</th>
                                            <th>Id/Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($query_master as $qm) : ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="check_one[]" id="check_one<?= $no ?>" class="form-control" onclick="sel_one('<?= $no ?>', '<?= $qm->id ?>')">
                                                    <input type="hidden" name="check_onex[]" id="check_onex<?= $no ?>" value="0" class="form-control">
                                                    <input type="hidden" name="menu[]" id="menu<?= $no ?>" value="<?= $qm->menu ?>" class="form-control">
                                                    <input type="hidden" name="invoice[]" id="invoice<?= $no ?>" value="<?= $qm->id ?>" class="form-control">
                                                    <input type="hidden" name="tabel[]" id="tabel<?= $no ?>" value="<?= $qm->tabel ?>" class="form-control">
                                                </td>
                                                <td class="text-center"><?= $no ?></td>
                                                <td><?= $qm->menu ?></td>
                                                <td><?= $qm->tgl . ' ~ ' . $qm->jam ?></td>
                                                <td><?= $qm->id ?></td>
                                            </tr>
                                        <?php $no++;
                                        endforeach; ?>
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
    function sel_hapus() {
        const form = $('#form_sampah')
        var tableBarangIn = document.getElementById('tableSampah'); // ambil id table detail
        var rowCount = tableBarangIn.rows.length; // hitung jumlah rownya
        var no = 0;

        for (var i = 1; i <= rowCount; i++) {
            if ($('#check_onex' + i).val() == 1) {
                no += 1;
            }
        }

        Swal.fire({
            title: "Kamu yakin?",
            html: "<b style='color: red'>" + no + "</b> Data akan dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin

                // jalankan fungsi
                $.ajax({
                    url: siteUrl + 'Sampah/deleted',
                    data: form.serialize(),
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik
                        if (result.status == 1) { // jika mendapatkan hasil 1

                            Swal.fire("Data Sampah", "Berhasil di hapus!", "success").then(() => {
                                getUrl('Sampah');
                            });
                        } else { // selain itu

                            Swal.fire("Data Sampah", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    function sel_pulihkan() {
        const form = $('#form_sampah')
        var tableBarangIn = document.getElementById('tableSampah'); // ambil id table detail
        var rowCount = tableBarangIn.rows.length; // hitung jumlah rownya
        var no = 0;

        for (var i = 1; i <= rowCount; i++) {
            if ($('#check_onex' + i).val() == 1) {
                no += 1;
            }
        }

        Swal.fire({
            title: "Kamu yakin?",
            html: "<b style='color: red'>" + no + "</b> Data akan dipulihkan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, pulihkan!",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin

                // jalankan fungsi
                $.ajax({
                    url: siteUrl + 'Sampah/restore',
                    data: form.serialize(),
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik
                        if (result.status == 1) { // jika mendapatkan hasil 1

                            Swal.fire("Data Sampah", "Berhasil di pulihkan!", "success").then(() => {
                                getUrl('Sampah');
                            });
                        } else { // selain itu

                            Swal.fire("Data Sampah", "Gagal di pulihkan!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    function sel_one(param1, param2) {
        document.getElementById('check_all').checked = false
        if (document.getElementById('check_one' + param1).checked == true) {
            $('#check_onex' + param1).val(1)
        } else {
            $('#check_onex' + param1).val(0)
        }
    }

    function sel_all() {
        var no = 1;
        var isChecked = document.getElementById('check_all').checked;

        var queryMaster = JSON.parse('<?= json_encode($query_master) ?>');

        $.each(queryMaster, function(index, value) {
            var checkBoxId = 'check_one' + no;
            var hiddenInputId = '#check_onex' + no;

            document.getElementById(checkBoxId).checked = isChecked ? true : false;
            $(hiddenInputId).val(isChecked ? 1 : 0);

            no++;
        });
    }

    function check_on(params) {
        if (document.getElementById('id_menu' + params).checked == true) {
            document.getElementById('id_menu' + params).checked = true
            $('#id_menu2' + params).val(1)
        } else {
            document.getElementById('id_menu' + params).checked = false
            $('#id_menu2' + params).val(0)
        }

        location.href = '<?= site_url("Sampah?param=") ?>' + params
    }
</script>