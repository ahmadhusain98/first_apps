<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<div class="row">
    <div class="col-md-12">
        <form method="post" id="form_daftar">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold h4"># Daftar Member</span>
                    <?php if ($created == 1) : ?>
                        <button type="button" class="btn btn-sm float-right mb-1 btn-success ml-1" onclick="getUrl('Member/form_daftar/0')"><ion-icon name="add-circle-outline"></ion-icon> Baru</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm float-right mb-1 btn-primary ml-1" onclick="reloadTable()"><ion-icon name="rocket-outline"></ion-icon> Refresh</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tableDaftar" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="10%">NIK</th>
                                            <th width="25%">Nama</th>
                                            <th width="20%">Alamat</th>
                                            <th width="15%">Trx Terakhir</th>
                                            <th width="10%">Aksi</th>
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
    var table = $('#tableDaftar');
    var tabledb = 'member';

    table.DataTable({
        "destroy": true,
        "processing": true,
        "responsive": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": `http://localhost/rest_member/Rest_api/member`,
            "sync": true,
            "dataSrc": function(data) {
                return data.data;
            }
        },
        "columns": [{
                "data": "nik"
            },
            {
                "data": "nama"
            },
            {
                "data": () => ({
                    url: `${siteUrl}Member/getProvinsi/` + "provinsi",
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) {
                        console.log(result)
                        result.provinsi;
                    },
                    error: function(result) {
                        "";
                    }
                }),
                // "data": "kabupaten",
                // "data": "kecamatan",
                // "data": "desa",
                // "data": "kodepos",
                // "data": "rt",
                // "data": "rw",
            },
            {
                "data": "last_regist"
            }
        ],
        "scrollCollapse": false,
        "paging": true,
        "oLanguage": {
            "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
            "sInfoEmpty": "",
            "sInfoFiltered": "",
            "sSearch": "",
            "sSearchPlaceholder": "Cari data...",
            "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
            "sLengthMenu": "_MENU_ Baris",
            "sZeroRecords": "<div class='text-center'>Data Kosong</div>",
            "oPaginate": {
                "sPrevious": "Sebelumnya",
                "sNext": "Berikutnya"
            }
        },
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "Semua"]
        ],
        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],
    });

    // rest_test();

    // function rest_test() {
    //     var table = 'member';
    //     $.ajax({
    //         url: `http://localhost/rest_member/Rest_api/${table}`,
    //         dataType: 'JSON',
    //         success: function(result) {
    //             var no = 1;
    //             $.each(result.data, function(index, value) {
    //                 $('#restid' + no).text(value.email);
    //                 no++;
    //             });
    //         }
    //     });
    // }
</script>