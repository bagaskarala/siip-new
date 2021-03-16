<?php
$is_wrapping_started       = format_datetime($book_receive->wrapping_start_date);
$is_wrapping_finished      = format_datetime($book_receive->wrapping_end_date);
$is_wrapping_deadline_set  = format_datetime($book_receive->wrapping_deadline);
$staff_gudang              = $this->book_receive->get_staff_gudang_by_progress('wrapping', $book_receive->book_receive_id);

?>
<section id="wrapping-progress-wrapper" class="card">
    <div id="wrapping-progress">
        <header class="card-header">
            <div class="d-flex align-items-center"><span class="mr-auto">Wrapping</span>
                <?php if (!$is_final) :
                        //modal select
                        $this->load->view('book_receive/view/common/select_modal', [
                            'progress' => 'wrapping',
                            'staff_gudang' => $staff_gudang
                        ]);
                    ?>
                <div class="card-header-control">
                    <button id="btn-start-wrapping" title="Mulai proses wrapping" type="button"
                        class="d-inline btn 
                        <?= !$is_wrapping_started ? 'btn-warning' : 'btn-secondary'; ?> <?= ($is_wrapping_started || !$is_wrapping_deadline_set) ? 'btn-disabled' : ''; ?>
                        "
                        <?= ($is_wrapping_started || !$is_wrapping_deadline_set) ? 'disabled' : ''; ?>><i class="fas fa-play"></i><span class="d-none d-lg-inline"> Mulai</span></button>
                    <button id="btn-finish-wrapping" title="Selesai proses wrapping" type="button"
                        class="d-inline btn btn-secondary <?= !$is_wrapping_started ? 'btn-disabled' : '' ?>"
                        <?= !$is_wrapping_started ? 'disabled' : '' ?>><i class="fas fa-stop"></i><span
                            class="d-none d-lg-inline"> Selesai</span></button>
                </div>
                <?php endif ?>
            </div>
        </header>

        <!-- ALERT -->
        <?php 
            $this->load->view('book_receive/view/common/progress_alert', [
                'progress'          => 'wrapping',
                'staff_gudang'  => $staff_gudang
            ]); 
            ?>

        <div class="list-group list-group-flush list-group-bordered" id="list-group-wrapping">
            <div class="list-group-item justify-content-between">
                <span class="text-muted">Status</span>
                <span class="font-weight-bold">
                    <?php if ($book_receive->is_wrapping) : ?>
                    <span class="text-success">
                        <i class="fa fa-check"></i>
                        <span>Selesai</span>
                    </span>
                    <?php elseif (!$book_receive->is_wrapping && $book_receive->book_receive_status == 'reject') : ?>
                    <span class="text-danger">
                        <i class="fa fa-times"></i>
                        <span>Ditolak</span>
                    </span>
                    <?php elseif (!$book_receive->is_wrapping && $book_receive->wrapping_start_date) : ?>
                    <span class="text-primary">
                        <span>Sedang Diproses</span>
                    </span>
                    <?php endif ?>
                </span>
            </div>

            <div class="list-group-item justify-content-between">
                <span class="text-muted">Tanggal mulai</span>
                <strong>
                    <?= format_datetime($book_receive->wrapping_start_date); ?></strong>
            </div>

            <div class="list-group-item justify-content-between">
                <span class="text-muted">Tanggal selesai</span>
                <strong>
                    <?= format_datetime($book_receive->wrapping_end_date); ?></strong>
            </div>

            <div class="list-group-item justify-content-between">
                <?php //if (($_SESSION['level'] == 'superadmin' || ($_SESSION['level'] == 'admin_gudang' && empty($book_receive->wrapping_deadline))) && $staff_gudang && !$is_final) : ?>
                <a href="#" id="btn-modal-deadline-wrapping" title="Ubah deadline" data-toggle="modal"
                    data-target="#modal-deadline-wrapping">Deadline <i class="fas fa-edit fa-fw"></i></a>
                <!-- <?php //else : ?>
                <span class="text-muted">Deadline</span>
                <?php //endif ?> -->
                <strong><?= format_datetime($book_receive->wrapping_deadline); ?></strong>
            </div>

            <?php if ($staff_gudang) : ?>
            <div class="list-group-item justify-content-between">
                <span class="text-muted">Staff Bertugas</span>
                <strong>
                    <?php foreach ($staff_gudang as $staff) : ?>
                    <span class="badge badge-info p-1"><?= $staff->username; ?></span>
                    <?php endforeach; ?>
                </strong>
            </div>
            <?php endif; ?>

            <?php if ($book_receive->total) : ?>
            <div class="list-group-item justify-content-between">
                <span class="text-muted">Hasil Jilid</span>
                <strong id="total-wrapping"><?= $book_receive->total; ?></strong>
            </div>
            <?php endif; ?>

            <div class="m-3">
                <div class="text-muted pb-1">Catatan Admin</div>
                <?= $book_receive->wrapping_notes_admin ?>
            </div>
            <hr class="m-0">
        </div>

        <div class="card-body">
            <div class="card-button">
                <!-- button aksi -->
                <?php if (($_SESSION['level'] == 'superadmin' || $_SESSION['level'] == 'admin_gudang') && !$is_final) : ?>
                <button title="Aksi admin"
                    class="btn btn-outline-dark <?= !$book_receive->total ? 'btn-disabled' : ''; ?>"
                    data-toggle="modal" data-target="#modal-action-wrapping"
                    <?= !$book_receive->total ? 'disabled' : ''; ?>>Aksi</button>
                <?php endif; ?>

                <!-- button tanggapan wrapping -->
                <button type="button" class="btn btn-outline-success" data-toggle="modal"
                    data-target="#modal-wrapping-notes">Catatan</button>
            </div>
        </div>

        <?php
            // modal deadline
            $this->load->view('book_receive/view/common/deadline_modal', [
                'progress' => 'wrapping',
            ]);

            // modal action
            $this->load->view('book_receive/view/common/action_modal', [
                'progress' => 'wrapping',
            ]);

            // modal note
            $this->load->view('book_receive/view/common/notes_modal', [
                'progress' => 'wrapping',
            ]);
            ?>
    </div>
