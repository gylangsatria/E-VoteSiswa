<?php
	foreach($idsekolah as $load) {}
?>
<div class="card">
	<div class="card-header"><h2>Identitas Sekolah</h2></div>
	<div class="card-body">
		<?php if($this->session->flashdata('info')) { ?>
			<div class="alert alert-success"><?php echo $this->session->flashdata('info'); ?></div>
		<?php } ?>
		<?php if($this->session->flashdata('failed')) { ?>
			<div class="alert alert-danger"><?php echo $this->session->flashdata('failed'); ?></div>
		<?php } ?>
		<?php echo form_open("admin/updateidsekolah", array('method' => 'post')); ?>
			<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
				<div class="card mb-0">
					<div class="card-header"><h2>Data Sekolah</h2></div>
					<div class="card-body">
						<?php
						$fields = array(
							'npsn'       => 'NPSN',
							'nm_sekolah' => 'Nama Sekolah',
							'jln'        => 'Alamat Jln',
							'desa'       => 'Desa / Kelurahan',
							'kec'        => 'Kecamatan',
							'kab'        => 'Kabupaten / Kota'
						);
						$first = true;
						foreach ($fields as $name => $label) {
						?>
							<label class="label <?php echo $first ? '' : 'mt-4'; ?>" for="id-<?php echo $name; ?>"><?php echo $label; ?></label>
						<?php
							$first = false;
							echo form_input(array('type' => 'text', 'class' => 'input', 'id' => 'id-' . $name, 'name' => $name, 'value' => $load[$name]));
						}
						?>
					</div>
				</div>
				<div class="card mb-0">
					<div class="card-header"><h2>Kepala Sekolah</h2></div>
					<div class="card-body">
						<label class="label" for="id-kpl_sekolah">Nama Kepala Sekolah</label>
						<?php
							echo form_input(array('type' => 'text', 'class' => 'input', 'id' => 'id-kpl_sekolah', 'name' => 'kpl_sekolah', 'value' => $load['kpl_sekolah']));
						?>
						<label class="label mt-4" for="id-nip">NIP</label>
						<?php
							echo form_input(array('type' => 'text', 'class' => 'input', 'id' => 'id-nip', 'name' => 'nip', 'value' => $load['nip']));
						?>
						<button type="submit" class="btn btn-primary mt-5"><i class="fa fa-save"></i> Update Data</button>
					</div>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
