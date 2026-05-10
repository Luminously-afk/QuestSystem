<?php require_once '../app/views/layouts/header.php'; ?>

<div class="flex justify-center items-center min-h-[70vh]">
    <div class="w-full max-w-md bg-white border-4 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] flex flex-col">
        <div class="bg-primary border-b-4 border-black p-4 text-center">
            <h2 class="font-h1 text-h2 uppercase text-white tracking-widest" style="text-shadow: 2px 2px 0px #000;">LOGIN</h2>
        </div>

        <div class="p-8">
            <?php if (!empty($success)): ?>
                <div class="bg-[#9aed83] border-4 border-black p-4 mb-6 font-mono font-bold uppercase text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-black">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($info)): ?>
                <div class="bg-[#ffd54f] border-4 border-black p-4 mb-6 font-mono font-bold uppercase text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-black">
                    <?php echo htmlspecialchars($info); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="bg-[#ffdad6] border-4 border-black p-4 mb-6 font-mono font-bold uppercase text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-[#ba1a1a]">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo BASE_URL; ?>/auth/login" class="flex flex-col gap-6 font-mono">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold uppercase">Email or Student ID</label>
                    <input type="text" name="identifier" class="border-4 border-black p-3 focus:outline-none focus:border-primary text-lg" value="<?php echo htmlspecialchars($old['identifier'] ?? ''); ?>" required autofocus>
                    <div class="text-[10px] text-secondary uppercase font-bold mt-1">Example: 241c-1234</div>
                </div>
                
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold uppercase">Password</label>
                    <input type="password" name="password" class="border-4 border-black p-3 focus:outline-none focus:border-primary text-lg" required>
                </div>
                
                <button type="submit" class="bg-primary text-white border-4 border-black p-4 font-button-text hover:bg-[#3d3db6] active:translate-y-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] uppercase text-lg mt-2">
                    START <span class="material-symbols-outlined align-middle ml-2 text-xl">play_arrow</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t-4 border-black border-dashed text-center">
                <p class="font-mono text-xs uppercase text-secondary font-bold">Need an account? Contact the admin.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
