<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Edit Quest</h3>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="<?php echo BASE_URL; ?>/admin/editQuest/<?php echo $quest['quest_id']; ?>">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($quest['title'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($quest['description'] ?? ''); ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($quest['category'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Points</label>
        <input type="number" name="points" min="1" class="form-control" value="<?php echo htmlspecialchars($quest['points'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Deadline</label>
        <input type="datetime-local" name="deadline" class="form-control" value="<?php echo htmlspecialchars($quest['deadline_input'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" <?php echo ($quest['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo ($quest['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Update Quest</button>
        <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/admin/quests">Cancel</a>
    </div>
</form>

<?php require_once '../app/views/layouts/footer.php'; ?>
