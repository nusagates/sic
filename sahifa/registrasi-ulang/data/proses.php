<?php

require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "akademik_registrasi";
$id_column = "registrasi_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("SELECT akademik_registrasi.registrasi_id, akademik_registrasi.nim, student_mahasiswa.nama, akademik_registrasi.tanggal_registrasi, akademik_tahun_akademik.keterangan, akademik_tahun_akademik.batas_registrasi, akademik_registrasi.semester from akademik_registrasi
LEFT JOIN student_mahasiswa on akademik_registrasi.nim=student_mahasiswa.nim
LEFT JOIN akademik_tahun_akademik on akademik_registrasi.tahun_akademik_id=akademik_tahun_akademik.tahun_akademik_id WHERE akademik_tahun_akademik.status='y'");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $aktifkan = "<a data-aksi='" . $row['' . $id_column . ''] . "' class='tombol-aktifkan btn btn-xs btn-success' href='#'>Aktifkan</i></a>";
            $nonaktifkan = "<a data-aksi='" . $row['' . $id_column . ''] . "' class='tombol-nonaktifkan btn btn-xs btn-danger' href='#'>Nonktifkan</i></a>";
            $menu = $row['tanggal_registrasi'] === "0000-00-00" ? $aktifkan : $nonaktifkan;
        }
        $sem = substr($row['keterangan'], 4, 1);
        $status = $row['tanggal_registrasi'] === "0000-00-00" ? "Nonaktif" : "Aktif";
        $tgl_registrasi = $row['tanggal_registrasi'] === "0000-00-00" ? "" : date("d F Y", strtotime($row['tanggal_registrasi']));
        $data[] = array(strtoupper($row['nim']), strtoupper($row['nama']), $row['keterangan'], semester($row['keterangan'], angkatan($row['nim'])), $status, $tgl_registrasi, $menu, $row['nama_lengkap'], $menu);
    }
    echo json_encode($data);
}
if (isset($_GET['hapus'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    $hapus = $db->prepare("delete from " . $tabel . " where " . $id_column . "=?");
    if ($hapus->execute(array($data))) {
        echo "1";
    }
}
if (isset($_GET['tambah'])) {
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';
    $angkatan = angkatan($nim);
    $tahun = isset($_POST['tahun-akademik']) ? $_POST['tahun-akademik'] : '';
    $tahun_akademik = tahun_akademik($tahun);
    $semester = semester($tahun_akademik, $angkatan);
    $tambah = $db->prepare("insert into " . $tabel . "(nim, tahun_akademik_id, semester) values(?, ?, ?)");
    if (cek_data($tabel, "nim", $nim) === '0') {
        if ($tambah->execute(array($nim, $tahun, $semester))) {
            echo "1";
        }
    } else {
        echo "Data sudah ada";
    }
}

if (isset($_GET['term'])) {
    $pilih = $db->prepare("SELECT DISTINCT nim FROM student_mahasiswa where nim like :term");
    $pilih->execute(array('term' => $_GET['term'] . '%'));
    $return_arr = array();
    while ($row = $pilih->fetch()) {
        $return_arr[] = strtoupper($row['nim']);
    }
    echo json_encode($return_arr);
}
if (isset($_GET['aktifkan'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $tanggal = date("Y-m-d");
    $update = $db->prepare("update " . $tabel . " set tanggal_registrasi=? where " . $id_column . "=?");
    if ($update->execute(array($tanggal, $id_data))) {
        echo "1";
    }
}
if (isset($_GET['nonaktifkan'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $update = $db->prepare("update " . $tabel . " set tanggal_registrasi=? where " . $id_column . "=?");
    if ($update->execute(array("0000-00-00", $id_data))) {
        echo "1";
    }
}