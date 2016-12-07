<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />

        <title>Data Sekolah Salatiga</title>
        <script type="text/javascript" language="javascript" src="http://referensi.data.kemdikbud.go.id/js/jquery.js"></script>
        <link rel="stylesheet" type="text/css" href="http://referensi.data.kemdikbud.go.id/css/demo_table_jui.css"/>
        <link rel="stylesheet" type="text/css" href="http://referensi.data.kemdikbud.go.id/css/smoothness/jquery-ui-1.8.4.custom.css"/>
        <script type="text/javascript" src="http://referensi.data.kemdikbud.go.id/js/jquery-ui-tabs.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/r/ju-1.11.4/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function () {

                function fetch_sekolah() {
                    var outTable = $('#data-sekolah').dataTable({"iDisplayLength" : 50});
                    $.ajax({
                        url: 'data.php?semua',
                        dataType: 'json',
                        success: function (s) {
                            outTable.fnClearTable();
                            for (var i = 0; i < s.length; i++)
                            {
                                outTable.fnAddData([s[i][0], s[i][1], s[i][2], s[i][3], s[i][4], s[i][5], s[i][6]]);
                            } // End For
                            outTable.fnSort([[5, 'desc']]);
                        },
                        error: function (e) {
                            console.log(e.responseText);
                        }
                    });
                }
                fetch_sekolah();
            });
        </script>
    </head>
    <body>
        <div class="container">
            <table id="data-sekolah" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="4%">No.</th>
                        <th width="10%">NPSN</th>
                        <th>Nama Satuan Pendidikan</th>
                        <th>Alamt</th>
                        <th>Kelurahan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        
    </body>
</html>