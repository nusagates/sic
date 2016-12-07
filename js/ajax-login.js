jQuery(document).ready(function($) {

    jQuery('form#login').on('submit', function(e){
        jQuery('.info').html("<span class='text-info'><i class='fa fa-spinner fa-spin'></i> Sedang divalidasi</span>");
		var user = jQuery('form#login #username').val();
		var sandi = jQuery('form#login #password').val();
		var satpam = jQuery('form#login #security').val();
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': user, 
                'password': sandi, 
                'security': satpam},
            success: function(data){
                $('.info').html("<span class='text-success'><i class='glyphicon glyphicon-info-sign'></i> Selamat! Anda berhasil login.</span>");
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
            },
			error: function(s){
				if(user==""){
					$('.info').html("<span class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Kolom pengguna tidak boleh kosong!</span>");
				}
				else if(sandi==""){
					$('.info').html("<span class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Kolom sandi tidak boleh kosong!</span>");
				}
				else{
					$('.info').html("<span class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Nama pengguna/email dan sandi tidak cocok!</span>");
				}
			}
        });
        e.preventDefault();
    });

});