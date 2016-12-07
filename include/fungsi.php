<?php

function dropdown($tabel, $name, $id) {
    global $db;
    $pilih = $db->query("select * from " . $tabel . "");
    $data = "<select id='" . $id . "' name='" . $name . "' class='form-control'>";
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data.= "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
    }
    $data.="</select>";
    return $data;
}

function pilihan($tabel, $name, $id, $value, $label) {
    global $db;
    $pilih = $db->query("select DISTINCT * from " . $tabel . "");
    $data = "<select id='" . $id . "' name='" . $name . "' class='form-control'>";
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data.= "<option value='" . $row[$value] . "'>" . strtoupper($row[$label]) . "</option>";
    }
    $data.="</select>";
    return $data;
}

function semester($sem, $angk) {
    $semester = $sem;
    $angkatan = $angk;

    if ($semester % 2 != 0) {
        $a = (($semester + 10) - 1 ) / 10;
        $b = $a - $angkatan;
        $c = ($b * 2) - 1;
        return $c;
    } else {
        $a = (($semester + 10) - 2) / 10;
        $b = $a - $angkatan;
        $c = $b * 2;
        return $c;
    }
}

function angkatan($nim) {
    global $db;
    $pilih = $db->query("SELECT student_angkatan.keterangan from student_mahasiswa left JOIN student_angkatan on student_mahasiswa.angkatan_id=student_angkatan.angkatan_id where nim='$nim'");
    $nims = $pilih->fetch(PDO::FETCH_BOTH);
    return $nims['keterangan'];
}

function tahun_akademik($id) {
    global $db;
    $pilih = $db->query("SELECT keterangan from akademik_tahun_akademik where tahun_akademik_id='$id'");
    $nims = $pilih->fetch(PDO::FETCH_BOTH);
    return $nims['keterangan'];
}

function cek_data($tabel, $column, $record) {
    global $db;
    $sql = $db->prepare("SELECT COUNT(*) AS `total` FROM $tabel WHERE $column = ?");
    $sql->execute(array($record));
    $result = $sql->fetchObject();

    if ($result->total > 0) {
        return '1';
    } else {
        return '0';
    }
}
