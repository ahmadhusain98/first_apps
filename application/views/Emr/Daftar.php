<?php
$created = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;

$cek_session = $this->session->userdata('kode_user');
$cek_sess_dokter = $this->M_global->getData('dokter', ['kode_dokter' => $cek_session]);
?>

<form id="">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Daftar Pasien</span>
                    <div class="float-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-down"></i>&nbsp;&nbsp;Unduh
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="preview('pendaftaran')"><i class="fa-solid fa-fw fa-tv"></i>&nbsp;&nbsp;Preview</a></li>
                                <li><a class="dropdown-item" href="#" onclick="print('pendaftaran')"><i class="fa-regular fa-fw fa-file-pdf"></i>&nbsp;&nbsp;Pdf</a></li>
                                <li><a class="dropdown-item" href="#" onclick="excel('pendaftaran')"><i class="fa-regular fa-fw fa-file-excel"></i>&nbsp;&nbsp;Excel</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="reloadTableEmr()"><i class="fa-solid fa-rotate-right"></i>&nbsp;&nbsp;Refresh</button>
                        <?php if ($created == 1) : ?>
                            <button type="button" class="btn btn-success" onclick="getUrl('Health/form_pendaftaran/0')"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="kode_dokter" id="kode_dokter" class="form-control select2_dokter_all" data-placeholder="~ Pilih Dokter" onchange="getPoli(this.value)">
                                        <?php if ($cek_sess_dokter) : ?>
                                            <option value="<?= $cek_session ?>"><?= 'Dr. ' . $cek_sess_dokter->nama ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="kode_poli" id="kode_poli" class="form-control select2_poli_dokter2" data-placeholder="~ Pilih Poli"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 col-5 mb-3">
                                    <input type="date" name="dari" id="dari" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-4 col-5 mb-3">
                                    <input type="date" name="sampai" id="sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-4 col-2 mb-3">
                                    <button type="button" class="btn btn-info" style="width: 100%" onclick="filterEmr()"><i class="fa-solid fa-sort"></i>&nbsp;&nbsp;Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tableEmr" width="100%" style="border-radius: 10px;">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%" style="border-radius: 10px 0px 0px 0px;">#</th>
                                    <th width="15%">No. Trx</th>
                                    <th width="20%">Member</th>
                                    <th>Tgl/Jam Masuk - Keluar</th>
                                    <th width="20%">Dokter</th>
                                    <th width="10%">Antri</th>
                                    <th width="15%" style="border-radius: 0px 10px 0px 0px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    var timeLeft = 10;
    // var countdownTimer = setInterval(function() {
    //     if (timeLeft <= 0) {
    //         timeLeft = 10; // Reset the timer
    //         reloadTableEmr(); // Call reloadTable function
    //     }
    //     document.getElementById("countdown").innerHTML = timeLeft + " Detik";
    //     timeLeft -= 1;
    // }, 1000);

    function reloadTableEmr() {
        tableEmr.DataTable().ajax.reload(null, false);
    }

    // variable
    var tableEmr = $('#tableEmr');
    var kode_dokter = $('#kode_dokter');
    var kode_poli = $('#kode_poli');

    initailizeSelect2_dokter_all();
    initailizeSelect2_poli_dokter2('<?= $cek_session ?>');

    tableEmr.DataTable({
        "destroy": true,
        "processing": true,
        "responsive": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": `<?= site_url() ?>Emr/daftar_list/1?kode_poli=&kode_dokter=<?= $cek_session ?>`,
            "type": "POST",
        },
        "scrollCollapse": false,
        "paging": true,
        "language": {
            "emptyTable": "<div class='text-center'>Data Kosong</div>",
            "infoEmpty": "",
            "infoFiltered": "",
            "search": "",
            "searchPlaceholder": "Cari data...",
            "info": " Jumlah _TOTAL_ Data (_START_ - _END_)",
            "lengthMenu": "_MENU_ Baris",
            "zeroRecords": "<div class='text-center'>Data Kosong</div>",
            "paginate": {
                "previous": "Sebelumnya",
                "next": "Berikutnya"
            }
        },
        "lengthMenu": [
            [10, 25, 50, 75, 100, -1],
            [10, 25, 50, 75, 100, "Semua"]
        ],
        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }],
    });

    function filterEmr() {
        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
        var kpoli = $('#kode_poli').val();
        var kdokter = $('#kode_dokter').val();

        var parameterString = `2~${dari}~${sampai}`; // Inisialisasi parameter dasar

        if (kpoli || kdokter) { // Periksa apakah kpoli dan kdokter memiliki nilai
            parameterString += `?kode_poli=${kpoli}&kode_dokter=${kdokter}`; // Tambahkan parameter jika keduanya ada
        }

        tableEmr.DataTable().ajax.url('<?= site_url() ?>Emr/daftar_list/' + parameterString).load();
    }


    // getpoli dokter
    function getPoli(param) {
        // hapus poli sebelumnya
        kode_poli.val('').change();

        // cek poli berdasarkan kode_dokter
        initailizeSelect2_poli_dokter2(param);
    }

    function select2_default(param) {
        var mymessage = "Data tidak ditemukan";
        $("." + param).select2({
            placeholder: $(this).data('placeholder'),
            width: '100%',
            language: {
                noResults: function() {
                    return mymessage;
                }
            },
        });
    }

    function initailizeSelect2_dokter_all() {
        $(".select2_dokter_all").select2({
            allowClear: true,
            multiple: false,
            placeholder: '~ Pilih Dokter',
            dropdownAutoWidth: true,
            width: '100%',
            language: {
                inputTooShort: function() {
                    return 'Ketikan Nomor minimal 1 huruf';
                },
                noResults: function() {
                    return 'Data Tidak Ditemukan';
                }
            },
            ajax: {
                url: '<?= site_url() ?>Select2_master/dataDokterAll',
                type: 'POST',
                dataType: 'JSON',
                delay: 100,
                data: function(result) {
                    return {
                        searchTerm: result.term
                    };
                },

                processResults: function(result) {
                    return {
                        results: result
                    };
                },
                cache: true
            }
        });
    }

    function initailizeSelect2_poli_dokter2(param) {
        if (param == '' || param == null || param == 'null') { // jika parameter kosong/ null
            // jalankan fungsi select2_default
            select2_default('select2_poli_dokter2');
        } else { // selain itu
            // jalan fungsi select2 asli
            $(".select2_poli_dokter2").select2({
                allowClear: true,
                multiple: false,
                placeholder: '~ Pilih Poli',
                dropdownAutoWidth: true,
                width: '100%',
                language: {
                    inputTooShort: function() {
                        return 'Ketikan Nomor minimal 1 huruf';
                    },
                    noResults: function() {
                        return 'Data Tidak Ditemukan';
                    }
                },
                ajax: {
                    url: '<?= site_url() ?>Select2_master/dataPoliDokter/' + param,
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 100,
                    data: function(result) {
                        return {
                            searchTerm: result.term
                        };
                    },

                    processResults: function(result) {
                        return {
                            results: result
                        };
                    },
                    cache: true
                }
            });
        }
    }
</script>