<?php if ($this->session->flashdata('success')): ?>
	<div class="alert alert-success">
		<?= $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('warning')): ?>
	<div class="alert alert-warning">
		<?= $this->session->flashdata('warning'); ?>
	</div>
<?php endif; ?>

<div class="card">
	<div class="card-header"><h2>Tambah Data Kelas</h2></div>
	<div class="card-body">
		<?php if($this->session->flashdata('info')) { ?>
		<div class="alert alert-success"><?php echo $this->session->flashdata('info'); ?></div>
		<?php } ?>
		<?php if($this->session->flashdata('failed')) { ?>
		<div class="alert alert-danger"><?php echo $this->session->flashdata('failed'); ?></div>
		<?php } ?>
		<?php echo form_open('admin/simpankelas', array('method' => 'post', 'class' => 'mx-auto max-w-md')); ?>
			<label class="label" for="nm_kelas">Nama Kelas</label>
			<?php
				echo form_input(array('type' => 'text', 'id' => 'nm_kelas', 'class' => 'input', 'name' => 'nm_kelas'));
			?>
			<button type="submit" class="btn btn-primary mt-4"><i class="fa fa-save"></i> Simpan Data</button>
		<?php echo form_close(); ?>
	</div>
</div>
<div class="card">
	<div class="card-header">
		<h2>Data Kelas</h2>
		<form method="post" action="<?= base_url('index.php/admin/hapussemuakelas'); ?>" onsubmit="return confirm('Apakah anda yakin ingin menghapus semua data kelas?');">
			<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus semua data</button>
		</form>
	</div>
	<div class="card-body">
		<div class="table-wrap">
			<table class="table">
				<thead>
					<tr>
						<th class="w-14 text-center">No</th>
						<th>Kode Kelas</th>
						<th>Nama Kelas</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$no = 1;
						foreach($datakelas as $load) {
					?>
						<tr>
							<td class="text-center"><?php echo $no++; ?></td>
							<td><?php echo $load['kd_kelas']; ?></td>
							<td><?php echo $load['nm_kelas']; ?></td>
							<td>
								<a class="btn btn-warning btn-sm" href="<?php echo base_url('index.php/admin/hapuskelas'); ?>/<?php echo $load['kd_kelas']; ?>" onClick="return confirm('Apakah anda yakin ingin menghapus data ini?');"><i class="fa fa-remove"></i> Hapus</a>
							</td>
						</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
