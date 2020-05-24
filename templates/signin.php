<form id="streamer_login_form" class="streamer_login_form" action="" method="post">
    <?php wp_nonce_field('streamer_login_form'); ?>
    <legend><?php echo __('Log into Your Account', 'wp-streamers'); ?></legend>
    <p class="login-username">
        <label for="streamer_user_login">
            <?php echo __('Username or Email', 'wp-streamers');?>
        </label>
        <input autocomplete="username" name="streamer_user_login" id="streamer_user_login" class="required input"
            type="text" required>
    </p>
    <p class="login-password">
        <label for="streamer_user_pass">
            <?php echo __('Password', 'wp-streamers');?>
        </label>
        <input name="streamer_user_pass" id="streamer_user_pass" class="password required input" type="password"
            autocomplete="current-password" required>
    </p>
    <p class="login-remember">
        <label>
            <input name="rememberme" type="checkbox" id="rememberme" value="forever">
            <?php echo __('Remember Me', 'wp-streamers');?>
        </label>
    </p>
    <p class="login-submit">
        <input type="hidden" name="streamer_login_redirect" value="<?php echo home_url()."/me";?>">

        <input id="streamer_login_submit" type="submit" name="send_login_form" class="submit" value="Log In">
    </p>
    <p class="lost-password">
        <a href="<?php echo esc_url( wp_lostpassword_url( home_url() ) ); ?>">
            <?php echo __('Lost Password?','wp-streamers');?>
        </a>
    </p>
</form>