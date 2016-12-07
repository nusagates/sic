<?php
require_once '../../../konfig.php';
global $user, $tabel;
$user = wp_get_current_user();
$id = $user->id;
$akses = $user->roles[0];
$tabel = "akademik_jadwal_kuliah";
$id_column = "jadwal_id";
if (isset($_GET['ambil'])) {
    $menu = "";
    $ambil = $db->query("SELECT DISTINCT
akademik_jadwal_kuliah.jadwal_id,
app_hari.hari,
makul_matakuliah.kode_makul,
makul_matakuliah.nama_makul,
makul_matakuliah.sks,
app_ruangan.nama_ruangan,
akademik_jadwal_kuliah.jam_mulai,
akademik_jadwal_kuliah.jam_selesai,
app_dosen.nama_lengkap,
akademik_tahun_akademik.keterangan,
akademik_konsentrasi.nama_konsentrasi,
akademik_jadwal_kuliah.semester FROM akademik_jadwal_kuliah
LEFT JOIN app_hari ON akademik_jadwal_kuliah.hari_id=app_hari.hari_id
LEFT JOIN makul_matakuliah ON akademik_jadwal_kuliah.makul_id=makul_matakuliah.makul_id
LEFT JOIN app_ruangan ON akademik_jadwal_kuliah.ruangan_id=app_ruangan.ruangan_id
LEFT JOIN app_dosen ON akademik_jadwal_kuliah.dosen_id=app_dosen.dosen_id
LEFT JOIN akademik_tahun_akademik ON akademik_jadwal_kuliah.tahun_akademik_id=akademik_tahun_akademik.tahun_akademik_id
LEFT JOIN akademik_konsentrasi ON akademik_jadwal_kuliah.konsentrasi_id = akademik_konsentrasi.konsentrasi_id
WHERE akademik_jadwal_kuliah.hari_id BETWEEN 1 AND 7
GROUP BY akademik_jadwal_kuliah.jadwal_id
ORDER BY makul_matakuliah.nama_makul");
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
        $data[] = array(strtoupper($row['hari']), $row['kode_makul'], $row['nama_makul'], $row['sks'], $row['nama_ruangan'], date("h:i", strtotime($row['jam_mulai'])) . " s/d " . date("h:i", strtotime($row['jam_selesai'])), $row['nama_lengkap'], $menu);
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
    $konsentrasi = isset($_POST['konsentrasi']) ? $_POST['konsentrasi'] : '';
    $makul = isset($_POST['makul']) ? $_POST['makul'] : '';
    $hari = isset($_POST['hari']) ? $_POST['hari'] : '';
    $ruangan = isset($_POST['ruangan']) ? $_POST['ruangan'] : '';
    $dosen = isset($_POST['dosen']) ? $_POST['dosen'] : '';
    $mulai = isset($_POST['mulai']) ? $_POST['mulai'] : '';
    $selesai = isset($_POST['selesai']) ? $_POST['selesai'] : '';
    $semester = substr($tahun, 0, 4);
    $tambah = $db->prepare("insert into " . $tabel . "(tahun_akademik_id, konsentrasi_id, makul_id, hari_id, ruangan_id, dosen_id, semester, jam_mulai, jam_selesai) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($tambah->execute(array($tahun, $konsentrasi, $makul, $hari, $ruangan, $dosen, $semester, $mulai, $selesai))) {
        echo "1";
    }
}
if (isset($_GET['edit-form'])) {
    $id_data = $_GET['edit-form'];
    $ambil = $db->query("SELECT 
                        akademik_jadwal_kuliah.jadwal_id,
                        app_hari.hari_id,
                        makul_matakuliah.kode_makul,
                        makul_matakuliah.makul_id,
                        makul_matakuliah.sks,
                        app_ruangan.ruangan_id,
                        akademik_jadwal_kuliah.jam_mulai,
                        akademik_jadwal_kuliah.jam_selesai,
                        app_dosen.dosen_id,
                        akademik_tahun_akademik.tahun_akademik_id,
                        akademik_konsentrasi.konsentrasi_id,
                        akademik_jadwal_kuliah.semester FROM akademik_jadwal_kuliah
                        LEFT JOIN app_hari ON akademik_jadwal_kuliah.hari_id=app_hari.hari_id
                        LEFT JOIN makul_matakuliah ON akademik_jadwal_kuliah.makul_id=makul_matakuliah.makul_id
                        LEFT JOIN app_ruangan ON akademik_jadwal_kuliah.ruangan_id=app_ruangan.ruangan_id
                        LEFT JOIN app_dosen ON akademik_jadwal_kuliah.dosen_id=app_dosen.dosen_id
                        LEFT JOIN akademik_tahun_akademik ON akademik_jadwal_kuliah.tahun_akademik_id=akademik_tahun_akademik.tahun_akademik_id
                        LEFT JOIN akademik_konsentrasi ON akademik_jadwal_kuliah.konsentrasi_id = akademik_konsentrasi.konsentrasi_id
                        WHERE jadwal_id=" . $id_data . "
                        GROUP BY akademik_jadwal_kuliah.jadwal_id
                        ORDER BY makul_matakuliah.nama_makul");
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
                        <label for="tahun">Tahun Akademik</label>
                        <?php echo pilihan("akademik_tahun_akademik", "tahun", "tahun", "0", "1"); ?>
                        <input  type="hidden" value="<?php echo $row['tahun_akademik_id']; ?>" id="th"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="konsentrasi">Takhosus</label>
                        <?php echo pilihan("akademik_konsentrasi", "konsentrasi", "konsentrasi", "0", "1"); ?>
                        <input  type="hidden" value="<?php echo $row['konsentrasi_id']; ?>" id="konsen"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="makul">Matakuliah</label>
                        <?php echo pilihan("makul_matakuliah", "makul", "makul", "0", "2"); ?>
                        <input  type="hidden" value="<?php echo $row['makul_id']; ?>" id="mk"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <?php echo pilihan("app_hari", "hari", "hari", "0", "1"); ?>
                        <input  type="hidden" value="<?php echo $row['hari_id']; ?>" id="hr"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="ruangan">Ruangan</label>
                        <?php echo pilihan("app_ruangan", "ruangan", "ruangan", "0", "1"); ?>
                        <input  type="hidden" value="<?php echo $row['ruangan_id']; ?>" id="ru"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dosen">Dosen</label>
                        <?php echo pilihan("app_dosen", "dosen", "dosen", "0", "1"); ?>
                        <input  type="hidden" value="<?php echo $row['dosen_id']; ?>" id="do"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="mulai">Jam Mulai</label>
                        <?php echo pilihan("akademik_waktu_kuliah", "mulai", "mulai", "1", "1"); ?>
                        <input  type="hidden" value="<?php echo date("h:i", strtotime($row['jam_mulai'])); ?>" id="mu"/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="selesai">Jam Selesai</label>
                        <?php echo pilihan("akademik_waktu_kuliah", "selesai", "selesai", "1", "1"); ?>
                        <input  type="hidden" value="<?php echo date("h:i", strtotime($row['jam_selesai'])); ?>" id="se"/>
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
                $("select[name='tahun'] option[value='" + $("#th").val() + "']").prop('selected', 'selected');
                $("select[name='konsentrasi'] option[value='" + $("#konsen").val() + "']").prop('selected', 'selected');
                $("select[name='makul'] option[value='" + $("#mk").val() + "']").prop('selected', 'selected');
                $("select[name='hari'] option[value='" + $("#hr").val() + "']").prop('selected', 'selected');
                $("select[name='ruangan'] option[value='" + $("#ru").val() + "']").prop('selected', 'selected');
                $("select[name='dosen'] option[value='" + $("#do").val() + "']").prop('selected', 'selected');
                $("select[name='mulai'] option[value='" + $("#mu").val() + "']").prop('selected', 'selected');
                $("select[name='selesai'] option[value='" + $("#se").val() + "']").prop('selected', 'selected');
            });
        </script>
    </div>
    <?php
}
if (isset($_GET['edit'])) {
    $id_data = isset($_POST['id']) ? $_POST['id'] : '';
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
    $konsentrasi = isset($_POST['konsentrasi']) ? $_POST['konsentrasi'] : '';
    $makul = isset($_POST['makul']) ? $_POST['makul'] : '';
    $hari = isset($_POST['hari']) ? $_POST['hari'] : '';
    $ruangan = isset($_POST['ruangan']) ? $_POST['ruangan'] : '';
    $dosen = isset($_POST['dosen']) ? $_POST['dosen'] : '';
    $mulai = isset($_POST['mulai']) ? $_POST['mulai'] : '';
    $selesai = isset($_POST['selesai']) ? $_POST['selesai'] : '';
    $semester = substr($tahun, 0, 4);
    $update = $db->prepare("update " . $tabel . " set tahun_akademik_id=?, konsentrasi_id=?, makul_id=?, hari_id=?, ruangan_id=?, dosen_id=?, jam_mulai=?, jam_selesai=?, semester=? where " . $id_column . "=?");
    if ($update->execute(array($tahun, $konsentrasi, $makul, $hari, $ruangan, $dosen, $mulai, $selesai, $semester, $id_data))) {
        echo "1";
    }
}