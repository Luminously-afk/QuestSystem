<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">MY SUBMISSIONS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">MY QUEST SUBMISSIONS</h2>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] === 'submitted'): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        Submission sent successfully.
    </div>
<?php endif; ?>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">SUBMISSION HISTORY</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($submissions)): ?>
            <div class="p-6 font-mono text-sm uppercase">You have not submitted any quests yet.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">QUEST</th>
                        <th class="p-4 uppercase font-black">POINTS</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black">REMARKS</th>
                        <th class="p-4 uppercase font-black">SUBMITTED</th>
                        <th class="p-4 uppercase font-black text-right">REVIEWED</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission): ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4 font-bold text-on-surface uppercase"><?php echo htmlspecialchars($submission['title']); ?></td>
                            <td class="p-4 text-primary font-black">+<?php echo htmlspecialchars($submission['points']); ?> XP</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase 
                                    <?php echo $submission['status'] === 'approved' ? 'bg-[#9aed83] text-[#1e6d12]' : ($submission['status'] === 'rejected' ? 'bg-error-container text-error' : 'bg-[#ffd54f] text-on-surface'); ?>">
                                    <?php echo htmlspecialchars($submission['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-xs">
                                <?php if (!empty($submission['remarks'])): ?>
                                    <span class="<?php echo $submission['status'] === 'rejected' ? 'text-error font-bold' : 'text-secondary'; ?>">
                                        <?php echo htmlspecialchars($submission['remarks']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-outline">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-xs uppercase"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($submission['submitted_at']))); ?></td>
                            <td class="p-4 text-xs uppercase text-right"><?php echo htmlspecialchars($submission['reviewed_at'] ? date('Y-m-d H:i', strtotime($submission['reviewed_at'])) : '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../app/views/layouts/footer.php'; ?>
