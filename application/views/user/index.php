<!-- Voting Progress Status -->
<div class="container">
	<div class="vote-progress-wrapper">
		<div class="vote-progress-header">
			<h2>
				<img src="<?= base_url(); ?>asset/img/logomt11.png" alt="Logo" class="vote-logo" onerror="this.style.display='none'">
				<span style="color:#fff">E-VoteSiswa</span>
			</h2>
			<p>Pilihlah Calon Ketua OSIS dan MPK dengan bijak!</p>
		</div>

		<div class="vote-progress-steps">
			<div class="vote-step <?= $sudah_memilih_osis ? 'completed' : '' ?>">
				<div class="vote-step-icon">
					<?php if ($sudah_memilih_osis): ?>
						<svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
					<?php else: ?>
						<span>1</span>
					<?php endif; ?>
				</div>
				<div class="vote-step-label">OSIS</div>
				<div class="vote-step-status <?= $sudah_memilih_osis ? 'done' : 'pending' ?>">
					<?= $sudah_memilih_osis ? 'Selesai' : 'Belum' ?>
				</div>
			</div>
			<div class="vote-step-connector <?= ($sudah_memilih_osis && !$sudah_memilih_mpk) ? 'active' : ($sudah_memilih_osis && $sudah_memilih_mpk ? 'completed' : '') ?>"></div>
			<div class="vote-step <?= $sudah_memilih_mpk ? 'completed' : '' ?>">
				<div class="vote-step-icon">
					<?php if ($sudah_memilih_mpk): ?>
						<svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
					<?php else: ?>
						<span>2</span>
					<?php endif; ?>
				</div>
				<div class="vote-step-label">MPK</div>
				<div class="vote-step-status <?= $sudah_memilih_mpk ? 'done' : 'pending' ?>">
					<?= $sudah_memilih_mpk ? 'Selesai' : 'Belum' ?>
				</div>
			</div>
		</div>

		<?php if (!$sudah_memilih_osis || !$sudah_memilih_mpk): ?>
			<div class="vote-notice">
				<svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
				<span>Anda harus menyelesaikan voting untuk <strong>OSIS</strong> dan <strong>MPK</strong></span>
			</div>
		<?php endif; ?>

		<?php if ($sudah_memilih_osis && $sudah_memilih_mpk): ?>
			<div class="vote-notice success">
				<svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
				<span>Anda telah menyelesaikan seluruh voting. Silakan <a href="<?= base_url(); ?>index.php/user/viewlogout">klik di sini untuk logout</a>.</span>
			</div>
		<?php endif; ?>
	</div>

	<!-- Calon Ketua OSIS -->
	<div class="section-wrapper">
		<div class="section-badge osis">
			<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
			<span>OSIS</span>
		</div>
		<h3 class="section-title">Calon Ketua OSIS</h3>

		<div class="row candidate-row">
			<?php 
			$osis_candidates = array_filter($datacalon, function($c) { return $c['opsi_mpkosis'] == 1; });
			$col_class = count($osis_candidates) <= 2 ? 'col-lg-6 col-md-6' : 'col-lg-4 col-md-6';
			?>
			<?php foreach($datacalon as $loaddata): ?>
				<?php if ($loaddata['opsi_mpkosis'] == 1): ?>
					<div class="<?= $col_class ?> col-sm-12">
						<div class="vote-card card-osis" data-number="<?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?>" data-name="<?= htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?>" data-nisn="<?= htmlspecialchars($loaddata['nisn'], ENT_QUOTES, 'UTF-8'); ?>" data-opsi="0" data-photo="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>">
							<div class="vote-card-badge">No. Urut <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></div>
							<div class="vote-card-img-wrap">
								<img class="vote-card-img" src="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto Calon OSIS" loading="lazy"/>
							</div>
							<div class="vote-card-body">
								<h4 class="vote-card-name"><?= htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?></h4>
								<p class="vote-card-position">Calon Ketua OSIS</p>
								<?php if (!$sudah_memilih_osis): ?>
									<button type="button" class="vote-btn btn-osis vote-trigger" data-target="osis">
										<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
										Pilih No <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?>
									</button>
								<?php else: ?>
									<button class="vote-btn btn-disabled" disabled>
										<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
										Sudah Memilih
									</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>

	<!-- Calon Ketua MPK -->
	<div class="section-wrapper">
		<div class="section-badge mpk">
			<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 0 0 4.561 21h14.878a2 2 0 0 0 1.94-1.515L22 17"/></svg>
			<span>MPK</span>
		</div>
		<h3 class="section-title">Calon Ketua MPK</h3>

		<div class="row candidate-row">
			<?php 
			$mpk_candidates = array_filter($datacalon, function($c) { return $c['opsi_mpkosis'] == 0; });
			$col_class_mpk = count($mpk_candidates) <= 2 ? 'col-lg-6 col-md-6' : 'col-lg-4 col-md-6';
			?>
			<?php foreach($datacalon as $loaddata): ?>
				<?php if ($loaddata['opsi_mpkosis'] == 0): ?>
					<div class="<?= $col_class_mpk ?> col-sm-12">
						<div class="vote-card card-mpk" data-number="<?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?>" data-name="<?= htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?>" data-nisn="<?= htmlspecialchars($loaddata['nisn'], ENT_QUOTES, 'UTF-8'); ?>" data-opsi="1" data-photo="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>">
							<div class="vote-card-badge">No. Urut <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></div>
							<div class="vote-card-img-wrap">
								<img class="vote-card-img" src="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto Calon MPK" loading="lazy"/>
							</div>
							<div class="vote-card-body">
								<h4 class="vote-card-name"><?= htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?></h4>
								<p class="vote-card-position">Calon Ketua MPK</p>
								<?php if (!$sudah_memilih_mpk): ?>
									<button type="button" class="vote-btn btn-mpk vote-trigger" data-target="mpk">
										<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
										Pilih No <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?>
									</button>
								<?php else: ?>
									<button class="vote-btn btn-disabled" disabled>
										<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
										Sudah Memilih
									</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="confirm-modal-content">
			<button type="button" class="confirm-modal-close" data-dismiss="modal" aria-label="Close">
				<svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
			</button>
			<div class="confirm-modal-icon">
				<svg viewBox="0 0 24 24" width="40" height="40" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
			</div>
			<h3 id="confirmModalLabel">Konfirmasi Pilihan</h3>
			<div class="confirm-modal-candidate">
				<img id="confirmPhoto" src="" alt="Foto Calon"/>
				<div>
					<span id="confirmCategory" class="confirm-category-badge"></span>
					<h4 id="confirmName"></h4>
					<p id="confirmNumber"></p>
				</div>
			</div>
			<p class="confirm-modal-text">Apakah Anda yakin dengan pilihan Anda?<br/>Setelah memilih, tidak dapat diubah kembali.</p>
			<div class="confirm-modal-actions">
				<button class="confirm-btn cancel" data-dismiss="modal">Batal</button>
				<?php echo form_open('user/vote', ['id' => 'voteForm', 'style' => 'display:inline']); ?>
					<input type="hidden" name="nisn" id="confirmNisn" value="">
					<input type="hidden" name="opsi_mpkosis" id="confirmOpsi" value="">
					<button type="submit" class="confirm-btn submit">
						<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
						Ya, Pilih!
					</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<style>
