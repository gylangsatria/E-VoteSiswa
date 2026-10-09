<?php
if ($this->session->flashdata('info')) {
    $terhapus = TRUE;
    $pesan    = 'Berhasil Menghapus Data';
} elseif ($this->session->flashdata('failed')) {
    $terhapus = FALSE;
    $pesan    = 'Gagal Menghapus Data';
} else {
    $terhapus = NULL;
    $pesan    = '';
}
?>
<div class="card">
    <div class="card-header"><h2>Data Calon Ketua <?php echo org_label('organisasi'); ?> dan MPK</h2></div>
    <div class="card-body">
        <?php if(isset($terhapus)) { ?>
            <div class="alert alert-<?php echo $terhapus ? 'success' : 'danger'; ?>"><?php echo $pesan; ?></div>
        <?php } ?>
        <?php echo form_open('admin/hapuscalon', array('id' => 'hapusCalonForm')); ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">No Kandidat</th>
                        <th class="text-center">Nama Calon</th>
                        <th class="text-center">Nama Calon Wakil</th>
                        <th class="text-center">Jenis Kandidat</th>
                        <th class="text-center">Foto Paslon</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($datacalon as $loaddata) {
                        $jenis = ($loaddata['opsi_mpkosis'] == 1) ? org_label('organisasi') : 'MPK';
                ?>
                    <tr>
                        <td class="text-center"><?php echo htmlspecialchars($loaddata['no'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($loaddata['nama_wakil'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center"><span class="badge badge-<?php echo ($loaddata['opsi_mpkosis'] == 1) ? 'primary' : 'warning'; ?>"><?php echo htmlspecialchars($jenis, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td class="text-center">
                            <img class="mx-auto h-[60px] w-[50px] rounded-md object-cover" src="<?php echo base_url(); ?>/asset/img/<?php echo htmlspecialchars($loaddata['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto <?php echo htmlspecialchars($loaddata['nama'], ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <a class="btn btn-info btn-sm" href="<?php echo base_url('index.php/admin/editcalon/'.htmlspecialchars($loaddata['nisn'], ENT_QUOTES, 'UTF-8')); ?>">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <button type="submit" form="hapusCalonForm" name="nisn" value="<?php echo htmlspecialchars($loaddata['nisn'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-danger btn-sm" data-confirm="Apakah anda yakin ingin menghapus data ini?"><i class="fa fa-trash"></i> Hapus</button>
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

