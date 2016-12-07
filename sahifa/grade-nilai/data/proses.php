<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "app_nilai_grade";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("select * from " . $tabel . " order by grade asc");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "data-edit='" . $row['nilai_id'] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['nilai_id'] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $data[] = array($row['grade'], $row['dari'], $row['sampai'], $row['keterangan'], $menu);
    }
    echo json_encode($data);
}
if (isset($_GET['hapus'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    $hapus = $db->prepare("delete from " . $tabel . " where nilai_id=?");
    if ($hapus->execute(array($data))) {
        echo "1";
    }
}
if (isset($_GET['tambah'])) {
    $grade = isset($_POST['grade']) ? $_POST['grade'] : '';
    $min = isset($_POST['min']) ? $_POST['min'] : '';
    $max = isset($_POST['max']) ? $_POST['max'] : '';
    $keterangan = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';
    $tambah = $db->prepare("insert into " . $tabel . "(dari, sampai, grade, keterangan) values(?, ?, ?, ?)");
    if ($tambah->execute(array($min, $max, $grade, $keterangan))) {
        echo "1";
    }
}
if (isset($_GET['edit-form'])) {
    sleep(1);
    $id = $_GET['edit-form'];
    $ambil = $db->query("select * from ".$tabel." where nilai_id='$id'");
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
                        <label for="grade">Grade</label>
                        <input value="<?php echo $row['grade']; ?>" placeholder="contoh: A" type="text" class="form-control" name="grade" id="grade"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="min">MIN</label>
                        <input value="<?php echo $row['dari']; ?>" placeholder="contoh: 9.0" type="text" class="form-control" name="min" id="min"/>
                    </div>  
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="max">MAX</label>
                        <input value="<?php echo $row['sampai']; ?>" placeholder="contoh: 10" type="text" class="form-control" name="max" id="max"/>
                    </div>  
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="max">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="contoh:Lulus"><?php echo $row['keterangan']; ?></textarea>
                    </div>  
                </div>
                <input type="hidden" value="<?php echo $row['nilai_id']; ?>" name="id"/>
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
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $grade = isset($_POST['grade']) ? $_POST['grade'] : '';
    $min = isset($_POST['min']) ? $_POST['min'] : '';
    $max = isset($_POST['max']) ? $_POST['max'] : '';
    $keterangan = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';
    $update = $db->prepare("update ".$tabel." set dari=?, sampai=?, grade=?, keterangan=? where nilai_id=?");
    if ($update->execute(array($min, $max, $grade, $keterangan, $id))) {
        echo "1";
    }
}