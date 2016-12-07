function fetch_mahasiswa(){
	var outTable = $('#tabel-mahasiswa').dataTable();
	$.ajax({
		url: 'data/mahasiswa/ambil.php',
		dataType: 'json',
		success: function(s){
				outTable.fnClearTable();
			for(var i = 0; i < s.length; i++) 
				{ 
				outTable.fnAddData([i+1, s[i][0], s[i][1], s[i][2], s[i][3], s[i][4] ]);
				} // End For
			}, 
			error: function(e){ console.log(e.responseText);} 
		}); 
}
function tambah_mahasiswa(){
	var data=$("#form-tambah-mahasiswa").serialize();
	$(".button-tambah-mahasiswa").html("<i class='fa fa-refresh fa-spin'></i> Loading");
		$.ajax({
		method: "POST",
		url: "data/mahasiswa/tambah.php",
		data: data,
		success: function(msg){
		if(msg!="berhasil"){
			$(".button-tambah-mahasiswa").text("Tambah");
			$(".error").addClass('glyphicon glyphicon-info-sign');
			$(".error").text(" "+msg);
		}else{
			$(".error").removeClass('glyphicon glyphicon-info-sign');
			$(".error").text(" ");
			tutup_dialog();
			fetch_mahasiswa();
		}
		
		},
		error: function(){

		}
	});
}
function dialog(judul, isi){
	var spans = $( ".ui-dialog-buttonpane");
	spans.remove();
	$( "#dialog" ).attr('title',judul);
	$( "#dialog" ).html(isi);
	$( "#dialog" ).dialog({
		modal: true,
		width: 'auto',
		dialogClass:'col-lg-6',
		resizable: true,
		show: {
        effect: "explode",
        duration: 500
      },
      hide: {
        effect: "fold",
        duration: 1000
      }
	});
	$('#dialog').css('overflow', 'hidden');
	$(".ui-dialog-title").html(judul);
	$('.ui-dialog-titlebar-close').addClass('ui-icon ui-icon-close');
	$('.ui-dialog-titlebar-close').addClass('bg-danger');
	
	
}
function tutup_dialog(){
	$( "#dialog" ).dialog( "close" );
}
function auto(id, sumber){
	$(""+id+"").autocomplete({
		source : ""+sumber+""	
	});
}

    $(function () {
        fetch_mahasiswa();
		$("#tambah-mahasiswa").click(function(){
			$.ajax({
				url: 'form/mahasiswa/tambah.php',
				success: function(s){
					dialog("<i class='text-info glyphicon glyphicon-info-sign'> Tambah Data</i>",s);
					auto("#nip","data/mahasiswa/auto-nip.php");
					auto("#nama-pegawai","data/mahasiswa/auto-nama.php");
					auto("#jabatan","data/mahasiswa/auto-jabatan.php");
					auto("#masuk-keluar","data/mahasiswa/auto-penerima.php");
					$("#nip").keyup(function(){
						var nip =$(this).val(); 
						$.ajax({
							url:"data/mahasiswa/auto-nip.php?nip="+nip,
							dataType: 'json',
							success: function(msg){
								if(nip=="" || msg.length == 0){
									$("#nama-pegawai").val("")
									$("#jabatan").val("")
								}
								else{
									$("#nama-pegawai").val(msg[0][3]);
									$("#jabatan").val(msg[0][4]);
								}
							},
							error: function(s){
								alert(s)
							}
						});
					});
					$("#dialog").dialog({
						buttons: [
									{
									  text: "Batal",
									  'class':'btn btn-warning',
									  click: function() {
										  tutup_dialog();
									  }
									},
									{
									  text: "Kirim",
									  'class':'btn btn-info button-tambah-mahasiswa',
									  click: function() {
										  tambah_mahasiswa();
									  }
									}
						]
					});
				}
			});
		});
		$('#tabel-mahasiswa').on('click', 'a#hapus-mahasiswa', function (e) {
			e.preventDefault();
			kode = $(this).attr("nip");
			dialog("<i class='text-warning glyphicon glyphicon-info-sign'> Peringatan</i>","Apakah Anda serius akan menghapus arsip dengan nomor id <b>"+kode+"</b>?");
			$("#dialog").dialog({
						buttons: [
									{
									  text: "Batal",
									  'class':'btn btn-warning',
									  click: function() {
										  tutup_dialog();
									  }
									},
									{
									  text: "Betul",
									  'class':'btn btn-info button-hapus-mahasiswa',
									  click: function() {
											$.ajax({
												url:"data/mahasiswa/hapus.php?kode="+kode+"",
												method: "POST",
												success: function(msg){
												$( "#dialog" ).dialog( "close" );
												$( "#dialog" ).on( "dialogclose", function( event, ui ) {
													fetch_mahasiswa();
												});
												},
												error: function(e){ alert(e.responseText);} 
											});
									  }
									}
						]
					});
		});
		$('#tabel-mahasiswa').on('click', 'a#edit-mahasiswa', function (e) {
			e.preventDefault();
			kode = $(this).attr("nip");
			
			$.ajax({
				url:"form/mahasiswa/edit.php?kode="+kode+"",
				success: function(msg){
					dialog("<i class='text-info glyphicon glyphicon-info-sign'> Edit Data</i>",msg);
					$("#dialog").dialog({
						buttons: [
									{
									  text: "Batal",
									  'class':'btn btn-warning',
									  click: function() {
										  tutup_dialog();
									  }
									},
									{
									  text: "Update",
									  'class':'btn btn-info button-edit-mahasiswa',
									  click: function() {
											data=$("#form-edit-mahasiswa").serialize();
											$(".button-edit-mahasiswa").html("<i class='fa fa-refresh fa-spin'></i> Loading");
											$.ajax({
												url:"data/mahasiswa/edit.php?edit",
												method: "POST",
												data:data+'&id='+kode,
												success: function(msg){
													alert(data)
												$(".error").addClass('glyphicon glyphicon-info-sign');
												$(".error").text(" "+msg);
												if(msg=="berhasil"){
													$( "#dialog" ).dialog( "close" );
												$( "#dialog" ).on( "dialogclose", function( event, ui ) {
													fetch_mahasiswa();
												});
												}
												},
												error: function(e){ alert(e.responseText);} 
											});
									  }
									}
						]
					});
				},
				error: function(e){ alert(e.responseText);} 
			});
			
			
		});
		
		$('#tabel-mahasiswa').on('click', 'a#tindakan', function (e) {
			e.preventDefault();
			kode = $(this).attr("tindakan");
			
			$.ajax({
				url:"form/mahasiswa/tindakan.php?kode="+kode+"",
				success: function(msg){
					dialog("<i class='text-info glyphicon glyphicon-info-sign'> Tindakan Pimpinan</i>",msg);
					$("#dialog").dialog({
						buttons: [
									{
									  text: "Batal",
									  'class':'btn btn-warning',
									  click: function() {
										  tutup_dialog();
									  }
									},
									{
									  text: "Update",
									  'class':'btn btn-info button-edit-tindakan',
									  click: function() {
											data=$("#form-edit-tindakan").serialize();
											$(".button-edit-tindakan").html("<i class='fa fa-refresh fa-spin'></i> Loading");
											$.ajax({
												url:"data/mahasiswa/edit.php?tindakan",
												method: "POST",
												data:data+'&id='+kode,
												success: function(msg){
												$(".button-edit-tindakan").html("Update");
												$(".error").addClass('glyphicon glyphicon-info-sign');
												$(".error").text(" "+msg);
												if(msg=="berhasil"){
													$(".error").removeClass('glyphicon glyphicon-info-sign');
													$(".error").text("");
													$( "#dialog" ).dialog( "close" );
												$( "#dialog" ).on( "dialogclose", function( event, ui ) {
													fetch_mahasiswa();
												});
												}
												},
												error: function(e){ alert(e.responseText);} 
											});
									  }
									}
						]
					});
				},
				error: function(e){ alert(e.responseText);} 
			});
			
			
		});
		
		$("#print-mahasiswa").click(function(){
			$.ajax({
				url:'form/mahasiswa/print.php',
				success:function(msg){
					dialog("<i class='text-info glyphicon glyphicon-info-sign'> Print</i>",msg);
					$("#dialog").dialog({
						buttons: [
									{
									  text: "Batal",
									  'class':'btn btn-warning',
									  click: function() {
										  tutup_dialog();
									  }
									},
									{
									  text: "Print",
									  'class':'btn btn-info button-tambah',
									  click: function() {
											var data=$("#form-print").serialize();
											window.open('data/mahasiswa/cetak.php?'+data,'_blank');
											tutup_dialog();
									  }
									}
						]
					});
				},
				error: function(e){ alert(e.responseText);} 
			})
			
		});
		$("#import-mahasiswa").click(function(){
			$.ajax({
				url:'form/mahasiswa/import.php',
				success:function(msg){
					dialog("<i class='text-info glyphicon glyphicon-info-sign'> Import Data</i>",msg);
					$("#dialog").dialog({
						buttons: [
									{
									  text: "Batal",
									  'class':'btn btn-warning',
									  click: function() {
										  tutup_dialog();
									  }
									},
									{
									  text: "Import",
									  'class':'btn btn-info button-import-mahasiswa',
									  click: function() {
											var file_data = $('#csv').prop('files')[0];   
											var form_data = new FormData();
											form_data.append('file', file_data);
											$(".button-import-mahasiswa").html("<i class='fa fa-refresh fa-spin'></i> Loading");
											$.ajax({
												url: 'data/mahasiswa/import.php',
												method: 'post',
												dataType: 'text',
												cache: false,
												contentType: false,
												processData: false,
												data: form_data,
												success: function(msg){
													if(msg.toLowerCase().indexOf("berhasil") >= 0){
														tutup_dialog();
														$( "#dialog" ).on( "dialogclose", function( event, ui ) {
															fetch_mahasiswa();
														});
														
													}
													else{
														$(".help-block").html(msg)
														$(".button-import-mahasiswa").html("Import");
													}
												},
												error:function(er){
													alert(er.responseText)
												}
											});
											
									  }
									}
						]
					});
				},
				error: function(e){ alert(e.responseText);} 
			})
			
		});
    });