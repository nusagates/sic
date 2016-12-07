<?php
header("Content-Type: application/json; charset=UTF-8");
try{
$sys = new PDO('mysql:host=localhost;dbname=indomedi_e_office', "indomedi_budairi", "Allahcintaku99");
$sys->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$result="";
if (isset($_GET['paud'])) {
    $result = $sys->query("SELECT * FROM e_sekolah where jenjang='paud'");
}
elseif (isset($_GET['tk'])) {
    $result = $sys->query("SELECT * FROM e_sekolah where jenjang='tk'");
}
elseif (isset($_GET['sd'])) {
    $result = $sys->query("SELECT * FROM e_sekolah where jenjang='sd'");
}
elseif (isset($_GET['sltp'])) {
    $result = $sys->query("SELECT * FROM e_sekolah where jenjang='smp'");
}
elseif (isset($_GET['slta'])) {
    $result = $sys->query("SELECT * FROM e_sekolah where jenjang='smk' and jenjang='smk'");
}
else{
    $result = $sys->query('SELECT * FROM e_sekolah');
}
while($row = $result->fetch()) {
    $data[]=$row;
}

$dbh = null;
}
catch (PDOException $e){
	print "Koneksi atau query bermasalah: " . $e->getMessage() . "<br/>";
	die();
}
echo json_encode($data);