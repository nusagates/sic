<?php
require_once '../../../konfig.php';
global $user;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("select * from student_angkatan");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        $status = $row["aktif"] == 'n' ? "Tidak Aktif" : "Aktif";
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "angkatan='" . $row['keterangan'] . "' "
                    . "status='" . $row['aktif'] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['keterangan'] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $data[] = array($row['keterangan'], $status, $menu);
    }
    echo json_encode($data);
}
if (isset($_GET['hapus'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    $hapus = $db->prepare("delete from student_angkatan where keterangan=?");
    if ($hapus->execute(array($data))) {
        echo "1";
    }
}
if (isset($_GET['tambah'])) {
    $angkatan = isset($_POST['angkatan']) ? $_POST['angkatan'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $tambah = $db->prepare("insert into student_angkatan(keterangan, aktif) values(?, ?)");
    if ($tambah->execute(array($angkatan, $status))) {
        echo "1";
    }
}
if (isset($_GET['edit-form'])) {
    sleep(1);
    $angkatan = $_GET['edit-form'];
    $ambil = $db->query("select keterangan, aktif from student_angkatan where keterangan='$angkatan'");
    $row = $ambil->fetch(PDO::FETCH_BOTH);
    $n = $row["aktif"] == 'n' ? 'selected' : '';
    $y = $row["aktif"] == 'y' ? 'selected' : '';
    ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Angkatan</h4>
        </div>
        <div class="modal-body modal-edit">
            <form id="form-edit">
                <div class="form-group">
                    <label for="angkatan">Angkatan</label>
                    <input placeholder="contoh: 2016-2017" value="<?php echo $row['keterangan']; ?>" type="text" class="form-control" name="angkatan" id="angkatan"/>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" name="status" id=status">
                        <option value="n" <?php echo $n; ?>>Tidak Aktif</option>
                        <option value="y" <?php echo $y; ?>>Aktif</option>
                    </select>
                </div>
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
    $angkatan = isset($_POST['angkatan']) ? $_POST['angkatan'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $update = $db->prepare("update student_angkatan set aktif=? where keterangan=?");
    if($update->execute(array($status, $angkatan))){
        echo "1";
    }
}