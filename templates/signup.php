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
            <div class="row">
                <div class="col">
                    <?php wp_nonce_field('Hn9rU3ek0rG8rb','bH37nfG7ej5G0F3'); ?>
                    <label for="user_login"><?php __('Username', 'wp-streamers') ?>
                        <input type="text" id="user_login" name="user_login" placeholder="Username" class="form-control"
                            required />
                    </label><br />
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="user_email"><?php __('Email address', 'wp-streamers') ?>
                        <input type="text" id="user_email" name="user_email" class="form-control" placeholder="Email"
                            required />
                    </label><br />
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="user_password"><?php __('Password', 'wp-streamers') ?>
                        <input type="text" id="user_password" name="user_password" class="form-control"
                            placeholder="Password" required />
                    </label><br />
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="valorant_server"><?php __('Valorant server', 'wp-streamers') ?>
                        <select id="streamer_valorant_server" class="form-control" name="streamer_valorant_server"
                            required>
                            <?php 
                            foreach ($valorant_server as $server): ?>
                            <option value="<?=$server->term_id?>">
                                <?=$server->name?>
                            </option>
                            <?php    
                            endforeach;
                        ?>
                        </select>
                    </label><br />
                </div>
            </div>
            <?php echo __('Date of birthday', 'wp-streamers') ?>
            <div class="row">
                <div class="col-sm-4">
                    <select id="user_birthday_dd" name="user_birthday_dd" class="sel-wd-birth-date select"
                        data-placeholder="<?php
					_e( 'DD', 'wp-streamers' ); ?>">
                        <option value=""></option>
                        <?php
						for ( $i = 1; $i < 32; $i++ ) {
							echo '<option value="' . $i . '" ' . '>' .
									( $i < 10 ? '0' . $i : $i ) .
									'</option>' . "\n";
						}
						unset($i);
						?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select id="user_birthday_mm" name="user_birthday_mm" class="sel-wd-birth-month select"
                        data-placeholder="<?php
					    _e( 'MM', 'wp-streamers' ); ?>">
                        <option value=""></option>
                        <?php
						for ( $i = 1; $i < 13; $i++ ) {
							echo '<option value="' . $i . '" ' . '>' .
									date_i18n( 'F', mktime( 0, 0, 0, $i, 10 ) ) .
									'</option>' . "\n";
						}
						unset($i);
						?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select id="user_birthday_yy" name="user_birthday_yy" class="sel-wd-birth-year select"
                        data-placeholder="<?php
					_e( 'YYYY', 'socialbet' ); ?>">
                        <option value=""></option>
                        <?php
						for ( $i = intval( date('Y') ); $i > 1930; $i-- ) {
							echo '<option value="' . $i . '" ' . '>' .
									$i .
									'</option>' . "\n";
						}
						unset($i);
						?>
                    </select>
                </div>
            </div>
            <br />
            <input type="submit" name="send_user_registeration" value="Create account" />
        </form>
    </div>
</div>
</div>
</script>