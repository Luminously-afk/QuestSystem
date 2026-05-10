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

<!-- Dashboard Stats Bento Grid (Top 3-Column Stats) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <!-- Quests Stats -->
    <a href="<?php echo BASE_URL; ?>/admin/quests" class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:bg-primary-container transition-colors cursor-pointer">
        <div class="flex justify-between items-start mb-4">
            <span class="font-h3 font-black text-black uppercase">QUESTS</span>
            <span class="material-symbols-outlined text-primary text-3xl">swords</span>
        </div>
        <div class="flex flex-col gap-4 mt-6">
            <div>
                <div class="text-4xl font-black text-primary"><?php echo htmlspecialchars($stats['active_quests']); ?></div>
                <div class="text-xs font-mono uppercase text-secondary font-bold">ACTIVE</div>
            </div>
            <div>
                <div class="text-2xl font-black text-black"><?php echo htmlspecialchars($stats['total_quests']); ?></div>
                <div class="text-xs font-mono uppercase text-secondary font-bold">TOTAL</div>
            </div>
        </div>
    </a>

    <!-- Reviews Stats -->
    <a href="<?php echo BASE_URL; ?>/admin/submissions" class="bg-[#ffd54f] border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:bg-primary-container transition-colors cursor-pointer">
        <div class="flex justify-between items-start mb-4">
            <span class="font-h3 font-black text-black uppercase">REVIEWS</span>
            <span class="material-symbols-outlined text-black text-3xl">pending_actions</span>
        </div>
        <div class="flex flex-col gap-4 mt-6">
            <div>
                <div class="text-4xl font-black text-error"><?php echo htmlspecialchars($stats['pending_submissions']); ?></div>
                <div class="text-xs font-mono uppercase text-black font-bold">PENDING SUBMISSIONS</div>
            </div>
            <div>
                <div class="text-2xl font-black text-black"><?php echo htmlspecialchars($stats['pending_redemptions']); ?></div>
                <div class="text-xs font-mono uppercase text-secondary font-bold">PENDING REDEMPTIONS</div>
            </div>
        </div>
    </a>

    <!-- Students Stats -->
    <a href="<?php echo BASE_URL; ?>/admin/students" class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:bg-primary-container transition-colors cursor-pointer">
        <div class="flex justify-between items-start mb-4">
            <span class="font-h3 font-black text-black uppercase">STUDENTS</span>
            <span class="material-symbols-outlined text-tertiary text-3xl">groups</span>
        </div>
        <div class="flex flex-col gap-4 mt-6">
            <div>
                <div class="text-4xl font-black text-tertiary"><?php echo htmlspecialchars($stats['active_students']); ?></div>
                <div class="text-xs font-mono uppercase text-secondary font-bold">ACTIVE</div>
            </div>
            <div>
                <div class="text-2xl font-black text-black"><?php echo htmlspecialchars($stats['total_students']); ?></div>
                <div class="text-xs font-mono uppercase text-secondary font-bold">TOTAL</div>
            </div>
        </div>
    </a>
</div>

<!-- Management Pixel Panels (Rewards & Penalties) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
    <!-- Audit Log Panel -->
    <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <div class="bg-[#1e6d12] border-b-4 border-black p-3">
            <h2 class="font-label-pixel text-white uppercase text-center">AUDIT_LOG</h2>
        </div>
        <div class="p-2 bg-surface">
            <div class="flex flex-col font-label-pixel text-black">
                <a href="<?php echo BASE_URL; ?>/admin/auditLogs" class="pixel-menu-item flex items-center gap-4 p-4 border-b border-zinc-200 hover:bg-[#ffd54f] transition-colors">
                    <span class="material-symbols-outlined text-lg">terminal</span> VIEW AUDIT LOGS
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/auditLogs" class="pixel-menu-item flex items-center gap-4 p-4 hover:bg-[#ffd54f] transition-colors">
                    <span class="material-symbols-outlined text-lg">history</span> SYSTEM HISTORY
                </a>
            </div>
        </div>
    </div>

    <!-- Penalties Management Panel -->
    <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <div class="bg-error border-b-4 border-black p-3">
            <h2 class="font-label-pixel text-white uppercase text-center">PENALTY_MANAGEMENT</h2>
        </div>
        <div class="p-2 bg-surface">
            <div class="flex flex-col font-label-pixel text-black">
                <div class="flex items-center gap-4 p-4 border-b border-zinc-200">
                    <span class="material-symbols-outlined text-lg">gavel</span>
                    <div>
                        <div class="text-2xl font-black text-error"><?php echo htmlspecialchars($stats['recent_penalties']); ?></div>
                        <div class="text-xs uppercase text-secondary">LAST 7 DAYS</div>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/penalties" class="pixel-menu-item flex items-center gap-4 p-4 hover:bg-[#ffd54f] transition-colors">
                    <span class="material-symbols-outlined text-lg">list</span> VIEW ALL PENALTIES
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
