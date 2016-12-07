<?php

define('ADMDIR', dirname(__FILE__));
define ("BASE_SIAKAD", "http://".$_SERVER['SERVER_NAME'] ."/".basename(__DIR__)."/");
require( dirname(ADMDIR).'/wp-load.php' );


require_once ADMDIR.'/include/koneksi.php';
require_once ADMDIR.'/include/fungsi.php';


$themable = !isset($_REQUEST['notheme'])||$_REQUEST['notheme'];

