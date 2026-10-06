<?php
foreach($datakddpt as $load) { $dpt = $load; }
?>
<div class="card">
	<div class="card-header"><h2>Update DPT (Daftar Pemilih Tetap)</h2></div>
	<div class="card-body">
		<?php if($this->session->flashdata('info')) { ?>
			<div class="alert alert-success"><?php echo $this->session->flashdata('info'); ?></div>
		<?php } ?>
		<?php if($this->session->flashdata('failed')) { ?>
			<div class="alert alert-danger"><?php echo $this->session->flashdata('failed'); ?></div>
		<?php } ?>
		<?php echo form_open('admin/updatedpt', array('method' => 'post', 'class' => 'mx-auto max-w-md')); ?>
			<label class="label" for="edp-nisn">NISN</label>
			<?php
				echo form_input(array('type' => 'text', 'class' => 'input bg-slate-50', 'id' => 'edp-nisn', 'name' => 'nisn', 'value' => $dpt['username'], 'readonly' => ''));
			?>
			<label class="label mt-4" for="edp-nama">Nama</label>
			<?php
				echo form_input(array('type' => 'text', 'class' => 'input', 'id' => 'edp-nama', 'name' => 'nm_siswa', 'value' => $dpt['nm_siswa']));
			?>
			<label class="label mt-4" for="edp-jk">Jenis Kelamin</label>
			<select class="input" name="jk" id="edp-jk" required>
				<option value="L" <?php echo ($dpt['jk'] == 'L') ? 'selected' : ''; ?>>L</option>
				<option value="P" <?php echo ($dpt['jk'] == 'P') ? 'selected' : ''; ?>>P</option>
			</select>
			<label class="label mt-4" for="edp-kelas">Kelas</label>
			<select class="input" name="kd_kelas" id="edp-kelas" required>
				<?php foreach($datakelas as $kelas) { ?>
					<option value="<?php echo $kelas['kd_kelas']; ?>" <?php echo ($kelas['kd_kelas'] == $dpt['kd_kelas']) ? 'selected' : ''; ?>> <?php echo $kelas['nm_kelas']; ?> </option>
				<?php } ?>
			</select>
			<button type="submit" class="btn btn-primary mt-5"><i class="fa fa-save"></i> Simpan DPT</button>
		<?php echo form_close(); ?>
	</div>
</div>
