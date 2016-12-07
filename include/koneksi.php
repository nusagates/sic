<?php
global $db;
try{
	$db=new PDO("mysql:host=localhost;dbname=qsoft_sic;charset=utf8mb4","qsoft_sic","Allahcintaku");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $ex){
	exit("Tidak dapat menyambungkan dengan database! ".$ex->getMessage());
}

function pos_kosong($label, &$var = null) {
	return empty($_POST[$label]) || ''===($var = trim($_POST[$label]));
}
function get_kosong($label, &$var = null) {
	return empty($_GET[$label]) || ''===($var = trim($_GET[$label]));
}
