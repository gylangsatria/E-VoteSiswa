<div class="mx-auto max-w-5xl">
    <div class="relative mb-9 mt-6 overflow-hidden rounded-2xl border border-blue-100 bg-blue-50 px-6 py-8 text-center text-slate-800 shadow-sm">
        <h2 class="mb-1 flex items-center justify-center gap-2 text-xl font-bold text-blue-700 sm:text-2xl">
            <img class="h-9 w-9 object-contain" src="<?= base_url(); ?>asset/img/logomt11.png" alt="Logo" data-hide-on-error>
            E-VoteSiswa
        </h2>
        <p class="text-sm text-blue-600/80">Pilihlah Calon Ketua dan Wakil Ketua <?= org_label('organisasi'); ?> dan MPK dengan bijak!</p>

        <?php if (!empty($jadwal) && !empty($jadwal['aktif'])): ?>
            <div class="mt-4 flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm <?= $voting_open ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'; ?>">
                <i class="fa fa-clock-o"></i>
                <span>
                    <?php if ($voting_open): ?>
                        Voting dibuka sampai <strong><?= tgl_jadwal($jadwal); ?></strong>.
                    <?php else: ?>
                        Voting hanya dibuka pada <strong><?= tgl_jadwal($jadwal); ?></strong>.
                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

        <div class="mt-5 flex items-center justify-center gap-2">
            <?php foreach ([[org_label('organisasi'), $sudah_memilih_osis, '1'], ['MPK', $sudah_memilih_mpk, '2']] as $i => $step): ?>
                <?php if ($i === 1): ?>
                    <div class="mb-6 h-[3px] w-8 rounded sm:w-14 <?= ($sudah_memilih_osis && $sudah_memilih_mpk) ? 'bg-blue-600' : ($sudah_memilih_osis ? 'bg-blue-400' : 'bg-blue-200') ?>"></div>
                <?php endif; ?>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full text-lg font-bold <?= $step[1] ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-500' ?>">
                        <?php if ($step[1]): ?><i class="fa fa-check"></i><?php else: ?><?= $step[2]; ?><?php endif; ?>
                    </div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-blue-700"><?= $step[0]; ?></div>
                    <div class="rounded-full px-3 py-0.5 text-[11px] font-semibold <?= $step[1] ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-500' ?>"><?= $step[1] ? 'Selesai' : 'Belum' ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!$sudah_memilih_osis || !$sudah_memilih_mpk): ?>
            <div class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-blue-100 px-4 py-2.5 text-sm text-blue-700">
                <i class="fa fa-exclamation-triangle"></i>
                <span>Anda harus menyelesaikan voting untuk <strong><?= org_label('organisasi'); ?></strong> dan <strong>MPK</strong></span>
            </div>
        <?php endif; ?>

    </div>

    <?php foreach ([
        ['key' => 1, 'target' => 'osis', 'badge' => org_label('organisasi'), 'title' => 'Calon Ketua dan Wakil Ketua ' . org_label('organisasi'), 'done' => $sudah_memilih_osis, 'badge_class' => 'bg-red-500/10 text-red-600'],
        ['key' => 0, 'target' => 'mpk',  'badge' => 'MPK',  'title' => 'Calon Ketua dan Wakil Ketua MPK',  'done' => $sudah_memilih_mpk,  'badge_class' => 'bg-brand-100 text-brand-600'],
    ] as $section):
        $list = array_values(array_filter($datacalon, function ($c) use ($section) { return $c['opsi_mpkosis'] == $section['key']; }));
    ?>
        <div class="mb-11">
            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-wider <?= $section['badge_class']; ?>">
                <i class="fa fa-graduation-cap"></i><span><?= $section['badge']; ?></span>
            </div>
            <h3 class="mb-5 text-2xl font-bold text-slate-800"><?= $section['title']; ?></h3>

            <?php if (empty($list)): ?>
                <div class="rounded-xl border border-dashed border-slate-300 bg-white py-10 text-center text-sm text-slate-400">Belum ada kandidat <?= $section['badge']; ?>.</div>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 <?= count($list) > 2 ? 'lg:grid-cols-3' : '' ?>">
                    <?php foreach ($list as $loaddata): ?>
                        <div class="overflow-hidden rounded-2xl border-2 border-transparent bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl <?= $section['target'] === 'osis' ? 'hover:border-red-500/30' : 'hover:border-brand-500/30'; ?>"
                             data-number="<?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-name="<?= htmlspecialchars($loaddata['nama'] . ' / ' . $loaddata['nama_wakil'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-nisn="<?= htmlspecialchars($loaddata['nisn'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-opsi="<?= $section['key']; ?>"
                             data-photo="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>"
                             data-target="<?= $section['target']; ?>">
                            <div class="relative">
                                <span class="absolute left-3 top-3 z-10 rounded-full bg-black/60 px-3.5 py-1 text-xs font-semibold text-white backdrop-blur">No. Urut <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <img class="h-52 w-full object-cover" src="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto Calon <?= $section['badge']; ?>" loading="lazy"/>
                            </div>
                            <div class="p-5 text-center">
                                <h4 class="text-lg font-bold text-slate-800"><?= htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                <p class="text-sm text-slate-500">Calon Ketua dan Wakil Ketua <?= $section['badge']; ?></p>
                                <p class="mb-4 text-sm text-slate-500">Wakil: <?= htmlspecialchars($loaddata['nama_wakil'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php if (!$section['done'] && !empty($voting_open)): ?>
                                    <button type="button" class="vote-trigger inline-flex w-full items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white transition <?= $section['target'] === 'osis' ? 'bg-red-600 hover:bg-red-700' : 'bg-brand-500 hover:bg-brand-600'; ?>">
                                        <i class="fa fa-check"></i> Pilih No <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?>
                                    </button>
                                <?php elseif (!$section['done']): ?>
                                    <button class="inline-flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-lg bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500" disabled>
                                        <i class="fa fa-clock-o"></i> Voting Ditutup
                                    </button>
                                <?php else: ?>
                                    <button class="inline-flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-lg bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500" disabled>
                                        <i class="fa fa-check"></i> Sudah Memilih
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<dialog id="confirmModal" class="w-[92vw] max-w-md rounded-2xl p-0 backdrop:bg-black/60">
	<div class="relative px-6 py-7 text-center">
		<button type="button" class="absolute right-4 top-4 text-slate-400 hover:text-slate-600" data-close-dialog aria-label="Close">
			<i class="fa fa-close text-xl"></i>
		</button>
		<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 text-brand-600">
			<i class="fa fa-shield text-3xl"></i>
		</div>
		<h3 class="mb-5 text-lg font-bold text-slate-800">Konfirmasi Pilihan</h3>
		<div class="mb-5 flex items-center gap-4 rounded-xl bg-slate-50 p-4 text-left">
			<img id="confirmPhoto" class="h-20 w-16 rounded-lg object-cover" src="" alt="Foto Calon"/>
			<div>
				<span id="confirmCategory" class="mb-1 inline-block rounded-full bg-brand-100 px-3 py-0.5 text-xs font-semibold text-brand-600"></span>
				<h4 id="confirmName" class="font-semibold text-slate-800"></h4>
				<p id="confirmNumber" class="text-sm text-slate-500"></p>
			</div>
		</div>
		<p class="mb-6 text-sm text-slate-500">Apakah Anda yakin dengan pilihan Anda?<br/>Setelah memilih, tidak dapat diubah kembali.</p>
		<div class="flex gap-3">
			<button type="button" class="btn btn-default flex-1" data-close-dialog>Batal</button>
			<?php echo form_open('user/vote', ['id' => 'voteForm', 'class' => 'flex-1']); ?>
				<input type="hidden" name="nisn" id="confirmNisn" value="">
				<input type="hidden" name="opsi_mpkosis" id="confirmOpsi" value="">
				<button type="submit" class="btn btn-primary w-full"><i class="fa fa-check"></i> Ya, Pilih!</button>
			<?php echo form_close(); ?>
		</div>
	</div>
</dialog>

<script>
document.querySelectorAll('.vote-trigger').forEach(function (btn) {
	btn.addEventListener('click', function () {
		var card = this.closest('[data-target]');
		document.getElementById('confirmPhoto').src = card.dataset.photo;
		document.getElementById('confirmName').textContent = card.dataset.name;
		document.getElementById('confirmNumber').textContent = 'No. Urut ' + card.dataset.number;
		document.getElementById('confirmCategory').textContent = card.dataset.target === 'osis' ? '<?= org_label('organisasi'); ?>' : 'MPK';
		document.getElementById('confirmNisn').value = card.dataset.nisn;
		document.getElementById('confirmOpsi').value = card.dataset.opsi;
		document.getElementById('confirmModal').showModal();
	});
});
</script>

