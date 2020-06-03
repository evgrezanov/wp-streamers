<style>
/*
* > normalize.css
* > https://unpkg.com/tachyons/css/tachyons.min.css (https://tachyons.io/)
*
*/

@import url("https://fonts.googleapis.com/css?family=Lato&display=swap");

/**************************\
Basic Modal Styles
\**************************/

body {
    font-family: "Lato", -apple-system, BlinkMacSystemFont, avenir next, avenir,
        helvetica neue, helvetica, ubuntu, roboto, noto, segoe ui, arial, sans-serif;
}

.demo-wrapper {
    width: 100px;
    padding: 20px;
    margin: 0 auto;
}

.modal {
    font-family: "Lato", -apple-system, BlinkMacSystemFont, avenir next, avenir,
        helvetica neue, helvetica, ubuntu, roboto, noto, segoe ui, arial, sans-serif;
}

.modal__overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal__container {
    background-color: #fff;
    padding: 30px;
    max-width: 500px;
    max-height: 100vh;
    border-radius: 4px;
    overflow-y: auto;
    box-sizing: border-box;
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
    background: transparent;
    border: 0;
}

.modal__header .modal__close:before {
    content: "\2715";
}

.modal__content {
    margin-top: 2rem;
    margin-bottom: 2rem;
    line-height: 1.5;
    color: rgba(0, 0, 0, 0.8);
}

.modal__btn {
    font-size: 0.875rem;
    padding-left: 1rem;
    padding-right: 1rem;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    background-color: #e6e6e6;
    color: rgba(0, 0, 0, 0.8);
    border-radius: 0.25rem;
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
    transition: -webkit-transform 0.25s ease-out;
    transition: transform 0.25s ease-out;
    transition: transform 0.25s ease-out, -webkit-transform 0.25s ease-out;
}

.modal__btn:focus,
.modal__btn:hover {
    -webkit-transform: scale(1.05);
    transform: scale(1.05);
}

.modal__btn-primary {
    background-color: #00449e;
    color: #fff;
}

/**************************\
Demo Animation Style
\**************************/
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
    animation: mmfadeIn 0.3s cubic-bezier(0, 0, 0.2, 1);
}

.micromodal-slide[aria-hidden="false"] .modal__container {
    animation: mmslideIn 0.3s cubic-bezier(0, 0, 0.2, 1);
}

.micromodal-slide[aria-hidden="true"] .modal__overlay {
    animation: mmfadeOut 0.3s cubic-bezier(0, 0, 0.2, 1);
}

.micromodal-slide[aria-hidden="true"] .modal__container {
    animation: mmslideOut 0.3s cubic-bezier(0, 0, 0.2, 1);
}

.micromodal-slide .modal__container,
.micromodal-slide .modal__overlay {
    will-change: transform;
}

/**************************\
Button Style
\**************************/

a.modal-login {
    position: relative;
    display: inline-block;
    padding: 1em 2em;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    user-select: none;
    color: black;
    background: #cecece;
    border-radius: 4px;
}

a.modal-login::after {
    position: relative;
    display: inline-block;
    transition: transform 0.2s ease;
    font-weight: bold;
    letter-spacing: 0.01em;
    will-change: transform;
    transform: translateY(var(--ty, 0)) rotateX(var(--rx, 0)) rotateY(var(--ry, 0));
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
<div class="modal micromodal-slide" id="modal-2" aria-hidden="false">
    <div class="modal__overlay" tabindex="-1" data-custom-close="">
        <div class="modal__container w-90 w-40-ns" role="dialog" aria-modal="true" aria-labelledby="modal-login-title">
            <header class="modal__header">
                <h3 class="modal__title" id="modal-2-title">
                    <i class="fas fa-user pr2"></i> Login
                </h3>
                <button class="modal__close" aria-label="Close modal" data-custom-close=""></button>
            </header>
            <?php    if ( is_user_logged_in() ): ?>
            <div class="modal__content" id="modal-2-content">
                <div class="measure">
                    <?=__('You already logged in','wp-streamers');?>
                    <p><a href="<?=wp_logout_url( home_url() )?>"><?=__('Logout', 'wp-streamers')?></a></p>
                    <?php    else: ?>
                    <form id="streamer_login_form" class="black-80" action="/">
                        <div id="streamerSignInResponse"></div>
                        <div class="modal__content" id="modal-2-content">
                            <div class="measure">
                                <label for="email" class="f6 b db mb2 js-email">Login or Email</label>
                                <input name="log" id="streamer_user_login"
                                    class="input-reset ba b--black-20 pa2 mb2 db w-100 js-emailInput" type="text"
                                    autocomplete="off">
                                <label for="password" class="f6 b db mb2 mt3">Password <span
                                        class="normal black-60">(required)</span>

                                </label>
                                <input name="pwd" id="streamer_user_pass"
                                    class="input-reset ba b--black-20 pa2 mb2 db w-100" type="password" required=""
                                    autocomplete="off">
                                <small id="name-desc" class="f6 black-60 db mb2">Must be at least 6 characters
                                    long.</small>
                            </div>
                        </div>
                        <footer class="modal__footer">
                            <input type="submit" id="streamer_login_submit" class="modal__btn modal__btn-primary"
                                value="Login">
                            <a class="f6 ml2 dark-blue no-underline underline-hover"
                                href="<?=esc_url( wp_lostpassword_url( home_url() ) )?>" aria-label="Reset password"
                                data-custom-close><?=__('Lost Password?','wp-streamers')?></a>
                        </footer>
                    </form>
                    <?php    endif; ?>
                </div>
            </div>
        </div>
        <div class="modal micromodal-slide" id="modal-login" aria-hidden="true">
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