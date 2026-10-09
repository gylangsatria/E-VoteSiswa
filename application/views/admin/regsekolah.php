<body class="min-h-screen bg-slate-100 font-sans text-slate-700 antialiased">
<div class="mx-auto max-w-md py-10">
    <h2 class="mb-6 text-center text-2xl font-bold text-slate-800">Registrasi Sekolah</h2>

    <div class="card">
        <div class="card-body">
            <?php if($this->session->flashdata('regfailed')) { ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('regfailed'), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>
            <?php echo form_open('admin/simpansekolah', array('method' => 'post')); ?>
                <label class="label" for="reg-npsn">NPSN</label>
                <div class="mb-4 flex items-stretch">
                    <span class="flex items-center rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 px-4 text-brand-500"><i class="fa fa-star"></i></span>
                    <?php
                        echo form_input(array('type' => 'text', 'class' => 'input rounded-l-none', 'id' => 'reg-npsn', 'name' => 'npsn', 'placeholder' => 'NPSN'));
                    ?>
                </div>

                <label class="label" for="reg-nm_sekolah">Nama Sekolah</label>
                <div class="mb-6 flex items-stretch">
                    <span class="flex items-center rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 px-4 text-brand-500"><i class="fa fa-home"></i></span>
                    <?php
                        echo form_input(array('type' => 'text', 'class' => 'input rounded-l-none', 'id' => 'reg-nm_sekolah', 'name' => 'nm_sekolah', 'placeholder' => 'Nama Sekolah'));
                    ?>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3 text-base">Daftar</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

</body>
</html>
