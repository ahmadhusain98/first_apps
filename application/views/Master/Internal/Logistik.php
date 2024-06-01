<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_logistik">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Logistik</span>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-warning ml-1" onclick="print('logistik')"><ion-icon name="print-outline"></ion-icon> Cetak</button>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-success ml-1" onclick="getUrl('Master/form_logistik/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-primary ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2 col-12">
                            <select name="kode_kategori" id="kode_kategori" onchange="getKat(this.value)" data-placeholder="~ Pilih Kategori" class="form-control select2_global">
                                <option value="">~ Pilih Bagian</option>
                                <option value="semua"># Semua</option>
                                <?php foreach ($kategori as $k) : ?>
                                    <option value="<?= $k->kode_kategori ?>"><?= $k->keterangan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableLogistik" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th rowspan="2" width="5%">#</th>
                                            <th rowspan="2">ID</th>
                                            <th rowspan="2">Nama</th>
                                            <th rowspan="2">Satuan</th>
                                            <th rowspan="2">Kategori</th>
                                            <th colspan="4">Harga</th>
                                            <th rowspan="2" width="15%">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>HNA</th>
                                            <th>HPP</th>
                                            <th>Jual</th>
                                            <th>Persediaan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // variable
    var table = $('#tableLogistik');

    // fungsi ubah bagian
    function getKat(bagian) {
        table.DataTable().ajax.url(siteUrl + 'Master/logistik_list/' + bagian).load();
    }

    //fungsi ubah berdasarkan lemparan kode
    function ubah(kode_logistik) {
        // jalankan fungsi
        getUrl('Master/form_logistik/' + kode_logistik);
    }

    // fungsi hapus berdasarkan kode_logistik
    function hapus(kode_logistik) {
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
                    url: siteUrl + 'Master/delGud/' + kode_logistik,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Logistik", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Logistik", "Gagal di hapus!, silahkan dicoba kembali", "info");
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