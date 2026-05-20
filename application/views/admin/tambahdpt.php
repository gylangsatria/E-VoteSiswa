<?php if($this->session->flashdata('log')) { ?>
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Log Debug:</strong>
        <ul style="text-align: left; font-size: 13px;">
            <?php foreach($this->session->flashdata('log') as $baris) { ?>
                <li><?php echo htmlspecialchars($baris); ?></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<div class="box">
	<div class="box-inner">
		<div class="box-header well">
			<h2> Tambah DPT (Daftar Pemilih Tetap) </h2>
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
			echo form_open_multipart('admin/simpandpt', $form_attribute);
		?>
		<div class="row">
		<div class="col-lg-5">
			<h4>Tambah Data Satu/Satu</h4>
			<hr/>
			<label class="label-control">NISN</label>
			<?php
				$form_attribute = array(
					'type'		=> 'text',
					'class'		=> 'form-control',
					'name'		=> 'nisn',
					'required'	=> 'required'
				);
				echo form_input($form_attribute);
			?>
			<label class="label-control">Nama</label>
			<?php
				$form_attribute = array(
					'type'		=> 'text',
					'class'		=> 'form-control',
					'name'		=> 'nm_siswa',
					'required'	=> 'required'
				);
				echo form_input($form_attribute);
			?>
			<label class="label-control">Jenis Kelamin</label>
			<select class="form-control" name="jk">
				<option selected value="L">L</option>
				<option value="P">P</option>
			</select>
			<label class="label-control">Kelas</label>
			<select class="form-control" name="kd_kelas" required>
				<?php foreach($datakelas as $load) { ?>
					<option value="<?php echo $load['kd_kelas']; ?>"> (<?php echo $load['kd_kelas']; ?>) <?php echo $load['nm_kelas']; ?> </option>
				<?php 
					}
				?>
			</select>
			<br/>
			<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan DPT</button>
		</div>
		<?php 
			echo form_close();
		?>
		<div class="col-lg-6 text-center" style="background: #f5f5f5; padding: 10px 20px; border: 1px solid #ddd; border-radius: 4px;">
    <h4>Tambah Data Massal</h4>
    <hr/>
    <p class="text-muted">Gunakan file Excel dengan format: kolom NISN, Nama, JK (L/P), dan Kode Kelas.</p>
    <br/>
    <div class="box">
        <div class="box-inner">
            <div class="box-header well">
                <h2>Upload File DPT (Excel)</h2>
            </div>
            <div class="box-content">
                <?php 
                $form_attribute = array (
                    'method' => 'post',
                    'class'  => 'form-horizontal'
                );
                echo form_open_multipart('admin/simpanmassaldpt', $form_attribute);
                ?>
                    <input name='datadpt' type="file" required class="form-control" accept=".xls,.xlsx"/> <br/>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <span class="glyphicon glyphicon-cloud-upload"></span> Upload Data
                    </button>
                <?php 
                    echo form_close();
                ?>
            </div>
        </div>
    </div>
    <br/>
    <span style="color: #888;">Pastikan file Excel memiliki format yang benar. Data akan ditambahkan ke DPT yang sudah ada.</span>
</div>

</div>


