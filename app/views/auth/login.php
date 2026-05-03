<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h3 class="mb-3">Login</h3>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if (!empty($info)): ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars($info); ?></div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="post" action="/quest/public/auth/login">
                    <div class="mb-3">
                        <label class="form-label">Email or Student ID</label>
                        <input type="text" name="identifier" class="form-control" value="<?php echo htmlspecialchars($old['identifier'] ?? ''); ?>" required>
                        <div class="form-text">Example student ID: 241c-1234</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="mt-3 mb-0 text-muted">Need an account? Contact the admin.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
