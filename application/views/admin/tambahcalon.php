<div class="box">
	<div class="box-inner">
		<div class="box-header well">
			<h2>Tambah Kandidat Ketua OSIM dan MPK</h2>
		</div>
		<div class="box-content">
			<?php if($this->session->flashdata('info')) { ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('info'); ?>
				</div>
			<?php } ?>
			<?php if($this->session->flashdata('failed')) { ?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('failed'); ?>
				</div>
			<?php } ?>
			<?php 
			$form_attribute = array (
				'method'	=> 'post',
				'class'		=> 'form-horizontal'
			);
			echo form_open_multipart('admin/simpancalon', $form_attribute);
			?>
			<div class="form-container">
				<label class="label-control" for="nisn"> NISN</label>
				<?php 
				$form_attribute	= array (
					'type'		=> 'text',
					'name'		=> 'nisn',
					'id'		=> 'nisn',
					'class'		=> 'form-control',
					'required'	=> 'required',
					'pattern'	=> '[0-9]{8,20}',
					'title'		=> 'Masukkan NISN yang valid (8-20 digit angka)'
				);
				echo form_input($form_attribute);
				?>

				<label class="label-control" for="opsi_mpkosis">Kandidat</label>
				<?php
				$options_kandidat = array(
					'0' => 'MPK',
					'1' => 'OSIM'
				);
				$form_attribute = array(
					'class'    => 'form-control',
					'name'     => 'opsi_mpkosis',
					'id'       => 'opsi_mpkosis',
					'required' => 'required'
				);
				echo form_dropdown($form_attribute['name'], $options_kandidat, '1', $form_attribute);
				?>

				<label class="label-control" for="nama"> Nama Calon Ketua <span class="kategori-label">OSIM</span></label>
				<?php 
				$form_attribute	= array (
					'type'		=> 'text',
					'name'		=> 'nama',
					'id'		=> 'nama',
					'class'		=> 'form-control',
					'required'	=> 'required'
				);
				echo form_input($form_attribute);
				?>

				<label class="label-control" for="nama_wakil"> Nama Calon Wakil Ketua <span class="kategori-label">OSIM</span></label>
				<?php 
				$form_attribute	= array (
					'type'		=> 'text',
					'name'		=> 'nama_wakil',
					'id'		=> 'nama_wakil',
					'class'		=> 'form-control',
					'required'	=> 'required'
				);
				echo form_input($form_attribute);
				?>

				<label class="label-control" for="no"> Nomor Urut Paslon</label>
				<?php 
				$form_attribute	= array (
					'type'		=> 'text',
					'name'		=> 'no',
					'id'		=> 'no',
					'class'		=> 'form-control',
					'required'	=> 'required',
					'pattern'	=> '[0-9]{1,3}',
					'title'		=> 'Masukkan nomor urut paslon (1-3 digit angka)'
				);
				echo form_input($form_attribute);
				?>

				<label class="label-control" for="photo"> Foto Paslon</label>
				<?php 
				$form_attribute = array (
					'name' => 'photo',
					'id' => 'photo',
					'class' => 'form-control',
					'accept' => '.jpg,.jpeg,.png,.gif'
				);
				echo form_upload($form_attribute);
				?>
				<br/>
				<button type="submit" class="btn btn-primary"> Simpan Data</button>
			</div>
			<?php 
			echo form_close();
			?>
		</div>
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
