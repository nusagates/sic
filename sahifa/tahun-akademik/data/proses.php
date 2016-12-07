<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "akademik_tahun_akademik";
$id_column = "tahun_akademik_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("select * from " . $tabel . " order by keterangan");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "data-edit='" . $row['' . $id_column . ''] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['' . $id_column . ''] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $sem = substr($row['keterangan'], 4, 1);
        $semester = $sem === "1" ? "Semester Ganjil" : "Semester Genap";
        $status = $row['status'] === "n" ? "Tutup" : "Buka";
        $data[] = array(substr($row['keterangan'], 0, 4), $semester, $row['batas_registrasi'], $status, $menu);
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
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
    $batas = isset($_POST['batas']) ? $_POST['batas'] : '';
    $th = substr($tahun, 0, 4);
    $tambah = $db->prepare("insert into " . $tabel . "(keterangan, batas_registrasi, status, tahun) values(?, ?, 'n', ?)");
    if ($tambah->execute(array($tahun, $batas, $th))) {
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
                        <label for="tahun">Tahun Akademik</label>
                        <input value="<?php echo $row['keterangan']; ?>" placeholder="contoh: 20161" type="text" class="form-control" name="tahun" id="tahun"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="batas">Batas Registrasi</label>
                        <input value="<?php echo $row['batas_registrasi']; ?>" placeholder="contoh: 2016-10-05" type="text" class="form-control batas" name="batas" id="batas"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="batas">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="y">Buka</option>
                            <option value="n">Tutup</option>
                        </select>
                        <input value="<?php echo $row['status']; ?>" type="hidden" id="id"/>
                    </div>
                </div>
                <input  type="hidden" value="<?php echo $row['' . $id_column . '']; ?>" name="id"/>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
            <button id="tombol-edit" type="button" class="btn btn-primary">Update</button>
        </div>
        <script>
            $(function () {
                $("#batas").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                var id = $("#id").val();
                $("select[name=status] option[value=" + id + "]").attr('selected', 'selected');
            });
        </script>
    </div>
    <?php
}
if (isset($_GET['edit'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
    $batas = isset($_POST['batas']) ? $_POST['batas'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $th = substr($tahun, 0, 4);
    $update = $db->prepare("update " . $tabel . " set keterangan=?, batas_registrasi=?, status=? where " . $id_column . "=?");
    if ($update->execute(array($tahun, $batas, $status, $id_data))) {
        echo "1";
    }
}