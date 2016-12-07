<?php
/**
 * @package Ninjateam livechat
 * @version 1.0
 */
 function live_chat_facebook($page, $domain = "") {
    define("LIVE_CHAT_FACEBOOK_BACKGROUD", "#3a5897");
    define("LIVE_CHAT_FACEBOOK_OPEN", "Hubungi Developer");
    define("LIVE_CHAT_FACEBOOK_TITLE", "Hubungi Developer");
    define("LIVE_CHAT_FACEBOOK_TEXT", "Anda bisa menghubungi developer melalui fitur obrolan ini. Silahkan memulai. :)");
    define("LIVE_CHAT_FACEBOOK_START", "Mulai");
    define("LIVE_CHAT_FACEBOOK_LANG", "en_US");
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $domain ?>/e-office/library/facebook/css/live_chat_facbook.css" >
    <script type="text/javascript" src="<?php echo $domain ?>/e-office/library/facebook/js/live_chat_facbook.js"></script>
    <style type="text/css">
        #b-c-facebook .chat-f-b, #chat_f_b_smal, #f_bt_start_chat {
            background: <?php echo LIVE_CHAT_FACEBOOK_BACKGROUD ?>;
        }
    </style>
    <a title="<?php echo LIVE_CHAT_FACEBOOK_OPEN ?>" id="chat_f_b_smal" onclick="chat_f_show()" class="chat_f_vt"><?php echo LIVE_CHAT_FACEBOOK_OPEN ?></a>
    <div id="b-c-facebook" class="chat_f_vt">
    	<div id="chat-f-b" onclick="b_f_chat()" class="chat-f-b">
    		<img class="chat-logo" src="<?php echo $domain ?>/e-office/library/facebook/images/facebook.png" alt="logo chat" />
    		<label>
    			<?php echo LIVE_CHAT_FACEBOOK_TITLE ?>
    		</label>
    		<span id="fb_alert_num">
    			1
    		</span>
            <?php
            /*
            * Close chat
            */

            ?>
    		<div id="t_f_chat">
    			<a title="Close Chat" href="javascript:;" onclick="chat_f_close()" id="chat_f_close" class="chat-left-5"><img src="<?php echo $domain ?>/e-office/library/facebook/images/close.png" alt="Close chat" title="Close chat" /></a>
    		</div>
    	</div>
    	<div id="f-chat-conent" class="f-chat-conent">
    		<div class="fb-page" data-tabs="messages" data-href="<?php echo $page ?>" data-width="250" data-height="310" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true"
    		data-show-facepile="false" data-show-posts="true">
    		</div>
    		<div id="fb_chat_start">
    			<div id="f_enter_1" class="msg_b fb_hide">
    				<?php echo LIVE_CHAT_FACEBOOK_TEXT ?>
    			</div>
    			
    			<p id="f_enter_3" class="fb_hide" align="center">
    				<a href="javascript:;" onclick="f_bt_start_chat()" id="f_bt_start_chat"><?php echo LIVE_CHAT_FACEBOOK_START ?></a>
    			</p>
    			
    		</div>
    
    	</div>
    </div>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/<?php echo LIVE_CHAT_FACEBOOK_LANG ?>/sdk.js#xfbml=1&version=v2.5";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <?php
}
 
