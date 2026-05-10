<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>IT_QUEST_SYSTEM</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Lexend:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container-low": "#f4f3f3",
                        "tertiary-fixed-dim": "#88da73",
                        "on-primary-container": "#735c00",
                        "on-tertiary-container": "#1e6d12",
                        "on-secondary-container": "#636262",
                        "on-tertiary": "#ffffff",
                        "tertiary-container": "#9aed83",
                        "secondary-container": "#e2dfde",
                        "primary-container": "#ffd54f",
                        "on-background": "#1a1c1c",
                        "surface-container-highest": "#e2e2e2",
                        surface: "#f9f9f9",
                        "surface-container": "#eeeeee",
                        "secondary-fixed-dim": "#c8c6c5",
                        "on-surface-variant": "#4d4634",
                        "on-error": "#ffffff",
                        primary: "#735c00",
                        "inverse-on-surface": "#f1f1f1",
                        "surface-container-lowest": "#ffffff",
                        error: "#ba1a1a",
                        tertiary: "#1e6d12",
                        "tertiary-fixed": "#a3f78c",
                        "on-secondary": "#ffffff",
                        "surface-variant": "#e2e2e2",
                        secondary: "#5f5e5e",
                        "on-primary": "#ffffff",
                        background: "#f9f9f9",
                        "outline-variant": "#d0c6ae",
                        "on-tertiary-fixed-variant": "#055300",
                        "surface-bright": "#f9f9f9",
                        "on-secondary-fixed-variant": "#474746",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-error-container": "#93000a",
                        "secondary-fixed": "#e5e2e1",
                        outline: "#7f7662",
                        "on-primary-fixed-variant": "#574500",
                        "error-container": "#ffdad6",
                        "on-primary-fixed": "#241a00",
                        "inverse-surface": "#2f3131",
                        "surface-tint": "#735c00",
                        "primary-fixed-dim": "#ebc23e",
                        "primary-fixed": "#ffe087",
                        "on-tertiary-fixed": "#012200",
                        "inverse-primary": "#ebc23e",
                        "on-surface": "#1a1c1c",
                        "surface-container-high": "#e8e8e8",
                        "surface-dim": "#dadada"
                    },
                    spacing: {
                        md: "16px",
                        sm: "8px",
                        unit: "4px",
                        xs: "4px",
                        margin: "24px",
                        lg: "32px",
                        gutter: "24px",
                        xl: "64px"
                    },
                    fontFamily: {
                        "label-pixel": ["Press Start 2P"],
                        h2: ["Press Start 2P"],
                        "button-text": ["Press Start 2P"],
                        "body-lg": ["Lexend"],
                        "body-md": ["Lexend"],
                        h1: ["Press Start 2P"],
                        h3: ["Press Start 2P"]
                    },
                    fontSize: {
                        "label-pixel": ["10px", { lineHeight: "1", letterSpacing: "1px", fontWeight: "400" }],
                        h2: ["18px", { lineHeight: "1.4", letterSpacing: "0px", fontWeight: "400" }],
                        "button-text": ["12px", { lineHeight: "1", letterSpacing: "0px", fontWeight: "400" }],
                        "body-lg": ["18px", { lineHeight: "1.6", letterSpacing: "0px", fontWeight: "400" }],
                        "body-md": ["16px", { lineHeight: "1.6", letterSpacing: "0px", fontWeight: "400" }],
                        h1: ["24px", { lineHeight: "1.5", letterSpacing: "0px", fontWeight: "400" }],
                        h3: ["12px", { lineHeight: "1.4", letterSpacing: "0px", fontWeight: "400" }]
                    }
                }
            }
        };
    </script>
    <style>
        .pixel-border { border: 4px solid #000000; }
        .pixel-shadow { box-shadow: 6px 6px 0px 0px rgba(0,0,0,1); }
        .pixel-shadow-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
        .pressed-active:active {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px 0px rgba(0,0,0,1);
        }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { min-height: max(884px, 100dvh); }
        dialog::backdrop { background: rgba(0, 0, 0, 0.6); }
        dialog { margin: auto; }
    </style>
</head>
<body class="bg-background font-body-md text-on-background selection:bg-primary-container">
<div class="flex min-h-screen">

<!-- NavigationDrawer -->
<?php if (isset($_SESSION['user_id'])): ?>
<aside class="h-screen w-64 flex-col border-r-4 border-black bg-white sticky top-0 hidden md:flex">
    <div class="p-6 flex flex-col items-start space-y-4 border-b-4 border-black bg-surface-container-lowest">
        <div class="w-16 h-16 pixel-border pixel-shadow-sm bg-primary-container overflow-hidden flex items-center justify-center">
            <span class="material-symbols-outlined text-4xl text-black">person</span>
        </div>
        <div>
            <h2 class="font-h2 text-h2 text-black truncate w-48"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></h2>
            <p class="font-label-pixel text-label-pixel text-zinc-500 mt-1 uppercase"><?php echo htmlspecialchars($_SESSION['role'] ?? 'Guest'); ?></p>
        </div>
    </div>
    <nav class="flex-1 py-4 overflow-y-auto space-y-2 px-2">
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/admin">
                <span class="material-symbols-outlined">dashboard</span> <span class="text-button-text">Dashboard</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/admin/quests">
                <span class="material-symbols-outlined">swords</span> <span class="text-button-text">Quests</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/admin/submissions">
                <span class="material-symbols-outlined">fact_check</span> <span class="text-button-text">Reviews</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/admin/rewards">
                <span class="material-symbols-outlined">inventory_2</span> <span class="text-button-text">Rewards</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/admin/redemptions">
                <span class="material-symbols-outlined">redeem</span> <span class="text-button-text">Redemptions</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/admin/students">
                <span class="material-symbols-outlined">groups</span> <span class="text-button-text">Students</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/admin/leaderboard">
                <span class="material-symbols-outlined">leaderboard</span> <span class="text-button-text">Leaderboard</span>
            </a>
        <?php else: ?>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/student">
                <span class="material-symbols-outlined">dashboard</span> <span class="text-button-text">Dashboard</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/student/quests">
                <span class="material-symbols-outlined">swords</span> <span class="text-button-text">Quests</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/student/submissions">
                <span class="material-symbols-outlined">fact_check</span> <span class="text-button-text">Submissions</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/student/rewards">
                <span class="material-symbols-outlined">inventory_2</span> <span class="text-button-text">Rewards</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/student/redemptions">
                <span class="material-symbols-outlined">redeem</span> <span class="text-button-text">Redemptions</span>
            </a>
            <a class="w-full flex items-center gap-4 p-4 text-black border-2 border-transparent font-mono font-bold uppercase hover:bg-zinc-100 transition-colors" href="<?php echo BASE_URL; ?>/student/leaderboard">
                <span class="material-symbols-outlined">leaderboard</span> <span class="text-button-text">Leaderboard</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>
<?php endif; ?>

<main class="flex-1 flex flex-col min-w-0 bg-surface">
    <!-- TopAppBar -->
    <header class="flex justify-between items-center w-full px-6 py-4 sticky top-0 z-50 bg-[#FFD54F] border-b-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-black text-3xl">videogame_asset</span>
            <a href="<?php echo BASE_URL; ?>/" class="text-2xl font-black text-black tracking-widest uppercase font-mono hover:text-black">IT QUEST</a>
        </div>
        <div class="flex items-center gap-6">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="font-mono uppercase tracking-tighter font-black text-black border-2 border-black px-4 py-1 bg-white hover:bg-zinc-100 active:translate-x-[4px] active:translate-y-[4px] active:shadow-none transition-all duration-75 text-button-text">
                    LOGOUT
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/auth/login" class="font-mono uppercase tracking-tighter font-black text-black border-2 border-black px-4 py-1 bg-white hover:bg-zinc-100 active:translate-x-[4px] active:translate-y-[4px] active:shadow-none transition-all duration-75 text-button-text">
                    LOGIN
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div class="p-margin max-w-[1280px] w-full mx-auto space-y-lg relative pb-xl">
