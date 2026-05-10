<?php require_once '../app/views/layouts/header.php'; ?>

<?php
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
    <span class="text-black">QUEST BOARD</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">QUEST BOARD</h2>
</div>

<?php if (!empty($error)): ?>
    <div class="bg-[#ffdad6] border-4 border-black p-4 mb-8 font-mono font-bold uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-black">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<section class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] mb-10">
    <div class="p-4 border-b-4 border-black flex flex-wrap gap-4 justify-between items-center bg-zinc-50">
        <h2 class="font-h2 text-black uppercase font-black">AVAILABLE QUESTS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($available_quests)): ?>
            <div class="p-6 font-mono text-sm uppercase">No available quests at the moment.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-zinc-100 border-b-4 border-black">
                    <tr>
                        <th class="p-4 uppercase font-black">TITLE</th>
                        <th class="p-4 uppercase font-black">CATEGORY</th>
                        <th class="p-4 uppercase font-black">PROOF</th>
                        <th class="p-4 uppercase font-black">POINTS</th>
                        <th class="p-4 uppercase font-black">DEADLINE</th>
                        <th class="p-4 uppercase font-black text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($available_quests as $quest): ?>
                        <tr class="border-b-2 border-zinc-100 hover:bg-zinc-50">
                            <td class="p-4">
                                <div class="font-bold text-black uppercase"><?php echo htmlspecialchars($quest['title']); ?></div>
                                <div class="text-xs text-secondary mt-1"><?php echo htmlspecialchars($quest['description']); ?></div>
                            </td>
                            <td class="p-4 uppercase font-bold text-secondary"><?php echo htmlspecialchars($quest['category']); ?></td>
                            <td class="p-4 uppercase text-xs font-bold text-zinc-600">
                                <?php echo htmlspecialchars($proofLabels[$quest['proof_type']] ?? 'Text'); ?>
                            </td>
                            <td class="p-4 text-primary font-black">+<?php echo htmlspecialchars($quest['points']); ?> XP</td>
                            <td class="p-4 uppercase text-xs font-bold text-error"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                            <td class="p-4 text-right">
                                <form method="post" action="<?php echo BASE_URL; ?>/student/acceptQuest/<?php echo htmlspecialchars($quest['quest_id']); ?>">
                                    <button type="submit" class="bg-primary text-white border-2 border-black px-3 py-1 font-button-text hover:bg-[#3d3db6] uppercase">ACCEPT</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<section class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
    <div class="p-4 border-b-4 border-black flex flex-wrap gap-4 justify-between items-center bg-zinc-50">
        <h2 class="font-h2 text-black uppercase font-black">ACCEPTED QUESTS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($accepted_quests)): ?>
            <div class="p-6 font-mono text-sm uppercase">No accepted quests yet.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-zinc-100 border-b-4 border-black">
                    <tr>
                        <th class="p-4 uppercase font-black">TITLE</th>
                        <th class="p-4 uppercase font-black">CATEGORY</th>
                        <th class="p-4 uppercase font-black">PROOF</th>
                        <th class="p-4 uppercase font-black">POINTS</th>
                        <th class="p-4 uppercase font-black">DEADLINE</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accepted_quests as $quest): ?>
                        <?php
                            $submissionStatus = $quest['submission_status'] ?? null;
                            $isRejected = $submissionStatus === 'rejected';
                            $isPending = $submissionStatus === 'pending';
                            $isApproved = $submissionStatus === 'approved';
                        ?>
                        <tr class="border-b-2 border-zinc-100 hover:bg-zinc-50">
                            <td class="p-4">
                                <div class="font-bold text-black uppercase"><?php echo htmlspecialchars($quest['title']); ?></div>
                                <div class="text-xs text-secondary mt-1"><?php echo htmlspecialchars($quest['description']); ?></div>
                            </td>
                            <td class="p-4 uppercase font-bold text-secondary"><?php echo htmlspecialchars($quest['category']); ?></td>
                            <td class="p-4 uppercase text-xs font-bold text-zinc-600">
                                <?php echo htmlspecialchars($proofLabels[$quest['proof_type']] ?? 'Text'); ?>
                            </td>
                            <td class="p-4 text-primary font-black">+<?php echo htmlspecialchars($quest['points']); ?> XP</td>
                            <td class="p-4 uppercase text-xs font-bold text-error"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                            <td class="p-4">
                                <?php if ($submissionStatus === null): ?>
                                    <span class="px-2 py-1 text-[10px] border border-black font-black uppercase bg-zinc-200 text-zinc-600">NOT SUBMITTED</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-[10px] border border-black font-black uppercase
                                        <?php echo $isApproved ? 'bg-[#9aed83] text-[#1e6d12]' : ($isRejected ? 'bg-[#ffdad6] text-[#ba1a1a]' : 'bg-[#ffd54f] text-black'); ?>">
                                        <?php echo htmlspecialchars($submissionStatus); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-right">
                                <?php if ($submissionStatus === null || $isRejected): ?>
                                    <button onclick="openSubmitModal(<?php echo htmlspecialchars(json_encode($quest)); ?>)" class="bg-primary text-white border-2 border-black px-3 py-1 font-button-text hover:bg-[#3d3db6] uppercase">
                                        <?php echo $isRejected ? 'RESUBMIT' : 'SUBMIT PROOF'; ?>
                                    </button>
                                <?php else: ?>
                                    <span class="text-[10px] text-zinc-400 font-bold uppercase">SUBMITTED</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<!-- Submit Quest Dialog -->
