<?php if (! $sudah_memilih_osis || ! $sudah_memilih_mpk): ?>
    <div class="alert alert-warning text-center">
        Anda belum menyelesaikan voting untuk OSIS dan MPK.
    </div>
<?php endif; ?>

<div class="container">
    <div class="box">
        <div class="box-inner">
            <div class="box-header well">
                <h2>Selamat Datang di E-VoteSiswa</h2>
            </div>
            <div class="box-content">
                <p>Silakan pilih Calon Ketua OSIS dan Ketua MPK di bawah ini.</p>
                <hr/>

                <?php
                $username = $this->session->userdata('username');
                $cek_osis = $this->db->get_where('tb_pilih', ['username' => $username, 'opsi_mpkosis' => 0])->num_rows();
                $cek_mpk  = $this->db->get_where('tb_pilih', ['username' => $username, 'opsi_mpkosis' => 1])->num_rows();
                ?>

                <!-- Calon Ketua OSIS -->
                <h3 class="text-center">Calon Ketua OSIS</h3>
                <div class="row">
                    <?php foreach($datacalon as $loaddata): ?>
                        <?php if ($loaddata['opsi_mpkosis'] == 1): ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="box">
                                    <div class="box-inner">
                                        <div class="box-header well">
                                            <h2 class="text-center">NO <?= $loaddata['no']; ?> || <?= $loaddata['nama']; ?></h2>
                                        </div>
                                        <div class="box-content">
                                            <img width="100%" height="400" src="<?= base_url(); ?>asset/img/<?= $loaddata['photo']; ?>" alt="Foto OSIS"/><br/><br/>
                                            <?php if ($cek_osis == 0): ?>
    <form action="<?= site_url('user/vote'); ?>" method="post" class="form-horizontal">
        <input type="hidden" name="nisn" value="<?= $loaddata['nisn']; ?>">
        <input type="hidden" name="opsi_mpkosis" value="0">
        <button type="submit" class="btn btn-danger" style="width: 100%;">Pilih NO <?= $loaddata['no']; ?> (OSIS)</button>
    </form>
<?php else: ?>
    <button class="btn btn-secondary" style="width: 100%;" disabled>Sudah Memilih OSIS</button>
<?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <hr/>

                <!-- Calon Ketua MPK -->
                <h3 class="text-center">Calon Ketua MPK</h3>
                <div class="row">
                    <?php foreach($datacalon as $loaddata): ?>
                        <?php if ($loaddata['opsi_mpkosis'] == 0): ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="box">
                                    <div class="box-inner">
                                        <div class="box-header well">
                                            <h2 class="text-center">NO <?= $loaddata['no']; ?> || <?= $loaddata['nama']; ?></h2>
                                        </div>
                                        <div class="box-content">
                                            <img width="100%" height="400" src="<?= base_url(); ?>asset/img/<?= $loaddata['photo']; ?>" alt="Foto MPK"/><br/><br/>
                                           <?php if ($cek_mpk == 0): ?>
    <form action="<?= site_url('user/vote'); ?>" method="post" class="form-horizontal">
        <input type="hidden" name="nisn" value="<?= $loaddata['nisn']; ?>">
        <input type="hidden" name="opsi_mpkosis" value="1">
        <button type="submit" class="btn btn-primary" style="width: 100%;">Pilih NO <?= $loaddata['no']; ?> (MPK)</button>
    </form>
<?php else: ?>
    <button class="btn btn-secondary" style="width: 100%;" disabled>Sudah Memilih MPK</button>
<?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

