<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_piutang">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Mutasi Kas & Bank</span>
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
                        <li><a class="dropdown-item" href="#" onclick="preview('mutasi_kas')"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</a></li>
                        <li><a class="dropdown-item" href="#" onclick="print('mutasi_kas')"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</a></li>
                        <li><a class="dropdown-item" href="#" onclick="excel('mutasi_kas')"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Accounting/form_mutasi_kas/0')" <?= (($created == 1) ? '' : 'disabled') ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableMutasi" width="100%" style="border-radius: 10px;">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                            <th width="15%">Invoice</th>
                            <th width="15%">Tgl/Jam Mutasi</th>
                            <th width="15%">Dari Kas</th>
                            <th width="10%">Menuju Kas</th>
                            <th width="15%">Total</th>
                            <th width="10%">Status</th>
                            <th width="5%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    var table = $('#tableMutasi')

    function ubah(param) {
        getUrl('Accounting/form_mutasi_kas/' + param)
    }

    // fungsi hapus berdasarkan invoice
    function hapus(param) {
        // ajukan pertanyaaan
        Swal.fire({
            title: "Kamu yakin?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
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
                    url: siteUrl + 'Accounting/delMutasiKas/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Mutasi Kas & Bank", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Mutasi Kas & Bank", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }
</script>