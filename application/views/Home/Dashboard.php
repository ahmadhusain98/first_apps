<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $jumlah_beli ?></h3>
                <p>Transaksi Keluar Hari Ini</p>
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
                <h3><?= $jumlah_bayar ?></h3>
                <p>Transaksi Dibayar Hari Ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a type="button" onclick="getUrl('Kasir')" class="small-box-footer">Info Lanjut <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>Rp. <?= number_format($saldo_kas - (($hutang->hutang > 0) ? $hutang->hutang : 0)) ?></h3>
                <p>Keuntungan</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-landmark"></i>
            </div>
            <a type="button" class="small-box-footer">&ensp;</a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-light">
            <div class="inner">
                <h3>Rp. <?= number_format($saldo_kas) ?></h3>
                <p>Saldo Kas/Bank</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <a type="button" class="small-box-footer">&ensp;</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-6">
        <div class="small-box bg-light">
            <div class="inner">
                <canvas id="poli"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Rp. <?= (!empty($piutang) ? number_format($piutang->piutang) : 0) ?></h3>
                <p>Piutang</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-scale-unbalanced-flip"></i>
            </div>
            <a type="button" class="small-box-footer">&ensp;</a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp. <?= (!empty($hutang) ? number_format((0 - $hutang->hutang)) : 0) ?></h3>
                <p>Hutang</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-scale-unbalanced"></i>
            </div>
            <a type="button" class="small-box-footer">&ensp;</a>
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