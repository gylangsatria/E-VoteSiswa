<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Navbar Header -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url('index.php/admin'); ?>">E-Pilketos</a>
        </div>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="main-navbar">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo base_url('index.php'); ?>"><i class="glyphicon glyphicon-globe"></i> Visit Site</a></li>

                <!-- Dropdown: Data Sekolah -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-education"></i> Data Sekolah <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('index.php/admin/idsekolah'); ?>">Identitas Sekolah</a></li>
                        <li><a href="<?php echo base_url('index.php/admin/datakelas'); ?>">Data Kelas</a></li>
                    </ul>
                </li>

                <!-- Dropdown: Kandidat -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> Data Kandidat <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('index.php/admin/tambahcalon'); ?>">Tambah Kandidat</a></li>
                        <li><a href="<?php echo base_url('index.php/admin/datacalon'); ?>">Lihat Kandidat</a></li>
                    </ul>
                </li>

                <!-- Dropdown: DPT -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-list-alt"></i> Data DPT <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('index.php/admin/tambahdpt'); ?>">Tambah DPT</a></li>
                        <li><a href="<?php echo base_url('index.php/admin/datadpt'); ?>">Lihat DPT</a></li>
                    </ul>
                </li>

                <!-- Dropdown: Hasil & Laporan -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-stats"></i> Hasil & Laporan <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('index.php/admin/hasilvote'); ?>">Hasil Pemilihan</a></li>
                        <li><a href="<?php echo base_url('index.php/admin/daftarhadir'); ?>">Daftar Hadir</a></li>
                        <li><a href="<?php echo base_url('index.php/admin/laporan'); ?>">Laporan Pilketos</a></li>
                    </ul>
                </li>
            </ul>

            <!-- User Menu -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i> admin <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('index.php/admin/gantipassword'); ?>">Ganti Password</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url('index.php/admin/logout'); ?>">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

