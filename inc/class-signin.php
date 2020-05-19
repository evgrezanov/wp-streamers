<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SIGNIN {

    public static $errors;

	public static function init(){
      add_shortcode('streamer_sigin', [__CLASS__, 'signin']);
      add_action('init', [__CLASS__, 'signin_form']);
      add_action('streamer_login', [__CLASS__, 'login']);
    }

    public static function signin(){
        ob_start();
        if ( !is_user_logged_in() ):
            require_once plugin_dir_path(__DIR__).'templates/signin.php';
        else:    
            echo __('You already logged in','wp-streamers');
            echo '<br>';
            echo '<a href="'.wp_logout_url( home_url() ).'">'.__('Logout', 'wp-streamers').'</a>';
        endif;
        return ob_get_clean();
    }

    public static function signin_form(){
        // todo add verify nonce
        //if( empty($_POST)  || !wp_verify_nonce($_POST['streamer_login_form']) || isset($_POST['send_login_form'])){
        if( empty($_POST) || !isset($_POST['send_login_form'])){    
            return;
        } else {
            self::$errors = new \WP_Error();
            do_action('streamer_login', $_POST);
        }
    }

    public static function login($data){
        if($remember) $remember = "true";  
        else $remember = "false";  
   
        $login_data = array();  
        $login_data['user_login'] = stripslashes( trim( $data['streamer_user_login'] ) );  
        $login_data['user_pass'] = stripslashes( trim( $data['streamer_user_pass'] ) );  
        $login_data['remember'] = $remember;
        $redirect_to = esc_url_raw( $data['streamer_login_redirect'] );
        $secure_cookie = null;
        
        if($redirect_to == '')
		    $redirect_to= get_site_url(). '/me/' ; 
		
		if ( ! force_ssl_admin() ) {
			$user = is_email( $login_data['user_login'] ) ? get_user_by( 'email', $login_data['user_login'] ) : get_user_by( 'login', sanitize_user( $login_data['user_login'] ) );

		    if ( $user && get_user_option( 'use_ssl', $user->ID ) ) {
			    $secure_cookie = true;
			    force_ssl_admin( true );
            }
        
        }
        
        if ( is_null( $secure_cookie ) ) {
            $secure_cookie = false;
        }
    
        if ( $secure_cookie && strstr( $redirect_to, 'wp-admin' ) ) {
            $redirect_to = str_replace( 'http:', 'https:', $redirect_to );
        }
    
        if ( is_wp_error( $user ) && !empty($user) ) {
            if ( $user->errors ) {
                self::$errors->add( 'invalid_user_credentials', __('Please enter your username and password to login.', 'wp-streamers') );
            } 
        } 

        if( empty( self::$errors->get_error_messages() ) && !is_null($user) ) {
            //Auth
            //var_dump($user->ID);
            clean_user_cache($user->ID);
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true, false);
            update_user_caches($user);
            wp_safe_redirect(home_url().'/me');
        }
        
        
    }
}
WP_STREAMER_SIGNIN::init();