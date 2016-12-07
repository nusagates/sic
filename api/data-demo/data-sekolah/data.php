<?php
require '../../../include/koneksi.php';
require('../../../../wp-load.php' );
if (isset($_GET['semua'])) {
    $pilih = $db->query('SELECT npsn, nama_sp, alamat_jalan, desa_kelurahan, jenjang, status_sekolah FROM e_sekolah order by nama_sp desc');
    $no = 1;
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data[] = array($no, "<a data-fancybox-type='iframe' title='Nama Sekolah: " . $row['nama_sp'] . "' target='_blank' href='" . get_home_url() . "/e-office/api/data-demo/data-sekolah/data.php?npsn=" . $row['npsn'] . "'>" . $row['npsn'] . "</a>", $row['nama_sp'], $row['alamat_jalan'], $row['desa_kelurahan'], $row['status_sekolah']);
        $no++;
    }
    echo json_encode($data);
} elseif (isset($_GET['paud'])) {
    $pilih = $db->query("SELECT npsn, nama_sp, alamat_jalan, desa_kelurahan, jenjang, status_sekolah FROM e_sekolah where jenjang='PAUD' order by nama_sp desc");
    $no = 1;
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data[] = array($no, "<a data-fancybox-type='iframe'  title='Nama Sekolah: " . $row['nama_sp'] . "' target='_blank' href='" . get_home_url() . "/e-office/api/data-demo/data-sekolah/data.php?npsn=" . $row['npsn'] . "'>" . $row['npsn'] . "</a>", $row['nama_sp'], $row['alamat_jalan'], $row['desa_kelurahan'], $row['status_sekolah']);
        $no++;
    }
    echo json_encode($data);
} elseif (isset($_GET['tk'])) {
    $pilih = $db->query("SELECT npsn, nama_sp, alamat_jalan, desa_kelurahan, jenjang, status_sekolah FROM e_sekolah where jenjang='TK' order by nama_sp desc");
    $no = 1;
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data[] = array($no, "<a data-fancybox-type='iframe' title='Nama Sekolah: " . $row['nama_sp'] . "' target='_blank' href='" . get_home_url() . "/e-office/api/data-demo/data-sekolah/data.php?npsn=" . $row['npsn'] . "'>" . $row['npsn'] . "</a>", $row['nama_sp'], $row['alamat_jalan'], $row['desa_kelurahan'], $row['status_sekolah']);
        $no++;
    }
    echo json_encode($data);
} elseif (isset($_GET['sd'])) {
    $pilih = $db->query("SELECT npsn, nama_sp, alamat_jalan, desa_kelurahan, jenjang, status_sekolah FROM e_sekolah where jenjang='SD' order by nama_sp desc");
    $no = 1;
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data[] = array($no, "<a data-fancybox-type='iframe' title='Nama Sekolah: " . $row['nama_sp'] . "' target='_blank' href='" . get_home_url() . "/e-office/api/data-demo/data-sekolah/data.php?npsn=" . $row['npsn'] . "'>" . $row['npsn'] . "</a>", $row['nama_sp'], $row['alamat_jalan'], $row['desa_kelurahan'], $row['status_sekolah']);
        $no++;
    }
    echo json_encode($data);
} elseif (isset($_GET['sltp'])) {
    $pilih = $db->query("SELECT npsn, nama_sp, alamat_jalan, desa_kelurahan, jenjang, status_sekolah FROM e_sekolah where jenjang='SMP' order by nama_sp desc");
    $no = 1;
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data[] = array($no, "<a data-fancybox-type='iframe' title='Nama Sekolah: " . $row['nama_sp'] . "' target='_blank' href='" . get_home_url() . "/e-office/api/data-demo/data-sekolah/data.php?npsn=" . $row['npsn'] . "'>" . $row['npsn'] . "</a>", $row['nama_sp'], $row['alamat_jalan'], $row['desa_kelurahan'], $row['status_sekolah']);
        $no++;
    }
    echo json_encode($data);
} elseif (isset($_GET['slta'])) {
    $pilih = $db->query("SELECT npsn, nama_sp, alamat_jalan, desa_kelurahan, jenjang, status_sekolah FROM e_sekolah where jenjang='SMA' or jenjang='SMK' order by nama_sp desc");
    $no = 1;
    while ($row = $pilih->fetch(PDO::FETCH_BOTH)) {
        $data[] = array($no, "<a data-fancybox-type='iframe' title='Nama Sekolah: " . $row['nama_sp'] . "' target='_blank' href='" . get_home_url() . "/e-office/api/data-demo/data-sekolah/data.php?npsn=" . $row['npsn'] . "'>" . $row['npsn'] . "</a>", $row['nama_sp'], $row['alamat_jalan'], $row['desa_kelurahan'], $row['status_sekolah']);
        $no++;
    }
    echo json_encode($data);
} else if (isset($_GET['npsn'])) {
    $npsn = $_GET['npsn'];
    $pilih = $db->query("SELECT * FROM e_sekolah where npsn='$npsn' order by nama_sp desc");
    $row = $pilih->fetch(PDO::FETCH_BOTH);
    ?>
    <!doctype HTML>
    <html>
        <head>
            <title>Data <?php echo $row['nama_sp']; ?></title>
            <style>
                #info{
                    background: #0088cc;
                    border-radius: 5px;
                    color: #fff;
                    padding:3px 5px;
                    margin-bottom: 10px
                }
            </style>
        </head>
        <body>
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <link rel="stylesheet" type="text/css" href="http://referensi.data.kemdikbud.go.id/css/demo_table_jui.css"/>
            <link rel="stylesheet" type="text/css" href="http://referensi.data.kemdikbud.go.id/css/smoothness/jquery-ui-1.8.4.custom.css"/>
            <script type="text/javascript" src="http://referensi.data.kemdikbud.go.id/js/jquery-ui-tabs.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/r/ju-1.11.4/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8xcTTbDuSrQjdDn9iLsPKtVYtpFSWor0&callback=initMap"
            async defer></script>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Identitas Sekolah</a></li>
                    <li><a href="#tabs-2">Legalitas</a></li>
                    <li><a href="#tabs-3">Sarpras</a></li>
                    <li><a href="#tabs-4">Kontak</a></li>
                    <li><a href="#tabs-5">Peta Sekolah</a></li>
                </ul>
                <div id="tabs-1">
                    <table>
                        <tr><td>Nama</td><td>: <?php echo $row['nama_sp']; ?></td></tr>
                        <tr><td>NPSN</td><td>: <?php echo $row['npsn']; ?></td></tr>
                        <tr><td>Alamat</td><td>: <?php echo $row['alamat_jalan']; ?></td></tr>
                        <tr><td>Kelurahan</td><td>: <?php echo $row['desa_kelurahan']; ?></td></tr>
                        <tr><td>Kecamatan</td><td>: <?php echo $row['kec_']; ?></td></tr>
                        <tr><td>Status</td><td>: <?php echo $row['status_sekolah']; ?></td></tr>
                        <tr><td>Waktu Penyelenggaraan</td><td>: <?php echo $row['waktu penyelenggaraan']; ?></td></tr>
                        <tr><td>Jenjang Pendidikan</td><td>: <?php echo $row['jenjang']; ?></td></tr>
                    </table>
                </div>
                <div id="tabs-2">
                    <table>
                        <tr><td>No. SK Pendirian</td><td>: <?php echo $row['sk_pendirian_sekolah']; ?></td></tr>
                        <tr><td>Tanggal SK Pendirian</td><td>: <?php echo $row['tanggal_sk_pendirian']; ?></td></tr>
                        <tr><td>No. SK Operasional</td><td>: <?php echo $row['sk_izin_operasional']; ?></td></tr>
                        <tr><td>Tanggal SK Operasional</td><td>: <?php echo $row['tanggal_sk_izin_operasional']; ?></td></tr>
                        <tr><td>Akreditasi</td><td>: <?php echo $row['akreditasi']; ?></td></tr>
                        <tr><td>No. SK Akreditasi</td><td>: <?php echo $row['sk_akreditasi']; ?></td></tr>
                        <tr><td>Tanggal SK Akreditasi</td><td>: <?php echo $row['tanggal_sk_akreditasi']; ?></td></tr>
                        <tr><td>Sertifikasi ISO</td><td>: <?php echo $row['sertifikasi_iso']; ?></td></tr>
                    </table>
                </div>
                <div id="tabs-3">
                    <table>
                        <tr><td>Akses Internet</td><td>: <?php echo $row['akses_internet']; ?></td></tr>
                    </table>
                </div>
                <div id="tabs-4">
                    <table>
                        <tr><td>No. Telpon</td><td>: <?php echo $row['nomor_telepon']; ?></td></tr>
                        <tr><td>No. FAX</td><td>: <?php echo $row['nomor_fax']; ?></td></tr>
                        <tr><td>Email</td><td>: <?php echo $row['email']; ?></td></tr>
                        <tr><td>Website</td><td>: <?php echo $row['website']; ?></td></tr>
                    </table>
                </div>
                <div id="tabs-5">
                    <div id="peta"></div>
                    <span style="display:none" id="info"></span>
                </div>
            </div>
            <script>


                $(function () {

                    $("#tabs").tabs({
                        activate: function (event, ui) {
                            if (ui.newPanel.is('#tabs-5') && $('#peta').is(':empty')) {
                                var atas = $("#tabs").height();
                                var layar = screen.height;
                                var logo = $(".logo_container").height();
                                $("#peta").css("height", (layar - 500));

                                function initMap() {
                                    var map;
                                    var image = {
                                        url: 'http://demo.indomedia.co.id/e-office/api/data-demo/peta-sekolah/sma.png',
                                        size: new google.maps.Size(64, 64)
                                    };
                                    var sekolah = {lat: <?php echo $row['lintang']; ?>, lng: <?php echo $row['bujur']; ?>};
                                    map = new google.maps.Map(document.getElementById('peta'), {
                                        center: sekolah,
                                        zoom: 15
                                    });
                                    tandaSekolah = new google.maps.Marker({
                                        position: sekolah,
                                        icon: image,
                                        map: map,
                                        title: "<?php echo $row['nama_sp']; ?>"
                                    });
                                }
                                initMap();

                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function (position) {
                                        var icons = {
                                            start: new google.maps.MarkerImage(
                                                    // URL
                                                    'http://demo.indomedia.co.id/e-office/api/data-demo/peta-sekolah/sd.png',
                                                    // (width,height)
                                                    new google.maps.Size(44, 32),
                                                    // The origin point (x,y)
                                                    new google.maps.Point(0, 0),
                                                    // The anchor point (x,y)
                                                    new google.maps.Point(22, 32)
                                                    ),
                                            end: new google.maps.MarkerImage(
                                                    // URL
                                                    'http://demo.indomedia.co.id/e-office/api/data-demo/peta-sekolah/sd.png',
                                                    // (width,height)
                                                    new google.maps.Size(44, 32),
                                                    // The origin point (x,y)
                                                    new google.maps.Point(0, 0),
                                                    // The anchor point (x,y)
                                                    new google.maps.Point(22, 32)
                                                    )};
                                        var sekolah = {lat: <?php echo $row['lintang']; ?>, lng: <?php echo $row['bujur']; ?>},
                                        posisiku = {lat: position.coords.latitude, lng: position.coords.longitude},
                                        myOptions = {
                                            zoom: 7,
                                            center: sekolah,
                                        },
                                                map = new google.maps.Map(document.getElementById('peta'), myOptions),
                                                // Instantiate a directions service.
                                                directionsService = new google.maps.DirectionsService,
                                                directionsDisplay = new google.maps.DirectionsRenderer({
                                                    polylineOptions: {strokeColor: "#4a4a4a", strokeWeight: 5}, suppressMarkers: true,
                                                    map: map
                                                }),
                                                tandaSekolah = new google.maps.Marker({
                                                    position: sekolah,
                                                    title: "<?php echo $row['nama_sp']; ?>",
                                                    map: map,
                                                    label: 'S'
                                                }),
                                                tandaPosisiku = new google.maps.Marker({
                                                    position: posisiku,
                                                    title: "Lokasi Anda",
                                                    label: "P",
                                                    map: map
                                                });

                                        // get route from A to B
                                        calculateAndDisplayRoute(directionsService, directionsDisplay, sekolah, posisiku);
                                        function calculateAndDisplayRoute(directionsService, directionsDisplay, sekolah, posisiku) {
                                            directionsService.route({
                                                origin: sekolah,
                                                destination: posisiku,
                                                avoidTolls: true,
                                                avoidHighways: false,
                                                travelMode: google.maps.TravelMode.DRIVING
                                            }, function (response, status) {
                                                if (status == google.maps.DirectionsStatus.OK) {
                                                    directionsDisplay.setDirections(response);
                                                    var leg = response.routes[ 0 ].legs[ 0 ];
                                                    makeMarker(leg.sekolah, icons.start);
                                                    makeMarker(leg.posisiku, icons.end);
                                                    var totalDistance = 0;
                                                    var totalDuration = 0;
                                                    //var legs = directionsResult.routes[0].legs;
                                                    for (var i = 0; i < leg.length; ++i) {
                                                        totalDistance += leg[i].distance.value;
                                                        totalDuration += leg[i].duration.value;
                                                    }
                                                   // $('#distance').text(totalDistance);
                                                    //$('#duration').text(totalDuration);
                                                    var judul = $(".fancybox-title").text();
                                                    var info = $("#info");
                                                    info.index = 1;
                                                    info.slideDown();
                                                    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(info[0]);
                                                    info.text("Jarak Anda dengan <?php echo $row['nama_sp']; ?> adalah "+leg.distance.text+". Waktu tempuh ± "+leg.duration.text);
                                                } else {
                                                    window.alert('Gagal menampilkan arah ' + status);
                                                }
                                            });
                                        }
                                        function makeMarker(position, icon) {
                                            new google.maps.Marker({
                                                position: position,
                                                map: map,
                                                icon: icon
                                            });
                                        }
                                    }, function () {

                                    });
                                } else {
                                    initMap();

                                    alert("Browser Anda tidak support penggunaan lokasi");
                                }


                            }
                        }
                    });

                });
            </script>
        </body>
    </html>
    <?php
}