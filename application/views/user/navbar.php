<body class="min-h-screen bg-slate-100 font-sans text-slate-700 antialiased">
<div class="flex min-h-screen flex-col">
<nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
  <div class="mx-auto flex w-full max-w-7xl items-center gap-3 px-4 py-3 sm:px-6">
    <a class="mr-auto flex items-center gap-2 text-base font-bold text-slate-800" href="<?php echo base_url('index.php/user/index'); ?>">
      <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white"><i class="fa fa-check-square-o"></i></span>
      E-VoteSiswa
    </a>

    <details class="md:hidden">
      <summary class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-300 text-slate-600"><i class="fa fa-bars"></i></summary>
      <div class="absolute left-0 right-0 z-50 border-b border-slate-200 bg-white px-4 py-3 shadow-lg">
        <ul class="flex flex-col gap-1 text-sm">
          <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100" href="<?php echo base_url('index.php/user/index'); ?>"><i class="fa fa-home"></i> Beranda</a></li>
          <li><a class="flex items-center gap-2 rounded-lg px-3 py-2 text-red-600 hover:bg-red-50" href="<?php echo base_url('index.php/user/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
      </div>
    </details>

    <div class="hidden items-center gap-1 md:flex">
      <a class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-600" href="<?php echo base_url('index.php/user/index'); ?>"><i class="fa fa-home"></i> Beranda</a>
      <span class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600">
        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-blue-600"><i class="fa fa-user"></i></span>
        <span class="max-w-[100px] truncate"><?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
      </span>
      <a class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50" href="<?php echo base_url('index.php/user/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a>
    </div>
  </div>
</nav>

<main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6">

