<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include '../../konfig.php';

$admintitle = "Jadwal Kuliah";

include '../../header.php';
?>
<div data-title="<?php echo $admintitle; ?>">

    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $admintitle; ?></h3>
            <div class="pull-right box-tools">
                <?php if ($akses == "pegawai") { ?>
                    <button class="btn btn-info btn-sm"  id="import" title="" ><i class="fa  fa-file-excel-o"> Import</i></button>
                    <button class="btn btn-info btn-sm"  id="print" title="" ><i class="fa  fa-print"> Print</i></button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-file-text-o"> Tambah</i></button>
                <?php } ?>
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Ciutkan">
                    <i class="fa fa-minus"></i></button>

            </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body" >
            <table id="tabel" data-role="table" class="table stripe table-hover ui-responsive">
                <thead>
                    <tr>
                        <th width="7%">NO.</th>
                        <th>HARI</th>
                        <th>KODE</th>
                        <th>MATAKULIAH</th>
                        <th width="7%">SKS</th>
                        <th width="7%">RUANG</th>
                        <th width="7%">JAM</th>
                        <th width="7%">DOSEN</th>
                        <th width="5%">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div id="tambah" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tambah</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form id="form-tambah">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="tahun">Tahun Akademik</label>
                                        <?php echo pilihan("akademik_tahun_akademik", "tahun", "tahun", "0", "1"); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="konsentrasi">Takhosus</label>
                                        <?php echo pilihan("akademik_konsentrasi", "konsentrasi", "konsentrasi", "0", "1"); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="makul">Matakuliah</label>
                                        <?php echo pilihan("makul_matakuliah", "makul", "makul", "0", "2"); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="hari">Hari</label>
                                        <?php echo pilihan("app_hari", "hari", "hari", "0", "1"); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="ruangan">Ruangan</label>
                                        <?php echo pilihan("app_ruangan", "ruangan", "ruangan", "0", "1"); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="dosen">Dosen</label>
                                        <?php echo pilihan("app_dosen", "dosen", "dosen", "0", "1"); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="mulai">Jam Mulai</label>
                                        <?php echo pilihan("akademik_waktu_kuliah", "mulai", "mulai", "1", "1"); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="selesai">Jam Selesai</label>
                                        <?php echo pilihan("akademik_waktu_kuliah", "selesai", "selesai", "1", "1"); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
                        <button id="tombol-tambah" type="button" class="btn btn-primary">Tambah</button>
                    </div>
                </div>

            </div>
        </div>
        <div id="edit" class="modal fade" role="dialog">
            <div class="modal-dialog modal-edit modal-lg">
            </div>
        </div>
        <div id="overlay" class="text-center" style="display:none;width:100px;height: 100px;background: none">
            <div class="overlay">
                <i style="color:#fff" class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
    <!-- /.box -->
    <script>
        function fetch_data() {
            var outTable = $('#tabel').dataTable();
            $.ajax({
                url: 'data/proses.php?ambil',
                dataType: 'json',
                success: function (s) {
                    outTable.fnClearTable();
                    for (var i = 0; i < s.length; i++)
                    {
                        outTable.fnAddData([i + 1, s[i][0], s[i][1], s[i][2], s[i][3], s[i][4], s[i][5], s[i][6], s[i][7]]);
                    } // End For

                },
                error: function (e) {
                    console.log(e.responseText);
                }
            });
        }
        function fetch_makul() {
            var kon = $("#konsentrasi").val();
            $.ajax({
                url: "data/proses.php?konsentrasi",
                method: "post",
                data: {id: id},
                success: function (msg) {
                    $("#konsentrasi").html(msg);

                },
                error: function () {
                    alert("gagal");
                }
            });
        }
        $(function () {
            fetch_data();
            $("#tabel").on("click", ".tombol-hapus", function (e) {
                var keterangan = $(this).attr("data-hapus");
                var row = $(this).parents('tr');
                row.css("background", "red");
                e.preventDefault();
                $.ajax({
                    url: "data/proses.php?hapus",
                    method: "post",
                    data: {data: keterangan},
                    success: function (msg) {

                        if (msg == "1") {
                            row.fadeOut('slow');
                            fetch_data();
                        } else {
                            alert("Eror: " + msg);
                            row.css("background", "none");
                        }

                    },
                    error: function () {
                        alert("gagal");
                    }
                });
            });
            $("#tombol-tambah").click(function () {
                var $btn = $(this);
                var data = $("#form-tambah").serialize();
                $btn.button('loading');
                $.ajax({
                    url: "data/proses.php?tambah",
                    method: "post",
                    data: data,
                    success: function (msg) {
                        alert(msg)
                        if (msg === "1") {
                            $('#tambah').modal('hide');
                            $btn.button('reset');
                            $("form").trigger("reset");
                            fetch_data();
                        } else {
                            $btn.button('reset');
                        }

                    },
                    error: function () {
                        alert("gagal");
                    }
                });
            });
            $("#tabel").on("click", ".tombol-edit", function (e) {
                e.preventDefault();
                var id = $(this).attr("data-edit");
                $("#edit").modal('show');
                $(".modal-edit").html($("#overlay").html());
                $.ajax({
                    url: "data/proses.php?edit-form=" + id,
                    success: function (data) {
                        $(".modal-edit").html(data);
                    }
                });
            });
            $(".modal-edit").on("click", "#tombol-edit", function () {
                var $btn = $(this);
                var data = $("#form-edit").serialize();
                $btn.button('loading');
                $.ajax({
                    url: "data/proses.php?edit",
                    method: "post",
                    data: data,
                    success: function (msg) {
                        $btn.button('reset');
                        if (msg === "1") {
                            $('#edit').modal('hide');
                            $("form").trigger("reset");
                            fetch_data();
                        }
                    }
                });
            });

        });
    </script>
</div>
<?php include '../../footer.php'; ?>



