<?php if (! $sudah_memilih_osis || ! $sudah_memilih_mpk): ?>
    <div class="alert alert-warning text-center">
        Anda belum menyelesaikan voting untuk OSIS dan MPK.
    </div>
<?php endif; ?>

<style>
	.vote-card {
		background: #fff;
		border: none;
		border-radius: 12px;
		box-shadow: 0 1px 4px rgba(0,0,0,0.08);
		overflow: hidden;
		transition: all 0.25s ease;
		margin-bottom: 24px;
	}
	.vote-card:hover {
		box-shadow: 0 8px 24px rgba(0,0,0,0.12);
		transform: translateY(-4px);
	}
	.vote-card .card-img {
		width: 100%;
		height: 280px;
		object-fit: cover;
		display: block;
		background: #f5f5f5;
	}
	.vote-card .card-body {
		padding: 16px 18px 20px;
		text-align: center;
	}
	.vote-card .card-number {
		font-size: 13px;
		font-weight: 700;
		color: #3EA99F;
		text-transform: uppercase;
		letter-spacing: 1px;
		margin-bottom: 4px;
	}
	.vote-card .card-name {
		font-size: 18px;
		font-weight: 600;
		color: #2c3e50;
		margin: 0 0 12px;
	}
	.vote-card .btn-vote {
		width: 100%;
		padding: 10px;
		border-radius: 8px;
		font-weight: 600;
		font-size: 15px;
		border: none;
		transition: all 0.2s ease;
		cursor: pointer;
	}
	.vote-card .btn-vote:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(0,0,0,0.15);
	}
	.vote-card .btn-vote:disabled {
		opacity: 0.6;
		cursor: not-allowed;
		transform: none !important;
		box-shadow: none !important;
	}
	.vote-card .btn-osis { background: #dc3545; color: #fff; }
	.vote-card .btn-osis:hover { background: #c82333; }
	.vote-card .btn-mpk { background: #3EA99F; color: #fff; }
	.vote-card .btn-mpk:hover { background: #2d8a82; }
	.vote-card .btn-disabled { background: #6c757d; color: #fff; }
	.section-title {
		font-size: 22px;
		font-weight: 700;
		color: #2c3e50;
		text-align: center;
		margin: 40px 0 24px;
		position: relative;
	}
	.section-title::after {
		content: '';
		display: block;
		width: 60px;
		height: 3px;
		background: #3EA99F;
		margin: 8px auto 0;
		border-radius: 2px;
	}
</style>

<div class="container">
	<div class="box">
		<div class="box-inner">
			<div class="box-header well">
				<h2>Selamat Datang di E-VoteSiswa</h2>
			</div>
			<div class="box-content">
				<p class="text-center" style="font-size: 15px; color: #7f8c8d;">Silakan pilih Calon Ketua OSIS dan Ketua MPK di bawah ini.</p>
				<hr style="border-color: #e8eaed; margin: 20px 0 30px;"/>

				<!-- Calon Ketua OSIS -->
				<h3 class="section-title">Calon Ketua OSIS</h3>
				<div class="row">
					<?php foreach($datacalon as $loaddata): ?>
						<?php if ($loaddata['opsi_mpkosis'] == 1): ?>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="vote-card">
									<img class="card-img" src="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto Calon OSIS"/>
									<div class="card-body">
										<div class="card-number">Nomor Urut <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></div>
										<h4 class="card-name"><?= htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?></h4>
										<?php if (!$sudah_memilih_osis): ?>
											<?php echo form_open('user/vote'); ?>
												<input type="hidden" name="nisn" value="<?= htmlspecialchars($loaddata['nisn'], ENT_QUOTES, 'UTF-8'); ?>">
												<input type="hidden" name="opsi_mpkosis" value="0">
												<button type="submit" class="btn-vote btn-osis">Pilih No <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></button>
											<?php echo form_close(); ?>
										<?php else: ?>
											<button class="btn-vote btn-disabled" disabled>Sudah Memilih</button>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<hr style="border-color: #e8eaed; margin: 30px 0;"/>

				<!-- Calon Ketua MPK -->
				<h3 class="section-title">Calon Ketua MPK</h3>
				<div class="row">
					<?php foreach($datacalon as $loaddata): ?>
						<?php if ($loaddata['opsi_mpkosis'] == 0): ?>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="vote-card">
									<img class="card-img" src="<?= base_url(); ?>asset/img/<?= htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto Calon MPK"/>
									<div class="card-body">
										<div class="card-number">Nomor Urut <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></div>
										<h4 class="card-name"><?= htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?></h4>
										<?php if (!$sudah_memilih_mpk): ?>
											<?php echo form_open('user/vote'); ?>
												<input type="hidden" name="nisn" value="<?= htmlspecialchars($loaddata['nisn'], ENT_QUOTES, 'UTF-8'); ?>">
												<input type="hidden" name="opsi_mpkosis" value="1">
												<button type="submit" class="btn-vote btn-mpk">Pilih No <?= htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></button>
											<?php echo form_close(); ?>
										<?php else: ?>
											<button class="btn-vote btn-disabled" disabled>Sudah Memilih</button>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>