</section>

<script>
$(document).ready(function() {
    const book_receive_id = '<?= $book_receive->book_receive_id ?>'

    // inisialisasi segment
    reload_wrapping_segment()

    // ketika load segment, re-initialize call function-nya
    function reload_wrapping_segment() {
        $('#wrapping-progress-wrapper').load(' #wrapping-progress', function() {
            // reinitiate modal after load
            initFlatpickrModal()
        });
    }

    // mulai wrapping
    $('#wrapping-progress-wrapper').on('click', '#btn-start-wrapping', function() {
        $.ajax({
            type: "POST",
            url: "<?//= base_url('book_receive/api_start_progress/'); ?>" + book_receive_id,
            datatype: "JSON",
            data: {
                progress: 'wrapping'
            },
            success: function(res) {
                showToast(true, res.data);
            },
            error: function(err) {
                showToast(false, err.responseJSON.message);
            },
            complete: function() {
                // reload segmen wrapping
                reload_wrapping_segment()
                // reload progress
                $('#progress-list-wrapper').load(' #progress-list');
                // reload data 
                $('#book-receive-data-wrapper').load(' #book-receive-data');
            },
        })
    })

    // selesai wrapping
    $('#wrapping-progress-wrapper').on('click', '#btn-finish-wrapping', function() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('book_receive/api_finish_progress/'); ?>" + book_receive_id,
            datatype: "JSON",
            data: {
                progress: 'wrapping'
            },
            success: function(res) {
                showToast(true, res.data);
            },
            error: function(err) {
                showToast(false, err.responseJSON.message);
            },
            complete: function() {
                // reload segmen wrapping
                reload_wrapping_segment()
                // reload progress
                $('#progress-list-wrapper').load(' #progress-list');
                // reload data
                $('#book-receive-data-wrapper').load(' #book-receive-data');
            },
        })
    })
})
</script>