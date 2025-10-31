<?php $pemilih = $jmlpemilih; ?>
<?php $vote = $jmlvote; ?>

<div class="box">
    <div class="box-inner">
        <div class="box-header well d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px;">
            <h2 style="margin: 0; font-size: 20px;">Daftar Hadir Pemilihan Ketua OSIS</h2>
            <form method="post" action="<?= base_url('index.php/admin/cetakdaftarhadir'); ?>">
                <button class="btn btn-sm btn-primary">
                    <span class="glyphicon glyphicon-save"></span> Download Daftar Hadir
                </button>
            </form>
        </div>

        <div class="box-content">
            <table border="0">
                <tr>
                    <td>Jumlah DPT</td>
                    <td>:</td>
                    <td><?= $pemilih['jumlah']; ?></td>
                </tr>
                <tr>
                    <td>Jumlah DPT yang Hadir</td>
                    <td>:</td>
                    <td><?= $vote['jumlah']; ?></td>
                </tr>
                <tr>
                    <td>Jumlah DPT yang Tidak Hadir</td>
                    <td>:</td>
                    <td><?= $pemilih['jumlah'] - $vote['jumlah']; ?></td>
                </tr>
            </table>

            <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
                <thead>
                    <tr>
                        <th width="15" class="text-center">No</th>
                        <th class="text-center">NISN</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($daftarhadir as $loaddata): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $loaddata['username']; ?></td>
                        <td><?= $loaddata['nm_siswa']; ?></td>
                        <td><?= $loaddata['nm_kelas']; ?></td>
                        <td><?= $loaddata['hadir']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

