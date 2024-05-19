<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $jumlah_beli ?></h3>
                <p>Penjualan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a type="button" onclick="getUrl('Transaksi/barang_out')" class="small-box-footer">Info Lanjut <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>0<sup style="font-size: 20px">%</sup></h3>
                <p>Persentase Hari Ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a type="button" onclick="getUrl('Kasir')" class="small-box-footer">Info Lanjut <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $jumlah_member ?></h3>
                <p>Member</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a type="button" onclick="getUrl('Health/daftar')" class="small-box-footer">Info Lanjut <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>65</h3>
                <p>Unique Visitors</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">Info Lanjut <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- <?php foreach ($kunjungan_poli as $ku) : ?>
    <?= $ku->jumlah . ' - ' . $ku->poli . '<br>'; ?>
<?php endforeach ?> -->

<div class="row">
    <div class="col-lg-6 col-6">
        <div class="small-box bg-light">
            <div class="inner">
                <canvas id="poli"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const poli = document.getElementById('poli');

    new Chart(poli, {
        type: 'doughnut',
        data: {
            labels: [<?php foreach ($kunjungan_poli as $kp) : ?> '<?= $kp->poli ?>',
                <?php endforeach ?>
            ],
            datasets: [{
                label: '# Orang',
                data: [<?php foreach ($kunjungan_poli as $kp) : ?> '<?= $kp->jumlah ?>',
                    <?php endforeach ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>