/* ─── Voting Progress ─── */
.vote-progress-wrapper {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	border-radius: 16px;
	padding: 32px 28px 28px;
	margin: 24px 0 36px;
	color: #fff;
	text-align: center;
	box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
	position: relative;
	overflow: hidden;
}
.vote-progress-wrapper::before {
	content: '';
	position: absolute;
	top: -50%;
	right: -20%;
	width: 300px;
	height: 300px;
	background: rgba(255,255,255,0.05);
	border-radius: 50%;
}
.vote-progress-wrapper::after {
	content: '';
	position: absolute;
	bottom: -30%;
	left: -10%;
	width: 200px;
	height: 200px;
	background: rgba(255,255,255,0.05);
	border-radius: 50%;
}
.vote-progress-header {
	position: relative;
	z-index: 1;
	margin-bottom: 20px;
}
.vote-progress-header h2 {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 10px;
	font-size: 24px;
	font-weight: 700;
	margin: 0 0 6px;
}
.vote-logo {
	width: 36px;
	height: 36px;
	object-fit: contain;
	filter: brightness(0) invert(1);
}
.vote-progress-header p {
	font-size: 14px;
	opacity: 0.85;
	margin: 0;
}

.vote-progress-steps {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 0;
	position: relative;
	z-index: 1;
}
.vote-step {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 6px;
}
.vote-step-icon {
	width: 44px;
	height: 44px;
	border-radius: 50%;
	background: rgba(255,255,255,0.2);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 18px;
	font-weight: 700;
	transition: all 0.3s ease;
}
.vote-step.completed .vote-step-icon {
	background: #fff;
	color: #667eea;
}
.vote-step-label {
	font-size: 13px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 1px;
}
.vote-step-status {
	font-size: 11px;
	padding: 2px 12px;
	border-radius: 20px;
	font-weight: 600;
}
.vote-step-status.done {
	background: rgba(255,255,255,0.25);
}
.vote-step-status.pending {
	background: rgba(255,255,255,0.1);
}
.vote-step-connector {
	width: 60px;
	height: 3px;
	background: rgba(255,255,255,0.2);
	border-radius: 2px;
	margin: 0 8px 26px;
	transition: all 0.4s ease;
}
.vote-step-connector.active {
	background: rgba(255,255,255,0.6);
	animation: pulseConnector 1.5s ease-in-out infinite;
}
.vote-step-connector.completed {
	background: #fff;
}

