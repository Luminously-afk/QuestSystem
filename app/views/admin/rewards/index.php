<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Reward Management</h3>
<p class="mb-3">Create and manage rewards students can redeem.</p>

<?php if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] === 'created'): ?>
        <div class="alert alert-success">Reward created successfully.</div>
    <?php elseif ($_GET['success'] === 'updated'): ?>
        <div class="alert alert-success">Reward updated successfully.</div>
    <?php elseif ($_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success">Reward deleted successfully.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a class="btn btn-primary" href="/quest/public/admin/createReward">Add Reward</a>
</div>

<?php if (empty($rewards)): ?>
    <div class="alert alert-info">No rewards found. Create your first reward.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Required Points</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rewards as $reward): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($reward['reward_name']); ?>
                            <div class="small text-muted"><?php echo htmlspecialchars($reward['description']); ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($reward['required_points']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $reward['status'] === 'available' ? 'success' : 'secondary'; ?>">
                                <?php echo htmlspecialchars($reward['status']); ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="/quest/public/admin/editReward/<?php echo $reward['reward_id']; ?>">Edit</a>
                            <form method="post" action="/quest/public/admin/deleteReward" class="d-inline" onsubmit="return confirm('Delete this reward?');">
                                <input type="hidden" name="reward_id" value="<?php echo $reward['reward_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
