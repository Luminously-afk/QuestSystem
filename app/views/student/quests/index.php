<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Active Quests</h3>
<p class="mb-3">Complete quests and submit proof to earn points.</p>

<?php if (empty($quests)): ?>
    <div class="alert alert-info">No active quests at the moment.</div>
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
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quests as $quest): ?>
                    <?php
                        $submissionStatus = $quest['submission_status'] ?? null;
                        $isRejected = $submissionStatus === 'rejected';
                        $isPending = $submissionStatus === 'pending';
                        $isApproved = $submissionStatus === 'approved';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($quest['title']); ?></td>
                        <td><?php echo htmlspecialchars($quest['category']); ?></td>
                        <td><?php echo htmlspecialchars($quest['points']); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                        <td>
                            <?php if ($submissionStatus === null): ?>
                                <span class="badge bg-secondary">not submitted</span>
                            <?php else: ?>
                                <span class="badge bg-<?php echo $isApproved ? 'success' : ($isRejected ? 'danger' : 'warning'); ?>">
                                    <?php echo htmlspecialchars($submissionStatus); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if ($submissionStatus === null || $isRejected): ?>
                                <a class="btn btn-sm btn-primary" href="/quest/public/student/submit/<?php echo $quest['quest_id']; ?>">
                                    <?php echo $isRejected ? 'Resubmit' : 'Submit Proof'; ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Submitted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
