<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-black">QUESTS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">QUEST MANAGEMENT</h2>
    <button onclick="openModal('create-quest-modal')" class="bg-[#FFD54F] text-black border-4 border-black px-4 py-2 font-button-text hover:bg-zinc-100 active:translate-y-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] uppercase">
        ADD QUEST
    </button>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-[#9aed83] border-4 border-black p-4 mb-8 font-mono font-bold uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-black">
        <?php 
            if ($_GET['success'] === 'created') echo "Quest created successfully.";
            elseif ($_GET['success'] === 'updated') echo "Quest updated successfully.";
            elseif ($_GET['success'] === 'deleted') echo "Quest deleted successfully.";
        ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="bg-[#ffdad6] border-4 border-black p-4 mb-8 font-mono font-bold uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-black">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<section class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
    <div class="p-4 border-b-4 border-black flex flex-wrap gap-4 justify-between items-center bg-zinc-50">
        <h2 class="font-h2 text-black uppercase font-black">ALL QUESTS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($quests)): ?>
            <div class="p-6 font-mono text-sm uppercase">No quests found. Create your first quest.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-zinc-100 border-b-4 border-black">
                    <tr>
                        <th class="p-4 uppercase font-black">TITLE</th>
                        <th class="p-4 uppercase font-black">CATEGORY</th>
                        <th class="p-4 uppercase font-black">POINTS</th>
                        <th class="p-4 uppercase font-black">DEADLINE</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quests as $quest): ?>
                        <tr class="border-b-2 border-zinc-100 hover:bg-zinc-50">
                            <td class="p-4 font-bold text-black"><?php echo htmlspecialchars($quest['title']); ?></td>
                            <td class="p-4 uppercase"><?php echo htmlspecialchars($quest['category']); ?></td>
                            <td class="p-4 text-primary font-black"><?php echo htmlspecialchars($quest['points']); ?></td>
                            <td class="p-4 uppercase"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-black font-black uppercase <?php echo $quest['status'] === 'active' ? 'bg-[#9aed83] text-[#1e6d12]' : 'bg-zinc-200 text-zinc-600'; ?>">
                                    <?php echo htmlspecialchars($quest['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($quest)); ?>)" class="text-primary hover:text-black mx-2">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <form method="post" action="<?php echo BASE_URL; ?>/admin/deleteQuest" class="inline-block" onsubmit="return confirm('Delete this quest?');">
                                    <input type="hidden" name="quest_id" value="<?php echo $quest['quest_id']; ?>">
                                    <button type="submit" class="text-error hover:text-black mx-2">
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
<dialog id="create-quest-modal" class="bg-white border-4 border-black p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-[#FFD54F] border-b-4 border-black p-4 flex justify-between items-center">
        <h2 class="font-h2 text-black uppercase">ADD NEW QUEST</h2>
        <button onclick="closeModal('create-quest-modal')" class="text-black hover:text-white"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/createQuest" class="p-6 flex flex-col gap-4 font-mono">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">TITLE</label>
            <input type="text" name="title" class="border-2 border-black p-2 focus:outline-none focus:border-primary" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DESCRIPTION</label>
            <textarea name="description" class="border-2 border-black p-2 focus:outline-none focus:border-primary" rows="4" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">CATEGORY</label>
            <input type="text" name="category" class="border-2 border-black p-2 focus:outline-none focus:border-primary" value="<?php echo htmlspecialchars($old['category'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">POINTS</label>
            <input type="number" name="points" min="1" class="border-2 border-black p-2 focus:outline-none focus:border-primary" value="<?php echo htmlspecialchars($old['points'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DEADLINE</label>
            <input type="datetime-local" name="deadline" class="border-2 border-black p-2 focus:outline-none focus:border-primary" value="<?php echo htmlspecialchars($old['deadline'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STATUS</label>
            <select name="status" class="border-2 border-black p-2 focus:outline-none focus:border-primary">
                <option value="active" <?php echo ($old['status'] ?? '') === 'active' ? 'selected' : ''; ?>>ACTIVE</option>
                <option value="inactive" <?php echo ($old['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>INACTIVE</option>
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('create-quest-modal')" class="flex-1 bg-zinc-200 border-2 border-black p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#9aed83] border-2 border-black p-3 font-button-text hover:bg-[#88da73]">SAVE QUEST</button>
        </div>
    </form>
</dialog>

<!-- Edit Quest Dialog -->
<dialog id="edit-quest-modal" class="bg-white border-4 border-black p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-[#FFD54F] border-b-4 border-black p-4 flex justify-between items-center">
        <h2 class="font-h2 text-black uppercase">EDIT QUEST</h2>
        <button onclick="closeModal('edit-quest-modal')" class="text-black hover:text-white"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/editQuest" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="quest_id" id="edit-quest-id">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">TITLE</label>
            <input type="text" name="title" id="edit-title" class="border-2 border-black p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DESCRIPTION</label>
            <textarea name="description" id="edit-description" class="border-2 border-black p-2 focus:outline-none focus:border-primary" rows="4" required></textarea>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">CATEGORY</label>
            <input type="text" name="category" id="edit-category" class="border-2 border-black p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">POINTS</label>
            <input type="number" name="points" id="edit-points" min="1" class="border-2 border-black p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DEADLINE</label>
            <input type="datetime-local" name="deadline" id="edit-deadline" class="border-2 border-black p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STATUS</label>
            <select name="status" id="edit-status" class="border-2 border-black p-2 focus:outline-none focus:border-primary">
                <option value="active">ACTIVE</option>
                <option value="inactive">INACTIVE</option>
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('edit-quest-modal')" class="flex-1 bg-zinc-200 border-2 border-black p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#9aed83] border-2 border-black p-3 font-button-text hover:bg-[#88da73]">UPDATE QUEST</button>
        </div>
    </form>
</dialog>

<script>
    function openEditModal(quest) {
        document.getElementById('edit-quest-id').value = quest.quest_id;
        document.getElementById('edit-title').value = quest.title;
        document.getElementById('edit-description').value = quest.description;
        document.getElementById('edit-category').value = quest.category;
        document.getElementById('edit-points').value = quest.points;
        
        // Format deadline for datetime-local input (YYYY-MM-DDThh:mm)
        var deadline = new Date(quest.deadline);
        deadline.setMinutes(deadline.getMinutes() - deadline.getTimezoneOffset());
        document.getElementById('edit-deadline').value = deadline.toISOString().slice(0,16);
        
        document.getElementById('edit-status').value = quest.status;
        
        // Change form action to include ID if needed, or just submit it.
        // Wait, the original controller uses `/admin/editQuest/{id}`. We can append it or use POST hidden field.
        document.querySelector('#edit-quest-modal form').action = "<?php echo BASE_URL; ?>/admin/editQuest/" + quest.quest_id;
        
        openModal('edit-quest-modal');
    }

    <?php if (!empty($open_create_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => { openModal('create-quest-modal'); });
    <?php endif; ?>
    <?php if (!empty($open_edit_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => { 
            // We'd ideally pre-fill it or the user will see it from $old, but let's just open it.
            openModal('edit-quest-modal'); 
        });
    <?php endif; ?>
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
