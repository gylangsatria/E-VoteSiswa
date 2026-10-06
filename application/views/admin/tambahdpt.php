<?php if($this->session->flashdata('log')) { ?>
    <div class="alert alert-warning">
        <div>
            <strong>Log Debug:</strong>
            <ul class="ml-5 list-disc">
                <?php foreach($this->session->flashdata('log') as $baris) { ?>
                    <li><?php echo htmlspecialchars($baris); ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>

<div class="card">
	<div class="card-header"><h2>Tambah DPT (Daftar Pemilih Tetap)</h2></div>
	<div class="card-body">
		<?php if($this->session->flashdata('info')) { ?>
			<div class="alert alert-success"><?php echo $this->session->flashdata('info'); ?></div>
		<?php } ?>
		<?php if($this->session->flashdata('failed')) { ?>
			<div class="alert alert-danger"><?php echo $this->session->flashdata('failed'); ?></div>
		<?php } ?>

		<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
			<div>
				<h4 class="font-semibold text-slate-800">Tambah Data Satu/Satu</h4>
				<hr class="my-4 border-slate-200"/>
				<?php echo form_open_multipart('admin/simpandpt', array('method' => 'post')); ?>
					<label class="label" for="nisn">NISN</label>
					<?php
						echo form_input(array(
							'type'		=> 'text',
							'class'		=> 'input',
							'name'		=> 'nisn',
							'id'		=> 'nisn',
							'required'	=> 'required',
							'pattern'	=> '[0-9]{8,20}',
							'title'		=> 'Masukkan NISN yang valid (8-20 digit angka)'
						));
					?>
					<label class="label mt-4" for="nm_siswa">Nama</label>
					<?php
						echo form_input(array('type' => 'text', 'class' => 'input', 'name' => 'nm_siswa', 'id' => 'nm_siswa', 'required' => 'required'));
					?>
					<label class="label mt-4" for="jk">Jenis Kelamin</label>
					<select class="input" name="jk" id="jk">
						<option selected value="L">L</option>
						<option value="P">P</option>
					</select>
					<label class="label mt-4" for="kd_kelas">Kelas</label>
					<select class="input" name="kd_kelas" id="kd_kelas" required>
						<?php foreach($datakelas as $load) { ?>
							<option value="<?php echo $load['kd_kelas']; ?>"> (<?php echo $load['kd_kelas']; ?>) <?php echo $load['nm_kelas']; ?> </option>
						<?php } ?>
					</select>
					<button type="submit" class="btn btn-primary mt-5"><i class="fa fa-save"></i> Simpan DPT</button>
				<?php echo form_close(); ?>
			</div>

			<div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
				<h4 class="font-semibold text-slate-800">Tambah Data Massal</h4>
				<hr class="my-4 border-slate-200"/>
				<p class="text-sm text-slate-500">Gunakan file Excel dengan format: kolom NISN, Nama, JK (L/P), dan Kode Kelas.</p>
				<?php echo form_open_multipart('admin/simpanmassaldpt', array('method' => 'post', 'class' => 'mt-4')); ?>
					<label class="label" for="datadpt">Upload File DPT (Excel)</label>
					<input name="datadpt" id="datadpt" type="file" required class="input" accept=".xls,.xlsx"/>
					<button type="submit" class="btn btn-primary mt-4 w-full"><i class="fa fa-cloud-upload"></i> Upload Data</button>
				<?php echo form_close(); ?>
				<p class="mt-4 text-xs text-slate-400">Pastikan file Excel memiliki format yang benar. Data akan ditambahkan ke DPT yang sudah ada.</p>
			</div>
		</div>
	</div>
</div>