@keyframes pulseConnector {
	0%, 100% { opacity: 0.6; }
	50% { opacity: 1; }
}

.vote-notice {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	margin-top: 18px;
	padding: 10px 18px;
	background: rgba(255,255,255,0.12);
	border-radius: 10px;
	font-size: 14px;
	position: relative;
	z-index: 1;
	backdrop-filter: blur(8px);
}
.vote-notice.success {
	background: rgba(255,255,255,0.18);
}
.vote-notice a {
	color: #fff;
	font-weight: 600;
	text-decoration: underline;
}

/* ─── Section Wrapper ─── */
.section-wrapper {
	margin-bottom: 44px;
}
.section-badge {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 6px 16px;
	border-radius: 20px;
	font-size: 12px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 1px;
	margin-bottom: 8px;
}
.section-badge.osis {
	background: rgba(220, 53, 69, 0.1);
	color: #dc3545;
}
.section-badge.mpk {
	background: rgba(62, 169, 159, 0.1);
	color: var(--primary);
}
.section-title {
	font-size: 24px;
	font-weight: 700;
	color: var(--text);
	margin: 0 0 20px;
}

/* ─── Candidate Cards ─── */
.candidate-row {
	display: flex;
	flex-wrap: wrap;
}
.vote-card {
	background: var(--card-bg);
	border: 2px solid transparent;
	border-radius: 16px;
	overflow: hidden;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	margin-bottom: 24px;
	position: relative;
}
.vote-card:hover {
	border-color: var(--border);
	box-shadow: 0 12px 40px rgba(0,0,0,0.1);
	transform: translateY(-6px);
}
.vote-card.card-osis:hover {
	border-color: rgba(220, 53, 69, 0.3);
}
.vote-card.card-mpk:hover {
	border-color: rgba(62, 169, 159, 0.3);
}

.vote-card-badge {
	position: absolute;
	top: 12px;
	left: 12px;
	background: rgba(0,0,0,0.6);
	backdrop-filter: blur(8px);
	color: #fff;
	padding: 4px 14px;
	border-radius: 20px;
	font-size: 12px;
	font-weight: 600;
	z-index: 2;
	letter-spacing: 0.5px;
}

.vote-card-img-wrap {
	width: 100%;
	height: 260px;
	overflow: hidden;
	background: #f0f2f5;
	position: relative;
}
.vote-card-img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.5s ease;
}
.vote-card:hover .vote-card-img {
	transform: scale(1.05);
}

.vote-card-body {
	padding: 18px 20px 20px;
	text-align: center;
}
.vote-card-name {
	font-size: 18px;
	font-weight: 700;
	color: var(--text);
	margin: 0 0 2px;
}
.vote-card-position {
	font-size: 13px;
	color: var(--text-muted);
	margin: 0 0 14px;
}

