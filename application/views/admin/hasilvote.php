<?php 
$pemilih   = !empty($jmlpemilih) ? $jmlpemilih[0]['jumlah'] : 0;
$voteMasuk = !empty($jmlvote) ? $jmlvote[0]['jumlah'] : 0;
?>

<style>
.vote-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.vote-card img {
    object-fit: cover;
    border-radius: 4px;
}
.vote-card h2 {
    font-size: 18px;
    margin-bottom: 10px;
}
.vote-card .category {
    font-size: 14px;
    font-weight: bold;
    color: #555;
    margin-bottom: 5px;
}
.vote-card .percentage {
    font-size: 16px;
    color: #007bff;
}
.vote-summary {
    margin-top: 30px;
}
.vote-summary table {
    width: 100%;
    max-width: 400px;
    margin: auto;
}
.vote-summary td {
    padding: 6px 8px;
}
@media (max-width: 767px) {
    .vote-card {
        margin-bottom: 15px;
    }
}
</style>

<div class="box" id="data">
    <div class="box-inner">
        <div class="box-header well">
            <h2>Hasil Voting</h2>
        </div>
        <div class="box-content">
            <div class="row">
                <?php foreach($vote as $datavote): 
                    $kategori = ($datavote['opsi_mpkosis'] == 0) ? 'OSIS' : 'MPK';
                    $jumlah   = (int) $datavote['jumlah'];
                    $persen   = ($voteMasuk > 0) ? round(($jumlah / $voteMasuk) * 100, 2) : 0;
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="vote-card">
                            <div class="category text-center"><?= $kategori; ?></div>
                            <h2>No <?= $datavote['no']; ?> | <?= $datavote['nama']; ?></h2>
                            <img src="<?= base_url(); ?>asset/img/<?= $datavote['photo']; ?>" width="100%" height="250" alt="Foto Kandidat">
                            <hr/>
                            <div class="text-center">
                                <p>Jumlah Vote</p>
                                <h1><?= $jumlah; ?></h1>
                                <div class="percentage"><?= $persen; ?>%</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="vote-summary">
                <table>
                    <tr>
                        <td><b>Jumlah DPT</b></td>
                        <td>:</td>
                        <td><?= $pemilih; ?></td>
                    </tr>
                    <tr>
                        <td><b>Jumlah DPT yang memilih</b></td>
                        <td>:</td>
                        <td><?= $voteMasuk; ?></td>
                    </tr>
                    <tr>
                        <td><b>Jumlah DPT yang tidak memilih</b></td>
                        <td>:</td>
                        <td><?= $pemilih - $voteMasuk; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

