<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_tin_single">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Tarif Single</span>
            <div class="float-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-circle-down"></i>&nbsp;&nbsp;Unduh
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="preview('tin_single')"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</a></li>
                        <li><a class="dropdown-item" href="#" onclick="print('tin_single')"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</a></li>
                        <li><a class="dropdown-item" href="#" onclick="excel('tin_single')"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Master/form_tin_single/0')" <?= (($created > 0) ? '' : 'disabled') ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableTarifSingle" width="100%" style="border-radius: 10px;">
                    <thead>
                        <tr class="text-center">
                            <th rowspan="2" width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                            <th rowspan="2" width="10%">ID</th>
                            <th rowspan="2">Nama</th>
                            <th colspan="4">Jasa</th>
                            <th rowspan="2" width="15%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
                        </tr>
                        <tr class="text-center">
                            <th>Rumah Sakit</th>
                            <th>Dokter</th>
                            <th>Pelayanan</th>
                            <th>Poli</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    var table = $('#tableTarifSingle');

    function ubah(kode_tarif) {
        getUrl('Master/form_tin_single/' + kode_tarif);
    }

    // fungsi hapus berdasarkan kode_supplier
    function hapus(kode_tarif) {
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
                    url: siteUrl + 'Master/delTarifSingle/' + kode_tarif,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1

                            Swal.fire("Tarif Single", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Tarif Single", "Gagal di hapus!, silahkan dicoba kembali", "info");
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