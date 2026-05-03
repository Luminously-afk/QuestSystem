<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Rewards</h3>
<p class="mb-3">Your points: <strong><?php echo htmlspecialchars($user_points); ?></strong></p>

<?php if (isset($_GET['success']) && $_GET['success'] === 'requested'): ?>
    <div class="alert alert-success">Redemption request submitted.</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <?php if ($_GET['error'] === 'not_enough_points'): ?>
        <div class="alert alert-warning">You do not have enough points for that reward.</div>
    <?php elseif ($_GET['error'] === 'already_requested'): ?>
        <div class="alert alert-warning">You already requested this reward.</div>
    <?php elseif ($_GET['error'] === 'not_available'): ?>
        <div class="alert alert-warning">This reward is currently unavailable.</div>
    <?php else: ?>
        <div class="alert alert-danger">Request failed. Please try again.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($rewards)): ?>
    <div class="alert alert-info">No rewards available right now.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Reward</th>
                    <th>Required Points</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rewards as $reward): ?>
                    <?php
                        $redemption = $redemption_map[$reward['reward_id']] ?? null;
                        $redemptionStatus = $redemption['status'] ?? null;
                        $canRequest = ($reward['status'] === 'available')
                            && ($redemptionStatus === null || $redemptionStatus === 'rejected')
                            && ($user_points >= (int) $reward['required_points']);
                    ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($reward['reward_name']); ?>
                            <div class="small text-muted"><?php echo htmlspecialchars($reward['description']); ?></div>
                            <?php if ($redemptionStatus === 'rejected' && !empty($redemption['remarks'])): ?>
                                <div class="small text-danger">Last remarks: <?php echo htmlspecialchars($redemption['remarks']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($reward['required_points']); ?></td>
                        <td>
                            <?php if ($redemptionStatus === 'approved'): ?>
                                <span class="badge bg-success">approved</span>
                            <?php elseif ($redemptionStatus === 'pending'): ?>
                                <span class="badge bg-warning">pending</span>
                            <?php elseif ($redemptionStatus === 'rejected'): ?>
                                <span class="badge bg-danger">rejected</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">available</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if ($canRequest): ?>
                                <form method="post" action="<?php echo BASE_URL; ?>/student/requestReward/<?php echo $reward['reward_id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Request</button>
                                </form>
                            <?php elseif ($redemptionStatus === 'pending'): ?>
                                <span class="text-muted">Requested</span>
                            <?php elseif ($redemptionStatus === 'approved'): ?>
                                <span class="text-success">Approved</span>
                            <?php elseif ($user_points < (int) $reward['required_points']): ?>
                                <span class="text-muted">Not enough points</span>
                            <?php else: ?>
                                <span class="text-muted">Unavailable</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
