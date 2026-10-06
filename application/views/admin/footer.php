<?php
	foreach($idsekolah as $load) {}
?>
</main><!--/#content-->
</div><!--/flex-col-->
<footer class="mt-auto border-t border-slate-200 bg-white px-4 py-5 text-center text-xs text-slate-500 sm:px-6">
    <div class="flex flex-col items-center justify-between gap-2 sm:flex-row">
        <p>&copy; <?php echo date('Y') ?> <a class="text-brand-600 hover:underline" href="https://github.com/fpls-software/pilketo" target="_blank">Original Epilketos</a> | <a class="text-brand-600 hover:underline" href="https://gylang.my.id">Recreate by Gylang Satria</a></p>
        <p><b><a class="text-brand-600 hover:underline" href="https://github.com/gylangsatria/E-VoteSiswa" target="_blank">E-VoteSiswa V.1.3.2</a></b></p>
        <p>Powered by: <a class="text-brand-600 hover:underline" href="#"><?php echo $load['nm_sekolah']; ?></a></p>
    </div>
</footer>
</body>
</html>
