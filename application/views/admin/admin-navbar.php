<body class="min-h-screen bg-slate-100 font-sans text-slate-700 antialiased">
<div class="flex min-h-screen flex-col">
<nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex w-full max-w-7xl items-center gap-3 px-4 py-3 sm:px-6">
        <a class="mr-auto flex items-center gap-2 text-base font-bold text-slate-800" href="<?php echo base_url('index.php/admin'); ?>">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 text-white">
                <i class="fa fa-check-square-o"></i>
            </span>
            E-VoteSiswa
        </a>

        <ul class="hidden items-center gap-1 md:flex">
            <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-brand-600" href="<?php echo base_url('index.php'); ?>"><i class="fa fa-globe"></i> Visit Site</a></li>

            <li class="relative">
                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-brand-600"><i class="fa fa-graduation-cap"></i> Data Sekolah <i class="fa fa-caret-down text-xs"></i></summary>
                    <ul class="absolute left-0 z-50 mt-1 w-56 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/idsekolah'); ?>"><i class="fa fa-home"></i> Identitas Sekolah</a></li>
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/datakelas'); ?>"><i class="fa fa-th-list"></i> Data Kelas</a></li>
                    </ul>
                </details>
            </li>

            <li class="relative">
                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-brand-600"><i class="fa fa-user"></i> Data Kandidat <i class="fa fa-caret-down text-xs"></i></summary>
                    <ul class="absolute left-0 z-50 mt-1 w-56 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/tambahcalon'); ?>"><i class="fa fa-plus"></i> Tambah Kandidat</a></li>
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/datacalon'); ?>"><i class="fa fa-eye"></i> Lihat Kandidat</a></li>
                    </ul>
                </details>
            </li>

            <li class="relative">
                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-brand-600"><i class="fa fa-list-alt"></i> Data DPT <i class="fa fa-caret-down text-xs"></i></summary>
                    <ul class="absolute left-0 z-50 mt-1 w-56 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/tambahdpt'); ?>"><i class="fa fa-plus-circle"></i> Tambah DPT</a></li>
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/datadpt'); ?>"><i class="fa fa-folder-open"></i> Lihat DPT</a></li>
                    </ul>
                </details>
            </li>

            <li class="relative">
                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-brand-600"><i class="fa fa-bar-chart"></i> Hasil &amp; Laporan <i class="fa fa-caret-down text-xs"></i></summary>
                    <ul class="absolute left-0 z-50 mt-1 w-60 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/hasilvote'); ?>"><i class="fa fa-check-square"></i> Hasil Pemilihan</a></li>
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/daftarhadir'); ?>"><i class="fa fa-list"></i> Daftar Hadir</a></li>
                        <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/laporan'); ?>"><i class="fa fa-print"></i> Laporan E-VoteSiswa</a></li>
                    </ul>
                </details>
            </li>
        </ul>

        <div class="relative">
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-brand-600"><i class="fa fa-user-circle"></i> admin <i class="fa fa-caret-down text-xs"></i></summary>
                <ul class="absolute right-0 z-50 mt-1 w-52 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                    <li><a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-600" href="<?php echo base_url('index.php/admin/gantipassword'); ?>"><i class="fa fa-lock"></i> Ganti Password</a></li>
                    <li class="border-t border-slate-100"><a class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50" href="<?php echo base_url('index.php/admin/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </details>
        </div>

        <details class="md:hidden">
            <summary class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-300 text-slate-600"><i class="fa fa-bars"></i></summary>
            <div class="absolute left-0 right-0 z-50 border-b border-slate-200 bg-white px-4 py-3 shadow-lg">
                <ul class="flex flex-col gap-1 text-sm">
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php'); ?>"><i class="fa fa-globe"></i> Visit Site</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/idsekolah'); ?>"><i class="fa fa-home"></i> Identitas Sekolah</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/datakelas'); ?>"><i class="fa fa-th-list"></i> Data Kelas</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/tambahcalon'); ?>"><i class="fa fa-plus"></i> Tambah Kandidat</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/datacalon'); ?>"><i class="fa fa-eye"></i> Lihat Kandidat</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/tambahdpt'); ?>"><i class="fa fa-plus-circle"></i> Tambah DPT</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/datadpt'); ?>"><i class="fa fa-folder-open"></i> Lihat DPT</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/hasilvote'); ?>"><i class="fa fa-check-square"></i> Hasil Pemilihan</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/daftarhadir'); ?>"><i class="fa fa-list"></i> Daftar Hadir</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/admin/laporan'); ?>"><i class="fa fa-print"></i> Laporan E-VoteSiswa</a></li>
                    <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-red-600 hover:bg-red-50" href="<?php echo base_url('index.php/admin/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </div>
        </details>
    </div>
</nav>

<main id="content" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6">

