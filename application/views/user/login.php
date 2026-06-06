<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login E-VoteSiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo base_url(); ?>asset/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-image: url('<?php echo base_url(); ?>asset/img/backgroundmts.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%);
            z-index: -1;
        }

        .login-box {
            background: #fff;
            padding: 40px 32px 32px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeUp 0.5s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-box img {
            max-width: 120px;
            margin-bottom: 16px;
        }

        .login-box h2 {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .login-box .input-group {
            margin-bottom: 16px;
            display: flex;
            align-items: stretch;
        }
        .login-box .input-group .input-group-addon {
            background: #f8f9fa;
            border: 1.5px solid #e8eaed;
            border-right: none;
            border-radius: 8px 0 0 8px;
            padding: 0 14px;
            display: flex;
            align-items: center;
        }
        .login-box .input-group .input-group-addon i { color: #3EA99F; font-size: 18px; }
        .login-box .input-group .form-control {
            border-radius: 0 8px 8px 0;
            border-left: none;
            height: 48px;
            font-size: 15px;
        }

        .btn-lg {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            background: #3EA99F;
            border: none;
            color: #fff;
            transition: all 0.2s ease;
        }
        .btn-lg:hover {
            background: #2d8a82;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(62, 169, 159, 0.3);
        }

        .alert {
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .alert-info { background: #e3f2fd; color: #1565c0; }
        .alert-danger { background: #fde8e8; color: #c0392b; }

        .input-group-addon i { color: #3EA99F !important; }

        @media (max-width: 480px) {
            .login-box { padding: 28px 20px 24px; }
            .login-box h2 { font-size: 19px; }
        }
    </style>
</head>
<body>

<div class="login-box">
    <img src="<?php echo base_url(); ?>asset/img/logomt11.png" alt="Logo E-VoteSiswa">
    <h2>Selamat Datang di E-VoteSiswa</h2>

    <div class="alert alert-info text-center">
        <b>Gunakan NISN Anda sebagai Username dan Password</b>
    </div>

    <?php if($this->session->flashdata('user_failed')) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('user_failed'); ?>
        </div>
    <?php } ?>

    <?php if($this->session->flashdata('block')) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('block'); ?>
        </div>
    <?php } ?>

    <?php 
        $form_attribute = array('method' => 'post', 'class' => 'form-horizontal');
        echo form_open('user/loginvalidation', $form_attribute);
    ?>

    <fieldset>
        <div class="input-group input-group-lg mb-3">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
            <label for="user-username" class="sr-only">NISN</label>
            <?php
                echo form_input([
                    'type' => 'text',
                    'id' => 'user-username',
                    'class' => 'form-control',
                    'name' => 'username',
                    'placeholder' => 'NISN',
                    'aria-label' => 'NISN',
                    'autocomplete' => 'username'
                ]);
            ?>
        </div>

        <div class="input-group input-group-lg mb-4">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
            <label for="user-password" class="sr-only">Password</label>
            <?php
                echo form_input([
                    'type' => 'password',
                    'id' => 'user-password',
                    'class' => 'form-control',
                    'name' => 'password',
                    'placeholder' => 'Password',
                    'aria-label' => 'Password',
                    'autocomplete' => 'current-password'
                ]);
            ?>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">
            <span class="glyphicon glyphicon-log-in"></span> Login
        </button>
    </fieldset>

    <?php echo form_close(); ?>
</div>

<script src="<?php echo base_url(); ?>asset/js/bootstrap.min.js"></script>
</body>
</html>

