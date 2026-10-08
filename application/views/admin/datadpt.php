<div class="card">
    <div class="card-header">
        <h2>Data Pemilih Tetap (DPT)</h2>
        <?php echo form_open('admin/hapussemuadpt', array('onsubmit' => "return confirm('Apakah anda yakin ingin menghapus semua data DPT?');")); ?>
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus semua data</button>
        <?php echo form_close(); ?>
    </div>
    <div class="card-body">
        <?php if($this->session->flashdata('info')) { ?>
            <div class="alert alert-success mb-4"><?php echo htmlspecialchars($this->session->flashdata('info'), ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <?php if($this->session->flashdata('failed')) { ?>
            <div class="alert alert-danger mb-4"><?php echo htmlspecialchars($this->session->flashdata('failed'), ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <?php if($this->session->flashdata('success')) { ?>
            <div class="alert alert-success mb-4"><?php echo htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <form method="GET" action="<?php echo base_url('index.php/admin/datadpt'); ?>" class="mb-4 flex flex-col gap-2 sm:flex-row">
            <input type="text" name="keyword" class="input sm:max-w-xs" placeholder="Cari NISN atau Nama..." value="<?php echo htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Cari</button>
            <a href="<?php echo base_url('index.php/admin/datadpt'); ?>" class="btn btn-default">Reset</a>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">NISN</th>
                        <th>Nama Siswa</th>
                        <th class="text-center">L/P</th>
                        <th>Kelas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach($datadpt as $load) {
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($load['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($load['nm_siswa'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($load['jk'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($load['nm_kelas'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a class="btn btn-primary btn-sm" href="<?php echo base_url('index.php/admin/editdpt').'?nisn='.rawurlencode($load['username']); ?>"><i class="fa fa-pencil"></i> Edit</a>
                                    <?php echo form_open('admin/hapusdpt', array('class' => 'inline', 'onsubmit' => "return confirm('Apakah anda yakin ingin menghapus data ini?')")); ?>
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($load['username'], ENT_QUOTES, 'UTF-8'); ?>" />
                                        <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-remove"></i> Hapus</button>
                                    <?php echo form_close(); ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

