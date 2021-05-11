<?php
$level              = check_level();
?>

<header class="page-title-bar mb-3">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>"><span class="fa fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('invoice'); ?>">Faktur</a>
            </li>
            <li class="breadcrumb-item">
                <a class="text-muted">
                    <?= $invoice->number ?></a>
            </li>
        </ol>
    </nav>
</header>

<div class="page-section">
    <section
        id="data-invoice"
        class="card"
    >
        <div class="card-body">
            <?php //=isset($input->draft_id) ? form_hidden('draft_id', $input->draft_id) : ''; ?>
            <div>
                <!-- book-data -->
                <div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td width="200px"> Nomor Faktur </td>
                                    <td><strong><?= $invoice->number ?></strong> </td>
                                </tr>
                                <tr>
                                    <td width="200px"> Tipe </td>
                                    <td><?= get_invoice_type()[$invoice->type]; ?></td>
                                </tr>
                                <tr>
                                    <td width="200px"> Nama Customer </td>
                                    <td><?= $invoice->customer->name ?? 'Umum' ?></td>
                                </tr>
                                <tr>
                                    <td width="200px"> Nomor Customer </td>
                                    <td><?= $invoice->customer->phone_number ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td width="200px"> Tanggal Jatuh Tempo </td>
                                    <td><?= $invoice->due_date ?></td>
                                </tr>
                                <tr>
                                    <td width="200px"> Total Berat </td>
                                    <td><?= $invoice->total_weight ?></td>
                                </tr>
                                <tr>
                                    <td width="200px"> Total Ongkir </td>
                                    <td><?= $invoice->delivery_fee ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td width="200px"> Tanggal dibuat </td>
                                    <td><?= $invoice->issued_date ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td class="align-middle" width="200px"> Status </td>
                                    <td class="align-middle"><?= get_invoice_status()[$invoice->status]; ?></td>
                                    <td class="align-middle text-right"><button class="btn btn-outline-primary" data-toggle="collapse" href="#collapse1">Detail</btn></td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="collapse1" class="panel-collapse collapse">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td class="align-middle" width="200px"> Tanggal Konfirmasi </td>
                                        <td class="align-middle"><?= $invoice->confirm_date ?></td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle" width="200px"> Tanggal Mulai Diproses </td>
                                        <td class="align-middle"><?= $invoice->preparing_start_date ?></td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle" width="200px"> Tanggal Selesai Diproses </td>
                                        <td class="align-middle"><?= $invoice->preparing_end_date ?></td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle" width="200px"> Tanggal Diambil Pemasaran </td>
                                        <td class="align-middle"><?= $invoice->finish_date ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>

                <table class="table table-striped mb-0">
                    <thead>
                        <tr class="text-center">
                            <th
                                scope="col"
                                style="width:2%;"
                            >No</th>
                            <th
                                scope="col"
                                style="width:30%;"
                            >Judul Buku</th>
                            <th
                                scope="col"
                                style="width:25%;"
                            >Nama Penulis</th>
                            <th
                                scope="col"
                                style="width:15%;"
                            >Harga</th>
                            <th
                                scope="col"
                                style="width:10%;"
                            >Jumlah</th>
                            <th
                                scope="col"
                                style="width:5%;"
                            >Diskon</th>
                            <th
                                scope="col"
                                style="width:15%;"
                            >Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($invoice_books as $invoice_book) : ?>
                    <?php $i++; ?>
                        <tr class="text-center">
                            <td class="align-middle pl-4">
                                <?= $i ?>
                            </td>
                            <td class="text-left align-middle">
                                <?= $invoice_book->book_title ?>
                            </td>
                            <td class="align-middle">
                                <?= $invoice_book->author_name ?>
                            </td>
                            <td class="align-middle">
                                Rp <?= $invoice_book->price ?>
                            </td>
                            <td class="align-middle">
                                <?= $invoice_book->qty ?>
                            </td>
                            <td class="align-middle">
                                <?= $invoice_book->discount ?> %
                            </td>
                            <td class="align-middle">
                                Rp <?= $invoice_book->price * $invoice_book->qty * (1 - $invoice_book->discount/100) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
				<br>
                <?php if ($invoice->status != 'waiting' && $invoice->status != 'cancel'): ?>
                    <div id="card-button" class="d-flex justify-content-end">
                        <button onclick="check_delivery()" class="btn btn-outline-danger">
                            Generate PDF<i class="fas fa-file-pdf fa-fw"></i>
                        </button>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </section>
</div>
<div
    class="modal modal-alert fade"
    id="modal-delivery"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modal-delivery"
    aria-hidden="true"
>
    <div
        class="modal-dialog"
        role="document"
    >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ongkos Kirim</h5>
            </div>
                <div class="modal-body">
                    <div class="my-2">
                        Total Berat: <b><?= $invoice->total_weight / 1000 ?></b> kg
                    </div>
                    <div class="my-2">
                        Alamat Customer:
                    </div>
                    <div class="mb-2">
                        <?= $invoice->customer->address ?? '-' ?>
                    </div>
                    <div class="form-group">
                        <label
                            for="delivery_fee"
                            class="font-weight-bold"
                        >
                            Masukkan Ongkos Kirim
                            <abbr title="Required">*</abbr>
                        </label>
                        <input
                            type="number"
                            name="delivery_fee"
                            id="delivery"
                            min=0
                            required
                            class="form-control"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="submit"
                        class="btn btn-primary"
                        onclick="save_delivery_fee()"
                    >Save</button>
                    <button
                        type="button"
                        class="btn btn-light"
                        data-dismiss="modal"
                    >Close</button>
                </div>
        </div>
    </div>
</div>
<script>
    function check_delivery(){
        var ongkir = "<?= $invoice->delivery_fee ?>"
        if (ongkir == ''){
            $("#modal-delivery").modal()
        }else
        {
            location.href="<?= base_url("invoice/generate_pdf/" . $invoice->invoice_id); ?>"
        }
    }

    function save_delivery_fee(){
        if ($("#delivery").val() == '') {
            alert("Ongkir wajib diisi!");
        }
        else {
            var ongkir = $("#delivery").val()
            location.href="<?= base_url("invoice/generate_pdf/" . $invoice->invoice_id); ?>" + '/' + ongkir
        }
    }
</script>