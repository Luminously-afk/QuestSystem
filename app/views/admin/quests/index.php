<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Quest Management</h3>
<p class="mb-3">Create and manage quests that students can complete.</p>

<?php if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] === 'created'): ?>
        <div class="alert alert-success">Quest created successfully.</div>
    <?php elseif ($_GET['success'] === 'updated'): ?>
        <div class="alert alert-success">Quest updated successfully.</div>
    <?php elseif ($_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success">Quest deleted successfully.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/admin/createQuest">Add Quest</a>
</div>

<?php if (empty($quests)): ?>
    <div class="alert alert-info">No quests found. Create your first quest.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Points</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quests as $quest): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($quest['title']); ?></td>
                        <td><?php echo htmlspecialchars($quest['category']); ?></td>
                        <td><?php echo htmlspecialchars($quest['points']); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $quest['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                <?php echo htmlspecialchars($quest['status']); ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo BASE_URL; ?>/admin/editQuest/<?php echo $quest['quest_id']; ?>">Edit</a>
                            <form method="post" action="<?php echo BASE_URL; ?>/admin/deleteQuest" class="d-inline" onsubmit="return confirm('Delete this quest?');">
                                <input type="hidden" name="quest_id" value="<?php echo $quest['quest_id']; ?>">
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
