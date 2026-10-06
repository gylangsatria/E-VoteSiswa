<?php
$pemilih = isset($jmlpemilih['jumlah']) ? (int) $jmlpemilih['jumlah'] : 0;
$hadir   = isset($jmlvote['jumlah']) ? (int) $jmlvote['jumlah'] : 0;

$kategori = [
    1 => ['label' => org_label('organisasi'), 'total' => 0, 'labels' => [], 'data' => [], 'colors' => ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#6f42c1']],
    0 => ['label' => 'MPK',  'total' => 0, 'labels' => [], 'data' => [], 'colors' => ['#dc3545', '#20c997', '#fd7e14', '#6610f2', '#e83e8c']],
];

foreach ($vote as $v) {
    $jumlah = isset($v['jumlah']) ? (int) $v['jumlah'] : 0;
    $key    = ($v['opsi_mpkosis'] == 1) ? 1 : 0;
    $kategori[$key]['total']   += $jumlah;
    $kategori[$key]['labels'][] = addslashes($v['nama'] . ' / ' . $v['nama_wakil']);
    $kategori[$key]['data'][]   = $jumlah;
}
?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning"><?= $this->session->flashdata('warning'); ?></div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<div class="card">
    <div class="card-header">
        <h2>Hasil Voting</h2>
        <?php echo form_open('admin/reset_vote', array('onsubmit' => "return confirm('Yakin ingin mereset semua hasil vote?');")); ?>
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i> Reset hasil vote</button>
        <?php echo form_close(); ?>
    </div>
    <div class="card-body">
        <?php foreach ($kategori as $key => $kat): ?>
            <h3 class="mb-4 text-center text-lg font-semibold text-slate-800">Kandidat <?= $kat['label']; ?></h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach($vote as $datavote):
                    if ($datavote['opsi_mpkosis'] != $key) continue;
                    $jumlah = isset($datavote['jumlah']) ? (int) $datavote['jumlah'] : 0;
                    $persen = ($kat['total'] > 0) ? round(($jumlah / $kat['total']) * 100, 2) : 0;
                ?>
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="bg-slate-50 py-2 text-center text-xs font-bold uppercase tracking-wide text-slate-500"><?= $kat['label']; ?></div>
                        <h2 class="px-4 pt-3 text-base font-semibold text-slate-800">No <?= (int) $datavote['no']; ?> | <?= htmlspecialchars($datavote['nama'], ENT_QUOTES, 'UTF-8'); ?> / <?= htmlspecialchars($datavote['nama_wakil'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <img class="h-[250px] w-full object-cover" src="<?= base_url(); ?>asset/img/<?= htmlspecialchars($datavote['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto Kandidat">
                        <hr class="border-slate-200"/>
                        <div class="pb-4 text-center">
                            <p class="text-sm text-slate-500">Jumlah Vote</p>
                            <h1 class="text-3xl font-bold text-slate-800"><?= $jumlah; ?></h1>
                            <div class="text-base font-semibold text-sky-600"><?= $persen; ?>%</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <div class="text-xs text-slate-500">Jumlah DPT</div>
                <div class="text-xl font-bold text-slate-800"><?= $pemilih; ?></div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <div class="text-xs text-slate-500">Jumlah DPT yang memilih</div>
                <div class="text-xl font-bold text-slate-800"><?= $hadir; ?></div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <div class="text-xs text-slate-500">Jumlah DPT yang tidak memilih</div>
                <div class="text-xl font-bold text-slate-800"><?= $pemilih - $hadir; ?></div>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-10">
            <div class="w-full max-w-md text-center">
                <h3 class="mb-3 font-semibold text-slate-800">Grafik Vote <?= org_label('organisasi'); ?></h3>
                <canvas id="chartOsim"></canvas>
            </div>
            <div class="w-full max-w-md text-center">
                <h3 class="mb-3 font-semibold text-slate-800">Grafik Vote MPK</h3>
                <canvas id="chartMpk"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const osim = <?= json_encode(['labels' => $kategori[1]['labels'], 'data' => $kategori[1]['data'], 'colors' => $kategori[1]['colors']]); ?>;
    const mpk  = <?= json_encode(['labels' => $kategori[0]['labels'], 'data' => $kategori[0]['data'], 'colors' => $kategori[0]['colors']]); ?>;

    const pieOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            datalabels: {
                formatter: (value, ctx) => {
                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    return sum > 0 ? (value / sum * 100).toFixed(1) + '%' : '0%';
                },
                color: '#fff',
                font: { weight: 'bold', size: 14 }
            }
        }
    };

    [[osim, 'chartOsim'], [mpk, 'chartMpk']].forEach(([src, id]) => {
        new Chart(document.getElementById(id), {
            type: 'pie',
            data: { labels: src.labels, datasets: [{ data: src.data, backgroundColor: src.colors }] },
            options: pieOptions,
            plugins: [ChartDataLabels]
        });
    });
</script>
