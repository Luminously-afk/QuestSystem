<?php require_once '../app/views/layouts/header.php'; ?>

<h3 class="mb-3">Quest Submissions</h3>
<p class="mb-3">Review student submissions and approve or reject them.</p>

<?php if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] === 'approved'): ?>
        <div class="alert alert-success">Submission approved.</div>
    <?php elseif ($_GET['success'] === 'rejected'): ?>
        <div class="alert alert-success">Submission rejected.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <?php if ($_GET['error'] === 'not_pending'): ?>
        <div class="alert alert-warning">This submission was already reviewed.</div>
    <?php elseif ($_GET['error'] === 'invalid'): ?>
        <div class="alert alert-danger">Invalid request.</div>
    <?php else: ?>
        <div class="alert alert-danger">Something went wrong. Please try again.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($submissions)): ?>
    <div class="alert alert-info">No submissions found.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Quest</th>
                    <th>Points</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($submission['full_name']); ?><br>
                            <small class="text-muted"><?php echo htmlspecialchars($submission['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($submission['title']); ?></td>
                        <td><?php echo htmlspecialchars($submission['points']); ?></td>
                        <td style="max-width: 260px;">
                            <div class="small text-break">
                                <?php echo nl2br(htmlspecialchars($submission['proof_text'])); ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $submission['status'] === 'approved' ? 'success' : ($submission['status'] === 'rejected' ? 'danger' : 'secondary'); ?>">
                                <?php echo htmlspecialchars($submission['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($submission['submitted_at']))); ?></td>
                        <td class="text-end">
                            <?php if ($submission['status'] === 'pending'): ?>
                                <form method="post" action="/quest/public/admin/reviewSubmission" class="d-flex flex-column gap-2">
                                    <input type="hidden" name="submission_id" value="<?php echo $submission['submission_id']; ?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="approved">Approve</option>
                                        <option value="rejected">Reject</option>
                                    </select>
                                    <input type="text" name="remarks" class="form-control form-control-sm" placeholder="Remarks (optional)">
                                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                </form>
                            <?php else: ?>
                                <div class="small text-muted">
                                    Reviewed on <?php echo htmlspecialchars($submission['reviewed_at'] ? date('Y-m-d H:i', strtotime($submission['reviewed_at'])) : '-'); ?>
                                </div>
                                <?php if (!empty($submission['remarks'])): ?>
                                    <div class="small text-muted">Remarks: <?php echo htmlspecialchars($submission['remarks']); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
