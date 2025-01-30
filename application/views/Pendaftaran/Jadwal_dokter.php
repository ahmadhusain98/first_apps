<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<style>
    :root {
        --fc-border-color: #e9ecef;
        --fc-daygrid-event-dot-width: 5px;
        --fc-button-primary: #007bff;
    }

    /* .select2-selection__rendered {
        line-height: 20px !important;
    }

    .select2-container .select2-selection--single {
        height: 30px !important;
    }

    .select2-selection__arrow {
        height: 20px !important;
    } */
</style>

<form method="post" id="form_jadwal">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Jadwal Dokter</span>
                </div>
                <div class="card-footer text-center">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="hadir" style="margin-right: 10px;">Hadir</label>
                            <input type="radio" checked style="accent-color: #007bff;">
                        </div>
                        <div class="col-md-3">
                            <label for="izin" style="margin-right: 10px;">Izin</label>
                            <input type="radio" checked style="accent-color: #ffd000;">
                        </div>
                        <div class="col-md-3">
                            <label for="sakit" style="margin-right: 10px;">Sakit</label>
                            <input type="radio" checked style="accent-color: #ed1e32;">
                        </div>
                        <div class="col-md-3">
                            <label for="cuti" style="margin-right: 10px;">Cuti</label>
                            <input type="radio" checked style="accent-color: #2aae47;">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Formulir</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="kode_dokter" class="control-label">Dokter <span class="text-danger">**</span></label>
                                    <input type="hidden" class="form-control" id="kodeJadwal" name="kodeJadwal" placeholder="Otomatis" readonly>
                                    <select name="kode_dokter" id="kode_dokter" class="form-control select2_dokter_all" data-placeholder="~ Pilih Dokter"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="kode_cabang" class="control-label">Cabang <span class="text-danger">**</span></label>
                                    <select name="kode_cabang" id="kode_cabang" class="form-control select2_all_cabang" data-placeholder="~ Pilih Cabang">
                                        <option value="<?= $this->session->userdata('cabang') ?>">
                                            <?= $this->M_global->getData('cabang', ['kode_cabang' => $this->session->userdata('cabang')])->cabang ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="status_dokter" class="control-label">Status <span class="text-danger">**</span></label>
                                    <select name="status_dokter" id="status_dokter" class="form-control select2_global" data-placeholder="~ Pilih Status">
                                        <option value="">~ Pilih Status</option>
                                        <option value="1" selected>Hadir</option>
                                        <option value="2">Izin</option>
                                        <option value="3">Sakit</option>
                                        <option value="4">Cuti</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_start" class="control-label">Dari Tgl <span class="text-danger">**</span></label>
                                    <input type="date" name="date_start" id="date_start" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="time_start" class="control-label">Dari Jam <span class="text-danger">**</span></label>
                                    <input type="time" name="time_start" id="time_start" class="form-control" value="<?= date('H:i') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_end" class="control-label">Sampai Tgl <span class="text-danger">**</span></label>
                                    <input type="date" name="date_end" id="date_end" class="form-control" value="<?= date('Y-m-d', strtotime('+1 Days')) ?>" min="<?= date('Y-m-d', strtotime('+1 Days')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="time_end" class="control-label">Sampai Jam <span class="text-danger">**</span></label>
                                    <input type="time" name="time_end" id="time_end" class="form-control" value="<?= date('H:i') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="comment" class="control-label">Catatan</label>
                            <textarea name="comment" id="comment" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-right">
                        <button type="button" class="btn btn-info" onclick="reseting()" id="btnReset"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;Reset</button>
                        <?php if ($created == 1) : ?>
                            <button type="button" class="btn btn-success" onclick="save()" id="btnSimpan"><i class="fa-regular fa-hard-drive"></i>&nbsp;&nbsp;Proses</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // first load
    $(document).ready(function() {
        initailizeSelect2_dokter_all();
        initailizeSelect2_all_cabang();

        fc_function();
    });

    // fungsi fullcalendar
    function fc_function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id', // ubah lokasi ke indonesia
            editable: true,
            headerToolbar: { // menampilkan button yang akan ditampilkan
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: { // merubah text button
                today: 'Hari ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            customButtons: { // merubah text button
                prev: {
                    text: 'Sebelumnya',
                    click: function() {
                        calendar.prev();
                    }
                },
                next: {
                    text: 'Berikutnya',
                    click: function() {
                        calendar.next();
                    }
                }
            },
            events: { // load data fullcalendar
                url: siteUrl + 'Health/jadwal_list',
                method: 'GET',
                failure: function() {
                    Swal.fire("Jadwal Dokter", "Gagal diload", "error");
                }
            },
            eventContent: function(arg) { // mengubah tanda koma (,) menjadi <br>
                let title = arg.event.title.split(',').join('<br>');
                return {
                    html: title
                };
            },
            eventDidMount: function(info) { //mengatur warna by status kehadiran
                switch (info.event.extendedProps.status_dokter) {
                    case '1': // Hadir
                        info.el.style.backgroundColor = '#007bff';
                        break;
                    case '2': // Izin
                        info.el.style.backgroundColor = '#ffd000';
                        break;
                    case '3': // Sakit
                        info.el.style.backgroundColor = '#ed1e32';
                        break;
                    case '4': // Cuti
                        info.el.style.backgroundColor = '#2aae47';
                        break;
                    default:
                        info.el.style.backgroundColor = '#76818d';
                }
            },
            eventDrop: function(info) { // fungsi update jadwal jika di drag
                $.ajax({
                    url: siteUrl + 'Health/jadwal_update',
                    type: 'POST',
                    data: {
                        kode_jadwal: info.event.id,
                        date_start: info.event.start.toISOString(),
                        date_end: info.event.end.toISOString(),
                        kode_dokter: info.event.extendedProps.kode_dokter
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.status == 1) {
                            Swal.fire("Jadwal Dokter", "Berhasil diubah", "success");
                        } else {
                            Swal.fire("Jadwal Dokter", "Gagal diubah", "error");
                        }
                    }
                });
            },
            eventResize: function(info) { // fungsi update jadwal jika di resize by date
                $.ajax({
                    url: siteUrl + 'Health/jadwal_update',
                    type: 'POST',
                    data: {
                        kode_jadwal: info.event.id,
                        date_start: info.event.start.toISOString(),
                        date_end: info.event.end.toISOString(),
                        kode_dokter: info.event.extendedProps.kode_dokter
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.status == 1) {
                            Swal.fire("Jadwal Dokter", "Berhasil diubah", "success");
                        } else {
                            Swal.fire("Jadwal Dokter", "Gagal diubah", "error");
                        }
                    }
                });
            },
            eventClick: function(info) { // fungsi hapus jadwal jika di klik
                Swal.fire({
                    title: 'Hapus Jadwal Dokter ' + info.event.extendedProps.nama_dokter,
                    text: "Hapus jadwal " + info.event.startStr + " sampai " + info.event.endStr + " ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: siteUrl + 'Health/jadwal_delete',
                            type: 'POST',
                            data: {
                                kode_jadwal: info.event.id,
                                kode_dokter: info.event.extendedProps.kode_dokter
                            },
                            dataType: 'JSON',
                            success: function(res) {
                                if (res.status == 1) {
                                    Swal.fire("Jadwal Dokter", "Berhasil dihapus", "success");
                                    info.event.remove();
                                } else {
                                    Swal.fire("Jadwal Dokter", "Gagal dihapus", "error");
                                }
                            }
                        });
                    }
                });
            }
        });
        calendar.render();
    }

    // set variable
    const form = $('#form_jadwal');
    var kodeJadwal = $('#kodeJadwal');
    var kode_dokter = $('#kode_dokter');
    var kode_cabang = $('#kode_cabang');
    var status_dokter = $('#status_dokter');
    var date_start = $('#date_start');
    var date_end = $('#date_end');
    var time_start = $('#time_start');
    var time_end = $('#time_end');
    var comment = $('#comment');

    // fungsi reset
    function reseting() { // membuat semua param kembali ke default
        kodeJadwal.val('');
        kode_dokter.val('').trigger('change');
        kode_cabang.val("<?= $this->session->userdata('cabang') ?>").trigger('change');
        status_dokter.val('1').trigger('change');
        date_start.val("<?= date('Y-m-d') ?>");
        date_end.val("<?= date('Y-m-d', strtotime('+1 Days')) ?>");
        time_start.val("<?= date('H:i') ?>");
        time_end.val("<?= date('H:i') ?>");
        comment.val('');
    }

    // fungsi simpan
    function save() {
        if (kode_dokter.val() == '' || kode_cabang.val() == '' || status_dokter.val() == '' || date_start.val() == '' || date_end.val() == '' || time_start.val() == '' || time_end.val() == '') { // cek data kosong
            return Swal.fire("Form Data", "Sudah diisi lengkap?", "question");
        }

        // jalankan fungsi
        $.ajax({
            url: siteUrl + 'Health/jadwal_insert',
            type: 'POST',
            data: form.serialize(),
            dataType: 'JSON',
            success: function(res) {
                if (res.status == 1) {
                    fc_function();
                    reseting();
                    Swal.fire("Jadwal Dokter", "Berhasil ditambahkan", "success");
                } else {
                    Swal.fire("Jadwal Dokter", "Gagal ditambahkan", "error");
                }
            }
        })
    }
</script>