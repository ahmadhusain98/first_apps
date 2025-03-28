<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
echo _lock_so();
?>

<form method="post" id="form_barang_in">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Menu</span>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <button type="button" class="btn btn-danger" onclick="getUrl('Backdoor')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="float-right">
                                <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tableAksesMenu" width="100%" style="border-radius: 10px;">
                                    <thead>
                                        <tr class="text-center">
                                            <th rowspan="2" width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                                            <th rowspan="2" width="25%">Menu</th>
                                            <th colspan="<?= count($role) ?>" width="70%">Akses</th>
                                        </tr>
                                        <tr class="text-center">
                                            <?php foreach ($role as $r) : ?>
                                                <th><?= $r->keterangan ?></td>
                                                <?php endforeach ?>
                                        </tr>
                                    </thead>
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
    var table = $('#tableAksesMenu');

    // change role
    function changeAkses(id_akses, kdrole, no, nor, nmenu, nrole, idmenu) {
        // console.log(id_akses + ' - ' + kdrole + ' - ' + no + ' - ' + nor + ' - ' + nmenu + ' - ' + nrole + ' - ' + idmenu);
        Swal.fire({
            title: "Kamu yakin?",
            html: "Menu <b>" + nmenu + "</b> untuk akses <b style='color: red;'>" + nrole + "</>!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, ubah!",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin

                // jalankan fungsi
                $.ajax({
                    url: siteUrl + 'Backdoor/changeMenu/?id_akses=' + id_akses + '&kdrole=' + kdrole + '&idmenu=' + idmenu,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("User " + nmenu, "Berhasil diubah aksesnya!", "success").then(() => {
                                reloadTable();
                                getUrl('Backdoor/menu_akses');
                            });
                        } else { // selain itu

                            Swal.fire("User " + nmenu, "Gagal diubah aksesnya!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            } else if (result.dismiss == 'cancel') {
                document.getElementById('krole' + no + '_' + nor).checked = false
            } else {
                document.getElementById('krole' + no + '_' + nor).checked = false
            }
        });
    }
</script>