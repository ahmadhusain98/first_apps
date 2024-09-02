<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_deposit">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Deposit Kas/Bank</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-md-4 col-4 mb-3">
                    <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-4 mb-3">
                    <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4 col-4 mb-3">
                    <button type="button" class="btn btn-light" onclick="filter($('#kode_gudang').val())"><i class="fa-solid fa-sort"></i>&nbsp;&nbsp;Filter</button>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="float-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-circle-down"></i>&nbsp;&nbsp;Unduh
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="preview('deposit_kas')"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</a></li>
                        <li><a class="dropdown-item" href="#" onclick="print('deposit_kas')"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</a></li>
                        <li><a class="dropdown-item" href="#" onclick="excel('deposit_kas')"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Accounting/form_deposit_kas/0')" <?= (($created == 1) ? _lock_button() : 'disabled') ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableDeposit" width="100%" style="border-radius: 10px;">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                            <th width="15%">Tgl/Jam Deposit</th>
                            <th width="10%">User</th>
                            <th width="15%">Jenis Bayar</th>
                            <th width="15%">Cash</th>
                            <th width="15%">Card</th>
                            <th width="15%">Total</th>
                            <th width="10%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    var table = $('#tableDeposit');

    function ubah(tkn) {
        getUrl('Accounting/form_deposit_kas/' + tkn);
    }
</script>