<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SIGNUP {

  public static $errors;

	public static function init(){
      add_shortcode('streamer_signup', [__CLASS__, 'signup']);
      add_action('streamer_registration', [__CLASS__, 'registration']);
      add_action('display_notice', [__CLASS__, 'notice']);
      add_action('init', [__CLASS__, 'save_reg_form']);
      //add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
      //add_action('rest_api_init', [__CLASS__, 'wp_rest_user_endpoints']);
  }

  /**
   * Register a new streamer
   *
   * @param  WP_REST_Request $request Full details about the request.
   * @return array $args.
   **/
  public static function wp_rest_streamer_endpoints($request) {
    register_rest_route('streamers/v1', 'streamer/register', array(
      'methods' => 'POST',
      'callback' => 'rest_streamer_endpoint_register',
    ));
  }

  public static function rest_streamer_endpoint_register($request = null){
    $response = array();
    $data = $request->get_json_params();
    // exist username/login
    if ( username_exists( $data['user_login'] )) {
      self::$errors->add( 'username_exists', __('User name exist already!', 'wp-streamers') );
    } elseif (!validate_username( $data['user_login'] )) {
      self::$errors->add( 'username_invalid', ( __('В имени пользователя использованы недопустимые символы!', 'wp-streamers')) );
    }
    // email
    if ( empty( $data['user_email'] ) ) {
      self::$errors->add( 'email', __('Email field text is not email', 'wp-streamers') );
    } elseif ( !is_email( $data['user_email'] ) ) {
      self::$errors->add( 'email_invalid', __('You entered an invalid email address!', 'wp-streamers') );
    } elseif ( email_exists( $data['user_email'] ) ) {
      self::$errors->add( 'email_exist', __('This email address is already in use!', 'wp-streamers') );
    }

    // password validation
    $password = $data['user_password'];

    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if(!$uppercase || !$lowercase || !$number || strlen($password) < 6) {
      self::$errors->add( 'weak_password', __('Password should be at least 6 characters in length and should include at least one upper case letter, one number.', 'wp-streamer'));
    }

    // user birthday
    if ( empty($data['user_birthday_dd'])  || empty($data['user_birthday_mm']) || empty($data['user_birthday_yy'])) {
        self::$errors->add( 'user_birthday', __('Input you birthday', 'wp-streamers') );
    } 
    if ( (date('Y') - $data['user_birthday_yy']) < 15){
        self::$errors->add( 'cant_register', __('You must be at least 15 to be a member of valtzone ', 'wp-streamers') );  
    }
    
    // user region
    if ( empty ($data['streamer_valorant_server'])) {
      self::$errors->add( 'invalid_valorant_server', __('User Valorant server invalid', 'wp-streamers') );
    }
    
    if( empty( self::$errors->get_error_messages() ) ):
      $userdata = array(
        'user_pass'       => $data['user_password'], 
        'user_login'      => sanitize_text_field($data['user_login']), 
        'user_email'      => sanitize_text_field($data['user_email']),
        'role'            => 'streamers', 
        'user_registered' => date('Y-m-d H:i:s')
      );
      
      $new_user_id = wp_insert_user( $userdata );
      $user_birthday = $data['user_birthday_dd'] . '-' . $data['user_birthday_mm'] . '-' . $data['user_birthday_yy'];
      add_user_meta( $new_user_id, 'valorant_server', $data['streamer_valorant_server'] );
      add_user_meta( $new_user_id, 'user_birthday_dd', $user_birthday);

      // TODO why no have notification?
      wp_new_user_notification( $new_user_id, null, 'both');
      
      $response = [
        'message' => 'User created successfully!',
      ];
      wp_send_json_success($response);

      //Auth
      $user = get_user_by('id', $new_user_id );
      clean_user_cache($user->ID);
      wp_clear_auth_cookie();
      wp_set_current_user($user->ID);
      wp_set_auth_cookie($user->ID, true, false);
      update_user_caches($user);
      wp_safe_redirect(home_url().'/me');
      
    else:

      $response = [
        'message'    => 'User create fail! => ' . self::$errors->get_error_messages(),
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

  public static function save_reg_form(){
    if( empty($_POST) || !isset($_POST['send_user_registeration'])){    
      return;
    } else {    
      self::$errors = new \WP_Error();
      do_action('streamer_registration', $_POST);
    }
  }

  public static function registration($data){
    // exist username/login
    if ( username_exists( $data['user_login'] )) {
      self::$errors->add( 'username_exists', __('User name exist already!', 'wp-streamers') );
    } elseif (!validate_username( $data['user_login'] )) {
      self::$errors->add( 'username_invalid', ( __('В имени пользователя использованы недопустимые символы!', 'wp-streamers')) );
    }
    // email
    if ( empty( $data['user_email'] ) ) {
      self::$errors->add( 'email', __('Email field text is not email', 'wp-streamers') );
    } elseif ( !is_email( $data['user_email'] ) ) {
      self::$errors->add( 'email_invalid', __('You entered an invalid email address!', 'wp-streamers') );
    } elseif ( email_exists( $data['user_email'] ) ) {
      self::$errors->add( 'email_exist', __('This email address is already in use!', 'wp-streamers') );
    }

    // password validation
    $password = $data['user_password'];

    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if(!$uppercase || !$lowercase || !$number || strlen($password) < 6) {
      self::$errors->add( 'weak_password', __('Password should be at least 6 characters in length and should include at least one upper case letter, one number.', 'wp-streamer'));
    }

    // user birthday
    if ( empty($data['user_birthday_dd'])  || empty($data['user_birthday_mm']) || empty($data['user_birthday_yy'])) {
        self::$errors->add( 'user_birthday', __('Input you birthday', 'wp-streamers') );
    } 
    if ( (date('Y') - $data['user_birthday_yy']) < 15){
        self::$errors->add( 'cant_register', __('You must be at least 15 to be a member of valtzone ', 'wp-streamers') );  
    }
    
    // user region
    if ( empty ($data['streamer_valorant_server'])) {
      self::$errors->add( 'invalid_valorant_server', __('User Valorant server invalid', 'wp-streamers') );
    }
    
    if( empty( self::$errors->get_error_messages() ) ) {
      $userdata = array(
        'user_pass'       => $data['user_password'], 
        'user_login'      => sanitize_text_field($data['user_login']), 
        'user_email'      => sanitize_text_field($data['user_email']),
        'role'            => 'streamers', 
        'user_registered' => date('Y-m-d H:i:s')
      );
      
      $new_user_id = wp_insert_user( $userdata );
      $user_birthday = $data['user_birthday_dd'] . '-' . $data['user_birthday_mm'] . '-' . $data['user_birthday_yy'];
      add_user_meta( $new_user_id, 'valorant_server', $data['streamer_valorant_server'] );
      add_user_meta( $new_user_id, 'user_birthday_dd', $user_birthday);

      // TODO why no have notification?
      wp_new_user_notification( $new_user_id, null, 'both');
      
      //Auth
      $user = get_user_by('id', $new_user_id );
      clean_user_cache($user->ID);
      wp_clear_auth_cookie();
      wp_set_current_user($user->ID);
      wp_set_auth_cookie($user->ID, true, false);
      update_user_caches($user);
      wp_safe_redirect(home_url().'/me');
    }

  }
  
  public static function notice($context = ''){
    if(empty($_POST)){
      return;
    }

    if( ! method_exists(self::$errors,'get_error_messages')){
      return;
    }

    $errors = self::$errors->get_error_messages();

    if( empty( $errors ) ) {
      printf(
        '<div class="alert alert-success" role="alert">%s</div>',
        $text = 'Sucsess registration'
      );

    } else {
      printf(
        '<div class="alert alert-danger" role="alert">%s</div>',
        $text = self::$errors->get_error_message()
      );
    }


  }

  public static function assets(){
    
    wp_enqueue_script(
      'bootstrap-js',
      WP_STREAMERS_URL.('asset/bootstrap/bootstrap.min.js'),
      ['jquery'],
      WP_STREAMERS_VERSION,
      false
    );
    
    wp_enqueue_style(
      'bootstrap-css', 
      WP_STREAMERS_URL . 'asset/bootstrap/bootstrap.min.css'
    );


    /*wp_enqueue_script( 
      'signup-script', 
      WP_STREAMERS_URL.'asset/signup-script.js', 
      array('jquery', 'bootstrap-js', 'parsley-js'), 
      WP_STREAMERS_VERSION,
      true
    );*/
    
  }

/**
 * Register a new user
 *
 * @param  WP_REST_Request $request Full details about the request.
 * @return array $args.
 **/
public static function wp_rest_user_endpoints($request) {
  /**
   * Handle Register User request.
   */
  register_rest_route('wp/v2', 'users/register', array(
    'methods' => 'POST',
    'callback' => 'wc_rest_user_endpoint_handler',
  ));
}

}
WP_STREAMER_SIGNUP::init();