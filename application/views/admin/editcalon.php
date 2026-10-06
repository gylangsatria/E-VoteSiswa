<?php 
    foreach($datacalon as $loaddata) :
?>

<div class="card">
    <div class="card-header"><h2>Edit Calon Ketua OSIM dan MPK</h2></div>
    <div class="card-body">
        <?php if($this->session->flashdata('info')) { ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('info'); ?></div>
        <?php } ?>
        <?php if($this->session->flashdata('failed')) { ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('failed'); ?></div>
        <?php } ?>

        <?php echo form_open_multipart('admin/updatecalon', array('method' => 'post', 'class' => 'mx-auto max-w-xl')); ?>
            <label class="label" for="edit-nisn">NISN</label>
            <?php
                echo form_input(array('type' => 'text', 'id' => 'edit-nisn', 'name' => 'nisn', 'class' => 'input bg-slate-50', 'readonly' => '', 'value' => $loaddata['nisn']));
            ?>

            <label class="label mt-4" for="edit-no">Nomor Urut Paslon</label>
            <?php
                echo form_input(array('type' => 'text', 'id' => 'edit-no', 'name' => 'no', 'class' => 'input', 'value' => $loaddata['no']));
            ?>

            <label class="label mt-4" for="edit-nama">Nama Calon Ketua <span class="kategori-label"><?php echo ($loaddata['opsi_mpkosis'] == 1) ? 'OSIM' : 'MPK'; ?></span></label>
            <?php
                echo form_input(array('type' => 'text', 'id' => 'edit-nama', 'name' => 'nama', 'class' => 'input', 'value' => $loaddata['nama']));
            ?>

            <label class="label mt-4" for="edit-nama-wakil">Nama Calon Wakil Ketua <span class="kategori-label"><?php echo ($loaddata['opsi_mpkosis'] == 1) ? 'OSIM' : 'MPK'; ?></span></label>
            <?php
                echo form_input(array('type' => 'text', 'id' => 'edit-nama-wakil', 'name' => 'nama_wakil', 'class' => 'input', 'value' => $loaddata['nama_wakil']));
            ?>

            <label class="label mt-4" for="opsi_mpkosis">Jenis Kandidat</label>
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
                echo form_dropdown($form_attribute['name'], $options_kandidat, $loaddata['opsi_mpkosis'], $form_attribute);
            ?>

            <label class="label mt-4" for="edit-photo">Foto Paslon</label>
            <?php
                echo form_input(array('type' => 'file', 'id' => 'edit-photo', 'name' => 'photo', 'class' => 'input'));
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

<?php endforeach; ?>

