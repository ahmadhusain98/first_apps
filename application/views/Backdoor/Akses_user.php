<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
echo _lock_so();
?>

<form method="post" id="form_barang_in">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar User</span>
        </div>
    </div>
    <br>
    <div class="row mb-3">
        <div class="col-md-6 col-12">
            <button type="button" class="btn btn-danger" onclick="getUrl('Backdoor')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
        </div>
        <div class="col-md-6 col-12">
            <div class="float-right">
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableAksesUser" width="100%" style="border-radius: 10px;">
                    <thead>
                        <tr class="text-center">
                            <th rowspan="2" width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                            <th rowspan="2" width="25%">User</th>
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
</form>

<script>
    // variable
    var table = $('#tableAksesUser');

    // change role
    function changeRole(kduser, kdrole, no, nor, nuser, nrole) {
        Swal.fire({
            title: "Kamu yakin?",
            html: "Ubah <b>" + nuser + "</b> menjadi <b style='color: red;'>" + nrole + "</>!",
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
                    url: siteUrl + 'Backdoor/changeAkses/?kduser=' + kduser + '&kdrole=' + kdrole,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("User " + nuser, "Berhasil diubah aksesnya!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("User " + nuser, "Gagal diubah aksesnya!, silahkan dicoba kembali", "info");
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