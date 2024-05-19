<div id="carouselExampleCaptions" class="carousel slide mb-3" data-ride="carousel" style="margin-top: 100px;">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="<?= base_url('assets/img/promo/promo2.jpeg') ?>" class="d-block w-100" alt="..." style="object-fit: cover; width: 100%; height: 400px">
            <div class="carousel-caption d-none d-md-block">
                <h5>First slide label</h5>
                <p>Some representative placeholder content for the first slide.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="<?= base_url('assets/img/promo/promo1.jpg') ?>" class="d-block w-100" alt="..." style="object-fit: cover; width: 100%; height: 400px">
            <div class="carousel-caption d-none d-md-block">
                <h5>Second slide label</h5>
                <p>Some representative placeholder content for the second slide.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-target="#carouselExampleCaptions" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-target="#carouselExampleCaptions" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </button>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div style="font-size: 14px; font-weight: bold;">Filter Obat</div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <select name="kode_kategori" id="kode_kategori" class="select2_kategori" onchange="cari()"></select>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <input class="form-control input-sm" type="search" id="search" placeholder="~ Pencarian Barang..." autofocus onkeyup="cari()">
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center" id="body-card">
    <?php foreach ($barang as $b) : ?>
        <div class="col-md-3 col-6 pb-3" onclick="getUrl('App/detail/<?= $b->kode_barang ?>')">
            <div class="card h-100" data-aos="fade-up" title="<?= $b->nama ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?= $b->nama ?>">
                <div class="card-header" style="background-color: #b1bdc8;">
                    <span style="font-size: 14px;"><?= mb_strimwidth($b->nama, 0, 22, "..."); ?></span>
                </div>
                <div class="card-body">
                    <img src="<?= base_url('assets/img/obat/') . $b->image ?>" class="card-img-top" style="width: 100%; height: 200px; object-fit: cover;">
                </div>
                <div class="card-footer">
                    <div style="font-size: 12px;">
                        <span>Rp.<?= number_format($b->harga_jual, 2) ?></span>
                        <?php
                        $terjual = $this->db->query("SELECT SUM(keluar) AS qty, SUM(akhir) AS stok FROM barang_stok WHERE kode_barang = '$b->kode_barang'")->row();

                        $kat = $this->M_global->getData('m_kategori', ['kode_kategori' => $b->kode_kategori]);
                        if ($kat->keterangan == 'Biru') {
                            $color = 'blue';
                        } else if ($kat->keterangan == 'Hijau') {
                            $color = 'green';
                        } else if ($kat->keterangan == 'Merah') {
                            $color = 'red';
                        } else if ($kat->keterangan == 'Abu-abu') {
                            $color = 'grey';
                        } else if ($kat->keterangan == 'Hitam') {
                            $color = 'black';
                        } else {
                            $color = 'white';
                        }
                        ?>
                        <br>
                        <span>Stok: <?= (($terjual) ? number_format((int)$terjual->stok) : '-') . ' ' . $this->M_global->getData('m_satuan', ['kode_satuan' => $b->kode_satuan])->keterangan ?></span>
                        <br>
                        <span>Terjual: <?= (($terjual) ? number_format((int)$terjual->qty) : '-') ?> <span class="float-right" style="height: 12px; width: 12px; background-color: <?= $color; ?>; border-radius: 50%; display: inline-block;"></span></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function cari() {
        var kode_kategori = $("#kode_kategori").val();
        var params = ($('#search').val()).toLowerCase();
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("body-card").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "<?= base_url('App/pencarian/'); ?>" + params + '?kode_kategori=' + kode_kategori, true);
        xhttp.send();
    }
</script>