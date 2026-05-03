<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Student Accounts</h3>
<p class="mb-3">Create student accounts and view existing students.</p>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a class="btn btn-primary" href="/quest/public/admin/createStudent">Create Student Account</a>
</div>

<?php if (empty($students)): ?>
    <div class="alert alert-info">No students found.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <th>Total Points</th>
                    <th>Must Change Password</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['student_id'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['total_points']); ?></td>
                        <td>
                            <?php if ((int) $student['must_change_password'] === 1): ?>
                                <span class="badge bg-warning">yes</span>
                            <?php else: ?>
                                <span class="badge bg-success">no</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($student['created_at']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
