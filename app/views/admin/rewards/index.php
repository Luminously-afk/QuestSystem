<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">REWARDS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">REWARD MANAGEMENT</h2>
    <button onclick="openModal('create-reward-modal')" class="bg-primary text-on-surface border-2 border-on-surface px-4 py-2 font-button-text hover:bg-surface-container active:translate-y-1 pixel-shadow-sm uppercase">
        ADD REWARD
    </button>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php 
            if ($_GET['success'] === 'created') echo "Reward created successfully.";
            elseif ($_GET['success'] === 'updated') echo "Reward updated successfully.";
            elseif ($_GET['success'] === 'deleted') echo "Reward deleted successfully.";
        ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="bg-error-container border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">ALL REWARDS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($rewards)): ?>
            <div class="p-6 font-mono text-sm uppercase">No rewards found. Create your first reward.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">NAME</th>
                        <th class="p-4 uppercase font-black">REQUIRED POINTS</th>
                        <th class="p-4 uppercase font-black">STOCK</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rewards as $reward): ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4">
                                <div class="font-bold text-on-surface uppercase"><?php echo htmlspecialchars($reward['reward_name']); ?></div>
                                <div class="text-xs text-secondary mt-1"><?php echo htmlspecialchars($reward['description']); ?></div>
                            </td>
                            <td class="p-4 text-primary font-black"><?php echo htmlspecialchars($reward['required_points']); ?></td>
                            <td class="p-4 font-bold text-xs <?php echo ($reward['stock'] ?? null) !== null && (int)$reward['stock'] <= 0 ? 'text-error' : 'text-zinc-600'; ?>">
                                <?php echo ($reward['stock'] ?? null) === null ? '∞' : htmlspecialchars($reward['stock']); ?>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase <?php echo $reward['status'] === 'available' ? 'bg-[#9aed83] text-[#1e6d12]' : 'bg-zinc-200 text-zinc-600'; ?>">
                                    <?php echo htmlspecialchars($reward['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($reward)); ?>)" class="text-primary hover:text-on-surface mx-2">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <form method="post" action="<?php echo BASE_URL; ?>/admin/deleteReward" class="inline-block" onsubmit="return confirm('Delete this reward?');">
                                    <input type="hidden" name="reward_id" value="<?php echo $reward['reward_id']; ?>">
                                    <button type="submit" class="text-error hover:text-on-surface mx-2">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<!-- Create Reward Dialog -->
<dialog id="create-reward-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-tertiary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">ADD NEW REWARD</h2>
        <button onclick="closeModal('create-reward-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/createReward" class="p-6 flex flex-col gap-4 font-mono">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REWARD NAME</label>
            <input type="text" name="reward_name" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" value="<?php echo htmlspecialchars($old['reward_name'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DESCRIPTION</label>
            <textarea name="description" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" rows="3" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REQUIRED POINTS</label>
            <input type="number" name="required_points" min="1" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" value="<?php echo htmlspecialchars($old['required_points'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STOCK <span class="text-secondary font-normal">(leave blank = unlimited)</span></label>
            <input type="number" name="stock" min="0" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" value="<?php echo htmlspecialchars($old['stock'] ?? ''); ?>" placeholder="Unlimited">
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STATUS</label>
            <select name="status" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary">
                <option value="available" <?php echo ($old['status'] ?? '') === 'available' ? 'selected' : ''; ?>>AVAILABLE</option>
                <option value="unavailable" <?php echo ($old['status'] ?? '') === 'unavailable' ? 'selected' : ''; ?>>UNAVAILABLE</option>
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('create-reward-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-tertiary text-white border-2 border-on-surface p-3 font-button-text hover:bg-[#154d0d]">SAVE REWARD</button>
        </div>
    </form>
</dialog>

<!-- Edit Reward Dialog -->
<dialog id="edit-reward-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-tertiary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">EDIT REWARD</h2>
        <button onclick="closeModal('edit-reward-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/editReward" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="reward_id" id="edit-reward-id">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REWARD NAME</label>
            <input type="text" name="reward_name" id="edit-reward-name" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DESCRIPTION</label>
            <textarea name="description" id="edit-description" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" rows="3" required></textarea>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REQUIRED POINTS</label>
            <input type="number" name="required_points" id="edit-required-points" min="1" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STOCK <span class="text-secondary font-normal">(leave blank = unlimited)</span></label>
            <input type="number" name="stock" id="edit-stock" min="0" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary" placeholder="Unlimited">
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STATUS</label>
            <select name="status" id="edit-status" class="border-2 border-on-surface p-2 focus:outline-none focus:border-tertiary">
                <option value="available">AVAILABLE</option>
                <option value="unavailable">UNAVAILABLE</option>
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('edit-reward-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-tertiary text-white border-2 border-on-surface p-3 font-button-text hover:bg-[#154d0d]">UPDATE REWARD</button>
        </div>
    </form>
</dialog>

<script>
    function openEditModal(reward) {
        document.getElementById('edit-reward-id').value = reward.reward_id;
        document.getElementById('edit-reward-name').value = reward.reward_name;
        document.getElementById('edit-description').value = reward.description;
        document.getElementById('edit-required-points').value = reward.required_points;
        document.getElementById('edit-stock').value = reward.stock !== null ? reward.stock : '';
        document.getElementById('edit-status').value = reward.status;
        
        document.querySelector('#edit-reward-modal form').action = "<?php echo BASE_URL; ?>/admin/editReward/" + reward.reward_id;
        
        openModal('edit-reward-modal');
    }

    <?php if (!empty($open_create_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => { openModal('create-reward-modal'); });
    <?php endif; ?>
    <?php if (!empty($open_edit_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => { openModal('edit-reward-modal'); });
    <?php endif; ?>
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