.vote-btn {
	width: 100%;
	padding: 11px 16px;
	border-radius: 10px;
	font-weight: 600;
	font-size: 14px;
	border: none;
	transition: all 0.25s ease;
	cursor: pointer;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
}
.vote-btn:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.vote-btn:disabled {
	opacity: 0.6;
	cursor: not-allowed;
	transform: none !important;
	box-shadow: none !important;
}
.vote-btn.btn-osis {
	background: linear-gradient(135deg, #dc3545, #c82333);
	color: #fff;
}
.vote-btn.btn-mpk {
	background: linear-gradient(135deg, var(--primary), var(--primary-dark));
	color: #fff;
}
.vote-btn.btn-disabled {
	background: #e8eaed;
	color: #999;
}

/* ─── Confirmation Modal ─── */
.confirm-modal-content {
	background: #fff;
	border: none;
	border-radius: 20px;
	padding: 36px 32px 28px;
	text-align: center;
	position: relative;
	box-shadow: 0 24px 80px rgba(0,0,0,0.2);
}
.confirm-modal-close {
	position: absolute;
	top: 12px;
	right: 14px;
	background: none;
	border: none;
	cursor: pointer;
	color: #999;
	padding: 4px;
	transition: all 0.2s ease;
}
.confirm-modal-close:hover {
	color: #333;
	transform: rotate(90deg);
}
.confirm-modal-icon {
	width: 72px;
	height: 72px;
	border-radius: 50%;
	background: linear-gradient(135deg, #667eea, #764ba2);
	color: #fff;
	display: flex;
	align-items: center;
	justify-content: center;
	margin: 0 auto 16px;
}
.confirm-modal-content h3 {
	font-size: 20px;
	font-weight: 700;
	color: var(--text);
	margin: 0 0 16px;
}
.confirm-modal-candidate {
	display: flex;
	align-items: center;
	gap: 14px;
	background: #f8f9fa;
	border-radius: 12px;
	padding: 14px 18px;
	margin-bottom: 16px;
	text-align: left;
}
.confirm-modal-candidate img {
	width: 56px;
	height: 56px;
	border-radius: 50%;
	object-fit: cover;
	border: 2px solid var(--border);
	flex-shrink: 0;
}
.confirm-modal-candidate h4 {
	font-size: 16px;
	font-weight: 600;
	margin: 0 0 2px;
	color: var(--text);
}
.confirm-modal-candidate p {
	font-size: 13px;
	color: var(--text-muted);
	margin: 0;
}
.confirm-category-badge {
	display: inline-block;
	padding: 2px 10px;
	border-radius: 12px;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	margin-bottom: 4px;
}
.confirm-category-badge.osis-badge {
	background: rgba(220, 53, 69, 0.1);
	color: #dc3545;
}
.confirm-category-badge.mpk-badge {
	background: rgba(62, 169, 159, 0.1);
	color: var(--primary);
}

.confirm-modal-text {
	font-size: 14px;
	color: var(--text-muted);
	margin: 0 0 20px;
	line-height: 1.6;
}
.confirm-modal-actions {
	display: flex;
	gap: 10px;
	justify-content: center;
}
.confirm-btn {
	padding: 11px 28px;
	border-radius: 10px;
	font-weight: 600;
	font-size: 14px;
	border: none;
	cursor: pointer;
	transition: all 0.2s ease;
	display: inline-flex;
	align-items: center;
	gap: 6px;
}
.confirm-btn.cancel {
	background: #f0f2f5;
	color: var(--text);
}
.confirm-btn.cancel:hover {
	background: #e0e2e5;
}
.confirm-btn.submit {
	background: linear-gradient(135deg, #667eea, #764ba2);
	color: #fff;
}
.confirm-btn.submit:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.modal-dialog-centered {
	display: flex;
	align-items: center;
	min-height: calc(100% - 60px);
}

/* ─── Animations ─── */
.vote-card {
	animation: fadeInUp 0.5s ease both;
}
.vote-card:nth-child(1) { animation-delay: 0.05s; }
.vote-card:nth-child(2) { animation-delay: 0.1s; }
.vote-card:nth-child(3) { animation-delay: 0.15s; }
.vote-card:nth-child(4) { animation-delay: 0.2s; }
.vote-card:nth-child(5) { animation-delay: 0.25s; }
.vote-card:nth-child(6) { animation-delay: 0.3s; }

@keyframes fadeInUp {
	from { opacity: 0; transform: translateY(24px); }
	to { opacity: 1; transform: translateY(0); }
}

/* ─── Responsive ─── */
@media (max-width: 767px) {
	.vote-progress-wrapper { padding: 24px 16px 20px; }
	.vote-progress-header h2 { font-size: 20px; }
	.vote-step-connector { width: 30px; }
	.vote-card-img-wrap { height: 200px; }
	.confirm-modal-content { padding: 28px 20px 24px; }
	.confirm-modal-actions { flex-direction: column; }
	.confirm-btn { width: 100%; justify-content: center; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Confirmation modal trigger
	const triggers = document.querySelectorAll('.vote-trigger');
	triggers.forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			const card = this.closest('.vote-card');
			const name = card.dataset.name;
			const number = card.dataset.number;
			const nisn = card.dataset.nisn;
			const opsi = card.dataset.opsi;
			const photo = card.dataset.photo;
			const target = card.dataset.target || 'osis';

			document.getElementById('confirmName').textContent = name;
			document.getElementById('confirmNumber').textContent = 'Nomor Urut ' + number;
			document.getElementById('confirmNisn').value = nisn;
			document.getElementById('confirmOpsi').value = opsi;
			document.getElementById('confirmPhoto').src = photo;

			const badge = document.getElementById('confirmCategory');
			if (target === 'osis') {
				badge.textContent = 'OSIS';
				badge.className = 'confirm-category-badge osis-badge';
			} else {
				badge.textContent = 'MPK';
				badge.className = 'confirm-category-badge mpk-badge';
			}

			$('#confirmModal').modal('show');
		});
	});
});
</script>

