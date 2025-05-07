<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div class="card px-4 py-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <h3>Request Employee Card</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col">No</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $start = ($currentPage - 1) * $perPage;
                            if (empty($requestEmployeeCards)) { ?>
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($requestEmployeeCards as $key => $requestEmployeeCard) { ?>
                                    <tr>
                                        <td><?= $start + $key + 1 ?></td>
                                        <td><?= $requestEmployeeCard['employee_card_id'] ?></td>
                                        <td><?= $requestEmployeeCard['reason'] ?></td>
                                        <td><?= $requestEmployeeCard['status'] ?></td>
                                        <td>
                                            <?php if ($requestEmployeeCard['status'] !== 'APPROVED') { ?>
                                                <button
                                                    class="btn btn-primary btn-sm" title="Approve & Print Out"
                                                    data-toggle="modal"
                                                    data-target="#confirmPrintModal"
                                                    onclick="setDeleteUrl('/employee-request-cards/<?= $requestEmployeeCard['id'] ?>/approve')">
                                                    <i class="bx bx-printer"></i>
                                                </button>
                                            <?php } ?>
                                            <?php if ($requestEmployeeCard['status'] !== 'REJECTED') { ?>
                                                <a href="#" class="btn btn-sm btn-danger" title="Reject"
                                                    data-toggle="modal"
                                                    data-target="#confirmRejectModal"
                                                    onclick="setDeleteUrl('/employee-request-cards/<?= $requestEmployeeCard['id'] ?>/reject')">
                                                    <i class="bx bx-x"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <?php include __DIR__ . '/../../partials/pagination.php'; ?>
            </div>
        </div>
    </div>

</section>

<!-- Print Confirmation Modal -->
<div class="modal fade" id="confirmPrintModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Cetak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mencetak ID Card ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="confirmPrint()">Ya, Cetak</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmRejectModal" tabindex="-1" aria-labelledby="confirmRejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteForm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmRejectModalLabel">Konfirmasi Reject</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menolak pengajuan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php startPush('scripts') ?>
<script>
    function setDeleteUrl(url) {
        document.getElementById('deleteForm').setAttribute('action', url);
    }


    function confirmPrint() {
        $('#confirmPrintModal').modal('hide');
    }
</script>
<?php endPush() ?>