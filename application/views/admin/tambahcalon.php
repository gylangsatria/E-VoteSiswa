<div class="card">
	<div class="card-header"><h2>Tambah Kandidat Ketua OSIM dan MPK</h2></div>
	<div class="card-body">
		<?php if($this->session->flashdata('info')) { ?>
			<div class="alert alert-success"><?php echo $this->session->flashdata('info'); ?></div>
		<?php } ?>
		<?php if($this->session->flashdata('failed')) { ?>
			<div class="alert alert-danger"><?php echo $this->session->flashdata('failed'); ?></div>
		<?php } ?>
		<?php echo form_open_multipart('admin/simpancalon', array('method' => 'post', 'class' => 'mx-auto max-w-xl')); ?>
			<label class="label" for="nisn">NISN</label>
			<?php
			$form_attribute	= array (
				'type'		=> 'text',
				'name'		=> 'nisn',
				'id'		=> 'nisn',
				'class'		=> 'input',
				'required'	=> 'required',
				'pattern'	=> '[0-9]{8,20}',
				'title'		=> 'Masukkan NISN yang valid (8-20 digit angka)'
			);
			echo form_input($form_attribute);
			?>

			<label class="label mt-4" for="opsi_mpkosis">Kandidat</label>
			<?php
			$options_kandidat = array(
				'0' => 'MPK',
				'1' => 'OSIM'
			);
			$form_attribute = array(
				'class'    => 'input',
				'name'     => 'opsi_mpkosis',
				'id'       => 'opsi_mpkosis',
				'required' => 'required'
			);
			echo form_dropdown($form_attribute['name'], $options_kandidat, '1', $form_attribute);
			?>

			<label class="label mt-4" for="nama">Nama Calon Ketua <span class="kategori-label">OSIM</span></label>
			<?php
			$form_attribute	= array (
				'type'		=> 'text',
				'name'		=> 'nama',
				'id'		=> 'nama',
				'class'		=> 'input',
				'required'	=> 'required'
			);
			echo form_input($form_attribute);
			?>

			<label class="label mt-4" for="nama_wakil">Nama Calon Wakil Ketua <span class="kategori-label">OSIM</span></label>
			<?php
			$form_attribute	= array (
				'type'		=> 'text',
				'name'		=> 'nama_wakil',
				'id'		=> 'nama_wakil',
				'class'		=> 'input',
				'required'	=> 'required'
			);
			echo form_input($form_attribute);
			?>

			<label class="label mt-4" for="no">Nomor Urut Paslon</label>
			<?php
			$form_attribute	= array (
				'type'		=> 'text',
				'name'		=> 'no',
				'id'		=> 'no',
				'class'		=> 'input',
				'required'	=> 'required',
				'pattern'	=> '[0-9]{1,3}',
				'title'		=> 'Masukkan nomor urut paslon (1-3 digit angka)'
			);
			echo form_input($form_attribute);
			?>

			<label class="label mt-4" for="photo">Foto Paslon</label>
			<?php
			$form_attribute = array (
				'name' => 'photo',
				'id' => 'photo',
				'class' => 'input',
				'accept' => '.jpg,.jpeg,.png,.gif'
			);
			echo form_upload($form_attribute);
			?>
			<button type="submit" class="btn btn-primary mt-5"><i class="fa fa-save"></i> Simpan Data</button>
		<?php echo form_close(); ?>
	</div>
</div>

<script>
document.getElementById('opsi_mpkosis').addEventListener('change', function() {
	var label = this.value === '1' ? 'OSIM' : 'MPK';
	document.querySelectorAll('.kategori-label').forEach(function(el) {
		el.textContent = label;
	});
});
</script>
