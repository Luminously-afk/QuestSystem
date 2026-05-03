<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Student Dashboard</h3>
<p>Welcome, <?php echo htmlspecialchars($name); ?>. Your quest journey starts here.</p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-shadow">
            <div class="card-body">
                <p class="text-muted mb-1">Total Points</p>
                <h4 class="mb-0"><?php echo htmlspecialchars($stats['total_points'] ?? 0); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-shadow">
            <div class="card-body">
                <p class="text-muted mb-1">Completed Quests</p>
                <h4 class="mb-0"><?php echo htmlspecialchars($stats['completed_count'] ?? 0); ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="list-group">
    <a class="list-group-item list-group-item-action" href="<?php echo BASE_URL; ?>/student/quests">View Active Quests</a>
    <a class="list-group-item list-group-item-action" href="<?php echo BASE_URL; ?>/student/submissions">Track Submissions</a>
    <a class="list-group-item list-group-item-action" href="<?php echo BASE_URL; ?>/student/rewards">View Rewards</a>
    <a class="list-group-item list-group-item-action" href="<?php echo BASE_URL; ?>/student/redemptions">My Redemptions</a>
    <a class="list-group-item list-group-item-action" href="<?php echo BASE_URL; ?>/student/leaderboard">Leaderboard</a>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
