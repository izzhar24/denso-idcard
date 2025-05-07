<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div class="card px-4 py-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <h3>Employee</h3>
                    <?php  if(($_SESSION['user']['role'] ) == "admin") { ?>
                    <a href="/employees/create" class="btn btn-sm btn-primary">Create Employee</a>
                    <?php } ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col">No</th>
                            <th scope="col">NPK</th>
                            <th scope="col">Name</th>
                            <th scope="col">Company</th>
                            <th scope="col">Plant</th>
                            <th scope="col">BU Code</th>
                            <th scope="col">BU Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $start = ($currentPage - 1) * $perPage;
                            if (empty($employees)) { ?>
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($employees as $key => $employee) { ?>
                                    <tr>
                                        <td><?= $start + $key + 1 ?></td>
                                        <td><?= $employee['npk'] ?></td>
                                        <td><?= $employee['name'] ?></td>
                                        <td><?= $employee['company'] ?></td>
                                        <td><?= $employee['plant'] ?></td>
                                        <td><?= $employee['kd_bu'] ?></td>
                                        <td><?= $employee['nm_bu'] ?></td>
                                        <td><?= $employee['status_karyawan'] ?></td>
                                        <td>
                                        <?php  if(($_SESSION['user']['role'] ) == "admin") { ?>
                                            <a href="/employees/<?= $employee['id'] ?>/edit" class="btn btn-warning btn-sm">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger"
                                                data-toggle="modal"
                                                data-target="#confirmDeleteModal"
                                                onclick="setDeleteUrl('/employees/<?= $employee['id'] ?>/delete')">
                                                <i class="bx bx-trash"></i>
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


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteForm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
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
</script>
<?php endPush() ?>