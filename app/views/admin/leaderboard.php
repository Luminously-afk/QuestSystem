<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">LEADERBOARD</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">STUDENT LEADERBOARD</h2>
</div>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">TOP PLAYERS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($leaderboard)): ?>
            <div class="p-6 font-mono text-sm uppercase">No students found.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black w-24">RANK</th>
                        <th class="p-4 uppercase font-black">STUDENT</th>
                        <th class="p-4 uppercase font-black text-right">TOTAL XP</th>
                        <th class="p-4 uppercase font-black text-right">QUESTS COMPLETED</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; ?>
                    <?php foreach ($leaderboard as $row): ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low <?php echo $rank <= 3 ? 'bg-error-container/20' : ''; ?>">
                            <td class="p-4 font-black text-2xl text-on-surface">
                                <?php if ($rank === 1): ?>
                                    <span class="text-primary">1</span><span class="material-symbols-outlined text-primary align-middle ml-1">military_tech</span>
                                <?php elseif ($rank === 2): ?>
                                    <span class="text-outline">2</span><span class="material-symbols-outlined text-outline align-middle ml-1">military_tech</span>
                                <?php elseif ($rank === 3): ?>
                                    <span class="text-amber-700">3</span><span class="material-symbols-outlined text-amber-700 align-middle ml-1">military_tech</span>
                                <?php else: ?>
                                    <?php echo $rank; ?>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 font-bold text-on-surface uppercase text-lg"><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td class="p-4 text-primary font-black text-xl text-right"><?php echo htmlspecialchars($row['total_points']); ?> XP</td>
                            <td class="p-4 text-secondary font-black text-right"><?php echo htmlspecialchars($row['completed_count']); ?></td>
                        </tr>
                        <?php $rank++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../app/views/layouts/footer.php'; ?>
