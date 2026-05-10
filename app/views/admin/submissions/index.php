<?php require_once '../app/views/layouts/header.php'; ?>

<?php
    $proofLabels = [
        'text' => 'Text',
        'image' => 'Single Image',
        'image_text' => 'Image + Text',
        'multi_image' => 'Multiple Images',
        'none' => 'No Proof'
    ];
?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-black">SUBMISSIONS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">QUEST SUBMISSIONS</h2>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-[#9aed83] border-4 border-black p-4 mb-8 font-mono font-bold uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-black">
        <?php 
            if ($_GET['success'] === 'approved') echo "Submission approved.";
            elseif ($_GET['success'] === 'rejected') echo "Submission rejected.";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="bg-[#ffdad6] border-4 border-black p-4 mb-8 font-mono font-bold uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-black">
        <?php 
            if ($_GET['error'] === 'not_pending') echo "This submission was already reviewed.";
            elseif ($_GET['error'] === 'invalid') echo "Invalid request.";
            else echo "Something went wrong. Please try again.";
        ?>
    </div>
<?php endif; ?>

<section class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
    <div class="p-4 border-b-4 border-black flex flex-wrap gap-4 justify-between items-center bg-zinc-50">
        <h2 class="font-h2 text-black uppercase font-black">ALL SUBMISSIONS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($submissions)): ?>
            <div class="p-6 font-mono text-sm uppercase">No submissions found.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-zinc-100 border-b-4 border-black">
                    <tr>
                        <th class="p-4 uppercase font-black">STUDENT</th>
                        <th class="p-4 uppercase font-black">QUEST</th>
                        <th class="p-4 uppercase font-black">PROOF</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black">SUBMITTED</th>
                        <th class="p-4 uppercase font-black text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission): ?>
                        <?php
                            $proofType = $submission['proof_type'] ?? ($submission['quest_proof_type'] ?? 'text');
                            $files = $submission_files[$submission['submission_id']] ?? [];
                        ?>
                        <tr class="border-b-2 border-zinc-100 hover:bg-zinc-50">
                            <td class="p-4">
                                <div class="font-bold text-black uppercase"><?php echo htmlspecialchars($submission['full_name']); ?></div>
                                <div class="text-xs text-secondary mt-1 lowercase"><?php echo htmlspecialchars($submission['email']); ?></div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold uppercase"><?php echo htmlspecialchars($submission['title']); ?></div>
                                <div class="text-primary font-black text-xs">+<?php echo htmlspecialchars($submission['points']); ?> XP</div>
                            </td>
                            <td class="p-4 text-xs">
                                <div class="text-[10px] uppercase font-bold text-zinc-600 mb-2">
                                    <?php echo htmlspecialchars($proofLabels[$proofType] ?? 'Text'); ?>
                                </div>
                                <?php if (!empty($submission['proof_text'])): ?>
                                    <div class="max-h-20 overflow-y-auto mb-2">
                                        <?php echo nl2br(htmlspecialchars($submission['proof_text'])); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($files)): ?>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($files as $filePath): ?>
                                            <a href="<?php echo BASE_URL . '/' . htmlspecialchars($filePath); ?>" target="_blank" rel="noopener" class="block">
                                                <img src="<?php echo BASE_URL . '/' . htmlspecialchars($filePath); ?>" alt="Proof image" class="h-16 w-16 object-cover border border-black" />
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (empty($submission['proof_text']) && empty($files)): ?>
                                    <div class="text-[10px] uppercase text-zinc-400">No proof provided.</div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-black font-black uppercase 
                                    <?php echo $submission['status'] === 'approved' ? 'bg-[#9aed83] text-[#1e6d12]' : ($submission['status'] === 'rejected' ? 'bg-[#ffdad6] text-[#ba1a1a]' : 'bg-[#ffd54f] text-black'); ?>">
                                    <?php echo htmlspecialchars($submission['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 uppercase text-xs"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($submission['submitted_at']))); ?></td>
                            <td class="p-4 text-right">
                                <?php if ($submission['status'] === 'pending'): ?>
                                    <button onclick="openReviewModal(<?php echo htmlspecialchars(json_encode($submission)); ?>)" class="bg-black text-white border-2 border-black px-3 py-1 font-button-text hover:bg-zinc-800 uppercase">
                                        REVIEW
                                    </button>
                                <?php else: ?>
                                    <div class="text-[10px] text-secondary uppercase">
                                        REVIEWED ON <?php echo htmlspecialchars($submission['reviewed_at'] ? date('Y-m-d', strtotime($submission['reviewed_at'])) : '-'); ?>
                                    </div>
                                    <?php if (!empty($submission['remarks'])): ?>
                                        <div class="text-[10px] text-zinc-500 mt-1 max-w-[150px] truncate" title="<?php echo htmlspecialchars($submission['remarks']); ?>">
                                            "<?php echo htmlspecialchars($submission['remarks']); ?>"
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<!-- Review Dialog -->
<dialog id="review-modal" class="bg-white border-4 border-black p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-md w-full backdrop:bg-black/60">
    <div class="bg-black border-b-4 border-black p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">REVIEW SUBMISSION</h2>
        <button onclick="closeModal('review-modal')" class="text-white hover:text-zinc-300"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/reviewSubmission" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="submission_id" id="review-submission-id">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DECISION</label>
            <select name="status" class="border-2 border-black p-2 focus:outline-none focus:border-black font-bold">
                <option value="approved">APPROVE</option>
                <option value="rejected">REJECT</option>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REMARKS (OPTIONAL)</label>
            <input type="text" name="remarks" class="border-2 border-black p-2 focus:outline-none focus:border-black">
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('review-modal')" class="flex-1 bg-zinc-200 border-2 border-black p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#ffd54f] text-black border-2 border-black p-3 font-button-text hover:bg-[#ebc23e]">SUBMIT</button>
        </div>
    </form>
</dialog>

<script>
    function openReviewModal(submission) {
        document.getElementById('review-submission-id').value = submission.submission_id;
        openModal('review-modal');
    }
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
