<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;
?>

<style>
    :root {
        --fc-border-color: #e9ecef;
        --fc-daygrid-event-dot-width: 5px;
        --fc-button-primary: #007bff;
    }

    .select2-selection__rendered {
        line-height: 20px !important;
    }

    .select2-container .select2-selection--single {
        height: 30px !important;
    }

    .select2-selection__arrow {
        height: 20px !important;
    }
</style>

<form method="post" id="form_jadwal">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span class="font-weight-bold h4"><i class="fa-solid fa-bookmark text-primary"></i> Jadwal Dokter</span>
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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="id" class="control-label">ID <span class="text-danger">**</span></label>
                                    <input type="text" class="form-control" id="kodeJadwal" name="kodeJadwal" placeholder="Otomatis" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="kode_dokter" class="control-label">Dokter <span class="text-danger">**</span></label>
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
                                    <select name="kode_cabang" id="kode_cabang" class="form-control select2_all_cabang" data-placeholder="~ Pilih Cabang"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="status_dokter" class="control-label">Status <span class="text-danger">**</span></label>
                                    <select name="status_dokter" id="status_dokter" class="form-control select2_global" data-placeholder="~ Pilih Status">
                                        <option value="">~ Pilih Status</option>
                                        <option value="1">Hadir</option>
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
                            <label for="comment" class="control-label">Catatan / Judul</label>
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
    $(document).ready(function() {
        initailizeSelect2_dokter_all();
        initailizeSelect2_all_cabang();

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            editable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
        });
        calendar.render();
    });

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

    function reseting() {
        kodeJadwal.val('');
        kode_dokter.val('').trigger('change');
        kode_cabang.val('').trigger('change');
        status_dokter.val('').trigger('change');
        date_start.val("<?= date('Y-m-d') ?>");
        date_end.val("<?= date('Y-m-d', strtotime('+1 Days')) ?>");
        time_start.val("<?= date('H:i') ?>");
        time_end.val("<?= date('H:i') ?>");
        comment.val('');
    }

    function save() {
        if (kode_dokter.val() == '' || kode_cabang.val() == '' || status_dokter.val() == '' || date_start.val() == '' || date_end.val() == '' || time_start.val() == '' || time_end.val() == '') {
            return Swal.fire("Form Data", "Sudah diisi lengkap?", "question");
        }

        $.ajax({
            url: siteUrl + 'Health/Jadwal_dokter_proses',
            type: 'POST',
            data: form.serialize(),
            dataType: 'JSON',
            success: function(res) {
                var response = JSON.parse(res)
                if (response.status == 'success') {
                    toastr.success(response.message)
                    reseting()
                } else {
                    toastr.error(response.message)
                }
            }
        })
    }
</script>