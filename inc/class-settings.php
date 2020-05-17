<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SETTINGS {

  public static $usermeta;
  public static $profile_errors;

  public static function init(){
    add_shortcode('streamer_personal_area', [__CLASS__, 'personal_area']);
    add_action('streamer_save_date', [__CLASS__, 'save_data']);
    add_action('display_notice', [__CLASS__, 'notice']);
    add_action('init', [__CLASS__, 'save_settings_form']);
}

public static function personal_area(){
  ob_start();
  if ( is_user_logged_in() ):
    $user_id = get_current_user_id();
    $img = UPPY_AVATAR::get_streamer_avatar($user_id, 'tumbnail');
    $user = get_userdata($user_id);
    $region = get_user_meta($user_id, 'user_region', true);
    $data = get_user_meta($user_id);
    require_once plugin_dir_path(__DIR__).'templates/personalarea.php';
  else:  
    echo __('Not logged in users can not see this page','wp-streamers');
    auth_redirect();
  endif;
  return ob_get_clean();
}

public static function save_settings_form(){
  if(empty($_POST) || !isset($_POST['save_personal_data'])){
    return;
  } else {
    self::$profile_errors = new \WP_Error();
    do_action('streamer_save_date', $_POST);
  } 
}

public static function save_data($data){
  $user_id = get_current_user_id();
  $userdata = [
    'ID' => $user_id
  ];
  // First name
  if(! empty(isset($data['first_name']))){
    $first_name = trim($data['first_name']);
    if (! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}[a-zа-яё]{2,16})?$/ui", $first_name)) {
      self::$profile_errors->add('1', __('The first name has not been changed, the name must contain at least two letters and no more than one space.', 'wp-streamers'));
    } else {
      //update_user_meta($user_id, 'first_name', $first_name);
      $userdata['first_name'] = $first_name;
    }
  }
  
  // Last Name
  if(! empty(isset($data['last_name']))){
    $last_name = trim($data['last_name']);
    if (! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}[a-zа-яё]{2,16})?$/ui", $last_name)) {
      self::$profile_errors->add('1', __('The second name has not been changed, the surname must contain at least two letters and no more than one space.', 'wp-streamers'));
    } else {
      //update_user_meta($user_id, 'last_name', $last_name);
      $userdata['last_name'] = $last_name;
   }
  }

  // email
  if(isset($data['user_email'])){
    if(is_email($data['user_email'])){
      $userdata['user_email'] = $data['user_email'];
      //wp_update_user( $userdata );
    } else {
      self::$profile_errors->add('2', __('Email has not been changed. The format of the entered address is not supported by the site.', 'wp-streamers'));
    }
  }

  // user birthday
  if ( isset($data['user_birthday']) ) {
    if(!strtotime($data['user_birthday'])){
      self::$profile_errors->add( 'user_birthday', __('Input you birthday', 'wp-streamers') );
    }  
  } else {
    $user_birthday = $data['user_birthday'];
    $current_year = date('Y');
    $birthday_year = strtotime($user_birthday);
    $year = date('Y', $birthday_year);
    $age = $current_year - $year;
    if ( $age < 15):
      self::$profile_errors->add( 'cant_register', __('You must be at least 15 to be a member of valtzone (current age '.$age.')', 'wp-streamers') );
    else:
      //update_user_meta( $user_id, 'user_birthday', $data['user_birthday'] );
      $userdata['user_birthday'] = $user_birthday;
    endif;
  }
  
  // user region
  if ( !empty ($data['region'])) {
    //update_user_meta( $user_id, 'user_region', $data['region'] );
    $userdata['user_region'] = $data['region'];
  } else {  
    self::$profile_errors->add( 'user_region', __('User region invalid', 'wp-streamers') );
  }
  
  // description
  if(isset($data['description']) && !preg_match('/(wp-login|wp-admin|\/)/',$data['description'])){
    //update_user_meta($user_id, 'description', $data['description']);
    $userdata['description'] = $data['description'];
  } elseif ( !isset($data['description']) ) {
    return;
  } else {  
    self::$profile_errors->add('1', sprintf('Invalid characters in description %s',$data['description']));
  }
  
  if( empty( self::$profile_errors->get_error_messages() ) ) {
    wp_update_user($userdata);
  }

}

public static function notice($context = ''){
  if(empty($_POST)){
    return;
  }

  if( ! method_exists(self::$profile_errors,'get_error_messages')){
    return;
  }

  $profile_errors = self::$profile_errors->get_error_messages();
  if( empty( $profile_errors ) ) {
    printf(
      '<div class="alert alert-success" role="alert">%s</div>',
      $text = __('Profile data sucsessfull update', 'wp-streamers')
    );

  } else {
    printf(
      '<div class="alert alert-danger" role="alert">%s</div>',
      $text = self::$profile_errors->get_error_message()
    );
  }

}

}

WP_STREAMER_SETTINGS::init();