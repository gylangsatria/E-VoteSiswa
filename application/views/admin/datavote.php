<?php 
	foreach($jmlpemilih as $pemilih) {}
	foreach($jmlvote as $votedata) {}
?>
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
	<?php foreach($vote as $datavote) { ?>
		<div class="card mb-0">
			<div class="card-body text-center">
				<p class="text-sm text-slate-500">Jumlah Vote</p>
				<h1 class="text-3xl font-bold text-slate-800"><?php echo $datavote['jumlah']; ?></h1>
			</div>
		</div>
	<?php } ?>
</div>

<div class="card mt-5">
	<div class="card-body">
		<table class="text-sm">
			<tr>
				<td class="py-1 pr-3 text-slate-600">Jumlah Pemilih Terdaftar</td>
				<td class="pr-3">:</td>
				<td class="font-semibold text-slate-800"><?php echo $pemilih['jumlah']; ?></td>
			</tr>
			<tr>
				<td class="py-1 pr-3 text-slate-600">Jumlah Pemilih yang memilih</td>
				<td class="pr-3">:</td>
				<td class="font-semibold text-slate-800"><?php echo $votedata['jumlah']; ?></td>
			</tr>
			<tr>
				<td class="py-1 pr-3 text-slate-600">Jumlah Pemilih yang tidak memilih</td>
				<td class="pr-3">:</td>
				<td class="font-semibold text-slate-800"><?php echo $pemilih['jumlah'] - $votedata['jumlah']; ?></td>
			</tr>
		</table>
	</div>
</div>
