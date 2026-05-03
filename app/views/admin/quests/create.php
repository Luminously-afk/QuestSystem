<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Create Quest</h3>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="<?php echo BASE_URL; ?>/admin/createQuest">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($old['category'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Points</label>
        <input type="number" name="points" min="1" class="form-control" value="<?php echo htmlspecialchars($old['points'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Deadline</label>
        <input type="datetime-local" name="deadline" class="form-control" value="<?php echo htmlspecialchars($old['deadline'] ?? ''); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" <?php echo ($old['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo ($old['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Save Quest</button>
        <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/admin/quests">Cancel</a>
    </div>
</form>

<?php require_once '../app/views/layouts/footer.php'; ?>
