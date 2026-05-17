<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>IT ENGAGEMENT SYSTEM</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary-fixed-variant": "#31447b",
                        "on-secondary-fixed": "#00174b",
                        "tertiary-fixed-dim": "#ffb596",
                        "surface-variant": "#e1e2ed",
                        "surface-container": "#ededf9",
                        "on-tertiary-fixed": "#360f00",
                        "primary-container": "#2563eb",
                        "on-surface-variant": "#434655",
                        "error-container": "#ffdad6",
                        "surface-container-low": "#f3f3fe",
                        "on-surface": "#191b23",
                        "secondary-fixed": "#dbe1ff",
                        "inverse-primary": "#b4c5ff",
                        "inverse-on-surface": "#f0f0fb",
                        "on-secondary-container": "#394c84",
                        "surface-container-highest": "#e1e2ed",
                        "surface": "#faf8ff",
                        "outline": "#737686",
                        "primary-fixed": "#dbe1ff",
                        "tertiary": "#943700",
                        "inverse-surface": "#2e3039",
                        "background": "#faf8ff",
                        "on-tertiary-fixed-variant": "#7d2d00",
                        "primary-fixed-dim": "#b4c5ff",
                        "primary": "#004ac6",
                        "tertiary-fixed": "#ffdbcd",
                        "surface-container-high": "#e7e7f3",
                        "on-primary-fixed-variant": "#003ea8",
                        "on-primary": "#ffffff",
                        "surface-tint": "#0053db",
                        "surface-dim": "#d9d9e5",
                        "on-error": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed-dim": "#b4c5ff",
                        "on-error-container": "#93000a",
                        "on-tertiary-container": "#ffede6",
                        "error": "#ba1a1a",
                        "on-primary-fixed": "#00174b",
                        "secondary-container": "#acbfff",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-container": "#eeefff",
                        "surface-bright": "#faf8ff",
                        "tertiary-container": "#bc4800",
                        "on-secondary": "#ffffff",
                        "secondary": "#495c95",
                        "on-background": "#191b23",
                        "outline-variant": "#c3c6d7"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        md: "16px",
                        sm: "8px",
                        gutter: "24px",
                        unit: "4px",
                        xs: "4px",
                        margin: "32px",
                        lg: "24px",
                        xl: "40px"
                    },
                    fontFamily: {
                        "headline-lg": ["Space Grotesk"],
                        "section-title": ["Inter"],
                        "headline-md": ["Space Grotesk"],
                        "display-xl": ["Space Grotesk"],
                        "body-md": ["Inter"],
                        "label-pixel": ["JetBrains Mono"],
                        "body-lg": ["Inter"],
                        "h1": ["Space Grotesk"],
                        "h2": ["Space Grotesk"],
                        "h3": ["Space Grotesk"],
                        "button-text": ["JetBrains Mono"]
                    },
                    fontSize: {
                        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "700"}],
                        "section-title": ["14px", {"lineHeight": "20px", "letterSpacing": "0.1em", "fontWeight": "800"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "display-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "label-pixel": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "h1": ["24px", { lineHeight: "1.5", letterSpacing: "0px", fontWeight: "700" }],
                        "h2": ["18px", { lineHeight: "1.4", letterSpacing: "0px", fontWeight: "700" }],
                        "h3": ["14px", { lineHeight: "1.4", letterSpacing: "0px", fontWeight: "700" }],
                        "button-text": ["12px", { lineHeight: "1", letterSpacing: "0px", fontWeight: "700" }]
                    }
                }
            }
        };
    </script>
    <style>
        .pixel-border { border: 2px solid #191b23; }
        .pixel-shadow { box-shadow: 4px 4px 0px 0px rgba(0, 74, 198, 1); }
        .pixel-shadow-sm { box-shadow: 2px 2px 0px 0px rgba(0, 74, 198, 1); }
        .step-gradient {
            background: linear-gradient(90deg, #004ac6 0%, #004ac6 20%, #2563eb 20%, #2563eb 40%, #acbfff 40%, #acbfff 60%, #e1e2ed 60%);
        }
        .pressed-active:active {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px 0px rgba(0, 74, 198, 1);
        }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        dialog::backdrop { background: rgba(0, 0, 0, 0.6); }
        dialog { margin: auto; }
    </style>
</head>
<body class="bg-background font-body-md text-on-background selection:bg-primary-container">
<div class="flex min-h-screen">

<!-- NavigationDrawer -->
<?php if (isset($_SESSION['user_id'])): ?>
<aside class="h-screen w-64 flex-col border-r-2 border-on-surface bg-surface-container-low sticky top-0 hidden md:flex">
    <div class="p-6 flex flex-col items-start space-y-4 border-b-2 border-on-surface bg-surface-container-low">
        <div class="w-16 h-16 border-2 border-on-surface pixel-shadow-sm bg-primary-container overflow-hidden flex items-center justify-center rounded-sm">
            <span class="material-symbols-outlined text-4xl text-on-primary-container">person</span>
        </div>
        <div>
            <h2 class="font-h2 text-h2 text-on-surface w-48 break-words whitespace-normal" title="<?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?>"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></h2>
            <p class="font-label-pixel text-label-pixel text-on-surface-variant mt-1 uppercase"><?php echo htmlspecialchars($_SESSION['role'] ?? 'Guest'); ?></p>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>
                <?php if (!empty($_SESSION['student_id'])): ?>
                    <p class="font-label-pixel text-[10px] text-outline mt-1">ID: <?php echo htmlspecialchars($_SESSION['student_id']); ?></p>
                <?php endif; ?>
                <?php if (!empty($_SESSION['year_level'])): ?>
                    <p class="font-label-pixel text-[10px] text-outline mt-1">YEAR: <?php echo htmlspecialchars($_SESSION['year_level']); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <nav class="flex-1 py-4 overflow-y-auto space-y-2 px-2">
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin">
                <span class="material-symbols-outlined">dashboard</span> <span class="text-button-text">Dashboard</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin/quests">
                <span class="material-symbols-outlined">swords</span> <span class="text-button-text">Quests</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin/submissions">
                <span class="material-symbols-outlined">fact_check</span> <span class="text-button-text">Reviews</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin/penalties">
                <span class="material-symbols-outlined">gavel</span> <span class="text-button-text">Penalties</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin/rewards">
                <span class="material-symbols-outlined">inventory_2</span> <span class="text-button-text">Rewards</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin/redemptions">
                <span class="material-symbols-outlined">redeem</span> <span class="text-button-text">Redemptions</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin/students">
                <span class="material-symbols-outlined">groups</span> <span class="text-button-text">Students</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/admin/leaderboard">
                <span class="material-symbols-outlined">leaderboard</span> <span class="text-button-text">Leaderboard</span>
            </a>
        <?php else: ?>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student">
                <span class="material-symbols-outlined">dashboard</span> <span class="text-button-text">Dashboard</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student/quests">
                <span class="material-symbols-outlined">swords</span> <span class="text-button-text">Quests</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student/submissions">
                <span class="material-symbols-outlined">fact_check</span> <span class="text-button-text">Submissions</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student/rewards">
                <span class="material-symbols-outlined">inventory_2</span> <span class="text-button-text">Rewards</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student/redemptions">
                <span class="material-symbols-outlined">redeem</span> <span class="text-button-text">Redemptions</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student/leaderboard">
                <span class="material-symbols-outlined">leaderboard</span> <span class="text-button-text">Leaderboard</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student/history">
                <span class="material-symbols-outlined">receipt_long</span> <span class="text-button-text">Point History</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-on-surface border-2 border-transparent font-mono font-bold uppercase hover:bg-surface-container transition-colors" href="<?php echo BASE_URL; ?>/student/profile">
                <span class="material-symbols-outlined">person</span> <span class="text-button-text">My Profile</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>
<?php endif; ?>

<main class="flex-1 flex flex-col min-w-0 bg-surface">
    <!-- TopAppBar -->
    <header class="flex justify-between items-center w-full px-6 py-4 sticky top-0 z-50 bg-surface border-b-2 border-on-surface shadow-[4px_4px_0px_0px_rgba(0,74,198,0.2)]">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-primary text-3xl">videogame_asset</span>
            <a href="<?php echo BASE_URL; ?>/" class="text-2xl font-black text-primary tracking-widest uppercase font-mono hover:text-primary">IT ENGAGEMENT</a>
        </div>
        <div class="flex items-center gap-6">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="font-mono uppercase tracking-tighter font-black text-on-surface border-2 border-on-surface px-4 py-1 bg-surface-container-lowest hover:bg-surface-variant active:translate-x-[4px] active:translate-y-[4px] active:shadow-none transition-all duration-75 text-button-text">
                    LOGOUT
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/auth/login" class="font-mono uppercase tracking-tighter font-black text-on-surface border-2 border-on-surface px-4 py-1 bg-surface-container-lowest hover:bg-surface-variant active:translate-x-[4px] active:translate-y-[4px] active:shadow-none transition-all duration-75 text-button-text">
                    LOGIN
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div class="p-margin max-w-[1280px] w-full mx-auto space-y-lg relative pb-xl">
