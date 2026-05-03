<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Change Password</h3>

<?php if (!empty($first_login)): ?>
    <div class="alert alert-warning">You must change your password before continuing.</div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="/quest/public/auth/changePassword">
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
        <div class="form-text">At least 6 characters.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Password</button>
        <a class="btn btn-secondary" href="/quest/public/auth/logout">Logout</a>
    </div>
</form>

<?php require_once '../app/views/layouts/footer.php'; ?>
