<?php if (!@$themable) return; ?>
<?php
$login_url = constant("BASE_SIAKAD")."page-login.php";
!is_user_logged_in() ? header("location: $login_url") : '';
global $user;
$user = wp_get_current_user();
$id = $user->id;
$awal = get_user_meta($id, 'first_name', true);
$akhir = get_user_meta($id, 'last_name', true);
$display = $awal . " " . $akhir;
$default = '';
$key = '';
$akses = $user->roles[0];

//add_user_meta( '1', 'token', 'eM3arnBxN84:APA91bFslPir9ZVZ4Ip2rXpuq0FN20fBz17f4nnoq6vPH7Yfh3-DXk7vNEFeMGmvhq9Ujpo0JFKiMjGD-3f0m1ENwFRU3f7WQvJQaoqjBntlgk9N3JRMOKyb56d0tcJYRCYPiEbOn5As', true );
function gambar($class) {
    $user = wp_get_current_user();
    $id = $user->id;
    $gambar = get_user_meta($id, 'gambar', true);
    if ($gambar) {
        echo "<img width='128' class='" . $class . "' src='" .constant('BASE_SIAKAD'). 'images/user/images/' . $id . '.png' . "'/>";
    } else {
        echo "<img width='128' class='" . $class . "' src='" .constant('BASE_SIAKAD'). 'images/user/default.png' . "'/>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>

        <style>
            input:required {
                background-color: lightyellow;
            }
            input::after{
                content: "*";
            }
            li.ui-menu-item { font-size:12px !important; }
        </style>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php if (!empty($admintitle)) echo($admintitle . ' | '); ?> SIAKAD 1.0</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <?php wp_head(); ?>
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>js/taginput/src/bootstrap-tagsinput.css" />
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>css/jquery-ui.css" />
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>plugins/datatables/dataTables.bootstrap.css">
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>css/jquery.datetimepicker.css" />
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>fancybox/jquery.fancybox.css">
        <link rel="stylesheet" href="<?php echo constant("BASE_SIAKAD") ?>css/siakad.css">


        <script>var $ = jQuery.noConflict();</script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>js/jQuery-2.1.4.min.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>js/jquery-ui.min.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>dist/js/app.min.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>js/jquery.datetimepicker.full.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>fancybox/jquery.fancybox.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>js/taginput/src/bootstrap-tagsinput.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>plugins/typeahead/bootstrap3-typeahead.min.js"></script>
        <script src="<?php echo constant("BASE_SIAKAD") ?>js/siakad.js"></script>

        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo esc_url(home_url()); ?>/siakad" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>S</b>AKD</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Si-</b>akad 1.0</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            <?php if ($akses == 'superadmin') { ?>
                                <!-- Notifications: style can be found in dropdown.less -->
                                <li class="dropdown notifications-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bell-o"></i>
                                        <span class="label label-warning">10</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="header">You have 10 notifications</li>
                                        <li>
                                            <!-- inner menu: contains the actual data -->
                                            <ul class="menu">
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-users text-red"></i> 5 new members joined
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-user text-red"></i> You changed your username
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="footer"><a href="#">View all</a></li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php gambar('user-image'); ?>
                                    <span class="hidden-xs"><?php echo $user->display_name; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <?php gambar('img-circle'); ?>
                                        <p>
                                            <?php echo $user->display_name; ?>
                                            <small><?php echo get_user_meta($id, 'jabatan', true); ?></small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a class="btn btn-default btn-flat" href="<?php echo wp_logout_url(constant('BASE_SIAKAD')); ?>">Logout</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <?php if ($akses == 'superadmin') { ?>
                                <li>
                                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                </nav>
            </header>
            <!--- sidebar -->
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <?php gambar('img-circle'); ?>
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $user->display_name; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" id="sidebarmenu">
                        <li class="header">MENU SIAKAD</li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa fa-database text-green"></i> <span>Master Data</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/tahun-angkatan/"><i class="fa fa-calendar-plus-o text-green"></i> Tahun Angkatan</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/kelompok-makul/"><i class="fa fa-newspaper-o text-green"></i> Kelompok Matakuliah</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/gedung/"><i class="fa fa-building-o text-green"></i> Gedung</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/ruangan/"><i class="fa fa-building text-green"></i> Ruangan</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/grade-nilai/"><i class="fa fa-bars text-green"></i> Grade Nilai</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/waktu/"><i class="fa fa-clock-o text-green"></i> Waktu Kuliah</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/prodi/"><i class="fa fa-bell-o text-green"></i> Prodi</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/konsentrasi/"><i class="fa fa-bell-o text-green"></i> Konsentrasi</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/dosen/"><i class="fa fa-users text-green"></i> Dosen</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa fa-database text-green"></i> <span>Data Akademik</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/tahun-akademik/"><i class="fa fa-calendar-plus-o text-green"></i> Tahun Akademik</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/matakuliah/"><i class="fa fa-calendar-plus-o text-green"></i> Matakuliah</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/jadwal-kuliah/"><i class="fa fa-calendar-plus-o text-green"></i> Jadwal Kuliah</a></li>
                                <li><a href="<?php echo constant("BASE_SIAKAD"); ?>sahifa/registrasi-ulang/"><i class="fa fa-calendar-plus-o text-green"></i> Registrasi Ulang</a></li>
                            </ul>
                        </li>
                 

           
                        <li class="header">Pengaturan</li>
                        <li><a href="profile.php"><i class="fa fa-user text-red"></i> <span>Profil Pengguna</span></a></li>
                        <li><a href="settings.php"><i class="fa fa-gears text-yellow"></i> <span>Pengaturan</span></a></li>
                        <li class="header">Support</li>
                        <li class="treeview">
                            <a href="#">
                                <i class="glyphicon glyphicon-phone-alt text-green"></i> <span>Doc & Support</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="documentation.php"><i class="glyphicon glyphicon-question-sign text-green"></i> Documentation</a></li>
                                <li><a href="support.php"><i class="glyphicon glyphicon-earphone text-green"></i> Support</a></li>
                            </ul>
                        </li> 
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1 id="content-title"><?php echo($admintitle); ?></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo esc_url(home_url()); ?>/e-office"><i class="fa fa-dashboard"></i> Beranda</a></li>
                        <li class="active"><?php echo($admintitle); ?></li>
                    </ol>
                </section>


                <!-- Main content -->
                <section  id="kontenutama" class="content">
