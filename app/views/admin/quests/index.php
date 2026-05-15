<?php require_once '../app/views/layouts/header.php'; ?>

<?php
    $scopeLabels = [
        'all' => 'All Years',
        'year' => 'Single Year',
        'multi' => 'Multiple Years'
    ];
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
    <span class="text-on-surface">QUESTS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">QUEST MANAGEMENT</h2>
    <button onclick="openModal('create-quest-modal')" class="bg-primary text-on-surface border-2 border-on-surface px-4 py-2 font-button-text hover:bg-surface-container active:translate-y-1 pixel-shadow-sm uppercase">
        ADD QUEST
    </button>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php 
            if ($_GET['success'] === 'created') echo "Quest created successfully.";
            elseif ($_GET['success'] === 'updated') echo "Quest updated successfully.";
            elseif ($_GET['success'] === 'deleted') echo "Quest deleted successfully.";
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
        <h2 class="font-h2 text-on-surface uppercase font-black">ALL QUESTS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($quests)): ?>
            <div class="p-6 font-mono text-sm uppercase">No quests found. Create your first quest.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">TITLE</th>
                        <th class="p-4 uppercase font-black">CATEGORY</th>
                        <th class="p-4 uppercase font-black">POINTS</th>
                        <th class="p-4 uppercase font-black">SCOPE</th>
                        <th class="p-4 uppercase font-black">PROOF</th>
                        <th class="p-4 uppercase font-black">DEADLINE</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quests as $quest): ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4 font-bold text-on-surface"><?php echo htmlspecialchars($quest['title']); ?></td>
                            <td class="p-4 uppercase"><?php echo htmlspecialchars($quest['category']); ?></td>
                            <td class="p-4 text-primary font-black"><?php echo htmlspecialchars($quest['points']); ?></td>
                            <td class="p-4 text-xs uppercase font-bold text-zinc-600">
                                <?php
                                    $scopeText = $scopeLabels[$quest['scope_type']] ?? 'All Years';
                                    if ($quest['scope_type'] !== 'all' && !empty($quest['scope_years'])) {
                                        $scopeText .= ' (' . $quest['scope_years'] . ')';
                                    }
                                    echo htmlspecialchars($scopeText);
                                ?>
                            </td>
                            <td class="p-4 text-xs uppercase font-bold text-zinc-600">
                                <?php echo htmlspecialchars($proofLabels[$quest['proof_type']] ?? 'Text'); ?>
                            </td>
                            <td class="p-4 uppercase"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase <?php echo $quest['status'] === 'active' ? 'bg-[#9aed83] text-[#1e6d12]' : 'bg-zinc-200 text-zinc-600'; ?>">
                                    <?php echo htmlspecialchars($quest['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($quest)); ?>)" class="text-primary hover:text-on-surface mx-2">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <form method="post" action="<?php echo BASE_URL; ?>/admin/deleteQuest" class="inline-block" onsubmit="return confirm('Delete this quest?');">
                                    <input type="hidden" name="quest_id" value="<?php echo $quest['quest_id']; ?>">
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

<!-- Create Quest Dialog -->
<dialog id="create-quest-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-on-surface uppercase">ADD NEW QUEST</h2>
        <button onclick="closeModal('create-quest-modal')" class="text-on-surface hover:text-white"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/createQuest" class="p-6 flex flex-col gap-4 font-mono">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">TITLE</label>
            <input type="text" name="title" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DESCRIPTION</label>
            <textarea name="description" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" rows="4" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">CATEGORY</label>
            <select name="category" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
                <option value="" disabled <?php echo empty($old['category'] ?? '') ? 'selected' : ''; ?>>SELECT CATEGORY</option>
                <option value="Curricular" <?php echo ($old['category'] ?? '') === 'Curricular' ? 'selected' : ''; ?>>CURRICULAR</option>
                <option value="extra cur" <?php echo ($old['category'] ?? '') === 'Extra-Curricular' ? 'selected' : ''; ?>>EXTRA CUR</option>
                <option value="co-curr" <?php echo ($old['category'] ?? '') === 'Co-Curricular' ? 'selected' : ''; ?>>CO-CURR</option>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">SCOPE TYPE</label>
            <select name="scope_type" id="create-scope-type" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
                <option value="all" <?php echo ($old['scope_type'] ?? 'all') === 'all' ? 'selected' : ''; ?>>ALL YEARS</option>
                <option value="year" <?php echo ($old['scope_type'] ?? '') === 'year' ? 'selected' : ''; ?>>SINGLE YEAR</option>
                <option value="multi" <?php echo ($old['scope_type'] ?? '') === 'multi' ? 'selected' : ''; ?>>MULTIPLE YEARS</option>
            </select>
        </div>
        <div id="create-scope-years" class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">YEAR LEVELS</label>
            <div class="flex flex-wrap gap-3 text-xs font-bold uppercase">
                <?php
                    $oldYears = $old['scope_years'] ?? [];
                    if (!is_array($oldYears)) {
                        $oldYears = array_filter(explode(',', (string) $oldYears));
                    }
                ?>
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="1" <?php echo in_array('1', $oldYears, true) ? 'checked' : ''; ?>>1</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="2" <?php echo in_array('2', $oldYears, true) ? 'checked' : ''; ?>>2</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="3" <?php echo in_array('3', $oldYears, true) ? 'checked' : ''; ?>>3</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="4" <?php echo in_array('4', $oldYears, true) ? 'checked' : ''; ?>>4</label>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">PROOF TYPE</label>
            <select name="proof_type" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
                <option value="text" <?php echo ($old['proof_type'] ?? 'text') === 'text' ? 'selected' : ''; ?>>TEXT</option>
                <option value="image" <?php echo ($old['proof_type'] ?? '') === 'image' ? 'selected' : ''; ?>>SINGLE IMAGE</option>
                <option value="image_text" <?php echo ($old['proof_type'] ?? '') === 'image_text' ? 'selected' : ''; ?>>IMAGE + TEXT</option>
                <option value="multi_image" <?php echo ($old['proof_type'] ?? '') === 'multi_image' ? 'selected' : ''; ?>>MULTIPLE IMAGES</option>
                <option value="none" <?php echo ($old['proof_type'] ?? '') === 'none' ? 'selected' : ''; ?>>NO PROOF</option>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">POINTS</label>
            <input type="number" name="points" min="1" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" value="<?php echo htmlspecialchars($old['points'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DEADLINE</label>
            <input type="datetime-local" name="deadline" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" value="<?php echo htmlspecialchars($old['deadline'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STATUS</label>
            <select name="status" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
                <option value="active" <?php echo ($old['status'] ?? '') === 'active' ? 'selected' : ''; ?>>ACTIVE</option>
                <option value="inactive" <?php echo ($old['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>INACTIVE</option>
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('create-quest-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#9aed83] border-2 border-on-surface p-3 font-button-text hover:bg-tertiary-fixed">SAVE QUEST</button>
        </div>
    </form>
</dialog>

<!-- Edit Quest Dialog -->
<dialog id="edit-quest-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-on-surface uppercase">EDIT QUEST</h2>
        <button onclick="closeModal('edit-quest-modal')" class="text-on-surface hover:text-white"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/editQuest" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="quest_id" id="edit-quest-id">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">TITLE</label>
            <input type="text" name="title" id="edit-title" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DESCRIPTION</label>
            <textarea name="description" id="edit-description" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" rows="4" required></textarea>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">CATEGORY</label>
            <input type="text" name="category" id="edit-category" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">SCOPE TYPE</label>
            <select name="scope_type" id="edit-scope-type" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
                <option value="all">ALL YEARS</option>
                <option value="year">SINGLE YEAR</option>
                <option value="multi">MULTIPLE YEARS</option>
            </select>
        </div>
        <div id="edit-scope-years" class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">YEAR LEVELS</label>
            <div class="flex flex-wrap gap-3 text-xs font-bold uppercase">
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="1">1</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="2">2</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="3">3</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="scope_years[]" value="4">4</label>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">PROOF TYPE</label>
            <select name="proof_type" id="edit-proof-type" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
                <option value="text">TEXT</option>
                <option value="image">SINGLE IMAGE</option>
                <option value="image_text">IMAGE + TEXT</option>
                <option value="multi_image">MULTIPLE IMAGES</option>
                <option value="none">NO PROOF</option>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">POINTS</label>
            <input type="number" name="points" id="edit-points" min="1" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DEADLINE</label>
            <input type="datetime-local" name="deadline" id="edit-deadline" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STATUS</label>
            <select name="status" id="edit-status" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
                <option value="active">ACTIVE</option>
                <option value="inactive">INACTIVE</option>
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('edit-quest-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#9aed83] border-2 border-on-surface p-3 font-button-text hover:bg-tertiary-fixed">UPDATE QUEST</button>
        </div>
    </form>
</dialog>

<script>
    function openEditModal(quest) {
        document.getElementById('edit-quest-id').value = quest.quest_id;
        document.getElementById('edit-title').value = quest.title;
        document.getElementById('edit-description').value = quest.description;
        document.getElementById('edit-category').value = quest.category;
        document.getElementById('edit-scope-type').value = quest.scope_type || 'all';
        document.getElementById('edit-proof-type').value = quest.proof_type || 'text';
        document.getElementById('edit-points').value = quest.points;
        
        // Format deadline for datetime-local input (YYYY-MM-DDThh:mm)
        var deadline = new Date(quest.deadline);
        deadline.setMinutes(deadline.getMinutes() - deadline.getTimezoneOffset());
        document.getElementById('edit-deadline').value = deadline.toISOString().slice(0,16);
        
        document.getElementById('edit-status').value = quest.status;

        var yearValues = (quest.scope_years || '').toString().split(',').map(y => y.trim()).filter(Boolean);
        document.querySelectorAll('#edit-scope-years input[type=checkbox]').forEach((checkbox) => {
            checkbox.checked = yearValues.includes(checkbox.value);
        });

        applyScopeType('edit-scope-type', 'edit-scope-years');
        
        // Change form action to include ID if needed, or just submit it.
        // Wait, the original controller uses `/admin/editQuest/{id}`. We can append it or use POST hidden field.
        document.querySelector('#edit-quest-modal form').action = "<?php echo BASE_URL; ?>/admin/editQuest/" + quest.quest_id;
        
        openModal('edit-quest-modal');
    }

    function applyScopeType(selectId, yearsId) {
        var select = document.getElementById(selectId);
        var yearsContainer = document.getElementById(yearsId);
        if (!select || !yearsContainer) {
            return;
        }

        if (select.value === 'all') {
            yearsContainer.classList.add('hidden');
        } else {
            yearsContainer.classList.remove('hidden');
        }
    }

    function bindScopeHandlers(selectId, yearsId) {
        var select = document.getElementById(selectId);
        var yearsContainer = document.getElementById(yearsId);
        if (!select || !yearsContainer) {
            return;
        }

        select.addEventListener('change', () => {
            if (select.value === 'all') {
                yearsContainer.querySelectorAll('input[type=checkbox]').forEach((checkbox) => {
                    checkbox.checked = false;
                });
            }
            applyScopeType(selectId, yearsId);
        });

        yearsContainer.addEventListener('change', (event) => {
            if (select.value !== 'year' || event.target.type !== 'checkbox') {
                return;
            }
            yearsContainer.querySelectorAll('input[type=checkbox]').forEach((checkbox) => {
                if (checkbox !== event.target) {
                    checkbox.checked = false;
                }
            });
        });

        applyScopeType(selectId, yearsId);
    }

    <?php if (!empty($open_create_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => { openModal('create-quest-modal'); });
    <?php endif; ?>
    <?php if (!empty($open_edit_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => {
            <?php if (!empty($quest)): ?>
                openEditModal(<?php echo json_encode($quest); ?>);
            <?php else: ?>
                openModal('edit-quest-modal');
            <?php endif; ?>
        });
    <?php endif; ?>

    window.addEventListener('DOMContentLoaded', () => {
        bindScopeHandlers('create-scope-type', 'create-scope-years');
        bindScopeHandlers('edit-scope-type', 'edit-scope-years');
    });
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
