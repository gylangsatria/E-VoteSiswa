<?php 
// Jumlah DPT
$pemilih = isset($jmlpemilih['jumlah']) ? (int) $jmlpemilih['jumlah'] : 0;

// Jumlah siswa yang memilih (unik)
$hadir = isset($jmlvote['jumlah']) ? (int) $jmlvote['jumlah'] : 0;

// Hitung total suara OSIS dan MPK untuk grafik
$totalVoteOsis = 0;
$totalVoteMpk = 0;
$labelsOsis = [];
$dataOsis = [];
$labelsMpk = [];
$dataMpk = [];

foreach ($vote as $v) {
    $jumlah = isset($v['jumlah']) ? (int) $v['jumlah'] : 0;
    if ($v['opsi_mpkosis'] == 1) {
        $totalVoteOsis += $jumlah;
        $labelsOsis[] = addslashes($v['nama']);
        $dataOsis[] = $jumlah;
    } else {
        $totalVoteMpk += $jumlah;
        $labelsMpk[] = addslashes($v['nama']);
        $dataMpk[] = $jumlah;
    }
}
?>

<!-- Flash Message -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning"><?= $this->session->flashdata('warning'); ?></div>
<?php endif; ?>

<!-- Chart.js & Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<!-- Style -->
<style>
    .vote-card { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 15px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .vote-card img { object-fit: cover; border-radius: 4px; }
    .vote-card h2 { font-size: 18px; margin-bottom: 10px; }
    .vote-card .category { font-size: 14px; font-weight: bold; color: #555; margin-bottom: 5px; }
    .vote-card .percentage { font-size: 16px; color: #007bff; }
    .vote-summary { margin-top: 30px; }
    .vote-summary table { width: 100%; max-width: 400px; margin: auto; }
    .vote-summary td { padding: 6px 8px; }
    .chart-row { display: flex; justify-content: center; flex-wrap: wrap; gap: 40px; margin: 40px auto; }
    .chart-box { max-width: 400px; width: 100%; text-align: center; }
    .chart-box canvas { width: 100%; height: auto; }
</style>

<!-- Header -->
<div class="box-header well d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px;">
    <h2 style="margin: 0; font-size: 20px;">Hasil Voting</h2>
    <form method="post" action="<?= base_url('index.php/admin/reset_vote'); ?>" onsubmit="return confirm('Yakin ingin mereset semua hasil vote?');">
        <button type="submit" class="btn btn-danger btn-sm">
            Reset hasil vote
        </button>
    </form>
</div>

<!-- Konten -->
<div class="box-content">
    <div class="row">
    <!-- Vote OSIS -->
    <div class="col-12">
        <h3 class="text-center">Kandidat OSIS</h3>
        <div class="row">
            <?php foreach($vote as $datavote): 
                if ($datavote['opsi_mpkosis'] == 1): 
                    $jumlah = isset($datavote['jumlah']) ? (int) $datavote['jumlah'] : 0;
                    $persen = ($totalVoteOsis > 0) ? round(($jumlah / $totalVoteOsis) * 100, 2) : 0;
            ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="vote-card">
                        <div class="category text-center">OSIS</div>
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
            <?php endif; endforeach; ?>
        </div>
    </div>

    <!-- Vote MPK -->
    <div class="col-12 mt-5">
        <h3 class="text-center">Kandidat MPK</h3>
        <div class="row">
            <?php foreach($vote as $datavote): 
                if ($datavote['opsi_mpkosis'] == 0): 
                    $jumlah = isset($datavote['jumlah']) ? (int) $datavote['jumlah'] : 0;
                    $persen = ($totalVoteMpk > 0) ? round(($jumlah / $totalVoteMpk) * 100, 2) : 0;
            ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="vote-card">
                        <div class="category text-center">MPK</div>
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
            <?php endif; endforeach; ?>
        </div>
    </div>
</div>

    </div>

    <!-- Ringkasan -->
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
        <td><?= $hadir; ?></td>
    </tr>
    <tr>
        <td><b>Jumlah DPT yang tidak memilih</b></td>
        <td>:</td>
        <td><?= $pemilih - $hadir; ?></td>
    </tr>
</table>


        <!-- Grafik -->
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

<!-- Script Chart -->
<script>
    const labelsOsis = <?= json_encode($labelsOsis); ?>;
    const dataOsis = <?= json_encode($dataOsis); ?>;
    const labelsMpk = <?= json_encode($labelsMpk); ?>;
    const dataMpk = <?= json_encode($dataMpk); ?>;

    const pieOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            datalabels: {
                formatter: (value, ctx) => {
                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    let percentage = sum > 0 ? (value / sum * 100).toFixed(1) + '%' : '0%';
                    return percentage;
                },
                color: '#fff',
                font: { weight: 'bold', size: 14 }
            }
        }
    };

    new Chart(document.getElementById('chartOsis'), {
        type: 'pie',
        data: {
            labels: labelsOsis,
            datasets: [{
                data: dataOsis,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#6f42c1'],
            }]
        },
        options: pieOptions,
        plugins: [ChartDataLabels]
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
        options: pieOptions,
        plugins: [ChartDataLabels]
    });
</script>

