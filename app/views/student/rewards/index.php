<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">REWARDS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">REWARDS SHOP</h2>
    <div class="bg-black text-primary border-2 border-on-surface px-4 py-2 font-mono font-bold text-xl pixel-shadow-sm flex items-center gap-2">
        <span class="material-symbols-outlined">stars</span>
        <?php echo htmlspecialchars($user_points); ?> XP
    </div>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] === 'requested'): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        Redemption request submitted.
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="bg-error-container border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php 
            if ($_GET['error'] === 'not_enough_points') echo "You do not have enough points for that reward.";
            elseif ($_GET['error'] === 'already_requested') echo "You already requested this reward.";
            elseif ($_GET['error'] === 'not_available') echo "This reward is currently unavailable.";
            else echo "Request failed. Please try again.";
        ?>
    </div>
<?php endif; ?>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">AVAILABLE REWARDS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($rewards)): ?>
            <div class="p-6 font-mono text-sm uppercase">No rewards available right now.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">REWARD</th>
                        <th class="p-4 uppercase font-black">COST (XP)</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rewards as $reward): ?>
                        <?php
                            $redemption = $redemption_map[$reward['reward_id']] ?? null;
                            $redemptionStatus = $redemption['status'] ?? null;
                            $canRequest = ($reward['status'] === 'available')
                                && ($redemptionStatus === null || $redemptionStatus === 'rejected')
                                && ($user_points >= (int) $reward['required_points']);
                        ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4">
                                <div class="font-bold text-on-surface uppercase"><?php echo htmlspecialchars($reward['reward_name']); ?></div>
                                <div class="text-xs text-secondary mt-1"><?php echo htmlspecialchars($reward['description']); ?></div>
                                <?php if ($redemptionStatus === 'rejected' && !empty($redemption['remarks'])): ?>
                                    <div class="text-[10px] text-error font-bold mt-1 uppercase">LAST REMARKS: <?php echo htmlspecialchars($redemption['remarks']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-tertiary font-black">-<?php echo htmlspecialchars($reward['required_points']); ?> XP</td>
                            <td class="p-4">
                                <?php if ($redemptionStatus === 'approved'): ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-[#9aed83] text-[#1e6d12]">APPROVED</span>
                                <?php elseif ($redemptionStatus === 'pending'): ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-[#ffd54f] text-on-surface">PENDING</span>
                                <?php elseif ($redemptionStatus === 'rejected'): ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-error-container text-error">REJECTED</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-zinc-200 text-zinc-600">AVAILABLE</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-right">
                                <?php if ($canRequest): ?>
                                    <button onclick="openRequestModal(<?php echo htmlspecialchars(json_encode($reward)); ?>)" class="bg-tertiary text-white border-2 border-on-surface px-3 py-1 font-button-text hover:bg-[#154d0d] uppercase">
                                        REQUEST
                                    </button>
                                <?php elseif ($redemptionStatus === 'pending'): ?>
                                    <span class="text-[10px] text-outline font-bold uppercase">REQUESTED</span>
                                <?php elseif ($redemptionStatus === 'approved'): ?>
                                    <span class="text-[10px] text-[#1e6d12] font-bold uppercase">APPROVED</span>
                                <?php elseif ($user_points < (int) $reward['required_points']): ?>
                                    <span class="text-[10px] text-outline font-bold uppercase">NOT ENOUGH XP</span>
                                <?php else: ?>
                                    <span class="text-[10px] text-outline font-bold uppercase">UNAVAILABLE</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<!-- Request Dialog -->
<dialog id="request-reward-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-sm w-full backdrop:bg-black/60">
    <div class="bg-tertiary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">CONFIRM REQUEST</h2>
        <button onclick="closeModal('request-reward-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="" id="request-form" class="p-6 flex flex-col gap-4 font-mono">
        <p class="text-sm">Are you sure you want to request <strong id="request-reward-name" class="uppercase"></strong>?</p>
        <p class="text-sm font-bold text-tertiary">Cost: <span id="request-reward-points"></span> XP</p>
        
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('request-reward-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-tertiary text-white border-2 border-on-surface p-3 font-button-text hover:bg-[#154d0d]">CONFIRM</button>
        </div>
    </form>
</dialog>

<script>
    function openRequestModal(reward) {
        document.getElementById('request-reward-name').textContent = reward.reward_name;
        document.getElementById('request-reward-points').textContent = reward.required_points;
        document.getElementById('request-form').action = "<?php echo BASE_URL; ?>/student/requestReward/" + reward.reward_id;
        openModal('request-reward-modal');
    }
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
