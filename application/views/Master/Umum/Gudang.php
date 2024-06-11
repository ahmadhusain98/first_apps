<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_gudang">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><ion-icon name="bookmark-outline" style="color: red;"></ion-icon> Daftar Gudang</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4 col-12">
            <select name="bagian" id="bagian" onchange="getBagian(this.value)" data-placeholder="~ Pilih Bagian" class="form-control select2_global">
                <option value="">~ Pilih Bagian</option>
                <option value="semua"># Semua</option>
                <option value="Internal">Internal</option>
                <option value="Logistik">Logistik</option>
            </select>
        </div>
        <div class="col-md-8">
            <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-warning" onclick="print('gudang')"><ion-icon name="print-outline"></ion-icon> Cetak</button>
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Master/form_gudang/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableGudang" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" class="bg-primary">#</th>
                            <th width="10%" class="bg-primary">ID</th>
                            <th class="bg-primary">Nama</th>
                            <th class="bg-primary">Bagian</th>
                            <th class="bg-primary">Pajak (%)</th>
                            <th class="bg-primary">Keterangan</th>
                            <th width="10%" class="bg-primary">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    // variable
    var table = $('#tableGudang');

    // fungsi ubah bagian
    function getBagian(bagian) {
        table.DataTable().ajax.url(siteUrl + 'Master/gudang_list/' + bagian).load();
    }

    //fungsi ubah berdasarkan lemparan kode
    function ubah(kode_gudang) {
        // jalankan fungsi
        getUrl('Master/form_gudang/' + kode_gudang);
    }

    // fungsi hapus berdasarkan kode_gudang
    function hapus(kode_gudang) {
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
                    url: siteUrl + 'Master/delGud/' + kode_gudang,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Gudang", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Gudang", "Gagal di hapus!, silahkan dicoba kembali", "info");
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