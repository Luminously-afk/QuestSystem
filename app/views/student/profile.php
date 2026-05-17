<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <a href="<?php echo BASE_URL; ?>/student" class="hover:underline">ROOT</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">MY PROFILE</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">MY PROFILE</h2>
</div>

<!-- Profile Header -->
<section class="bg-surface-container-lowest pixel-border pixel-shadow p-6 mb-8 flex flex-col md:flex-row gap-6 items-start">
    <div class="w-20 h-20 border-2 border-on-surface pixel-shadow-sm bg-primary-container overflow-hidden flex items-center justify-center rounded-sm flex-shrink-0">
        <span class="material-symbols-outlined text-5xl text-on-primary-container">person</span>
    </div>
    <div class="flex-1 min-w-0">
        <h3 class="font-h1 text-h1 text-on-surface break-words"><?php echo htmlspecialchars($profile['full_name'] ?? 'Student'); ?></h3>
        <div class="flex flex-wrap gap-4 mt-2 font-mono text-xs uppercase">
            <?php if (!empty($profile['student_id'])): ?>
                <span class="text-secondary">ID: <strong class="text-on-surface"><?php echo htmlspecialchars($profile['student_id']); ?></strong></span>
            <?php endif; ?>
            <?php if (!empty($profile['year_level'])): ?>
                <span class="text-secondary">YEAR: <strong class="text-on-surface"><?php echo htmlspecialchars($profile['year_level']); ?></strong></span>
            <?php endif; ?>
            <span class="text-secondary">EMAIL: <strong class="text-on-surface"><?php echo htmlspecialchars($profile['email'] ?? ''); ?></strong></span>
        </div>
    </div>
</section>

<!-- Stats Grid -->
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-surface-container-lowest pixel-border pixel-shadow p-4 flex flex-col items-center text-center">
        <span class="material-symbols-outlined text-3xl text-primary mb-2">star</span>
        <p class="font-h1 text-[28px] leading-none text-on-surface"><?php echo htmlspecialchars($profile['total_points'] ?? 0); ?></p>
        <p class="font-label-pixel text-[10px] text-secondary mt-1 uppercase">TOTAL XP</p>
    </div>
    <div class="bg-surface-container-lowest pixel-border pixel-shadow p-4 flex flex-col items-center text-center">
        <span class="material-symbols-outlined text-3xl text-[#1e6d12] mb-2">task_alt</span>
        <p class="font-h1 text-[28px] leading-none text-on-surface"><?php echo htmlspecialchars($profile['completed_quests'] ?? 0); ?></p>
        <p class="font-label-pixel text-[10px] text-secondary mt-1 uppercase">COMPLETED</p>
    </div>
    <div class="bg-surface-container-lowest pixel-border pixel-shadow p-4 flex flex-col items-center text-center">
        <span class="material-symbols-outlined text-3xl text-amber-600 mb-2">swords</span>
        <p class="font-h1 text-[28px] leading-none text-on-surface"><?php echo htmlspecialchars($profile['active_quests'] ?? 0); ?></p>
        <p class="font-label-pixel text-[10px] text-secondary mt-1 uppercase">IN PROGRESS</p>
    </div>
    <div class="bg-surface-container-lowest pixel-border pixel-shadow p-4 flex flex-col items-center text-center">
        <span class="material-symbols-outlined text-3xl text-[#ffd54f] mb-2">hourglass_top</span>
        <p class="font-h1 text-[28px] leading-none text-on-surface"><?php echo htmlspecialchars($profile['pending_submissions'] ?? 0); ?></p>
        <p class="font-label-pixel text-[10px] text-secondary mt-1 uppercase">PENDING</p>
    </div>
    <div class="bg-surface-container-lowest pixel-border pixel-shadow p-4 flex flex-col items-center text-center">
        <span class="material-symbols-outlined text-3xl text-tertiary mb-2">redeem</span>
        <p class="font-h1 text-[28px] leading-none text-on-surface"><?php echo htmlspecialchars($profile['redeemed_rewards'] ?? 0); ?></p>
        <p class="font-label-pixel text-[10px] text-secondary mt-1 uppercase">REDEEMED</p>
    </div>
    <div class="bg-surface-container-lowest pixel-border pixel-shadow p-4 flex flex-col items-center text-center">
        <span class="material-symbols-outlined text-3xl text-error mb-2">gavel</span>
        <p class="font-h1 text-[28px] leading-none text-on-surface"><?php echo htmlspecialchars($profile['penalties_count'] ?? 0); ?></p>
        <p class="font-label-pixel text-[10px] text-secondary mt-1 uppercase">PENALTIES</p>
    </div>
</section>

<!-- Point Transaction History -->
<section class="space-y-md mb-12" id="point-history">
    <div class="flex items-center gap-2 mb-4">
        <span class="material-symbols-outlined">receipt_long</span>
        <h3 class="font-h2 text-h2 uppercase">POINT TRANSACTION HISTORY</h3>
    </div>
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
                            <td class="p-4 uppercase font-bold text-on-surface">
                                <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase
                                    <?php
                                        if ($tx['type'] === 'Earned') echo 'bg-[#9aed83] text-[#1e6d12]';
                                        elseif ($tx['type'] === 'Manual') echo 'bg-[#b4c5ff] text-primary';
                                        elseif ($tx['type'] === 'Penalty') echo 'bg-error-container text-error';
                                        else echo 'bg-[#ffd54f] text-on-surface';
                                    ?>">
                                    <?php echo htmlspecialchars($tx['type']); ?>
                                </span>
                            </td>
                            <td class="p-4 uppercase text-on-surface text-xs"><?php echo htmlspecialchars($tx['reason']); ?></td>
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
