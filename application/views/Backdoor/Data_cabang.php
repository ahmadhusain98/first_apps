<form method="post" id="form_cabang">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Cabang</span>
                    <div class="float-right">
                        <button type="button" class="btn btn-success float-right" onclick="getUrl('Backdoor/form_cabang/0')"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger" onclick="getUrl('Backdoor')" id="btnKembali"><i class="fa-solid fa-circle-chevron-left"></i>&nbsp;&nbsp;Kembali</button>
                            <div class="float-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-circle-down"></i>&nbsp;&nbsp;Unduh
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="preview('cabang')"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="print('cabang')"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="excel('cabang')"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</a></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tableCabang" width="100%" style="border-radius: 10px;">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                                            <th width="10%">ID</th>
                                            <th width="15%">Keterangan</th>
                                            <th width="20%">Kontak</th>
                                            <th width="20%">Alamat</th>
                                            <th width="20%">Masa Aktif</th>
                                            <th width="10%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
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
    var table = $('#tableCabang');

    //fungsi ubah berdasarkan lemparan kode
    function ubah(kode_cabang) {
        // jalankan fungsi
        getUrl('Backdoor/form_cabang/' + kode_cabang);
    }

    // fungsi hapus berdasarkan kode_cabang
    function hapus(kode_cabang) {
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
                    url: siteUrl + 'Backdoor/delCabang/' + kode_cabang,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1

                            Swal.fire("Cabang", "Berhasil di hapus!", "success").then(() => {
                                reloadTable();
                            });
                        } else { // selain itu

                            Swal.fire("Cabang", "Gagal di hapus!, silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    function showGuide() {
        // clean text
        $('#modal_mgLabel').text(``);
        $('#modal-isi').text(``);

        $('#modal_mg').modal('show'); // show modal

        // isi text
        $('#modal_mgLabel').append(`Manual Guide Master Cabang`);
        $('#modal-isi').append(`
            <ol>
                <li style="font-weight: bold;">Tambah Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Tambah</li>
                        <li>Selanjutnya isikan Form yang tersedia<br>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
                <li style="font-weight: bold;">Ubah Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Ubah pada list data yang ingin di ubah</li>
                        <li>Ubah isi Form yang akan di ubah<br>Tanda (<span style="color: red;">**</span>) mengartikan wajib terisi</li>
                        <li>Klik tombol Proses</li>
                    </ul>
                </p>
                <li style="font-weight: bold;">Hapus Data</li>
                <p>
                    <ul>
                        <li>Klik tombol Hapus pada list data yang ingin di hapus</li>
                        <li>Saat Muncul Pop Up, klik "Ya, Hapus"</li>
                    </ul>
                </p>
            </ol>
        `);
    }
</script>