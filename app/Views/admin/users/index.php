<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div class="card px-4 py-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <h3>Users</h3>
                    <?=  ($_SESSION['user']['role'] ) == "admin"  ? '<a href="/users/create" class="btn btn-sm btn-primary">Create User</a>':''; ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $key => $user) { ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['role'] ?></td>
                                    <td>
                                        <?php  if(($_SESSION['user']['role'] ) == "admin") { ?>
                                        <a href="/users/<?= $user['id'] ?>/edit" class="btn btn-warning btn-sm">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger"
                                            data-toggle="modal"
                                            data-target="#confirmDeleteModal"
                                            onclick="setDeleteUrl('/users/<?= $user['id'] ?>/delete')">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
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