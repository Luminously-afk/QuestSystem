<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Create Student Account</h3>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (!empty($generated_password)): ?>
    <div class="alert alert-warning">
        Temporary Password: <strong><?php echo htmlspecialchars($generated_password); ?></strong><br>
        The student must change this password at first login.
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="/quest/public/admin/createStudent">
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input type="text" name="student_id" class="form-control" value="<?php echo htmlspecialchars($old['student_id'] ?? ''); ?>" required>
        <div class="form-text">Format: 241c-1234</div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Create Account</button>
        <a class="btn btn-secondary" href="/quest/public/admin/students">Back</a>
    </div>
</form>

<?php require_once '../app/views/layouts/footer.php'; ?>
