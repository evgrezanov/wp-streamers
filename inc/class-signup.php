<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SIGNUP {

  public static $errors;

	public static function init(){
      add_shortcode('streamer_signup', [__CLASS__, 'signup']);
      add_action('streamer_registration', [__CLASS__, 'registration']);
      add_action('display_notice', [__CLASS__, 'notice']);
      add_action('init', [__CLASS__, 'save_reg_form']);
  }

  public static function signup(){
    ob_start();
    if ( get_option( 'users_can_register' ) ) :
      if ( !is_user_logged_in() ):
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
    if(empty($_POST)){
      return;
    }
    if (!( empty($_POST) || !wp_verify_nonce($_POST['bH37nfG7ej5G0F3'],'Hn9rU3ek0rG8rb') )) {
      self::$errors = new \WP_Error();
      do_action('streamer_registration', $_POST);
    }
  }

  public static function registration($data){
    
    //var_dump($data);
    //$data = $_POST;
  
    // Проверяем на не допустимые значения
    /*foreach ($data as $key => &$d) :
      $d = wp_strip_all_tags($d);

      if( $key != 'user_login' && $key != 'user_email' && $key != 'user_password' &&
        $key != 'user_region' && $key != 'user_birthday'){
        if(preg_match('/(wp-login|wp-admin|\/|\.)/',$d) ) {
          self::$errors->add('1', sprintf('Incorrect value in field %s 😏',$d));
        }
      }
    endforeach;*/

    // check password
    /*if( ! empty($data['user_password']) ){
      $password = preg_match('^[A-Za-z0-9\(\!\"\?\$\%\^\&\)]{8,24}$',$data['user_password']);
      if(!$password){
        self::$errors->add('2', sprintf('Ошибка! Пароль "%s" недостаточно надежен. Ваши SBC под угрозой 😏',$data['user_password']));
      }
    }*/
    
    // exist username/login
    if ( username_exists( $data['user_login'] )) {
      self::$errors->add( 'username_exists', ( __('User name exist already!', 'wp-streamers')) );
    } elseif (!validate_username( $data['user_login'] )) {
      self::$errors->add( 'username_invalid', ( __('В имени пользователя использованы недопустимые символы!', 'wp-streamers')) );
    }
    // empty email
    if ( empty( $data['user_email'] ) ) {
      self::$errors->add( 'email', __('Email field text is not email', 'wp-streamers') );
    } elseif ( !is_email( $data['user_email'] ) ) {
      self::$errors->add( 'email_invalid', __('You entered an invalid email address!', 'wp-streamers') );
    } elseif ( email_exists( $data['user_email'] ) ) {
      self::$errors->add( 'email_exist', __('This email address is already in use!', 'wp-streamers') );
    }

    // user birthday
    if ( $data['user_birthday'] == '') {
        self::$errors->add( 'user_birthday', __('Input you birthday', 'wp-streamers') );
    } else {
      $user_birthday = $data['user_birthday'];
      $current_year = date('Y');
      $birthday_year = strtotime($user_birthday);
      $year = date('Y', $birthday_year);
      $age = $current_year - $year;
      if ( $age < 15):
        self::$errors->add( 'cant_register', __('You must be at least 15 to be a member of valtzone ', 'wp-streamers') );
      endif;  
    }
    
    // user region
    if ( empty ($data['region'])) {
      self::$errors->add( 'user_region', __('User region invalid', 'wp-streamers') );
    }
    
    if( empty( self::$errors->get_error_messages() ) ) {
      $userdata = array(
        'user_pass'       => $data['user_password'], 
        'user_login'      => $data['user_login'], 
        'user_email'      => $data['user_email'],
        'role'            => 'streamers', 
        'user_registered' => date('Y-m-d H:i:s')
      );
      
      $new_user_id = wp_insert_user( $userdata );
      
      update_user_meta( $new_user_id, 'user_region', $data['region'] );
      update_user_meta( $new_user_id, 'user_birthday', $user_birthday);

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

}
WP_STREAMER_SIGNUP::init();