<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">PENALTIES</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">POINT PENALTIES</h2>
    <button onclick="openModal('create-penalty-modal')" class="bg-primary text-on-surface border-2 border-on-surface px-4 py-2 font-button-text hover:bg-surface-container active:translate-y-1 pixel-shadow-sm uppercase">
        ADD PENALTY
    </button>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] === 'created'): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        Penalty recorded successfully.
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="bg-error-container border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">PENALTY LOG</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($penalties)): ?>
            <div class="p-6 font-mono text-sm uppercase">No penalties recorded.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">STUDENT</th>
                        <th class="p-4 uppercase font-black">POINTS</th>
                        <th class="p-4 uppercase font-black">REASON</th>
                        <th class="p-4 uppercase font-black">ADMIN</th>
                        <th class="p-4 uppercase font-black">DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($penalties as $penalty): ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4">
                                <div class="font-bold uppercase text-on-surface"><?php echo htmlspecialchars($penalty['full_name']); ?></div>
                                <div class="text-xs text-secondary mt-1 lowercase"><?php echo htmlspecialchars($penalty['email']); ?></div>
                            </td>
                            <td class="p-4 text-error font-black">-<?php echo htmlspecialchars($penalty['points_deducted']); ?> XP</td>
                            <td class="p-4 text-xs">
                                <div class="max-h-16 overflow-y-auto">
                                    <?php echo nl2br(htmlspecialchars($penalty['reason'])); ?>
                                </div>
                            </td>
                            <td class="p-4 text-xs uppercase font-bold text-zinc-600">
                                <?php echo htmlspecialchars($penalty['admin_name'] ?? 'System'); ?>
                            </td>
                            <td class="p-4 uppercase text-xs">
                                <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($penalty['created_at']))); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<!-- Create Penalty Dialog -->
<dialog id="create-penalty-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-on-surface uppercase">ADD PENALTY</h2>
        <button onclick="closeModal('create-penalty-modal')" class="text-on-surface hover:text-white"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/createPenalty" class="p-6 flex flex-col gap-4 font-mono">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STUDENT</label>
            <select name="user_id" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
                <option value="">SELECT STUDENT</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo htmlspecialchars($student['user_id']); ?>">
                        <?php echo htmlspecialchars($student['full_name']); ?> (<?php echo htmlspecialchars($student['student_id'] ?? 'N/A'); ?>) - <?php echo htmlspecialchars($student['total_points']); ?> XP
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">POINTS DEDUCTED</label>
            <input type="number" name="points_deducted" min="1" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REASON</label>
            <textarea name="reason" rows="4" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required></textarea>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('create-penalty-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#9aed83] border-2 border-on-surface p-3 font-button-text hover:bg-tertiary-fixed">SAVE</button>
        </div>
    </form>
</dialog>

<script>
    <?php if (!empty($open_create_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => { openModal('create-penalty-modal'); });
    <?php endif; ?>
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
