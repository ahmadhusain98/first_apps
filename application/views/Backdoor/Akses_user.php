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
        <div class="col-md-12 col-12">
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
                            <?php foreach($role as $r) : ?>
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
    function changeRole(kduser, kdrole, nor) {
    }
</script>