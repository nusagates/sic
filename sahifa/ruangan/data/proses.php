<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "app_ruangan";
$id_column = "ruangan_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("SELECT app_ruangan.ruangan_id, app_ruangan.nama_ruangan, app_ruangan.kapasitas, app_gedung.nama_gedung , app_ruangan.keterangan
                         from app_ruangan
                         INNER JOIN app_gedung ON app_ruangan.gedung_id = app_gedung.gedung_id");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "data-edit='" . $row['' . $id_column . ''] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['' . $id_column . ''] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $data[] = array($row['nama_ruangan'], $row['kapasitas'], $row['nama_gedung'], $row['keterangan'], $menu);
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
    $nama = isset($_POST['ruang']) ? $_POST['ruang'] : '';
    $gedung = isset($_POST['gedung']) ? $_POST['gedung'] : '';
    $kapasitas = isset($_POST['kapasitas']) ? $_POST['kapasitas'] : '';
    $keterangan = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';
    $tambah = $db->prepare("insert into " . $tabel . "(nama_ruangan, gedung_id, kapasitas, keterangan) values(?, ?, ?, ?)");
    if ($tambah->execute(array($nama, $gedung, $kapasitas, $keterangan))) {
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
                        <label for="ruang">Nama Ruang</label>
                        <input value="<?php echo $row['nama_ruangan']; ?>" placeholder="contoh: A.1" type="text" class="form-control" name="ruang" id="ruang"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="gedung">Nama Gedung</label>
                        <?php echo dropdown("app_gedung", "gedung", "pilihan-gedung"); ?>
                        <input value="<?php echo $row['gedung_id']; ?>" type="hidden" id="id"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas</label>
                        <input value="<?php echo $row['kapasitas']; ?>" placeholder="contoh: 20" type="number" class="form-control" name="kapasitas" id="kapasitas"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan"><?php echo $row['keterangan']; ?></textarea>
                    </div>
                </div>
                <input  type="hidden" value="<?php echo $row['' . $id_column . '']; ?>" name="id"/>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
            <button id="tombol-edit" type="button" class="btn btn-primary">Update</button>
        </div>
    </div>
<script>
    $(function(){
       var id= $("#id").val();
       $("select[name=gedung] option[value="+id+"]").attr('selected','selected');
    });
    </script>
    <?php
}
if (isset($_GET['edit'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $nama = isset($_POST['ruang']) ? $_POST['ruang'] : '';
    $gedung = isset($_POST['gedung']) ? $_POST['gedung'] : '';
    $kapasitas = isset($_POST['kapasitas']) ? $_POST['kapasitas'] : '0';
    $keterangan = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';
    $update = $db->prepare("update " . $tabel . " set nama_ruangan=?, gedung_id=?, kapasitas=?, keterangan=? where " . $id_column . "=?");
    if ($update->execute(array($nama, $gedung, $kapasitas, $keterangan, $id_data))) {
        echo "1";
    }
}