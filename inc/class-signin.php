<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SIGNIN {

    public static $errors;

	public static function init(){
      add_shortcode('streamer_sigin', [__CLASS__, 'signin']);
      add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
      add_action('rest_api_init', [__CLASS__, 'rest_user_endpoints']);
    }
    
    public static function assets() {
        $args = array(
          'site-url' => 'streamers/v1/streamer/signin',
        );
    
        wp_register_script(
          'streamer-signin',
          WP_STREAMERS_URL.('asset/signin-script.js')
        );
    
        wp_localize_script(
          'streamer-signin',
          'endpointStreamerSignIn',
          $args
        );
    
        wp_enqueue_script(
          'streamer-signin',
          WP_STREAMERS_URL.('asset/signin-script.js'),
          ['jquery', 'bootstrapjs', 'popperjs'],
          WP_STREAMERS_VERSION,
          true
        );
    }

    public static function rest_user_endpoints() {
        register_rest_route('streamers/v1', 'streamer/signin', array(
          'methods'   => 'POST',
          'callback'  => [__CLASS__, 'rest_streamer_endpoint_login'],
          'args' => array(
			'user_login' => array(
				'required'  => null,
			),
			'user_password' => array(
				'required'  => null,
            ),
		  ),
        ));
    }
    
    public static function rest_streamer_endpoint_login($request){
        self::$errors = new \WP_Error();
        if ( empty($_REQUEST['log']) || empty($_REQUEST['pwd']) ):
            self::$errors->add( 'login_empty', __('Username and password fields cant be empty!', 'wp-streamers') );
        else:
            $redirect_to = esc_url_raw( $_REQUEST['redirect_to'] );
        
            if($redirect_to == '')
		        $redirect_to= get_site_url(). '/me/' ; 
		
			$user = is_email( $_REQUEST['log'] ) ? get_user_by( 'email', $_REQUEST['log'] ) : get_user_by( 'login', sanitize_user( $_REQUEST['log'] ) );
            if ($user):
                $rememberme = false;
                if ( isset($_REQUEST['rememberme']) && !empty($_REQUEST['rememberme']) ):
                    $rememberme = true;
                endif;
                
                $creds = array(
                    'user_login'    => $_REQUEST['log'],
                    'user_password' => $_REQUEST['pwd'],
                    'remember'      => $rememberme
                );
             
                $cur_user = wp_signon( $creds, false );
             
                if ( is_wp_error($cur_user) ) :
                    self::$errors->add( $cur_user->get_error_code(), $cur_user->get_error_message() );
                endif;
            endif;
            
        endif;
        
        if( empty( self::$errors->get_error_messages() )) :
            $response = [
                'message'   => 'User log in successfully!',
                'redirect'  => home_url().'/me',
            ];
            //Auth
            clean_user_cache($user->ID);
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true, false);
            update_user_caches($user);
            
            wp_send_json_success($response);
        else:
            $all_errors = self::$errors->get_error_messages();
            $msg = '';
            foreach ($all_errors as $key => $value) {
                $msg .= '<p>'.$value.'</p>';
            }
            $response = [
                'message'     => 'User create fail!',
                'details'     => $msg,
            ];
            wp_send_json_error($response, 500);
        endif;
    }
      
    public static function signin(){
        ob_start();
        if ( !is_user_logged_in() ):
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
        endif;
        return ob_get_clean();
    }

}
WP_STREAMER_SIGNIN::init();