<div class="row mb-1">
    <div class="col-lg-4 col-4" type="button" onclick="empty_trx()">
        <div class="small-box bg-warning" style="height: 20vh;">
            <div class="inner">
                <h4>Empty Transaksi</h4>
            </div>
            <div class="icon">
                <i class="fa-solid fa-recycle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-4" type="button" onclick="getUrl('Backdoor/data_db')">
        <div class="small-box bg-success" style="height: 20vh;">
            <div class="inner">
                <h4>Backup & Download Database</h4>
            </div>
            <div class="icon">
                <i class="fa-solid fa-server"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-4" type="button" onclick="empty_all()">
        <div class="small-box bg-danger" style="height: 20vh;">
            <div class="inner">
                <h4>Empty Database</h4>
            </div>
            <div class="icon">
                <i class="fa-solid fa-database"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-4 col-4" type="button" onclick="getUrl('Backdoor/user_akses')">
        <div class="small-box bg-secondary" style="height: 20vh;">
            <div class="inner">
                <h4>Akses User</h4>
            </div>
            <div class="icon">
                <i class="fa-solid fa-users-gear"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-4" type="button" onclick="getUrl('Backdoor/menu_akses')">
        <div class="small-box bg-light" style="height: 20vh;">
            <div class="inner">
                <h4>Akses Menu</h4>
            </div>
            <div class="icon">
                <i class="fa-solid fa-user-gear"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-4" type="button" onclick="getUrl('Backdoor/cabang_akses')">
        <div class="small-box bg-info" style="height: 20vh;">
            <div class="inner">
                <h4>Akses Cabang</h4>
            </div>
            <div class="icon">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
    </div>
</div>

<script>
    function empty_trx() {
        Swal.fire({
            title: "Kamu yakin?",
            html: "<b style='color: red;'>Semua Log User, Transaksi (PO, Pembelian, dan Penjualan) beserta history kasir dan pendaftaran akan di kosongkan!</b>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, kosongkan",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin

                // jalankan fungsi
                $.ajax({
                    url: siteUrl + 'Backdoor/trx_empty',
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Empty Transaksi", "Berhasil dikosongkan", "success").then(() => {
                                location.href = siteUrl + "Auth/logout";
                            });
                        } else { // selain itu

                            Swal.fire("Empty Transaksi", "Gagal dikosongkan" + ", silahkan dicoba kembali", "info");
                        }
                    },
                    error: function(result) { // jika fungsi error

                        error_proccess();
                    }
                });
            }
        });
    }

    function empty_all() {
        Swal.fire({
            title: "Kamu yakin?",
            html: "<b style='color: red;'>Semua table termasuk master akan di kosongkan!</b>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, kosongkan",
            cancelButtonText: "Tidak!"
        }).then((result) => {
            if (result.isConfirmed) { // jika yakin

                // jalankan fungsi
                $.ajax({
                    url: siteUrl + 'Backdoor/db_empty',
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(result) { // jika fungsi berjalan dengan baik

                        if (result.status == 1) { // jika mendapatkan hasil 1
                            Swal.fire("Empty Database", "Berhasil dikosongkan", "success").then(() => {
                                location.href = siteUrl + "Auth/logout";
                            });
                        } else { // selain itu

                            Swal.fire("Empty Database", "Gagal dikosongkan" + ", silahkan dicoba kembali", "info");
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