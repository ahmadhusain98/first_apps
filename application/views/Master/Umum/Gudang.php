<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form method="post" id="form_gudang">
    <div class="row">
        <div class="col-md-12">
            <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Gudang</span>
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
            <div class="float-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-circle-down"></i>&nbsp;&nbsp;Unduh
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="preview('gudang')"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</a></li>
                        <li><a class="dropdown-item" href="#" onclick="print('gudang')"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</a></li>
                        <li><a class="dropdown-item" href="#" onclick="excel('gudang')"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                <button type="button" class="btn btn-success" onclick="getUrl('Master/form_gudang/0')" <?= (($created > 0) ? '' : 'disabled') ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableGudang" width="100%" style="border-radius: 10px;">
                    <thead>
                        <tr class="text-center">
                            <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                            <th width="10%">ID</th>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th>Pajak</th>
                            <th>Keterangan</th>
                            <th width="15%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
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