<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<form id="">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Pasien</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <input type="search" name="search" id="search" onkeyup="cari()" class="form-control" placeholder="Cari Pasien..." autofocus>
                        <br>
                        <div id="listPasien"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <button type="button" id="btnper" class="btn btn-primary" onclick="seltab(1)">Pemeriksaan Perawat</button>
                    <button type="button" id="btndok" class="btn btn-light" onclick="seltab(2)">Pemeriksaan Dokter</button>
                </div>
                <div class="card-body">
                    <div id="forper">aaaa</div>
                    <div id="fordok">bbbb</div>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
    const btnper = $('#btnper')
    const btndok = $('#btndok')
    const forper = $('#forper')
    const fordok = $('#fordok')

    seltab(1)
    cari()

    function cari() {
        var params = ($('#search').val()).toLowerCase();
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("listPasien").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "<?= base_url('Emr/pencarian/'); ?>" + params, true);
        xhttp.send();
    }

    function seltab(param) {
        if (param == 1) {
            btnper.removeClass('btn-light')
            btnper.addClass('btn-primary')

            btndok.addClass('btn-light')
            btndok.removeClass('btn-primary')

            forper.show(200)
            fordok.hide(200)
        } else {
            btnper.addClass('btn-light')
            btnper.removeClass('btn-primary')

            btndok.removeClass('btn-light')
            btndok.addClass('btn-primary')

            forper.hide(200)
            fordok.show(200)
        }
    }
</script>