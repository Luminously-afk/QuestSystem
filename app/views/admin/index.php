<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-black">DASHBOARD</span>
</div>

<section class="space-y-sm mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">ADMIN DASHBOARD</h2>
    <p class="font-body-lg text-body-lg text-secondary">Welcome, <?php echo htmlspecialchars($name); ?>. System status: NOMINAL.</p>
</section>

<!-- Dashboard Stats Bento Grid (Top Wide Cards) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex justify-between items-start mb-4">
            <span class="font-h3 font-black text-black uppercase">QUICK ACTIONS</span>
            <span class="material-symbols-outlined text-primary text-3xl">bolt</span>
        </div>
        <div class="flex flex-col gap-2 mt-4">
            <a href="<?php echo BASE_URL; ?>/admin/quests" class="text-sm font-mono hover:underline hover:text-primary">MANAGE QUESTS ></a>
            <a href="<?php echo BASE_URL; ?>/admin/students" class="text-sm font-mono hover:underline hover:text-primary">MANAGE STUDENTS ></a>
        </div>
    </div>
    <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex justify-between items-start mb-4">
            <span class="font-h3 font-black text-black uppercase">SYSTEM LOGS</span>
            <span class="material-symbols-outlined text-[#1e6d12] text-3xl">terminal</span>
        </div>
        <div class="flex flex-col gap-2 mt-4">
            <a href="<?php echo BASE_URL; ?>/admin/auditLogs" class="text-sm font-mono hover:underline hover:text-tertiary">VIEW AUDIT LOGS ></a>
        </div>
    </div>
    <div class="bg-[#ffd54f] border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex justify-between items-start mb-4">
            <span class="font-h3 font-black text-black uppercase">PENDING REVIEWS</span>
            <span class="material-symbols-outlined text-black text-3xl">pending_actions</span>
        </div>
        <div class="flex flex-col gap-2 mt-4">
            <a href="<?php echo BASE_URL; ?>/admin/submissions" class="text-sm font-mono hover:underline">REVIEW SUBMISSIONS ></a>
            <a href="<?php echo BASE_URL; ?>/admin/redemptions" class="text-sm font-mono hover:underline">REVIEW REDEMPTIONS ></a>
        </div>
    </div>
</div>

<!-- Management Pixel Panels (Quests & Rewards) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
    <!-- Quest Management Panel -->
    <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <div class="bg-primary border-b-4 border-black p-3">
            <h2 class="font-label-pixel text-white uppercase text-center">QUEST_MANAGEMENT</h2>
        </div>
        <div class="p-2 bg-surface">
            <div class="flex flex-col font-label-pixel text-black">
                <a href="<?php echo BASE_URL; ?>/admin/quests" class="pixel-menu-item flex items-center gap-4 p-4 border-b border-zinc-200 hover:bg-[#ffd54f] transition-colors">
                    <span class="material-symbols-outlined text-lg">search</span> VIEW QUESTS
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/submissions" class="pixel-menu-item flex items-center gap-4 p-4 hover:bg-[#ffd54f] transition-colors">
                    <span class="material-symbols-outlined text-lg">fact_check</span> APPROVE / REJECT SUBMISSIONS
                </a>
            </div>
        </div>
    </div>
    <!-- Reward Management Panel -->
    <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <div class="bg-tertiary border-b-4 border-black p-3">
            <h2 class="font-label-pixel text-white uppercase text-center">REWARD_MANAGEMENT</h2>
        </div>
        <div class="p-2 bg-surface">
            <div class="flex flex-col font-label-pixel text-black">
                <a href="<?php echo BASE_URL; ?>/admin/rewards" class="pixel-menu-item flex items-center gap-4 p-4 border-b border-zinc-200 hover:bg-[#ffd54f] transition-colors">
                    <span class="material-symbols-outlined text-lg">inventory_2</span> VIEW REWARDS
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/redemptions" class="pixel-menu-item flex items-center gap-4 p-4 hover:bg-[#ffd54f] transition-colors">
                    <span class="material-symbols-outlined text-lg">redeem</span> MANAGE REDEMPTIONS
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
