<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div class="card px-4 py-4">
            <div class="card-body">
                <h4>Reset Password User :: <?= $user['name'] ?></h4>
                <hr />
                <form method="post" action="<?='/users/'.$user['id'].'/reset-password'?>">
                    <?php if (isset($user)): ?>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-4">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirm_password" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                            <div class="col-sm-4">
                                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Password">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group row">
                        <div class="col-sm-4 offset-sm-3">
                            <button class="btn btn-success">Simpan</button>
                            <a class="btn btn-warning" href="/users" >Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
