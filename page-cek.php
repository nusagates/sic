<?php

include 'konfig.php';
global $user;
$user = wp_get_current_user();
$id = $user->id;
if(isset($_GET['islogin'])){
	if(is_user_logged_in()){
	echo "1";
	}
}
if (isset($_GET['login'])){
	
	$info = array();
	$info['user_login'] = isset($_POST['user_login'])?$_POST['user_login']:'';
	$info['user_password'] = isset($_POST['user_pass'])?$_POST['user_pass']:'';
	$info['remember'] = true;
	$user_signon = wp_signon($info, false);
	if (is_wp_error($user_signon)) {
		echo "Username atau sandi salah";
	}
	else{
		header("location:index.php");
	}
}



