<?php require_once '../app/views/layouts/header.php'; ?>

<?php
    $proofLabels = [
        'text' => 'Text',
        'image' => 'Single Image',
        'image_text' => 'Image + Text',
        'multi_image' => 'Multiple Images',
        'none' => 'No Proof',
        'qr' => 'QR Code'
    ];
?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">QUEST BOARD</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">QUEST BOARD</h2>
</div>

<?php if (!empty($error)): ?>
    <div class="bg-error-container border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow mb-10">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">ALL QUESTS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($available_quests)): ?>
            <div class="p-6 font-mono text-sm uppercase">No quests found at the moment.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
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
                        <?php
                            $isAvailable = isset($quest['is_available']) ? (int) $quest['is_available'] === 1 : true;
                            if (!isset($quest['is_available'])) {
                                $isAvailable = ($quest['status'] ?? 'active') === 'active'
                                    && strtotime($quest['deadline']) >= time();
                            }
                            $isActive = ($quest['status'] ?? 'active') === 'active';
                            $isExpired = !$isActive ? false : (strtotime($quest['deadline']) < time());
                            $availabilityLabel = $isActive ? ($isExpired ? 'EXPIRED' : 'UNAVAILABLE') : 'INACTIVE';
                        ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low <?php echo $isAvailable ? '' : 'opacity-60'; ?>">
                            <td class="p-4">
                                <div class="font-bold text-on-surface uppercase"><?php echo htmlspecialchars($quest['title']); ?></div>
                                <div class="text-xs text-secondary mt-1"><?php echo htmlspecialchars($quest['description']); ?></div>
                            </td>
                            <td class="p-4 uppercase font-bold text-secondary"><?php echo htmlspecialchars($quest['category']); ?></td>
                            <td class="p-4 uppercase text-xs font-bold text-zinc-600">
                                <?php echo htmlspecialchars($proofLabels[$quest['proof_type']] ?? 'Text'); ?>
                            </td>
                            <td class="p-4 text-primary font-black">+<?php echo htmlspecialchars($quest['points']); ?> XP</td>
                            <td class="p-4 uppercase text-xs font-bold text-error"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                            <td class="p-4 text-right">
                                <?php if ($isAvailable): ?>
                                    <form method="post" action="<?php echo BASE_URL; ?>/student/acceptQuest/<?php echo htmlspecialchars($quest['quest_id']); ?>">
                                        <button type="submit" class="bg-primary text-white border-2 border-on-surface px-3 py-1 font-button-text hover:bg-on-primary-fixed-variant uppercase">ACCEPT</button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" class="bg-zinc-200 text-zinc-500 border-2 border-on-surface px-3 py-1 font-button-text uppercase cursor-not-allowed" title="<?php echo htmlspecialchars($availabilityLabel); ?>" disabled>UNAVAILABLE</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">ACCEPTED QUESTS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($accepted_quests)): ?>
            <div class="p-6 font-mono text-sm uppercase">No accepted quests yet.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
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
                            $isQr = ($quest['proof_type'] ?? '') === 'qr';
                            $qrToken = $quest['qr_token'] ?? '';
                        ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4">
                                <div class="font-bold text-on-surface uppercase"><?php echo htmlspecialchars($quest['title']); ?></div>
                                <div class="text-xs text-secondary mt-1"><?php echo htmlspecialchars($quest['description']); ?></div>
                            </td>
                            <td class="p-4 uppercase font-bold text-secondary"><?php echo htmlspecialchars($quest['category']); ?></td>
                            <td class="p-4 uppercase text-xs font-bold text-zinc-600">
                                <?php echo htmlspecialchars($proofLabels[$quest['proof_type']] ?? 'Text'); ?>
                            </td>
                            <td class="p-4 text-primary font-black">+<?php echo htmlspecialchars($quest['points']); ?> XP</td>
                            <td class="p-4 uppercase text-xs font-bold text-error"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($quest['deadline']))); ?></td>
                            <td class="p-4">
                                <?php if ($isQr): ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-[#ffd54f] text-on-surface">
                                        <?php echo $qrToken ? 'AWAITING SCAN' : 'QR PENDING'; ?>
                                    </span>
                                <?php elseif ($submissionStatus === null): ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-zinc-200 text-zinc-600">NOT SUBMITTED</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase
                                        <?php echo $isApproved ? 'bg-[#9aed83] text-[#1e6d12]' : ($isRejected ? 'bg-error-container text-error' : 'bg-[#ffd54f] text-on-surface'); ?>">
                                        <?php echo htmlspecialchars($submissionStatus); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-right">
                                <?php if ($isQr): ?>
                                    <?php if ($qrToken): ?>
                                        <button type="button" onclick="openQrModal(<?php echo htmlspecialchars(json_encode($quest)); ?>)" class="bg-primary text-white border-2 border-on-surface px-3 py-1 font-button-text hover:bg-on-primary-fixed-variant uppercase">
                                            VIEW QR
                                        </button>
                                    <?php else: ?>
                                        <span class="text-[10px] text-outline font-bold uppercase">QR NOT READY</span>
                                    <?php endif; ?>
                                <?php elseif ($submissionStatus === null || $isRejected): ?>
                                    <button onclick="openSubmitModal(<?php echo htmlspecialchars(json_encode($quest)); ?>)" class="bg-primary text-white border-2 border-on-surface px-3 py-1 font-button-text hover:bg-on-primary-fixed-variant uppercase">
                                        <?php echo $isRejected ? 'RESUBMIT' : 'SUBMIT PROOF'; ?>
                                    </button>
                                <?php else: ?>
                                    <span class="text-[10px] text-outline font-bold uppercase">SUBMITTED</span>
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
<dialog id="submit-quest-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">SUBMIT QUEST PROOF</h2>
        <button onclick="closeModal('submit-quest-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="" id="submit-form" enctype="multipart/form-data" class="p-6 flex flex-col gap-4 font-mono">
        <div class="bg-surface-container border-2 border-on-surface p-4 mb-2">
            <h5 class="font-bold uppercase text-on-surface" id="submit-quest-title"></h5>
            <p class="text-xs text-secondary mt-1">Category: <span id="submit-quest-category"></span></p>
            <p class="text-xs text-secondary mt-1">Proof: <span id="submit-quest-proof" class="font-black"></span></p>
            <p class="text-xs text-secondary mt-1">Points: <span id="submit-quest-points" class="font-black text-primary"></span></p>
            <p class="text-xs text-error font-bold mt-1">Deadline: <span id="submit-quest-deadline"></span></p>
        </div>

        <div id="submit-quest-remarks-container" class="hidden alert alert-warning bg-error-container border-2 border-error text-error p-3 text-xs font-bold uppercase">
            Previous remarks: <span id="submit-quest-remarks"></span>
        </div>

        <div id="proof-text-container" class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">PROOF TEXT (LINKS, DESCRIPTIONS, ETC.)</label>
            <textarea name="proof_text" id="submit-proof-text" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" rows="5"><?php echo htmlspecialchars($proof_text ?? ''); ?></textarea>
        </div>

        <div id="proof-files-container" class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">UPLOAD IMAGE(S)</label>
            <input type="file" name="proof_files[]" id="submit-proof-files" accept="image/*" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" />
            <p id="proof-files-help" class="text-[10px] uppercase text-secondary"></p>
        </div>

        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('submit-quest-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-primary text-white border-2 border-on-surface p-3 font-button-text hover:bg-on-primary-fixed-variant">SUBMIT</button>
        </div>
    </form>
</dialog>

<!-- QR Code Dialog -->
<dialog id="qr-code-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-sm w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">QUEST QR</h2>
        <button onclick="closeModal('qr-code-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <div class="p-6 flex flex-col items-center gap-4 font-mono">
        <div class="text-xs uppercase font-bold text-on-surface" id="qr-quest-title"></div>
        <img id="qr-image" src="" alt="Quest QR Code" class="h-44 w-44 border-2 border-on-surface bg-white" />
        <div class="text-[10px] uppercase text-on-surface-variant">Token</div>
        <div class="text-xs font-bold break-all text-center" id="qr-token-text"></div>
    </div>
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
        } else if (proofType === 'qr') {
            proofFilesHelp.textContent = '';
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

    function openQrModal(quest) {
        if (!quest.qr_token) {
            return;
        }
        var baseUrl = "<?php echo BASE_URL; ?>";
        var origin = window.location.origin;
        var linkBase = origin + baseUrl;
        if (linkBase.endsWith('/')) {
            linkBase = linkBase.slice(0, -1);
        }
        var qrLink = linkBase + '/admin/qr/' + quest.qr_token;

        document.getElementById('qr-quest-title').textContent = quest.title;
        document.getElementById('qr-token-text').textContent = quest.qr_token;
        document.getElementById('qr-image').src =
            'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + encodeURIComponent(qrLink);
        openModal('qr-code-modal');
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
