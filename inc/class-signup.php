<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SIGNUP {

  public static $errors;

	public static function init(){
      add_shortcode('streamer_signup', [__CLASS__, 'signup']);
      add_shortcode('signup_modal', [__CLASS__, 'signup_modal']);
      add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
      add_action('rest_api_init', [__CLASS__, 'rest_user_endpoints']);
      add_action( 'wp_footer', [__CLASS__, 'signup_popup'], 30 );

  }

  public static function assets() {
    $args = array(
      'site-url' => 'streamers/v1/streamer/signup',
    );

    wp_register_script(
      'streamer-signup',
      WP_STREAMERS_URL.('asset/signup-script.js')
    );

    wp_localize_script(
      'streamer-signup',
      'endpointStreamerSignUp',
      $args
    );

    wp_enqueue_script(
      'streamer-signup',
      WP_STREAMERS_URL.('asset/signup-script.js'),
      ['jquery', 'bootstrapjs', 'popperjs'],
      WP_STREAMERS_VERSION,
      true
    );

    wp_enqueue_script(
      'modal-signup',
      WP_STREAMERS_URL.('asset/signup-modal-script.js'),
      [],
      WP_STREAMERS_VERSION,
      true
    );
  }

  public static function signup_popup(){
    require_once plugin_dir_path(__DIR__).'templates/signup-signin-modal.php';
  }
  
  public static function signup_modal(){
    ob_start();
    ?>
<a data-micromodal-trigger="modal-1" href='javascript:void(0);'>Register</a>
<a data-micromodal-trigger="modal-2" href='javascript:void(0);'>Login</a>

<?php
    return ob_get_clean();
  }
  /**
   * Register a new streamer
   *
   * @param  WP_REST_Request $request Full details about the request.
   * @return array $args.
   **/
  public static function rest_user_endpoints($request) {
    register_rest_route('streamers/v1', 'streamer/signup', array(
      'methods'   => WP_REST_Server::CREATABLE,
      'callback'  => [__CLASS__, 'rest_streamer_endpoint_register'],
    ));
  }

  public static function rest_streamer_endpoint_register(){
    self::$errors = new \WP_Error();
    
    // exist username/login
    if ( username_exists( $_REQUEST['user_login'] )) {
      self::$errors->add( 'username_exists', __('User name exist already!', 'wp-streamers') );
    } elseif (!validate_username( $_REQUEST['user_login'] )) {
      self::$errors->add( 'username_invalid', ( __('В имени пользователя использованы недопустимые символы!', 'wp-streamers')) );
    }

    // email
    if ( empty( $_REQUEST['user_email'] ) ) {
      self::$errors->add( 'email', __('Email field text is not email', 'wp-streamers') );
    } elseif ( !is_email( $_REQUEST['user_email'] ) ) {
      self::$errors->add( 'email_invalid', __('You entered an invalid email address!', 'wp-streamers') );
    } elseif ( email_exists( $_REQUEST['user_email'] ) ) {
      self::$errors->add( 'email_exist', __('This email address is already in use!', 'wp-streamers') );
    }

    // password validation
    $password = $_REQUEST['user_password'];

    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if(!$uppercase || !$lowercase || !$number || strlen($password) < 6) {
      self::$errors->add( 'weak_password', __('Password should be at least 6 characters in length and should include at least one upper case letter, one number.', 'wp-streamer'));
    }

    // user birthday
    if ( empty($_REQUEST['user_birthday_dd'])  || empty($_REQUEST['user_birthday_mm']) || empty($_REQUEST['user_birthday_yy'])) {
        self::$errors->add( 'user_birthday', __('Input you birthday', 'wp-streamers') );
    } 
    if ( (date('Y') - $_REQUEST['user_birthday_yy']) < 15){
        self::$errors->add( 'cant_register', __('You must be at least 15 to be a member of valtzone ', 'wp-streamers') );  
    }
    
    // user region
    if ( empty ($_REQUEST['streamer_valorant_server'])) {
      self::$errors->add( 'invalid_valorant_server', __('User Valorant server invalid', 'wp-streamers') );
    }
    
    if( empty( self::$errors->get_error_messages() ) ):
      $userdata = array(
        'user_pass'       => $_REQUEST['user_password'], 
        'user_login'      => sanitize_text_field($_REQUEST['user_login']), 
        'user_email'      => sanitize_text_field($_REQUEST['user_email']),
        'role'            => 'streamers', 
        'user_registered' => date('Y-m-d H:i:s')
      );
      
      $new_user_id = wp_insert_user( $userdata );
      $user_birthday = $_REQUEST['user_birthday_dd'] . '-' . $_REQUEST['user_birthday_mm'] . '-' . $_REQUEST['user_birthday_yy'];
      add_user_meta( $new_user_id, 'valorant_server', $_REQUEST['streamer_valorant_server'] );
      add_user_meta( $new_user_id, 'user_birthday_dd', $user_birthday);

      // TODO why no have notification?
      wp_new_user_notification( $new_user_id, null, 'both');
      
      $response = [
        'message'   => 'User created successfully!',
        'redirect'  => home_url().'/me',
      ];
      

      //Auth
      $user = get_user_by('id', $new_user_id );
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

  public static function signup(){
    ob_start();
    if ( get_option( 'users_can_register' ) ) :
      if ( !is_user_logged_in() ):
        $valorant_server = get_terms(array(
          'taxonomy'    =>  'valorant-server',
          'hide_empty'  => false
        ));
        require_once plugin_dir_path(__DIR__).'templates/signup.php';
      else:  
        echo __('You already register','wp-streamers');
      endif;
    else:
        echo __('Registration is disabled!','wp-streamers');
    endif;
    return ob_get_clean();
  }

}
WP_STREAMER_SIGNUP::init();