<?php
include 'custom-post.php';
function ajax_login_init() {

    wp_register_script('ajax-login-script', '/e-office/js/ajax-login.js', array('jquery'));
    wp_enqueue_script('ajax-login-script');

    wp_localize_script('ajax-login-script', 'ajax_login_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'redirecturl' => home_url() . '/e-office',
        'loadingmessage' => __('Sending user info, please wait...')
    ));

    add_action('wp_ajax_nopriv_ajaxlogin', 'ajax_login');
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}

function ajax_login() {

    // First check the nonce, if it fails the function will break
    check_ajax_referer('ajax-login-nonce', 'security');

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon($info, false);
    if (is_wp_error($user_signon)) {
        echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.')));
    } else {
        echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...')));
    }

    die();
}

function redirect_login_page() {
    $login_page = home_url('/login');
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    if ($page_viewed == "login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}

add_action('init', 'redirect_login_page');

function login_failed() {
    $login_page = home_url('/login');
    wp_redirect($login_page . '?login=failed');
    exit;
}

add_action('wp_login_failed', 'login_failed');

function verify_username_password($user, $username, $password) {
    $login_page = home_url('/login');
    if ($username == "" || $password == "") {
        wp_redirect($login_page . "?login=empty");
        exit;
    }
}

add_filter('authenticate', 'verify_username_password', 1, 3);

function logout_page() {
    $login_page = home_url('/login');
    wp_redirect($login_page . "?login=false");
    exit;
}

add_action('wp_logout', 'logout_page');

function login($atts, $content = null) {
    if (!is_user_logged_in()) {
        global $content;
        ob_start();
        include 'form.php';
        $output = ob_get_clean();
        return $output;
    } else {
        if (headers_sent()) {
            $alih = "
			<style>
				#countdown b{
					color: red;
					font-size: 16px;
				}
			</style>
			<span class='text-success alih'><i class='glyphicon glyphicon-info-sign'></i> Selamat! Anda berhasil login. " . home_url() . "</span>
			<div id='countdown'></div>
			<script>
				var timeLeft = 15;
				var elem = document.getElementById('countdown');
				
				var timerId = setInterval(countdown, 1000);
				function countdown() {
				  if (timeLeft == 0) {
					clearTimeout(timerId);
					document.location.href = '" . esc_url(home_url()) . '/e-office' . "';
				  } else {
					elem.innerHTML = 'Anda akan dialihkan ke dashboard dalam hitungan <b>'+ timeLeft +'</b> detik.';
					timeLeft--;
				  }
				}
			</script>
			
			";
            echo $alih;
            exit;
        } else {
            exit(header("Location: " . home_url() . "/e-office"));
        }
    }
}

add_shortcode('login', 'login');

function custom_login_logo() {
    echo '
	<style type="text/css">
		.login #login{
			margin-top:-50px
		}
		.login #login h1 a { 
			background-image:url(' . get_bloginfo('template_directory') . '/images/logo.png);
			background-size: 100px 139px;
			width: 100px;
			height:139px;
		}
		.login #nav a, .login #backtoblog a {
            color: #27adab !important;
        }
        .login #nav a:hover, .login #backtoblog a:hover {
            color: #d228bc !important;
        }
		</style>
	';
}

add_action('login_enqueue_scripts', 'custom_login_logo');

function my_login_logo_url() {
    return get_bloginfo('url');
}

add_filter('login_headerurl', 'my_login_logo_url');

function my_login_logo_url_title() {
    return get_bloginfo('name');
}

add_filter('login_headertitle', 'my_login_logo_url_title');

function wpse_11244_restrict_admin() {
    if (!current_user_can('manage_options')) {
        $error = <<<HTML
<div class="container">
	<section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Halaman tidak ditemukan.</h3>

          <p>
            Halaman yang diminta tidak ditemukan. Hanya itu yang kami tahu.
            Mungkin Anda mau <a href="' . home_url() . '">Kembali ke Beranda</a> atau menggunakan kolom pencarian di bawah ini.
          </p>

          <form action="' . home_url() . '" class="search-form">
            <div class="input-group">
              <input name="s" class="form-control" placeholder="Cari" type="text">

              <div class="input-group-btn">
                <button type="submit"  class="btn btn-warning btn-flat"><i class="fa fa-search"></i>
                </button>
              </div>
            </div>
            <!-- /.input-group -->
          </form>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
</section>
	</div>
HTML;
        wp_die($error);
    }
}

add_action('admin_init', 'wpse_11244_restrict_admin', 1);

