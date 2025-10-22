<?php 
$pemilih   = !empty($jmlpemilih) ? $jmlpemilih[0]['jumlah'] : 0;
$voteMasuk = !empty($jmlvote) ? $jmlvote[0]['jumlah'] : 0;

// Siapkan data terpisah untuk OSIS dan MPK
$labelsOsis = [];
$dataOsis = [];
$labelsMpk = [];
$dataMpk = [];
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    .chart-row {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 40px;
        margin: 40px auto;
    }
    .chart-box {
        max-width: 400px;
        width: 100%;
        text-align: center;
    }
    .chart-box canvas {
        width: 100%;
        height: auto;
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

                    // Simpan data untuk grafik sesuai kategori
                    if ($kategori === 'OSIS') {
                        $labelsOsis[] = addslashes($datavote['nama']);
                        $dataOsis[] = $jumlah;
                    } else {
                        $labelsMpk[] = addslashes($datavote['nama']);
                        $dataMpk[] = $jumlah;
                    }
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

                <!-- Grafik OSIS -->
                <div class="chart-row">
                    <div class="chart-box">
                        <h3>Grafik Vote OSIS</h3>
                        <canvas id="chartOsis"></canvas>
                    </div>
                    <div class="chart-box">
                        <h3>Grafik Vote MPK</h3>
                        <canvas id="chartMpk"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Script Grafik -->
<script>
    const labelsOsis = <?= json_encode($labelsOsis); ?>;
    const dataOsis = <?= json_encode($dataOsis); ?>;
    const labelsMpk = <?= json_encode($labelsMpk); ?>;
    const dataMpk = <?= json_encode($dataMpk); ?>;

    new Chart(document.getElementById('chartOsis'), {
      type: 'pie',
      data: {
        labels: labelsOsis,
        datasets: [{
          data: dataOsis,
          backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#6f42c1'],
      }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
  }
}
});

    new Chart(document.getElementById('chartMpk'), {
      type: 'pie',
      data: {
        labels: labelsMpk,
        datasets: [{
          data: dataMpk,
          backgroundColor: ['#dc3545', '#20c997', '#fd7e14', '#6610f2', '#e83e8c'],
      }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
  }
}
});
</script>


