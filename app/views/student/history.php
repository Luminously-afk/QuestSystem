<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <a href="<?php echo BASE_URL; ?>/student" class="hover:underline">ROOT</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">POINT HISTORY</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">POINT HISTORY</h2>
</div>

<!-- Point History -->
<section class="space-y-md mb-12" id="point-history">
    <div class="bg-surface-container-lowest pixel-border pixel-shadow overflow-x-auto">
        <?php if (empty($point_history)): ?>
            <div class="p-6 font-mono text-sm uppercase text-secondary">No point transactions yet.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">DATE</th>
                        <th class="p-4 uppercase font-black">TYPE</th>
                        <th class="p-4 uppercase font-black">REASON</th>
                        <th class="p-4 uppercase font-black text-right">POINTS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($point_history as $tx): ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4 uppercase text-secondary"><?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($tx['transaction_date']))); ?></td>
                            <td class="p-4 uppercase font-bold text-on-surface"><?php echo htmlspecialchars($tx['type']); ?></td>
                            <td class="p-4 uppercase text-on-surface"><?php echo htmlspecialchars($tx['reason']); ?></td>
                            <td class="p-4 font-black text-right <?php echo (int)$tx['points_change'] > 0 ? 'text-[#1e6d12]' : 'text-error'; ?>">
                                <?php echo (int)$tx['points_change'] > 0 ? '+' : ''; ?><?php echo htmlspecialchars($tx['points_change']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../app/views/layouts/footer.php'; ?>
