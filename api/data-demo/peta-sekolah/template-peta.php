
<script src="http://demo.indomedia.co.id/e-office/js/jQuery-2.1.4.min.js"></script>
<script src="http://demo.indomedia.co.id/e-office/bootstrap/js/bootstrap.min.js"></script>
<script src="http://demo.indomedia.co.id/e-office/dist/js/app.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://demo.indomedia.co.id/e-office/css/AdminLTE.min.css" />

<style type="text/css">
    html, body { height: 100%; margin: 0; padding: 0; }
    #map { height: 90%; }
    .custom-iw .gm-style-iw {
        top:15px !important;
        left:0 !important;
        border-radius:2px;
    }
    .custom-iw>div:first-child>div:nth-child(2) {
        display:none;
    }
    /** the shadow **/
    .custom-iw>div:first-child>div:last-child {
        left:0 !important;
        top:0px;
        box-shadow: rgba(0, 0, 0, 0.6) 0px 1px 6px;
        z-index:-1 !important;
    }

    .custom-iw .gm-style-iw, 
    .custom-iw .gm-style-iw>div, 
    .custom-iw .gm-style-iw>div>div {
        width:100% !important;
        max-width:100% !important;
    }
    /** set here the width **/
    .custom-iw, 
    .custom-iw>div:first-child>div:last-child {
        /*width:342px !important;*/
    }


    /** set here the desired background-color **/
    .widget-user, 
    .custom-iw>div:first-child>div:nth-child(n-1)>div>div, 
    .custom-iw>div>div:last-child, 
    .custom-iw .gm-style-iw, 
    .custom-iw .gm-style-iw>div, 
    .custom-iw .gm-style-iw>div>div {
        background-color:#0086d6 !important;
    }

    /** close-button(note that there may be a scrollbar) **/
    .custom-iw>div:last-child {
        top:1px !important;
        right:0 !important;
        background: #f71752
    }

    /** padding of the content **/
    .widget-user{
        padding:6px;
        width: auto;
    }
</style>
</head>
<body>
    <div id="map"></div>
    <script type="text/javascript">
        var map;
        function initMap() {
            var sma2 = {lat: -7.3522417, lng: 110.4994895};
            map = new google.maps.Map(document.getElementById('map'), {
                center: sma2,
                zoom: 13
            });
            setMarkers(map);

        }
        function setMarkers(map) {
            $(document).ready(function () {
                var gbr = "";
                var gbr1 = "http://saeindiaskcet.org/autozinios12/images/HOMEICON1.png";
                var gbr2 = "http://www.rochestergeneral.org/assets/default/images/mobile-home.png";
                var gbr3 = "https://prmi-nash.com/img/address-icon.png";
                var keterangan = new google.maps.InfoWindow();
                $.ajax({
                    dataType: "json",
                    url: 'http://demo.indomedia.co.id/e-office/api/data-demo/peta-sekolah/data-peta.php',
                    success: function (data)
                    {
                        for (var i = 0; i < data.length; i++) {
                            var sekolah = data[i];
                            if (data[i][8] == "SD" || data[i][8] == "SDLB" || data[i][8] == "SLB") {
                                gbr = gbr1
                            }
                            if (data[i][8] == "SMP" || data[i][8] == "SMP") {
                                gbr = gbr2
                            }
                            if (data[i][8] == "SMA" || data[i][8] == "SMK") {
                                gbr = gbr3
                            }
                            var image = {
                                url: gbr,
                                size: new google.maps.Size(64, 64)
                            };
                            var marker = new google.maps.Marker({
                                position: {lat: Number(data[i][34]), lng: Number(data[i][35])},
                                map: map,
                                icon: image,
                                title: sekolah[2]
                            });
                            (function (marker, sekolah) {
                                google.maps.event.addListener(keterangan, 'domready', function () {
                                    $('.widget-user')//the root of the content
                                            .closest('.gm-style-iw')
                                            .parent().addClass('custom-iw');
                                });
                                google.maps.event.addListener(marker, "mouseover", function (e) {
                                    keterangan.setContent("<div class='box box-widget widget-user'>\n\
                                                                                            <div class='widget-user-header bg-aqua-active'>\n\
                                                                                            <h3 class='widget-user-username'>" + sekolah[2] + "\n\
                                                                                            <h5 class='widget-user-desc'>" + sekolah[1] + "</h5></h3></div>\n\
                                                                                            <div class='widget-user-image'>\n\
                                                                                            <img class='img-circle user-image' src='http://www.w3schools.com/bootstrap/cinqueterre.jpg'></div>\n\
                                                                                            <div class='box-footer'>\n\
                                                                                                    <table>\n\
                                                                                                            <tr><td>Alamat</td><td>: " + sekolah[3] + "</td></tr>\n\
                                                                                                            <tr><td>Desa/Kelurahan</td><td>: " + sekolah[4] + "</td></tr>\n\
                                                                                                            <tr><td>Kecamatan</td><td>: " + sekolah[5] + "</td></tr>\n\
                                                                                                            <tr><td>Status Sekolah</td><td>: " + sekolah[9] + "</td></tr>\n\
                                                                                                    <tr><td>Jenjang Pendidikan</td><td>: " + sekolah[8] + "</td></tr></table></div></div>");
                                    keterangan.setPosition(marker.getPosition());
                                    keterangan.open(map, marker);
                                });
                                google.maps.event.addListener(marker, "mouseout", function (e) {
                                    //keterangan.close();
                                });
                            })(marker, sekolah);
                        }
                    }

                });

            });
        }
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8xcTTbDuSrQjdDn9iLsPKtVYtpFSWor0&callback=initMap"
    async defer></script>

 

