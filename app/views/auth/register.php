<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h3 class="mb-3">Register</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="post" action="<?php echo BASE_URL; ?>/auth/register">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Create Account</button>
                </form>

                <p class="mt-3 mb-0">Already have an account? <a href="<?php echo BASE_URL; ?>/auth/login">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
