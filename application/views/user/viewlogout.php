<div class="container">
	<div class="logout-wrapper">
		<div class="logout-card">
			<div class="logout-icon">
				<svg viewBox="0 0 24 24" width="56" height="56" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
			</div>
			<h2>Voting Berhasil!</h2>
			<p class="logout-subtitle">Terima kasih telah berpartisipasi dalam pemilihan OSIS dan MPK.</p>
			<div class="logout-details">
				<div class="logout-detail-item">
					<svg viewBox="0 0 24 24" width="18" height="18" stroke="#3EA99F" stroke-width="2" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
					<span>Suara Anda telah tercatat</span>
				</div>
				<div class="logout-detail-item">
					<svg viewBox="0 0 24 24" width="18" height="18" stroke="#3EA99F" stroke-width="2" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
					<span>Setiap suara dijaga kerahasiaannya</span>
				</div>
			</div>
			<p class="logout-hint">Silakan logout untuk mengakhiri sesi Anda.</p>
			<a href="<?php echo base_url(); ?>index.php/user/logout" class="logout-btn">
				<svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
				Logout Sekarang
			</a>
		</div>
	</div>
</div>

<style>
.logout-wrapper {
	min-height: 60vh;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 20px 0;
}
.logout-card {
	background: var(--card-bg);
	border-radius: 20px;
	box-shadow: 0 8px 40px rgba(0,0,0,0.08);
	padding: 48px 40px 40px;
	text-align: center;
	max-width: 480px;
	width: 100%;
	animation: fadeInUp 0.5s ease;
}
.logout-icon {
	width: 96px;
	height: 96px;
	border-radius: 50%;
	background: linear-gradient(135deg, #e8f8f0, #d0f0e0);
	color: #1a7d4a;
	display: flex;
	align-items: center;
	justify-content: center;
	margin: 0 auto 20px;
}
.logout-card h2 {
	font-size: 26px;
	font-weight: 700;
	color: var(--text);
	margin: 0 0 8px;
}
.logout-subtitle {
	font-size: 15px;
	color: var(--text-muted);
	margin: 0 0 24px;
}
.logout-details {
	background: #f8f9fa;
	border-radius: 12px;
	padding: 16px 20px;
	margin-bottom: 24px;
	text-align: left;
}
.logout-detail-item {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 6px 0;
	font-size: 14px;
	color: var(--text);
}
.logout-hint {
	font-size: 14px;
	color: var(--text-muted);
	margin: 0 0 20px;
}
.logout-btn {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 14px 36px;
	background: linear-gradient(135deg, #667eea, #764ba2);
	color: #fff;
	border: none;
	border-radius: 12px;
	font-size: 16px;
	font-weight: 600;
	text-decoration: none;
	transition: all 0.25s ease;
	cursor: pointer;
}
.logout-btn:hover {
	transform: translateY(-2px);
	box-shadow: 0 8px 24px rgba(102, 126, 234, 0.35);
	color: #fff;
	text-decoration: none;
}

@keyframes fadeInUp {
	from { opacity: 0; transform: translateY(24px); }
	to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
	.logout-card { padding: 36px 20px 28px; }
	.logout-card h2 { font-size: 22px; }
}
</style>