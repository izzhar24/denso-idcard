<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div class="card px-4 py-4">
            <div class="card-body">
                <h4><?= isset($user) ? 'Edit' : 'Tambah' ?> User</h4>
                <hr />
                <form method="post" action="<?= isset($user) ? '/users/' . $user['id'] . '/update' : '/users' ?>">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-4">
                            <input type="text" name="name" class="form-control" value="<?= $user['name'] ?? '' ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-4">
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?? '' ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Role</label>
                        <div class="col-sm-4">
                            <select name="role" class="form-control" required>
                                <option value="admin" <?= isset($user) && $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="user" <?= isset($user) && $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            </select>
                        </div>
                    </div>
                    <?php if (!isset($user)): ?>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-4">
                                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group row">
                        <div class="col-sm-4 offset-sm-2">
                            <button class="btn btn-success">Simpan</button>
                            <a class="btn btn-warning" href="/users" >Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
