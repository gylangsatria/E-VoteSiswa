<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login E-VoteSiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="<?php echo base_url(); ?>asset/vendor/tailwind/tailwind.min.js"></script>
    <link href="<?php echo base_url(); ?>asset/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php include(APPPATH . 'views/partials/tw.php'); ?>
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-900 p-5 font-sans">
    <div class="fixed inset-0 -z-10 bg-cover bg-center" style="background-image:url('<?php echo base_url(); ?>asset/img/background-login.webp')"></div>
    <div class="fixed inset-0 -z-10 bg-gradient-to-b from-slate-900/70 via-slate-900/60 to-slate-900/80"></div>

    <div class="w-full max-w-md rounded-2xl bg-white px-6 py-10 text-center shadow-2xl sm:px-8">
        <img class="mx-auto mb-4 max-w-[120px]" src="<?php echo base_url(); ?>asset/img/logomt11.png" alt="Logo E-VoteSiswa">
        <h2 class="mb-5 text-xl font-bold text-slate-800 sm:text-2xl">Selamat Datang di E-VoteSiswa</h2>

        <div class="alert alert-info justify-center text-center"><b>Gunakan NISN Anda sebagai Username dan Password</b></div>

        <?php if($this->session->flashdata('user_failed')) { ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('user_failed'); ?></div>
        <?php } ?>

        <?php if($this->session->flashdata('block')) { ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('block'); ?></div>
        <?php } ?>

        <?php echo form_open('user/loginvalidation', array('method' => 'post', 'class' => 'text-left')); ?>

        <fieldset>
            <label for="user-username" class="sr-only">NISN</label>
            <div class="mb-4 flex items-stretch">
                <span class="flex items-center rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 px-4 text-brand-500"><i class="fa fa-user"></i></span>
                <?php
                    echo form_input([
                        'type' => 'text',
                        'id' => 'user-username',
                        'class' => 'input rounded-l-none',
                        'name' => 'username',
                        'placeholder' => 'NISN',
                        'aria-label' => 'NISN',
                        'autocomplete' => 'username'
                    ]);
                ?>
            </div>

            <label for="user-password" class="sr-only">Password</label>
            <div class="mb-6 flex items-stretch">
                <span class="flex items-center rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 px-4 text-brand-500"><i class="fa fa-lock"></i></span>
                <?php
                    echo form_input([
                        'type' => 'password',
                        'id' => 'user-password',
                        'class' => 'input rounded-l-none',
                        'name' => 'password',
                        'placeholder' => 'Password',
                        'aria-label' => 'Password',
                        'autocomplete' => 'current-password'
                    ]);
                ?>
            </div>

            <button type="submit" class="btn btn-primary w-full py-3 text-base"><i class="fa fa-sign-in"></i> Login</button>
        </fieldset>

        <?php echo form_close(); ?>
    </div>
</body>
</html>
