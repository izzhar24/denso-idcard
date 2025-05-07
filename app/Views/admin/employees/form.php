<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div class="card px-4 py-4">
            <div class="card-body">
                <h4><?= isset($employee) ? 'Edit' : 'Tambah' ?> Employee</h4>
                <hr />
                <form method="post" action="<?= isset($employee) ? '/employees/' . $employee['id'] . '/update' : '/employees' ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group row">
                                <label for="npk" class="col-sm-4 col-form-label">NPK</label>
                                <div class="col-sm-8">
                                    <input type="text" name="npk" class="form-control" value="<?= $employee['npk'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" class="form-control" value="<?= $employee['name'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-4 col-form-label">Company</label>
                                <div class="col-sm-8">
                                    <input type="text" name="company" class="form-control" value="<?= $employee['company'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="plant" class="col-sm-4 col-form-label">Plant</label>
                                <div class="col-sm-8">
                                    <input type="text" name="plant" class="form-control" value="<?= $employee['plant'] ?? '' ?>" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8 offset-sm-4">
                                    <button class="btn btn-success">Simpan</button>
                                    <a class="btn btn-warning" href="/employees">Batal</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group row">
                                <label for="kd_bu" class="col-sm-4 col-form-label">Bussiness Unit Code</label>
                                <div class="col-sm-8">
                                    <input type="text" name="kd_bu" class="form-control" value="<?= $employee['kd_bu'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nm_bu" class="col-sm-4 col-form-label">Bussiness Unit Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="nm_bu" class="form-control" value="<?= $employee['nm_bu'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select name="status_karyawan" class="form-control" required>
                                        <option value="">Silahkan Pilih</option>
                                        <option value="KONTRAK" <?= isset($employee) && $employee['status_karyawan'] == 'KONTRAK' ? 'selected' : '' ?>>KONTRAK</option>
                                        <option value="TETAP" <?= isset($employee) && $employee['status_karyawan'] == 'TETAP' ? 'selected' : '' ?>>TETAP</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>