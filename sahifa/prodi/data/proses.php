<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "akademik_prodi";
$id_column = "prodi_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("select * from " . $tabel . " order by nama_prodi asc");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "data-edit='" . $row['' . $id_column . ''] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['' . $id_column . ''] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $data[] = array($row['no_izin'], $row['nama_prodi'], $row['ketua'], $menu);
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
    $izin = isset($_POST['izin']) ? $_POST['izin'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $ketua = isset($_POST['ketua']) ? $_POST['ketua'] : '';
    $tambah = $db->prepare("insert into " . $tabel . "(nama_prodi, ketua, no_izin, status) values(?, ?, ?, '1')");
    if ($tambah->execute(array($nama, $ketua, $izin))) {
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
                        <label for="nama">Nama Prodi</label>
                        <input value="<?php echo $row['nama_prodi']; ?>" placeholder="contoh: Tasawuf wa Thoriqotuhu" type="text" class="form-control" name="nama" id="nama"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="ketua">Nama Ketua</label>
                        <input value="<?php echo $row['ketua']; ?>" placeholder="contoh: Subchan, M,Fil.I" type="text" class="form-control" name="ketua" id="ketua"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="izin">No. Izin</label>
                        <input value="<?php echo $row['no_izin']; ?>" placeholder="contoh: 1169/D/T/2008" type="text" class="form-control" name="izin" id="izin"/>
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
    $izin = isset($_POST['izin']) ? $_POST['izin'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $ketua = isset($_POST['ketua']) ? $_POST['ketua'] : '';
    $update = $db->prepare("update " . $tabel . " set nama_prodi=?, ketua=?, no_izin=? where " . $id_column . "=?");
    if ($update->execute(array($nama, $ketua, $izin, $id_data))) {
        echo "1";
    }
}