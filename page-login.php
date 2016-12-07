<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include 'konfig.php';

$admintitle = "Login Siakad";


?>
<html>
<head>
 <link rel="stylesheet" href="css/AdminLTE.min.css">
 <link rel="stylesheet" href="css/skins/_all-skins.min.css">
 <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
 <link rel="stylesheet" href="css/jquery-ui.css" />
 <title>Silahkan Masuk E-Office</title>
</head>
<body>
<div class="row">
		
        <div id="loginbox" style="margin-top:200px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Silahkan Masuk E-Office</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Forgot password?</a></div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form id="login" action="page-cek.php?login" method="post" class="form-horizontal" role="form">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="username" type="text" class="form-control" name="user_login" value="" placeholder="username atau email">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="password" type="password" class="form-control" name="user_pass" placeholder="password">
                                    </div>
							<div style="margin-top:10px" class="input-group">
								<input class="submit_button btn btn-info" type="submit" value="Login" name="submit">
								<span class="info"></span>
                                </div> 
								<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
					           </form>     



                        </div>                     
                    </div>  
					</div>
        </div>

</body>
</html>



