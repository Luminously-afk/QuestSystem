<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Submit Quest Proof</h3>

<div class="card card-shadow mb-4">
    <div class="card-body">
        <h5 class="card-title mb-2"><?php echo htmlspecialchars($quest['title']); ?></h5>
        <p class="text-muted mb-1">Category: <?php echo htmlspecialchars($quest['category']); ?></p>
        <p class="text-muted mb-1">Points: <?php echo htmlspecialchars($quest['points']); ?></p>
        <p class="text-muted mb-0">Deadline: <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></p>
    </div>
</div>

<?php if (!empty($existing) && $existing['status'] === 'rejected' && !empty($existing['remarks'])): ?>
    <div class="alert alert-warning">Previous remarks: <?php echo htmlspecialchars($existing['remarks']); ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="<?php echo BASE_URL; ?>/student/submit/<?php echo $quest['quest_id']; ?>">
    <div class="mb-3">
        <label class="form-label">Proof Text</label>
        <textarea name="proof_text" class="form-control" rows="5" required></textarea>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Submit Proof</button>
        <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/student/quests">Cancel</a>
    </div>
</form>

<?php require_once '../app/views/layouts/footer.php'; ?>
