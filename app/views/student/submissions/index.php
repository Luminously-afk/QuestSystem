<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">My Submissions</h3>

<?php if (isset($_GET['success']) && $_GET['success'] === 'submitted'): ?>
    <div class="alert alert-success">Submission sent successfully.</div>
<?php endif; ?>

<?php if (empty($submissions)): ?>
    <div class="alert alert-info">You have not submitted any quests yet.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Quest</th>
                    <th>Points</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Submitted</th>
                    <th>Reviewed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['title']); ?></td>
                        <td><?php echo htmlspecialchars($submission['points']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $submission['status'] === 'approved' ? 'success' : ($submission['status'] === 'rejected' ? 'danger' : 'secondary'); ?>">
                                <?php echo htmlspecialchars($submission['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($submission['remarks'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($submission['submitted_at']))); ?></td>
                        <td><?php echo htmlspecialchars($submission['reviewed_at'] ? date('Y-m-d H:i', strtotime($submission['reviewed_at'])) : '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
