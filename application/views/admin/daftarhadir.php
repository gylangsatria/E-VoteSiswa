<?php $pemilih = $jmlpemilih; ?>
<?php $vote = $jmlvote; ?>

<div class="card">
    <div class="card-header">
        <h2>Daftar Hadir Pemilihan Ketua <?php echo org_label('organisasi'); ?></h2>
        <form method="post" action="<?= base_url('index.php/admin/cetakdaftarhadir'); ?>">
            <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download Daftar Hadir</button>
        </form>
    </div>

    <div class="card-body">
        <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <div class="text-xs text-slate-500">Jumlah DPT</div>
                <div class="text-xl font-bold text-slate-800"><?= $pemilih['jumlah']; ?></div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <div class="text-xs text-slate-500">Jumlah DPT yang Hadir</div>
                <div class="text-xl font-bold text-slate-800"><?= $vote['jumlah']; ?></div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <div class="text-xs text-slate-500">Jumlah DPT yang Tidak Hadir</div>
                <div class="text-xl font-bold text-slate-800"><?= $pemilih['jumlah'] - $vote['jumlah']; ?></div>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-14 text-center">No</th>
                        <th class="text-center">NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
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
                        <td class="text-center"><?= $loaddata['hadir']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

