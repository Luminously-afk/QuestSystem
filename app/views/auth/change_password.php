<?php require_once '../app/views/layouts/header.php'; ?>

<div class="flex justify-center items-center min-h-[70vh]">
    <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] flex flex-col">
        <div class="bg-tertiary border-b-4 border-on-surface p-4 text-center">
            <h2 class="font-h2 uppercase text-white tracking-widest" style="text-shadow: 2px 2px 0px #000;">CHANGE PASSWORD</h2>
        </div>

        <div class="p-8">
            <?php if (!empty($first_login)): ?>
                <div class="bg-[#ffd54f] border-2 border-on-surface p-4 mb-6 font-mono font-bold uppercase text-sm pixel-shadow-sm text-on-surface">
                    You must change your password before continuing.
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="bg-error-container border-2 border-on-surface p-4 mb-6 font-mono font-bold uppercase text-sm pixel-shadow-sm text-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo BASE_URL; ?>/auth/changePassword" class="flex flex-col gap-6 font-mono">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold uppercase">New Password</label>
                    <input type="password" name="new_password" class="border-2 border-on-surface p-3 focus:outline-none focus:border-tertiary text-lg" required autofocus>
                    <div class="text-[10px] text-secondary uppercase font-bold mt-1">At least 6 characters.</div>
                </div>
                
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold uppercase">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="border-2 border-on-surface p-3 focus:outline-none focus:border-tertiary text-lg" required>
                </div>
                
                <div class="flex gap-4 mt-4">
                    <button type="submit" class="flex-1 bg-tertiary text-white border-2 border-on-surface p-4 font-button-text hover:bg-[#154d0d] active:translate-y-1 pixel-shadow-sm uppercase">
                        UPDATE
                    </button>
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="bg-zinc-200 text-on-surface border-2 border-on-surface p-4 font-button-text hover:bg-zinc-300 active:translate-y-1 pixel-shadow-sm uppercase text-center">
                        LOGOUT
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
