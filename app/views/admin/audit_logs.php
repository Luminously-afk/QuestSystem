<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-black">AUDIT LOGS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">SYSTEM AUDIT LOGS</h2>
</div>

<section class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
    <div class="p-4 border-b-4 border-black flex flex-wrap gap-4 justify-between items-center bg-zinc-50">
        <h2 class="font-h2 text-black uppercase font-black">ADMINISTRATIVE ACTIONS</h2>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($logs)): ?>
            <div class="p-6 font-mono text-sm uppercase">No logs recorded yet.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-zinc-100 border-b-4 border-black">
                    <tr>
                        <th class="p-4 uppercase font-black">ADMIN</th>
                        <th class="p-4 uppercase font-black">ACTION</th>
                        <th class="p-4 uppercase font-black">DESCRIPTION</th>
                        <th class="p-4 uppercase font-black text-right">TIMESTAMP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr class="border-b-2 border-zinc-100 hover:bg-zinc-50">
                            <td class="p-4 font-bold text-black uppercase"><?php echo htmlspecialchars($log['full_name'] ?? 'Unknown'); ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-black font-black uppercase bg-[#ffd54f] text-black">
                                    <?php echo htmlspecialchars($log['action']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-xs"><?php echo htmlspecialchars($log['description']); ?></td>
                            <td class="p-4 text-xs uppercase text-right"><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($log['created_at']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../app/views/layouts/footer.php'; ?>
