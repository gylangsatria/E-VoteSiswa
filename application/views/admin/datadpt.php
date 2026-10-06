<div class="card">
    <div class="card-header">
        <h2>Data Pemilih Tetap (DPT)</h2>
        <form method="post" action="<?= base_url('index.php/admin/hapussemuadpt'); ?>" onsubmit="return confirm('Apakah anda yakin ingin menghapus semua data DPT?');">
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus semua data</button>
        </form>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('index.php/admin/datadpt'); ?>" class="mb-4 flex flex-col gap-2 sm:flex-row">
            <input type="text" name="keyword" class="input sm:max-w-xs" placeholder="Cari NISN atau Nama..." value="<?php echo $this->input->get('keyword'); ?>">
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
                            <td class="text-center"><?php echo $load['username']; ?></td>
                            <td><?php echo $load['nm_siswa']; ?></td>
                            <td class="text-center"><?php echo $load['jk']; ?></td>
                            <td><?php echo $load['nm_kelas']; ?></td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a class="btn btn-primary btn-sm" href="<?php echo base_url('index.php/admin/editdpt/'.$load['username']); ?>"><i class="fa fa-pencil"></i> Edit</a>
                                    <a class="btn btn-warning btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" href="<?php echo base_url('index.php/admin/hapusdpt/'.$load['username']); ?>"><i class="fa fa-remove"></i> Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

