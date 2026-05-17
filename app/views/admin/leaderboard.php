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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- BY POINTS -->
    <section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
        <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
            <h2 class="font-h2 text-on-surface uppercase font-black">TOP BY XP</h2>
        </div>
        <div class="overflow-x-auto">
            <?php if (empty($leaderboard_points)): ?>
                <div class="p-6 font-mono text-sm uppercase">No students found.</div>
            <?php else: ?>
                <table class="w-full text-left font-mono text-sm">
                    <thead class="bg-surface-container border-b-4 border-on-surface">
                        <tr>
                            <th class="p-4 uppercase font-black w-16">RANK</th>
                            <th class="p-4 uppercase font-black">STUDENT</th>
                            <th class="p-4 uppercase font-black text-right">TOTAL XP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; ?>
                        <?php foreach ($leaderboard_points as $row): ?>
                            <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low <?php echo $rank <= 3 ? 'bg-error-container/20' : ''; ?>">
                                <td class="p-4 font-black text-2xl text-on-surface">
                                    <div class="flex items-center">
                                        <?php if ($rank === 1): ?>
                                            <span class="text-primary">1</span><span class="material-symbols-outlined text-primary ml-1">military_tech</span>
                                        <?php elseif ($rank === 2): ?>
                                            <span class="text-outline">2</span><span class="material-symbols-outlined text-outline ml-1">military_tech</span>
                                        <?php elseif ($rank === 3): ?>
                                            <span class="text-amber-700">3</span><span class="material-symbols-outlined text-amber-700 ml-1">military_tech</span>
                                        <?php else: ?>
                                            <span><?php echo $rank; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-on-surface uppercase text-lg"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td class="p-4 text-primary font-black text-xl text-right"><?php echo htmlspecialchars($row['total_points']); ?> XP</td>
                            </tr>
                            <?php $rank++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <!-- BY QUESTS -->
    <section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
        <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
            <h2 class="font-h2 text-on-surface uppercase font-black">TOP BY QUESTS</h2>
        </div>
        <div class="overflow-x-auto">
            <?php if (empty($leaderboard_quests)): ?>
                <div class="p-6 font-mono text-sm uppercase">No students found.</div>
            <?php else: ?>
                <table class="w-full text-left font-mono text-sm">
                    <thead class="bg-surface-container border-b-4 border-on-surface">
                        <tr>
                            <th class="p-4 uppercase font-black w-16">RANK</th>
                            <th class="p-4 uppercase font-black">STUDENT</th>
                            <th class="p-4 uppercase font-black text-right">COMPLETED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; ?>
                        <?php foreach ($leaderboard_quests as $row): ?>
                            <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low <?php echo $rank <= 3 ? 'bg-error-container/20' : ''; ?>">
                                <td class="p-4 font-black text-2xl text-on-surface">
                                    <div class="flex items-center">
                                        <?php if ($rank === 1): ?>
                                            <span class="text-primary">1</span><span class="material-symbols-outlined text-primary ml-1">military_tech</span>
                                        <?php elseif ($rank === 2): ?>
                                            <span class="text-outline">2</span><span class="material-symbols-outlined text-outline ml-1">military_tech</span>
                                        <?php elseif ($rank === 3): ?>
                                            <span class="text-amber-700">3</span><span class="material-symbols-outlined text-amber-700 ml-1">military_tech</span>
                                        <?php else: ?>
                                            <span><?php echo $rank; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-on-surface uppercase text-lg"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td class="p-4 text-secondary font-black text-xl text-right"><?php echo htmlspecialchars($row['completed_count']); ?></td>
                            </tr>
                            <?php $rank++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <!-- BY EVENT PARTICIPATION -->
    <section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
        <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
            <h2 class="font-h2 text-on-surface uppercase font-black">TOP BY EVENTS</h2>
        </div>
        <div class="overflow-x-auto">
            <?php if (empty($leaderboard_events)): ?>
                <div class="p-6 font-mono text-sm uppercase">No students found.</div>
            <?php else: ?>
                <table class="w-full text-left font-mono text-sm">
                    <thead class="bg-surface-container border-b-4 border-on-surface">
                        <tr>
                            <th class="p-4 uppercase font-black w-16">RANK</th>
                            <th class="p-4 uppercase font-black">STUDENT</th>
                            <th class="p-4 uppercase font-black text-right">EVENTS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; ?>
                        <?php foreach ($leaderboard_events as $row): ?>
                            <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low <?php echo $rank <= 3 ? 'bg-error-container/20' : ''; ?>">
                                <td class="p-4 font-black text-2xl text-on-surface">
                                    <div class="flex items-center">
                                        <?php if ($rank === 1): ?>
                                            <span class="text-primary">1</span><span class="material-symbols-outlined text-primary ml-1">military_tech</span>
                                        <?php elseif ($rank === 2): ?>
                                            <span class="text-outline">2</span><span class="material-symbols-outlined text-outline ml-1">military_tech</span>
                                        <?php elseif ($rank === 3): ?>
                                            <span class="text-amber-700">3</span><span class="material-symbols-outlined text-amber-700 ml-1">military_tech</span>
                                        <?php else: ?>
                                            <span><?php echo $rank; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-on-surface uppercase text-lg"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td class="p-4 text-tertiary font-black text-xl text-right"><?php echo htmlspecialchars($row['event_count'] ?? 0); ?></td>
                            </tr>
                            <?php $rank++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
