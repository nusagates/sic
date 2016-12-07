<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "makul_matakuliah";
$id_column = "makul_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("SELECT makul_matakuliah.kode_makul, makul_kelompok.kode_kelompok , makul_matakuliah.nama_makul, makul_matakuliah.sks, makul_matakuliah.jam, makul_matakuliah.makul_id
                            from makul_matakuliah
                            INNER JOIN makul_kelompok on makul_matakuliah.kelompok_id=makul_kelompok.kelompok_id order by nama_makul");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "data-edit='" . $row['' . $id_column . ''] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['' . $id_column . ''] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $data[] = array($row['kode_makul'], strtoupper($row['kode_kelompok']), strtoupper($row['nama_makul']), $row['sks'], $row['jam'], $menu);
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
    $sks = isset($_POST['sks']) ? $_POST['sks'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $kelompok = isset($_POST['kelompok']) ? $_POST['kelompok'] : '';
    $prodi = isset($_POST['prodi']) ? $_POST['prodi'] : '';
    $konsentrasi = isset($_POST['konsentrasi']) ? $_POST['konsentrasi'] : '';
    $jam = isset($_POST['jam']) ? $_POST['jam'] : '';

    $tambah = $db->prepare("insert into " . $tabel . "(kode_makul, nama_makul, sks, semester, konsentrasi_id, kelompok_id, aktif, jam) values(?, ?, ?, ?, ?, ?, 'y', ?)");
    if ($tambah->execute(array($kode, $nama, $sks, $semester, $konsentrasi, $kelompok, $jam))) {
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
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="kode">Kode Matakuliah</label>
                        <input value="<?php echo $row['kode_makul']; ?>" placeholder="contoh: ISTI1" type="text" class="form-control" name="kode" id="kode"/>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="nama">Nama Matakuliah</label>
                        <input value="<?php echo $row['nama_makul']; ?>" placeholder="contoh: ISTIDAD 1" type="text" class="form-control" name="nama" id="nama"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="sks">SKS</label>
                        <input value="<?php echo $row['sks']; ?>" placeholder="contoh: 2" type="text" class="form-control" name="sks" id="sks"/>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" id="semester" name="semester">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
                        </select>
                        <input  type="hidden" value="<?php echo $row['semester']; ?>" id="semester_id"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="kelompok">Kelompok Matakuliah</label>
                        <?php echo pilihan("makul_kelompok", "kelompok", "kelompok", "0", "2"); ?>
                        <input  type="hidden" value="<?php echo $row['kelompok_id']; ?>" id="kelompok_id"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="jam">Jumlah Jam</label>
                        <input value="<?php echo $row['jam']; ?>" placeholder="contoh: 2" type="text" class="form-control" name="jam" id="jam"/>
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
        $(function () {
            var semester_id = $("#semester_id").val();
            var kelompok_id = $("#kelompok_id").val();
            var konsentrasi_id = $("#konsentrasi_id").val();
            $("select[name=semester] option[value=" + semester_id + "]").attr('selected', 'selected');
            $("select[name=kelompok] option[value=" + kelompok_id + "]").attr('selected', 'selected');
        });
    </script>
    <?php
}
if (isset($_GET['edit'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $sks = isset($_POST['sks']) ? $_POST['sks'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $kelompok = isset($_POST['kelompok']) ? $_POST['kelompok'] : '';
    $jam = isset($_POST['jam']) ? $_POST['jam'] : '';
    $update = $db->prepare("update " . $tabel . " set kode_makul=?, nama_makul=?, sks=?, semester=?, kelompok_id=?, jam=? where " . $id_column . "=?");
    if ($update->execute(array($kode, $nama, $sks, $semester, $kelompok, $jam, $id_data))) {
        echo "1";
    }
}
if (isset($_GET['konsentrasi'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $ambil = $db->query("select konsentrasi_id, nama_konsentrasi from akademik_konsentrasi where prodi_id=$id");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        echo "<option value='" . $row[0] . "'>"
        . "" . strtoupper($row[1]) . ""
        . "</option>";
    }
}