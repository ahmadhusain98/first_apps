<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Rp. <span id="trx_out-counter">0</span></h3>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const targetValue = <?= $jumlah_beli ?>;
                        const duration = 2000; // Animation duration in milliseconds
                        const counterElement = document.getElementById('trx_out-counter');
                        let startValue = 0;
                        const increment = targetValue / (duration / 10);

                        const counterInterval = setInterval(() => {
                            startValue += increment;
                            if (startValue >= targetValue) {
                                startValue = targetValue;
                                clearInterval(counterInterval);
                            }
                            counterElement.textContent = new Intl.NumberFormat().format(Math.floor(startValue));
                        }, 10);
                    });
                </script>
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
                <h3>Rp. <span id="trx_today-counter">0</span></h3>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const targetValue = <?= $jumlah_bayar ?>;
                        const duration = 2000; // Animation duration in milliseconds
                        const counterElement = document.getElementById('trx_today-counter');
                        let startValue = 0;
                        const increment = targetValue / (duration / 10);

                        const counterInterval = setInterval(() => {
                            startValue += increment;
                            if (startValue >= targetValue) {
                                startValue = targetValue;
                                clearInterval(counterInterval);
                            }
                            counterElement.textContent = new Intl.NumberFormat().format(Math.floor(startValue));
                        }, 10);
                    });
                </script>
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
                <h3>Rp. <span id="profit-counter">0</span></h3>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const targetValue = <?= $result_jual ?>;
                        const duration = 2000; // Animation duration in milliseconds
                        const counterElement = document.getElementById('profit-counter');
                        let startValue = 0;
                        const increment = targetValue / (duration / 10);

                        const counterInterval = setInterval(() => {
                            startValue += increment;
                            if (startValue >= targetValue) {
                                startValue = targetValue;
                                clearInterval(counterInterval);
                            }
                            counterElement.textContent = new Intl.NumberFormat().format(Math.floor(startValue));
                        }, 10);
                    });
                </script>
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
                <h3>Rp. <span id="saldo-counter">0</span></h3>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const targetValue = <?= $saldo_kas ?>;
                        const duration = 2000; // Animation duration in milliseconds
                        const counterElement = document.getElementById('saldo-counter');
                        let startValue = 0;
                        const increment = targetValue / (duration / 10);

                        const counterInterval = setInterval(() => {
                            startValue += increment;
                            if (startValue >= targetValue) {
                                startValue = targetValue;
                                clearInterval(counterInterval);
                            }
                            counterElement.textContent = new Intl.NumberFormat().format(Math.floor(startValue));
                        }, 10);
                    });
                </script>
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
                <h3>Rp. <span id="piutang-counter">0</span></h3>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const targetValue = <?= (!empty($piutang) ? number_format($piutang->piutang) : 0) ?>;
                        const duration = 2000; // Animation duration in milliseconds
                        const counterElement = document.getElementById('piutang-counter');
                        let startValue = 0;
                        const increment = targetValue / (duration / 10);

                        const counterInterval = setInterval(() => {
                            startValue += increment;
                            if (startValue >= targetValue) {
                                startValue = targetValue;
                                clearInterval(counterInterval);
                            }
                            counterElement.textContent = new Intl.NumberFormat().format(Math.floor(startValue));
                        }, 10);
                    });
                </script>
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
                <h3>Rp. <span id="hutang-counter">0</span></h3>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const targetValue = <?= (!empty($hutang) ? number_format($hutang->hutang) : 0) ?>;
                        const duration = 2000; // Animation duration in milliseconds
                        const counterElement = document.getElementById('hutang-counter');
                        let startValue = 0;
                        const increment = targetValue / (duration / 10);

                        const counterInterval = setInterval(() => {
                            startValue += increment;
                            if (startValue >= targetValue) {
                                startValue = targetValue;
                                clearInterval(counterInterval);
                            }
                            counterElement.textContent = new Intl.NumberFormat().format(Math.floor(startValue));
                        }, 10);
                    });
                </script>
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