<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">STUDENT DASHBOARD</span>
</div>

<!-- Welcome Section -->
<section class="space-y-sm mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">STUDENT DASHBOARD</h2>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
        <p class="font-body-lg text-body-lg text-secondary">Welcome, <?php echo htmlspecialchars($name); ?>. Your quest journey continues.</p>
    </div>
</section>

<!-- My Overview -->
<section class="space-y-md mb-12">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined">visibility</span>
        <h3 class="font-h2 text-h2 uppercase">MY OVERVIEW</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
        <div class="bg-surface-container-lowest pixel-border pixel-shadow p-6 flex items-center justify-between">
            <div>
                <p class="font-label-pixel text-label-pixel text-secondary mb-2">TOTAL POINTS</p>
                <p class="font-h1 text-[40px] leading-none text-on-surface"><?php echo htmlspecialchars($stats['total_points'] ?? 0); ?></p>
            </div>
            <div class="w-14 h-14 bg-primary-container pixel-border flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl text-on-surface">star</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest pixel-border pixel-shadow p-6 flex items-center justify-between">
            <div>
                <p class="font-label-pixel text-label-pixel text-secondary mb-2">COMPLETED QUESTS</p>
                <p class="font-h1 text-[40px] leading-none text-on-surface"><?php echo htmlspecialchars($stats['completed_count'] ?? 0); ?></p>
            </div>
            <div class="w-14 h-14 bg-tertiary-fixed pixel-border flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl text-on-surface">task_alt</span>
            </div>
        </div>
    </div>
</section>

<!-- Quests Section -->
<section class="space-y-md mb-12" id="quests-section">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
        <a href="<?php echo BASE_URL; ?>/student/quests" class="bg-surface-container-lowest pixel-border pixel-shadow p-lg flex flex-col items-center gap-md hover:bg-primary-container transition-all pressed-active">
            <span class="material-symbols-outlined text-5xl text-on-surface">search</span>
            <span class="font-button-text text-button-text text-center text-on-surface">VIEW AVAILABLE QUESTS</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/student/submissions" class="bg-surface-container-lowest pixel-border pixel-shadow p-lg flex flex-col items-center gap-md hover:bg-primary-container transition-all pressed-active">
            <span class="material-symbols-outlined text-5xl text-on-surface">timeline</span>
            <span class="font-button-text text-button-text text-center text-on-surface">VIEW QUEST STATUS</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/student/rewards" class="bg-surface-container-lowest pixel-border pixel-shadow p-lg flex flex-col items-center gap-md hover:bg-primary-container transition-all pressed-active">
            <span class="material-symbols-outlined text-5xl text-on-surface">redeem</span>
            <span class="font-button-text text-button-text text-center text-on-surface">REDEEM REWARDS</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/student/redemptions" class="bg-surface-container-lowest pixel-border pixel-shadow p-lg flex flex-col items-center gap-md hover:bg-primary-container transition-all pressed-active">
            <span class="material-symbols-outlined text-5xl text-on-surface">history</span>
            <span class="font-button-text text-button-text text-center text-on-surface">REDEMPTION HISTORY</span>
        <a href="<?php echo BASE_URL; ?>/student/history" class="bg-surface-container-lowest pixel-border pixel-shadow p-lg flex flex-col items-center gap-md hover:bg-primary-container transition-all pressed-active sm:col-span-2 md:col-span-1 md:col-start-auto">
            <span class="material-symbols-outlined text-5xl text-on-surface">receipt_long</span>
            <span class="font-button-text text-button-text text-center text-on-surface">POINT HISTORY</span>
        </a>
    </div>
</section>

<!-- Leaderboard Shortcut -->
<section class="mb-12">
    <a href="<?php echo BASE_URL; ?>/student/leaderboard" class="w-full bg-black text-primary-container pixel-border pixel-shadow p-6 flex items-center justify-between hover:bg-on-surface transition-colors">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-4xl">trophy</span>
            <span class="font-button-text text-button-text uppercase tracking-widest text-primary">VIEW GLOBAL LEADERBOARD</span>
        </div>
        <span class="material-symbols-outlined">arrow_forward</span>
    </a>
</section>

<?php require_once '../app/views/layouts/footer.php'; ?>
