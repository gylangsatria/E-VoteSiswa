<?php
$jumlahCalon = isset($jmlcalon['jumlah']) ? $jmlcalon['jumlah'] : 0;
$jumlahPemilih = isset($jmlpemilih['jumlah']) ? $jmlpemilih['jumlah'] : 0;
$loaddata = isset($datapilketos[0]) ? $datapilketos[0] : ['tapel' => '', 'tgl' => ''];
?>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-xl text-sky-600"><i class="fa fa-users"></i></span>
            <div>
                <div class="text-sm text-slate-500">Jumlah Kandidat</div>
                <div class="text-3xl font-bold text-slate-800"><?php echo $jumlahCalon; ?></div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-xl text-emerald-600"><i class="fa fa-user-plus"></i></span>
            <div>
                <div class="text-sm text-slate-500">Jumlah DPT</div>
                <div class="text-3xl font-bold text-slate-800"><?php echo $jumlahPemilih; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-5">
    <div class="card-header"><h2>Data Pilketos</h2></div>
    <div class="card-body">
        <?php if($this->session->flashdata('update')) { ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($this->session->flashdata('update'), ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <?php if($this->session->flashdata('updatefailed')) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('updatefailed'), ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <?php if($this->session->flashdata('regfailed')) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('regfailed'), ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <?php
        $jam_mulai   = isset($loaddata['jam_mulai']) ? $loaddata['jam_mulai'] : '';
        $jam_selesai = isset($loaddata['jam_selesai']) ? $loaddata['jam_selesai'] : '';
        $jadwal_aktif = !empty($loaddata['aktif']);
        ?>
        <?php echo form_open('admin/updatedatapilketos', array('method' => 'post')); ?>
        <label class="label" for="tapel">Tahun Pelajaran</label>
        <?php
        echo form_input(array('type' => 'text', 'class' => 'input', 'id' => 'tapel', 'name' => 'tapel', 'value' => $loaddata['tapel']));
        ?>
        <label class="label mt-4" for="tgl">Tanggal Pelaksanaan</label>
        <?php
        echo form_input(array('type' => 'date', 'class' => 'input', 'id' => 'tgl', 'name' => 'tgl', 'value' => $loaddata['tgl']));
        ?>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="jam_mulai">Jam Mulai</label>
                <?php
                echo form_input(array('type' => 'time', 'class' => 'input', 'id' => 'jam_mulai', 'name' => 'jam_mulai', 'value' => $jam_mulai));
                ?>
            </div>
            <div>
                <label class="label" for="jam_selesai">Jam Selesai</label>
                <?php
                echo form_input(array('type' => 'time', 'class' => 'input', 'id' => 'jam_selesai', 'name' => 'jam_selesai', 'value' => $jam_selesai));
                ?>
            </div>
        </div>
        <label class="mt-4 flex items-center gap-2 text-sm text-slate-600">
            <?php echo form_checkbox(array('name' => 'aktif', 'id' => 'aktif', 'value' => '1', 'checked' => $jadwal_aktif, 'class' => 'h-4 w-4')); ?>
            <span>Aktifkan batas waktu voting (siswa hanya bisa memilih pada tanggal &amp; jam di atas)</span>
        </label>
        <p class="mt-2 text-xs text-slate-400">Jika tidak diaktifkan, voting selalu terbuka.</p>
        <button type="submit" class="btn btn-primary mt-4"><i class="fa fa-save"></i> Simpan Data</button>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
    <div class="card">
        <div class="card-header"><h2>Reset Data Pemilihan</h2></div>
        <div class="card-body">
            <p class="text-sm text-slate-600">Fitur ini akan menghapus semua data kecuali Identitas <?php echo org_label('satuan'); ?> dan Data Kelas.</p>
            <p class="mt-2 text-sm text-slate-600">Gunakan apabila pemilihan telah selesai dan Anda telah mengunduh <a class="text-brand-600 hover:underline" href="<?php echo base_url('index.php/admin/daftarhadir'); ?>">Daftar Hadir</a> dan <a class="text-brand-600 hover:underline" href="<?php echo base_url('index.php/admin/laporan'); ?>">Laporan Pemilihan</a>.</p>
            <?php if($this->session->flashdata('reset')) { ?>
                <div class="alert alert-success mt-4"><?php echo htmlspecialchars($this->session->flashdata('reset'), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>
            <?php if($this->session->flashdata('resetfailed')) { ?>
                <div class="alert alert-danger mt-4"><?php echo htmlspecialchars($this->session->flashdata('resetfailed'), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>
            <button type="button" class="btn btn-primary mt-4" data-open-dialog="reset"><i class="fa fa-trash"></i> Reset Data Pemilihan</button>

            <dialog id="reset" class="w-[90vw] max-w-md rounded-xl p-0 backdrop:bg-black/50">
                <div class="border-b border-slate-200 px-5 py-4 font-semibold text-slate-800"><i class="fa fa-exclamation-triangle text-amber-500"></i> Peringatan</div>
                <div class="px-5 py-4 text-sm text-slate-600">
                    <p>Fitur ini akan menghapus semua data kecuali Identitas <?php echo org_label('satuan'); ?> dan Data Kelas.</p>
                    <p class="mt-2">Apakah anda yakin ingin me-reset semua data?</p>
                </div>
                <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-3">
                    <?php echo form_open('admin/resetdata'); ?>
                        <button type="submit" class="btn btn-success">Ya</button>
                    <?php echo form_close(); ?>
                    <button type="button" class="btn btn-danger" data-close-dialog>Batal</button>
                </div>
            </dialog>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2>Reset User DPT</h2></div>
        <div class="card-body">
            <p class="text-sm text-slate-600">Gunakan apabila seorang pemilih melaporkan belum pernah memilih tetapi usernya telah terkunci karena sudah memilih <b>(usernya digunakan orang lain)</b>.</p>
            <hr class="my-4 border-slate-200"/>
            <?php if($this->session->flashdata('resetuser_info')) { ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($this->session->flashdata('resetuser_info'), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>
            <?php if($this->session->flashdata('resetuser_failed')) { ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('resetuser_failed'), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>
            <?php echo form_open('admin/resetuser', array('method' => 'post')); ?>
            <label class="label" for="reset-nisn">NISN</label>
            <?php
            echo form_input(array('type' => 'text', 'class' => 'input', 'id' => 'reset-nisn', 'name' => 'username', 'required' => ''));
            ?>
            <button type="submit" class="btn btn-primary mt-4"><i class="fa fa-remove"></i> Reset User</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="card mt-5">
    <div class="card-header"><h2><i class="fa fa-info-circle"></i> Tentang E-VoteSiswa</h2></div>
    <div class="card-body text-sm text-slate-600">
        <p>
            E-VoteSiswa adalah platform pemilihan digital yang dirancang untuk memudahkan proses demokrasi di lingkungan <?php echo org_label('satuan_lc'); ?>.
            Aplikasi ini memungkinkan siswa memilih Ketua <?php echo org_label('organisasi'); ?> dan MPK secara aman, transparan, dan efisien—langsung dari perangkat mereka.
        </p>
    </div>
</div>
