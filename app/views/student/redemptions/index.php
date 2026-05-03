<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">My Redemptions</h3>

<?php if (empty($redemptions)): ?>
    <div class="alert alert-info">You have not requested any rewards yet.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Reward</th>
                    <th>Required Points</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Requested</th>
                    <th>Reviewed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redemptions as $redemption): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($redemption['reward_name']); ?></td>
                        <td><?php echo htmlspecialchars($redemption['required_points']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $redemption['status'] === 'approved' ? 'success' : ($redemption['status'] === 'rejected' ? 'danger' : 'secondary'); ?>">
                                <?php echo htmlspecialchars($redemption['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($redemption['remarks'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($redemption['requested_at']))); ?></td>
                        <td><?php echo htmlspecialchars($redemption['reviewed_at'] ? date('Y-m-d H:i', strtotime($redemption['reviewed_at'])) : '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
