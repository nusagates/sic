<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include '../../konfig.php';

$admintitle = "Master Waktu Perkuliahan";

include '../../header.php';
?>
<div data-title="<?php echo $admintitle; ?>">

    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $admintitle; ?></h3>
            <div class="pull-right box-tools">
                <?php if ($akses == "pegawai") { ?>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-file-text-o"> Tambah</i></button>
                <?php } ?>
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Ciutkan">
                    <i class="fa fa-minus"></i></button>

            </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body" > skip dulu ya..
            <table id="tabel" data-role="table" class="table stripe table-hover ui-responsive">
                <thead>
                    <tr>
                        <th width="5%">NO.</th>
                        <th>Waktu Perkuliahan</th>
                        <th width="5%">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div id="tambah" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tambah</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form id="form-tambah">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="waktu">Waktu Perkuliahan</label>
                                        <input type="text" class="form-control" name="waktu" id="waktu"/>
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
            <div class="modal-dialog modal-edit">
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
                url: 'data/proses.php?fetch',
                dataType: 'json',
                success: function (s) {
                    outTable.fnClearTable();
                    for (var i = 0; i < s.length; i++)
                    {
                        outTable.fnAddData([i + 1, s[i][0], s[i][1]]);
                    } // End For

                },
                error: function (e) {
                    console.log(e.responseText);
                }
            });
        }
        $(function () {
            fetch_data();
            $("#waktu").datetimepicker({
                datepicker: false,
                format: 'H:i',
                allowTimes: ['05:00', '05:05', '05:10', '05:15', '05:20', '05:25', '05:30', '05:35', '05:40', '05:45', '05:50', '05:55',
                             '06:00', '06:05', '06:10', '06:15', '06:20', '06:25', '06:30', '06:35', '06:40', '06:45', '06:50', '06:55', 
                             '07:00', '07:05', '07:10', '07:15', '07:20', '07:25', '07:30', '07:35', '07:40', '07:45', '07:50', '07:55',
                             '08:00', '08:05', '08:10', '08:15', '08:20', '08:25', '08:30', '08:35', '08:40', '08:45', '08:50', '08:55',
                             '09:00', '09:05', '09:10', '09:15', '09:20', '09:25', '09:30', '09:35', '09:40', '09:45', '09:50', '09:55',
                             '10:00', '10:05', '10:10', '10:15', '10:20', '10:25', '10:30', '10:35', '10:40', '10:45', '10:50', '10:55',
                             '11:00', '11:05', '11:10', '11:15', '11:20', '11:25', '11:30', '11:35', '11:40', '11:45', '11:50', '11:55',
                             '12:00', '12:05', '12:10', '12:15', '12:20', '12:25', '12:30', '12:35', '12:40', '12:45', '12:50', '12:55',
                             '13:00', '13:05', '13:10', '13:15', '13:20', '13:25', '13:30', '13:35', '13:40', '13:45', '13:50', '13:55',
                             '14:00', '14:05', '14:10', '14:15', '14:20', '14:25', '14:30', '14:35', '14:40', '14:45', '14:50', '14:55',
                             '15:00', '15:05', '15:10', '15:15', '15:20', '15:25', '15:30', '15:35', '15:40', '15:45', '15:50', '15:55',
                             '16:00', '16:05', '16:10', '16:15', '16:20', '16:25', '16:30', '16:35', '16:40', '16:45', '16:50', '16:55',
                             '17:00', '17:05', '17:10', '17:15', '17:20', '17:25', '17:30', '17:35', '17:40', '17:45', '17:50', '17:55',
                             '18:00', '18:05', '18:10', '18:15', '18:20', '18:25', '18:30', '18:35', '18:40', '18:45', '18:50', '18:55',
                             '19:00', '19:05', '19:10', '19:15', '19:20', '19:25', '19:30', '19:35', '19:40', '19:45', '19:50', '19:55',
                             '20:00', '20:05', '20:10', '20:15', '20:20', '20:25', '20:30', '20:35', '20:40', '20:45', '20:50', '20:55',
                             '21:00', '21:05', '21:10', '21:15', '21:20', '21:25', '21:30', '21:35', '21:40', '21:45', '21:50', '21:55',
                             '22:00', '22:05', '22:10', '22:15', '22:20', '22:25', '22:30', '22:35', '22:40', '22:45', '22:50', '22:55',
                             '23:00', '23:05', '23:10', '23:15', '23:20', '23:25', '23:30', '23:35', '23:40', '23:45', '23:50', '23:55', '24:00']
            });
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



