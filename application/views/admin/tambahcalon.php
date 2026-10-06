<div class="card">
	<div class="card-header"><h2>Tambah Kandidat Ketua <?php echo org_label('organisasi'); ?> dan MPK</h2></div>
	<div class="card-body">
		<?php if($this->session->flashdata('info')) { ?>
			<div class="alert alert-success"><?php echo $this->session->flashdata('info'); ?></div>
		<?php } ?>
		<?php if($this->session->flashdata('failed')) { ?>
			<div class="alert alert-danger"><?php echo $this->session->flashdata('failed'); ?></div>
		<?php } ?>
		<?php echo form_open_multipart('admin/simpancalon', array('method' => 'post', 'class' => 'mx-auto max-w-3xl')); ?>
			<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
				<div>
					<label class="label" for="nisn">NISN</label>
					<?php
					echo form_input(array(
						'type'    => 'text',
						'name'    => 'nisn',
						'id'      => 'nisn',
						'class'   => 'input',
						'required' => 'required',
						'pattern' => '[0-9]{8,20}',
						'title'   => 'Masukkan NISN yang valid (8-20 digit angka)'
					));
					?>
				</div>

				<div>
					<label class="label" for="no">Nomor Urut Paslon</label>
					<?php
					echo form_input(array(
						'type'    => 'text',
						'name'    => 'no',
						'id'      => 'no',
						'class'   => 'input',
						'required' => 'required',
						'pattern' => '[0-9]{1,3}',
						'title'   => 'Masukkan nomor urut paslon (1-3 digit angka)'
					));
					?>
				</div>

				<div>
					<label class="label" for="opsi_mpkosis">Kandidat</label>
					<?php
					echo form_dropdown('opsi_mpkosis', array('0' => 'MPK', '1' => org_label('organisasi')), '1', array(
						'class'    => 'input',
						'id'       => 'opsi_mpkosis',
						'required' => 'required'
					));
					?>
				</div>

				<div>
					<label class="label" for="photo">Foto Paslon</label>
					<?php
					echo form_upload(array(
						'name'   => 'photo',
						'id'     => 'photo',
						'class'  => 'input',
						'accept' => '.jpg,.jpeg,.png,.gif'
					));
					?>
					<p class="mt-1 text-xs text-slate-400">Format JPG, JPEG, PNG, atau GIF. Maks 1 MB.</p>
				</div>

				<div class="sm:col-span-2">
					<label class="label" for="nama">Nama Calon Ketua <span class="kategori-label"><?php echo org_label('organisasi'); ?></span></label>
					<?php
					echo form_input(array(
						'type'    => 'text',
						'name'    => 'nama',
						'id'      => 'nama',
						'class'   => 'input',
						'required' => 'required'
					));
					?>
				</div>

				<div class="sm:col-span-2">
					<label class="label" for="nama_wakil">Nama Calon Wakil Ketua <span class="kategori-label"><?php echo org_label('organisasi'); ?></span></label>
					<?php
					echo form_input(array(
						'type'    => 'text',
						'name'    => 'nama_wakil',
						'id'      => 'nama_wakil',
						'class'   => 'input',
						'required' => 'required'
					));
					?>
				</div>
			</div>

			<button type="submit" class="btn btn-primary mt-6"><i class="fa fa-save"></i> Simpan Data</button>
		<?php echo form_close(); ?>
	</div>
</div>

<script>
document.getElementById('opsi_mpkosis').addEventListener('change', function() {
	var label = this.value === '1' ? '<?php echo org_label('organisasi'); ?>' : 'MPK';
	document.querySelectorAll('.kategori-label').forEach(function(el) {
		el.textContent = label;
	});
});
</script>
