<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "makul_kelompok";
$id_column = "kelompok_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("select * from " . $tabel . " order by kode_kelompok asc");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "data-edit='" . $row['' . $id_column . ''] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['' . $id_column . ''] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $data[] = array($row['kode_kelompok'], $row['nama'], $menu);
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
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $tambah = $db->prepare("insert into " . $tabel . "(kode_kelompok, nama) values(?, ?)");
    if ($tambah->execute(array($kode, $nama))) {
        echo "1";
    }
}
if (isset($_GET['edit-form'])) {
    $id_data = $_GET['edit-form'];
    $ambil = $db->query("select * from " . $tabel . " where " . $id_column . "='$id_data'");
    $row = $ambil->fetch(PDO::FETCH_BOTH);
    ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Angkatan</h4>
        </div>
        <div class="modal-body modal-edit">
            <form id="form-edit">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="kode">Kode Kelompok</label>
                        <input value="<?php echo $row['kode_kelompok']; ?>" placeholder="contoh: MPK" type="text" class="form-control" name="kode" id="kode"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="nama">Nama Kelompok Matakuliah</label>
                        <input value="<?php echo $row['nama']; ?>" placeholder="contoh: Matakuliah Pengembangan Kepribadian" type="text" class="form-control" name="nama" id="nama"/>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $row['' . $id_column . '']; ?>" name="id"/>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
            <button id="tombol-edit" type="button" class="btn btn-primary">Update</button>
        </div>
    </div>
    <?php
}
if (isset($_GET['edit'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $update = $db->prepare("update " . $tabel . " set kode_kelompok=?, nama=? where " . $id_column . "=?");
    if ($update->execute(array($kode, $nama, $id_data))) {
        echo "1";
    }
}