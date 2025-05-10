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
                            <?php
                            } else {
                            ?>
                                <?php foreach ($requestEmployeeCards as $key => $requestEmployeeCard) {

                                    $status = $requestEmployeeCard['status'];
                                    $badgeStatus = '';
                                    switch ($status) {
                                        case 'APPROVED':
                                            $badgeStatus = 'success';
                                            break;
                                        case 'REJECTED':
                                            $badgeStatus = 'danger';
                                            break;
                                        default:
                                            $badgeStatus = 'primary';
                                            break;
                                    }
                                ?>
                                    <tr>
                                        <td><?= $start + $key + 1 ?></td>
                                        <td><?= $requestEmployeeCard['employee']['name'] ?></td>
                                        <td><?= $requestEmployeeCard['reason'] ?></td>
                                        <td>
                                            <span class="badge badge-<?= $badgeStatus ?>">
                                                <?= $requestEmployeeCard['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            if (($_SESSION['user']['role']) == "admin") {
                                                if ($requestEmployeeCard['status'] == 'PENDING') { ?>
                                                    <button
                                                        class="btn btn-primary btn-sm" title="Approve & Print Out"
                                                        data-toggle="modal"
                                                        data-target="#confirmPrintModal"
                                                        data-id="<?= $requestEmployeeCard['id'] ?>"
                                                        data-npk="<?= $requestEmployeeCard['employee']['npk'] ?>"
                                                        data-name="<?= $requestEmployeeCard['employee']['nickname'] ?>"
                                                        data-photo="<?= asset($requestEmployeeCard['employee_card']['selected_photo_path']) ?>"
                                                        data-bg="<?= asset($requestEmployeeCard['template']['image_path']) ?>">
                                                        <i class="bx bx-printer"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" title="Reject"
                                                        data-toggle="modal"
                                                        data-target="#confirmRejectModal"
                                                        onclick="setDeleteUrl('/employee-request-cards/<?= $requestEmployeeCard['id'] ?>/reject')">
                                                        <i class="bx bx-x"></i>
                                                    </button>
                                            <?php }
                                            } ?>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
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
    <div class="modal-dialog modal-dialog-centered" role="document">
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

    let printData = {};
    const modalPrint = document.getElementById('confirmPrintModal');
    $('#confirmPrintModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget); // Button yang memicu modal
        printData = {
            id: button.data('id'),
            npk: button.data('npk'),
            name: button.data('name'),
            photo: button.data('photo'),
            bg: button.data('bg'),
            approveUrl: button.data('approve-url')
        };

    })

    function confirmPrint() {
        $.ajax({
            url: '/employee-request-cards/approve',
            type: 'POST',
            data: {
                id: printData.id
            },
            success: function(response) {
                // console.log("Response: " + JSON.stringify(response));
                $('#confirmPrintModal').modal('hide');
                openPrintWindow(printData);
                window.addEventListener("message", (e) => {
                    if (e.data === "refresh") location.reload();
                });
            },
            error: function(err) {
                console.log("error", err);
            },
        });
    }

    function openPrintWindow(data) {
        const win = window.open('', '_blank', 'width=800,height=600');

        win.document.write(`
            <html>
            <head>
                <style>
                body { margin: 0; padding: 0; }
                .idcard-container {
                    width: 53.98mm; height: 85.6mm;
                    font-family: Arial; position: relative;
                }
                .idcard-bg {
                    position: absolute;
                    width: 100%; height: 100%;
                    object-fit: cover;
                }
                .idcard-content {
                    position: absolute;
                    top: 20mm;
                    left: 35%; 
                    transform: translateX(-50%);
                    text-align: center;
                }
                .idcard-photo {
                    width: 25mm; height: 35mm;
                    object-fit: cover; margin-bottom: 2mm;
                }
                .idcard-info {
                    font-size: 3mm;
                    font-weight: bold;
                    color: black;
                }
                @page {
                    size: 53.98mm 85.6mm;
                    margin: 0;
                }
                </style>
            </head>
            <body>
                <div class="idcard-container">
                    <img class="idcard-bg" src="${data.bg}">
                    <div class="idcard-content">
                        <img class="idcard-photo" src="${data.photo}">
                        <div class="idcard-info">${data.name}</div>
                        <div class="idcard-info">${data.npk}</div>
                    </div>
                </div>
            </body>
        </html>
        `);
        win.document.close();

        win.onload = function() {
            win.focus();
            win.print();
            win.close();
            if (win.opener) {
                win.opener.location.reload();
            }
        };
    }
</script>
<?php endPush() ?>