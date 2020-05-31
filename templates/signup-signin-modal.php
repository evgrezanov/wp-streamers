<style>
/**************************
  Basic Modal Styles
**************************/

.modal {
    font-family: -apple-system, BlinkMacSystemFont, avenir next, avenir, helvetica neue, helvetica, ubuntu, roboto, noto, segoe ui, arial, sans-serif;
}

.modal__overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.75);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.modal__container {
    background-color: transparent;
    padding: 0px;
    max-width: 740px;
    height: 75vh;
    width: 50%;
    border-radius: 0px;
    overflow: hidden;
    box-sizing: border-box;
}

.modal__header {
    position: relative;
    display: block;
    height: 30px;
    margin-bottom: 0px;
    padding: 30px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    background: #D3D3D3;
}

@supports (display: flex) {

    .modal__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: initial;
    }

}

.modal__title {
    position: absolute;
    top: 20px;
    left: 20px;
    margin-top: 0;
    margin-bottom: 0;
    font-weight: 600;
    font-size: 1.25rem;
    line-height: 1.25;
    color: #00449e;
    box-sizing: border-box;
}

.modal__close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: transparent;
    border: 0;
    cursor: pointer;
    margin: 0px;
    padding: 0px;
}

@supports (display: flex) {

    .modal__title {
        position: static;
    }

    .modal__close {
        position: static;
    }

}

.modal__header .modal__close:before {
    content: "\2715";
}

.modal-content-content {
    padding: 30px;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    background: #fff;
}

.modal__content {
    margin: 0px 0px 20px 0px;
    color: rgba(0, 0, 0, .8);
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0px 20px 0px 0px;
}

.modal__content p {
    margin-top: 0px;
}

.modal__btn {
    font-size: .875rem;
    padding-left: 1rem;
    padding-right: 1rem;
    padding-top: .5rem;
    padding-bottom: .5rem;
    background-color: #e6e6e6;
    color: rgba(0, 0, 0, .8);
    border-radius: .25rem;
    border-style: none;
    border-width: 0;
    cursor: pointer;
    -webkit-appearance: button;
    text-transform: none;
    overflow: visible;
    line-height: 1.15;
    margin: 0;
    will-change: transform;
    -moz-osx-font-smoothing: grayscale;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    transition: -webkit-transform .25s ease-out;
    transition: transform .25s ease-out;
    transition: transform .25s ease-out, -webkit-transform .25s ease-out;
}

.modal__btn-primary {
    background-color: #00449e;
    color: #fff;
}

/**************************
  Demo Animation Style
**************************/

@keyframes mmfadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes mmfadeOut {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}

@keyframes mmslideIn {
    from {
        transform: translateY(15%);
    }

    to {
        transform: translateY(0);
    }
}

@keyframes mmslideOut {
    from {
        transform: translateY(0);
    }

    to {
        transform: translateY(-10%);
    }
}

.micromodal-slide {
    display: none;
}

.micromodal-slide.is-open {
    display: block;
}

.micromodal-slide[aria-hidden="false"] .modal__overlay {
    animation: mmfadeIn .3s cubic-bezier(0.0, 0.0, 0.2, 1);
}

.micromodal-slide[aria-hidden="false"] .modal__container {
    animation: mmslideIn .3s cubic-bezier(0, 0, .2, 1);
}

.micromodal-slide[aria-hidden="true"] .modal__overlay {
    animation: mmfadeOut .3s cubic-bezier(0.0, 0.0, 0.2, 1);
}

.micromodal-slide[aria-hidden="true"] .modal__container {
    animation: mmslideOut .3s cubic-bezier(0, 0, .2, 1);
}

.micromodal-slide .modal__container,
.micromodal-slide .modal__overlay {
    will-change: transform;
}

/**************************
  Custom styles for individual modals
**************************/

.modal__container button {
    outline: none;
    cursor: pointer !important;
}

.modal__container h2.modal__title {
    color: #595959;
}

.modal__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal__title {
    margin-top: 0;
    margin-bottom: 0;
    font-weight: 600;
    font-size: 1.25rem;
    line-height: 1.25;
    color: #00449e;
    box-sizing: border-box;
}

.modal__close {
    font-size: 24px;
}

.modal__content {
    color: rgba(0, 0, 0, .8);
}

.modal__btn {
    padding: 10px 15px;
    background-color: #e6e6e6;
    border-radius: 4px;
    -webkit-appearance: none;
}

/**************************
  Mobile custom styles for individual modals
**************************/

