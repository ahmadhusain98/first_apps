<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_gudang">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Gudang</span>
                    <button type="button" class="btn btn-sm mb-1 btn-success float-right ml-1" onclick="getUrl('Master/form_gudang/0')" <?= (($created > 0) ? '' : 'disabled') ?>><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
                    <button type="button" class="btn btn-sm mb-1 btn-primary float-right ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2 col-12">
                            <select name="bagian" id="bagian" onchange="getBagian(this.value)" data-placeholder="~ Pilih Bagian" class="form-control select2_global">
                                <option value="">~ Pilih Bagian</option>
                                <option value="semua"># Semua</option>
                                <option value="Internal">Internal</option>
                                <option value="Logistik">Logistik</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableGudang" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">#</th>
                                            <th width="10%">ID</th>
                                            <th>Nama</th>
                                            <th>Bagian</th>
                                            <th>Pajak (%)</th>
                                            <th>Keterangan</th>
                                            <th width="20%">Aksi</th>
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