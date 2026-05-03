<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Admin Dashboard</h3>
<p>Welcome, <?php echo htmlspecialchars($name); ?>. Use the links below to start managing the system.</p>

<div class="list-group">
    <a class="list-group-item list-group-item-action" href="/quest/public/admin/quests">Quest Management</a>
    <a class="list-group-item list-group-item-action" href="/quest/public/admin/submissions">Submission Reviews</a>
    <a class="list-group-item list-group-item-action" href="/quest/public/admin/rewards">Reward Management</a>
    <a class="list-group-item list-group-item-action" href="/quest/public/admin/redemptions">Redemption Reviews</a>
    <a class="list-group-item list-group-item-action" href="/quest/public/admin/students">Student Accounts</a>
    <a class="list-group-item list-group-item-action" href="/quest/public/admin/leaderboard">Leaderboard</a>
    <a class="list-group-item list-group-item-action" href="/quest/public/admin/auditLogs">Audit Logs</a>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
