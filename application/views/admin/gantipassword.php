<?php foreach($dataadmin as $data) {} ?>
<div class="card">
    <div class="card-header"><h2>Ganti Password</h2></div>
    <div class="card-body">
        <?php if($this->session->flashdata('update')) { ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('update'); ?></div>
        <?php } ?>
        <?php if($this->session->flashdata('updatefailed')) { ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('updatefailed'); ?></div>
        <?php } ?>
        <?php echo form_open('admin/updatepassword', array('method' => 'post', 'class' => 'mx-auto max-w-md')) ?>
            <label class="label" for="ganti-username">Username</label>
            <?php
                echo form_input(array('type' => 'text', 'name' => 'username', 'id' => 'ganti-username', 'class' => 'input bg-slate-50', 'value' => $data['username'], 'readonly' => ''));
            ?>
            <label class="label mt-4" for="ganti-password">Password Baru</label>
            <?php
                echo form_input(array('type' => 'password', 'name' => 'password', 'id' => 'ganti-password', 'class' => 'input', 'required' => 'required', 'minlength' => 6));
            ?>
            <button type="submit" class="btn btn-primary mt-5"><i class="fa fa-save"></i> Update Password</button>
        <?php echo form_close(); ?>
    </div>
</div>
