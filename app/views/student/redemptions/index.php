<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-black">MY REDEMPTIONS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">MY REWARD REDEMPTIONS</h2>
</div>

<section class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
    <div class="p-4 border-b-4 border-black flex flex-wrap gap-4 justify-between items-center bg-zinc-50">
        <h2 class="font-h2 text-black uppercase font-black">REDEMPTION HISTORY</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($redemptions)): ?>
            <div class="p-6 font-mono text-sm uppercase">You have not requested any rewards yet.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-zinc-100 border-b-4 border-black">
                    <tr>
                        <th class="p-4 uppercase font-black">REWARD</th>
                        <th class="p-4 uppercase font-black">COST</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black">REMARKS</th>
                        <th class="p-4 uppercase font-black">REQUESTED</th>
                        <th class="p-4 uppercase font-black text-right">REVIEWED</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($redemptions as $redemption): ?>
                        <tr class="border-b-2 border-zinc-100 hover:bg-zinc-50">
                            <td class="p-4 font-bold text-black uppercase"><?php echo htmlspecialchars($redemption['reward_name']); ?></td>
                            <td class="p-4 text-tertiary font-black">-<?php echo htmlspecialchars($redemption['required_points']); ?> XP</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-black font-black uppercase 
                                    <?php echo $redemption['status'] === 'approved' ? 'bg-[#9aed83] text-[#1e6d12]' : ($redemption['status'] === 'rejected' ? 'bg-[#ffdad6] text-[#ba1a1a]' : 'bg-[#ffd54f] text-black'); ?>">
                                    <?php echo htmlspecialchars($redemption['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-xs">
                                <?php if (!empty($redemption['remarks'])): ?>
                                    <span class="<?php echo $redemption['status'] === 'rejected' ? 'text-error font-bold' : 'text-secondary'; ?>">
                                        <?php echo htmlspecialchars($redemption['remarks']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-zinc-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-xs uppercase"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($redemption['requested_at']))); ?></td>
                            <td class="p-4 text-xs uppercase text-right"><?php echo htmlspecialchars($redemption['reviewed_at'] ? date('Y-m-d H:i', strtotime($redemption['reviewed_at'])) : '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../app/views/layouts/footer.php'; ?>
