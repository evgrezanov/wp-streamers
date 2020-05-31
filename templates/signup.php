<?php
?>
<style>
.text-center {
    text-align: center;
}
</style>
<div class="row">
    <div class="col-12 text-center">
        <div id="streamerSignUpResponse">
        </div>
        <form method="post" id="streamer-signup-form" name="user_registeration">
            <div class="row">
                <div class="col">
                    <?php wp_nonce_field('Hn9rU3ek0rG8rb','bH37nfG7ej5G0F3'); ?>
                    <label for="user_login"><?php __('Username', 'wp-streamers') ?>
                        <input type="text" id="user_login" name="user_login" placeholder="Username" class="form-control"
                            autocomplete="username" required />
                    </label><br />
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="user_email"><?php __('Email address', 'wp-streamers') ?>
                        <input type="email" id="user_email" name="user_email" class="form-control" placeholder="Email"
                            autocomplete="email" required />
                    </label><br />
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="user_password"><?php __('Password', 'wp-streamers') ?></label>
                        <div class="input-group">
                            <input type="password" minlength="6" id="user_password" name="user_password"
                                class="form-control" placeholder="Password" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="valorant_server"><?php __('Valorant server', 'wp-streamers') ?>
                        <select id="streamer_valorant_server" class="form-control" name="streamer_valorant_server"
                            minlength="1" required>
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
            <div class="form-row text-center">
                <?php echo __('Date of birthday', 'wp-streamers') ?>
                <div class="col-auto">
                    <select min="1" max="31" id="user_birthday_dd" autocomplete="bday-day" name="user_birthday_dd"
                        class="sel-wd-birth-date select" placeholder="<?=__( 'DD', 'wp-streamers' )?>" required>
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
                <div class="col-auto">
                    <select min="1" max="12" id="user_birthday_mm" autocomplete="bday-month" name="user_birthday_mm"
                        class="sel-wd-birth-month select" placeholder="<?=__( 'MM', 'wp-streamers' )?>" required>
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
                <div class="col-auto">
                    <select min="1970" max="2005" id="user_birthday_yy" autocomplete="bday-year" name="user_birthday_yy"
                        class="sel-wd-birth-year select" placeholder="<?=__( 'YYYY', 'socialbet' )?>" required>
                        <option value=""></option>
                        <?php
                                for ( $i = intval( date('Y') ); $i > 1970; $i-- ) {
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
            <input class="btn btn-primary" type="submit" id="streamer_signup" name="streamer_signup"
                value="Create account" />
        </form>
    </div>
</div>