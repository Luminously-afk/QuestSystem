</div>
<footer
    class="w-full py-4 px-6 flex flex-col md:flex-row justify-between items-center bg-surface-container border-t-2 border-on-surface-variant gap-4 mt-auto z-10">
    <div class="flex items-center gap-4">
        <span class="font-mono text-xs uppercase text-on-surface-variant">© 2026 IT_QUEST_OS Ben & Rei</span>
        <span class="h-2 w-2 bg-primary rounded-full animate-pulse"></span>
    </div>
    <div class="flex gap-8">
        <a class="font-mono text-xs uppercase text-on-surface-variant hover:text-primary transition-colors" href="#">SUPPORT</a>
        <a class="font-mono text-xs uppercase text-on-surface-variant hover:text-primary transition-colors" href="#">SYSTEM_LOGS</a>
    </div>
</footer>
</main>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).showModal();
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).close();
        document.body.style.overflow = '';
    }
</script>
</body>

</html>