@media only screen and (min-device-width : 320px) and (max-device-width : 480px) {

    .modal__container {
        width: 90% !important;
        min-width: 90% !important;
    }

    @supports (display: flex) {

        .modal__container {
            width: 90% !important;
            min-width: 90% !important;
            height: 85vh;
        }

    }

    .modal__header {
        padding: 20px;
    }

    .modal-content-content {
        padding: 20px;
    }

    .modal__content {
        -webkit-overflow-scrolling: touch;
    }

}
</style>
<div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div id="modal-container" class="modal__container" role="dialog" aria-modal="true"
            aria-labelledby="modal-1-title">
            <header id="modal-header" class="modal__header">
                <h2 class="modal__title">
                    Register
                </h2>
                <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <div id="modal-content-content" class="modal-content-content">
                <div id="modal-content" class="modal__content">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div id="streamerSignUpResponse">
                            </div>
                            <form method="post" id="streamer-signup-form" name="user_registeration">
                                <div class="row">
                                    <div class="col">
                                        <?php wp_nonce_field('Hn9rU3ek0rG8rb','bH37nfG7ej5G0F3'); ?>
                                        <label for="user_login"><?php __('Username', 'wp-streamers') ?>
                                            <input type="text" id="user_login" name="user_login" placeholder="Username"
                                                class="form-control" autocomplete="username" required />
                                        </label><br />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="user_email"><?php __('Email address', 'wp-streamers') ?>
                                            <input type="email" id="user_email" name="user_email" class="form-control"
                                                placeholder="Email" autocomplete="email" required />
                                        </label><br />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="user_password"><?php __('Password', 'wp-streamers') ?></label>
                                            <div class="input-group">
                                                <input type="password" minlength="6" id="user_password"
                                                    name="user_password" class="form-control" placeholder="Password"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="valorant_server"><?php __('Valorant server', 'wp-streamers') ?>
                                            <select id="streamer_valorant_server" class="form-control"
                                                name="streamer_valorant_server" minlength="1" required>
                                                <?php 
                                                $valorant_server = get_terms(array(
                                                    'taxonomy'    =>  'valorant-server',
                                                    'hide_empty'  => false
                                                    ));
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
                                        <select min="1" max="31" id="user_birthday_dd" autocomplete="bday-day"
                                            name="user_birthday_dd" class="sel-wd-birth-date select"
                                            placeholder="<?=__( 'DD', 'wp-streamers' )?>" required>
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
                                        <select min="1" max="12" id="user_birthday_mm" autocomplete="bday-month"
                                            name="user_birthday_mm" class="sel-wd-birth-month select"
                                            placeholder="<?=__( 'MM', 'wp-streamers' )?>" required>
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
                                        <select min="1970" max="2005" id="user_birthday_yy" autocomplete="bday-year"
                                            name="user_birthday_yy" class="sel-wd-birth-year select"
                                            placeholder="<?=__( 'YYYY', 'socialbet' )?>" required>
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
                </div>
                <footer id="modal-footer" class="modal__footer">
                    <button class="modal__btn" data-micromodal-close
                        aria-label="Close this dialog window">Close</button>
                </footer>
            </div>
        </div>
    </div>
</div>
<div class="modal micromodal-slide" id="modal-2" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div id="modal-container" class="modal__container" role="dialog" aria-modal="true"
            aria-labelledby="modal-1-title">
            <header id="modal-header" class="modal__header">
                <h2 class="modal__title">
                    Login
                </h2>
                <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <div id="modal-content-content" class="modal-content-content">
                <div id="modal-content" class="modal__content">
                    <div class="row">
                        <div class="col-12 text-center">
                            <?php    if ( !is_user_logged_in() ):
            echo '<div id="streamerSignInResponse"></div>';
            wp_login_form( array(
                'echo'           => true,
                'redirect'       => get_site_url(). '/me/' , 
                'form_id'        => 'streamer_login_form',
                'label_username' => __( 'Username or Email', 'wp-streamers' ),
                'label_password' => __( 'Password', 'wp-streamers' ),
                'label_remember' => __( 'Remember Me', 'wp-streamers' ),
                'label_log_in'   => __( 'Log In', 'wp-streamers' ),
                'id_username'    => 'streamer_user_login',
                'id_password'    => 'streamer_user_pass',
                'id_remember'    => 'rememberme',
                'id_submit'      => 'streamer_login_submit',
                'remember'       => true,
                'value_username' => NULL,
                'value_remember' => false 
            ) );
            echo '<br>';
            echo '<a href="'.esc_url( wp_lostpassword_url( home_url() ) ).'">'.__('Lost Password?','wp-streamers').'</a>';
            //require_once plugin_dir_path(__DIR__).'templates/signin.php';
        else:    
            echo __('You already logged in','wp-streamers');
            echo '<br>';
            echo '<a href="'.wp_logout_url( home_url() ).'">'.__('Logout', 'wp-streamers').'</a>';
        endif;?>
                        </div>
                    </div>
                </div>
                <footer class="modal__footer">
                    <button class="modal__btn modal__btn-primary">Continue</button>
                    <button class="modal__btn" data-micromodal-close
                        aria-label="Close this dialog window">Close</button>
                </footer>
            </div>
        </div>
    </div>
</div>