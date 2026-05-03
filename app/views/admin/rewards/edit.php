<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Edit Reward</h3>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="<?php echo BASE_URL; ?>/admin/editReward/<?php echo $reward['reward_id']; ?>">
    <div class="mb-3">
        <label class="form-label">Reward Name</label>
        <input type="text" name="reward_name" class="form-control" value="<?php echo htmlspecialchars($reward['reward_name'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($reward['description'] ?? ''); ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Required Points</label>
        <input type="number" name="required_points" min="1" class="form-control" value="<?php echo htmlspecialchars($reward['required_points'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="available" <?php echo ($reward['status'] ?? '') === 'available' ? 'selected' : ''; ?>>Available</option>
            <option value="unavailable" <?php echo ($reward['status'] ?? '') === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Update Reward</button>
        <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/admin/rewards">Cancel</a>
    </div>
</form>

<?php require_once '../app/views/layouts/footer.php'; ?>
