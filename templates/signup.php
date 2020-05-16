<?php
?>
<style>
.text-center {
    text-align: center;
}
</style>
<div class="row">
    <div class="col-12 text-center">
        <?php do_action('display_notice', $content='streamer_signup');?>
        <form enctype="multipart/form-data" method="post" name="user_registeration">
            <?php wp_nonce_field('Hn9rU3ek0rG8rb','bH37nfG7ej5G0F3'); ?>
            <label for="user_login"><?php __('Username', 'wp-streamers') ?>
                <input type="text" id="user_login" name="user_login" placeholder="Username" class="text"
                    required /><br />
            </label>
            <label for="user_email"><?php __('Email address', 'wp-streamers') ?>
                <input type="text" id="user_email" name="user_email" class="text" placeholder="Email" required /> <br />
            </label>
            <label for="user_password"><?php __('Password', 'wp-streamers') ?>
                <input type="text" id="user_password" name="user_password" class="password" placeholder="Password"
                    required /> <br />
            </label>
            <label for="user_region"><?php __('Region', 'wp-streamers') ?>
                <select id="user_region" name="user_region" required>
                    <option value="NA">NA</option>
                    <option value="EU">EU</option>
                    <option value="OCE">OCE</option>
                </select>
            </label>
            <br />
            <label for="user_birthday"><?php __('Birthday', 'wp-streamers') ?>
                <input type="date" id="user_birthday" name="user_birthday" placeholder="birthday" required /> <br />
            </label>
            <input type='hidden' id="region" name="region" value="NA" />
            <input type="submit" name="user_registeration" value="SignUp" />
        </form>
    </div>
</div>
</div>
<script type="text/javascript" language="javascript">
(function($) {
    $("#user_region").change(function() {
        var region = $('option:selected', this).val();
        $('#region').val(region);
    });
})(window.jQuery);
</script>