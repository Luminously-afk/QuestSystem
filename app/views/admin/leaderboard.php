<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Student Leaderboard</h3>
<p class="mb-3">Rankings are based on total points, with completed quests as the tie-breaker.</p>

<?php if (empty($leaderboard)): ?>
    <div class="alert alert-info">No students found.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Student</th>
                    <th>Total Points</th>
                    <th>Completed Quests</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($leaderboard as $row): ?>
                    <tr>
                        <td><?php echo $rank; ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_points']); ?></td>
                        <td><?php echo htmlspecialchars($row['completed_count']); ?></td>
                    </tr>
                    <?php $rank++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