<dialog id="submit-quest-modal" class="bg-white border-4 border-black p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-4 border-black p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">SUBMIT QUEST PROOF</h2>
        <button onclick="closeModal('submit-quest-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="" id="submit-form" enctype="multipart/form-data" class="p-6 flex flex-col gap-4 font-mono">
        <div class="bg-zinc-100 border-2 border-black p-4 mb-2">
            <h5 class="font-bold uppercase text-black" id="submit-quest-title"></h5>
            <p class="text-xs text-secondary mt-1">Category: <span id="submit-quest-category"></span></p>
            <p class="text-xs text-secondary mt-1">Proof: <span id="submit-quest-proof" class="font-black"></span></p>
            <p class="text-xs text-secondary mt-1">Points: <span id="submit-quest-points" class="font-black text-primary"></span></p>
            <p class="text-xs text-error font-bold mt-1">Deadline: <span id="submit-quest-deadline"></span></p>
        </div>

        <div id="submit-quest-remarks-container" class="hidden alert alert-warning bg-[#ffdad6] border-2 border-[#ba1a1a] text-[#ba1a1a] p-3 text-xs font-bold uppercase">
            Previous remarks: <span id="submit-quest-remarks"></span>
        </div>

        <div id="proof-text-container" class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">PROOF TEXT (LINKS, DESCRIPTIONS, ETC.)</label>
            <textarea name="proof_text" id="submit-proof-text" class="border-2 border-black p-2 focus:outline-none focus:border-primary" rows="5"><?php echo htmlspecialchars($proof_text ?? ''); ?></textarea>
        </div>

        <div id="proof-files-container" class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">UPLOAD IMAGE(S)</label>
            <input type="file" name="proof_files[]" id="submit-proof-files" accept="image/*" class="border-2 border-black p-2 focus:outline-none focus:border-primary" />
            <p id="proof-files-help" class="text-[10px] uppercase text-secondary"></p>
        </div>

        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('submit-quest-modal')" class="flex-1 bg-zinc-200 border-2 border-black p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-primary text-white border-2 border-black p-3 font-button-text hover:bg-[#3d3db6]">SUBMIT</button>
        </div>
    </form>
</dialog>

<script>
    var proofLabels = <?php echo json_encode($proofLabels); ?>;

    function applyProofType(proofType) {
        var proofTextContainer = document.getElementById('proof-text-container');
        var proofFilesContainer = document.getElementById('proof-files-container');
        var proofFilesInput = document.getElementById('submit-proof-files');
        var proofFilesHelp = document.getElementById('proof-files-help');

        document.getElementById('submit-quest-proof').textContent = proofLabels[proofType] || 'Text';

        proofTextContainer.classList.add('hidden');
        proofFilesContainer.classList.add('hidden');
        proofFilesInput.removeAttribute('multiple');
        proofFilesInput.value = '';
        proofFilesHelp.textContent = '';

        if (proofType === 'text') {
            proofTextContainer.classList.remove('hidden');
        } else if (proofType === 'image') {
            proofFilesContainer.classList.remove('hidden');
            proofFilesHelp.textContent = 'Upload one image.';
        } else if (proofType === 'image_text') {
            proofTextContainer.classList.remove('hidden');
            proofFilesContainer.classList.remove('hidden');
            proofFilesHelp.textContent = 'Upload one image.';
        } else if (proofType === 'multi_image') {
            proofFilesContainer.classList.remove('hidden');
            proofFilesInput.setAttribute('multiple', 'multiple');
            proofFilesHelp.textContent = 'Upload one or more images.';
        }
    }

    function openSubmitModal(quest) {
        document.getElementById('submit-quest-title').textContent = quest.title;
        document.getElementById('submit-quest-category').textContent = quest.category;
        document.getElementById('submit-quest-points').textContent = quest.points;
        document.getElementById('submit-quest-deadline').textContent = quest.deadline.substring(0, 16);

        applyProofType(quest.proof_type || 'text');

        var remarksContainer = document.getElementById('submit-quest-remarks-container');
        if (quest.submission_status === 'rejected' && quest.submission_remarks) {
            document.getElementById('submit-quest-remarks').textContent = quest.submission_remarks;
            remarksContainer.classList.remove('hidden');
        } else {
            remarksContainer.classList.add('hidden');
        }

        document.getElementById('submit-form').action = "<?php echo BASE_URL; ?>/student/submit/" + quest.quest_id;

        openModal('submit-quest-modal');
    }

    <?php if (!empty($open_submit_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => {
            var questId = <?php echo json_encode($submit_quest_id ?? null); ?>;
            var quests = <?php echo json_encode($accepted_quests ?? []); ?>;
            var questToOpen = quests.find(q => q.quest_id == questId);
            if (questToOpen) {
                openSubmitModal(questToOpen);
            } else {
                openModal('submit-quest-modal');
            }
        });
    <?php endif; ?>
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
