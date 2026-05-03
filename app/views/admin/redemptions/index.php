<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Reward Redemptions</h3>
<p class="mb-3">Review redemption requests and approve or reject them.</p>

<?php if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] === 'approved'): ?>
        <div class="alert alert-success">Redemption approved.</div>
    <?php elseif ($_GET['success'] === 'rejected'): ?>
        <div class="alert alert-success">Redemption rejected.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <?php if ($_GET['error'] === 'not_enough_points'): ?>
        <div class="alert alert-warning">Student does not have enough points.</div>
    <?php elseif ($_GET['error'] === 'not_pending'): ?>
        <div class="alert alert-warning">This redemption was already reviewed.</div>
    <?php elseif ($_GET['error'] === 'invalid'): ?>
        <div class="alert alert-danger">Invalid request.</div>
    <?php else: ?>
        <div class="alert alert-danger">Something went wrong. Please try again.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($redemptions)): ?>
    <div class="alert alert-info">No redemptions found.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Reward</th>
                    <th>Required Points</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redemptions as $redemption): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($redemption['full_name']); ?><br>
                            <small class="text-muted"><?php echo htmlspecialchars($redemption['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($redemption['reward_name']); ?></td>
                        <td><?php echo htmlspecialchars($redemption['required_points']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $redemption['status'] === 'approved' ? 'success' : ($redemption['status'] === 'rejected' ? 'danger' : 'secondary'); ?>">
                                <?php echo htmlspecialchars($redemption['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($redemption['requested_at']))); ?></td>
                        <td class="text-end">
                            <?php if ($redemption['status'] === 'pending'): ?>
                                <form method="post" action="/quest/public/admin/reviewRedemption" class="d-flex flex-column gap-2">
                                    <input type="hidden" name="redemption_id" value="<?php echo $redemption['redemption_id']; ?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="approved">Approve</option>
                                        <option value="rejected">Reject</option>
                                    </select>
                                    <input type="text" name="remarks" class="form-control form-control-sm" placeholder="Remarks (optional)">
                                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                </form>
                            <?php else: ?>
                                <div class="small text-muted">
                                    Reviewed on <?php echo htmlspecialchars($redemption['reviewed_at'] ? date('Y-m-d H:i', strtotime($redemption['reviewed_at'])) : '-'); ?>
                                </div>
                                <?php if (!empty($redemption['remarks'])): ?>
                                    <div class="small text-muted">Remarks: <?php echo htmlspecialchars($redemption['remarks']); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
