<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "akademik_konsentrasi";
$id_column = "konsentrasi_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("SELECT akademik_konsentrasi.nama_konsentrasi, akademik_prodi.nama_prodi, akademik_konsentrasi.ketua, akademik_konsentrasi.jenjang, akademik_konsentrasi.jml_semester, akademik_konsentrasi.gelar, akademik_konsentrasi.konsentrasi_id 
                            FROM akademik_konsentrasi 
                            INNER JOIN akademik_prodi 
                            ON akademik_konsentrasi.prodi_id=akademik_prodi.prodi_id  order by akademik_konsentrasi.nama_konsentrasi");
    while ($row = $ambil->fetch(PDO::FETCH_BOTH)) {
        if ($akses == "pegawai") {
            $menu = "<a "
                    . "data-edit='" . $row['' . $id_column . ''] . "' "
                    . "class='tombol-edit btn btn-xs btn-success' href='#'><i class='fa fa-edit'></i></a> "
                    . "<a data-hapus='" . $row['' . $id_column . ''] . "' class='tombol-hapus btn btn-xs btn-danger' href='#'><i class='fa fa-times'></i></a>";
        }
        $data[] = array(strtoupper($row['nama_konsentrasi']), strtoupper($row['nama_prodi']), $row['ketua'], $row['jenjang'] . ", " . $row['jml_semester'], $row['gelar'], $menu);
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
    $prodi = isset($_POST['prodi']) ? $_POST['prodi'] : '';
    $konsentrasi = isset($_POST['konsentrasi']) ? $_POST['konsentrasi'] : '';
    $ketua = isset($_POST['ketua']) ? $_POST['ketua'] : '';
    $jenjang = isset($_POST['jenjang']) ? $_POST['jenjang'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $gelar = isset($_POST['gelar']) ? $_POST['gelar'] : '';
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';

    $tambah = $db->prepare("insert into " . $tabel . "(nama_konsentrasi, ketua, jenjang, jml_semester, kode_nomor, gelar, prodi_id) values(?, ?, ?, ?, ?, ?, ?)");
    if ($tambah->execute(array($konsentrasi, $ketua, $jenjang, $semester, $kode, $gelar, $prodi))) {
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
                        <label for="prodi">Nama Prodi</label>
                        <?php echo pilihan("akademik_prodi", "prodi", 'prodi', '0', '1'); ?>
                        <input value="<?php echo $row['prodi_id']; ?>" type="hidden" id="prodi_id"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="konsentrasi">Nama Konsentrasi</label>
                        <input value="<?php echo $row['nama_konsentrasi']; ?>"  type="text" class="form-control" name="konsentrasi" id="konsentrasi"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="ketua">Ketua</label>
                        <input value="<?php echo $row['ketua']; ?>" type="text" class="form-control" name="ketua" id="ketua"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="jenjang">Jenjang</label>
                        <select id="jenjang" name="jenjang" class="form-control">
                            <option value="d1">D1</option>
                            <option value="d2">D2</option>
                            <option value="d3">D3</option>
                            <option value="d4">D4</option>
                            <option value="s1">S1</option>
                        </select>
                        <input value="<?php echo $row['jenjang']; ?>" type="hidden" id="jenjang_id"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="semester">Jumlah Semester</label>
                        <select id="semester" name="semester" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                        <input value="<?php echo $row['jml_semester']; ?>" type="hidden" id="semester_id"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="gelar">Gelar</label>
                        <input value="<?php echo $row['gelar']; ?>" type="text" class="form-control" name="gelar" id="gelar"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="kode">Kode Nomor</label>
                        <input value="<?php echo $row['kode_nomor']; ?>" type="text" class="form-control" name="kode" id="kode"/>
                    </div>
                </div>
                <input id="id" type="hidden" value="<?php echo $row['' . $id_column . '']; ?>" name="id"/>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
            <button id="tombol-edit" type="button" class="btn btn-primary">Update</button>
        </div>
        <script>
            $(function () {
                var prodi_id = $("#prodi_id").val();
                $("select[name=prodi] option[value=" + prodi_id + "]").attr('selected', 'selected');
                var jenjang_id = $("#jenjang_id").val();
                $("select[name=jenjang] option[value=" + jenjang_id + "]").attr('selected', 'selected');
                var semester_id = $("#semester_id").val();
                $("select[name=semester] option[value=" + semester_id + "]").attr('selected', 'selected');
            });
        </script>
    </div>

    <?php
}
if (isset($_GET['edit'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $prodi = isset($_POST['prodi']) ? $_POST['prodi'] : '';
    $konsentrasi = isset($_POST['konsentrasi']) ? $_POST['konsentrasi'] : '';
    $ketua = isset($_POST['ketua']) ? $_POST['ketua'] : '';
    $jenjang = isset($_POST['jenjang']) ? $_POST['jenjang'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $gelar = isset($_POST['gelar']) ? $_POST['gelar'] : '';
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';
    $update = $db->prepare("update " . $tabel . " set nama_konsentrasi=?, ketua=?, jenjang=?, jml_semester=?, kode_nomor=?, gelar=?, prodi_id=? where " . $id_column . "=?");
    if ($update->execute(array($konsentrasi, $ketua, $jenjang, $semester, $kode, $gelar, $prodi, $id_data))) {
        echo "1";
    }
}