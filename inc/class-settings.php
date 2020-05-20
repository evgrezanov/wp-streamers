<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SETTINGS {

  public static $usermeta;
  
  public static $paerrors;
  
  // default value for rank field
  public static $streamer_rank = array(
    "iron"      => "Iron", 
    "bronze"    => "Bronze", 
    "silver"    => "Silver", 
    "gold"      =>  "Gold", 
    "platinum"  =>  "Platinum", 
    "diamond"   =>  "Diamond", 
    "immortal"  =>  "Immortal", 
    "valorant"  =>  "Valorant"
  );
  
  // default items for preferred agent
  public static $streamer_preferred_agent = array(
    "breach"    => "Breach", 
    "brimstone" => "Brimstone", 
    "cypher"    => "Cypher", 
    "jett"      => "Jett", 
    "omen"      => "Omen", 
    "phoenix"   => "Phoenix", 
    "raze"      => "Raze", 
    "sage"      => "Sage",
    "sova"      => "Sova",
    "viper"     => "Viper"
  );


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
    $rank_img = UPPY_AVATAR::get_streamer_rank_verify($user_id, 'tumbnail');
    $user = get_userdata($user_id);
    $usermeta = get_user_meta($user_id);
    //echo $usermeta['valorant_server'][0];
    //get rank array
    $streamer_rank = self::$streamer_rank;
    //get streamer_preferred_agent
    $preferred_agent = self::$streamer_preferred_agent;
    //get streamer_valorant_server
    $valorant_server = get_terms(array(
      'taxonomy'    =>  'valorant-server',
      'hide_empty'  => false
    ));
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
    self::$paerrors = new \WP_Error();
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
      self::$paerrors->add('1', __('The first name has not been changed, the name must contain at least two letters and no more than one space.', 'wp-streamers'));
    } else {
      $userdata['first_name'] = $first_name;
    }
  }
  
  // Last Name
  if(! empty(isset($data['last_name']))){
    $last_name = trim($data['last_name']);
    if (! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}[a-zа-яё]{2,16})?$/ui", $last_name)) {
      self::$paerrors->add('1', __('The second name has not been changed, the surname must contain at least two letters and no more than one space.', 'wp-streamers'));
    } else {
      $userdata['last_name'] = $last_name;
   }
  }

  // email
  if(isset($data['user_email'])){
    if(is_email($data['user_email'])){
      $userdata['user_email'] = $data['user_email'];
    } else {
      self::$paerrors->add('2', __('Email has not been changed. The format of the entered address is not supported by the site.', 'wp-streamers'));
    }
  }
  
  // user birthday
  if ( empty($data['user_birthday_dd'])  || empty($data['user_birthday_mm']) || empty($data['user_birthday_yy'])) {
    self::$paerrors->add( 'user_birthday', __('Input you birthday', 'wp-streamers') );
  } elseif ( (date('Y') - $data['user_birthday_yy']) < 15){
    self::$paerrors->add( 'cant_register', __('You must be at least 15 to be a member of valtzone ', 'wp-streamers') );  
  } else {
    $user_birthday = $data['user_birthday_dd'] . '-' . $data['user_birthday_mm'] . '-' . $data['user_birthday_yy'];
    self::$usermeta['streamer_bday'] = $user_birthday;
  }
  

  // valorant_server
  if ( !empty ($data['streamer_valorant_server'])) {
    self::$usermeta['streamer_valorant_server'] = $data['streamer_valorant_server'];
  } else {  
    self::$paerrors->add( 'streamer_valorant_server', __('User Valorant server invalid', 'wp-streamers') );
  }
  
  // description
  if(isset($data['description']) && !preg_match('/(wp-login|wp-admin|\/)/',$data['description'])){
    $userdata['description'] = $data['description'];
  } elseif ( !isset($data['description']) ) {
    return;
  } else {  
    self::$paerrors->add('1', sprintf('Invalid characters in description %s',$data['description']));
  }
  
  // IGN
  if(isset($data['streamer-ign']) && !preg_match('/(wp-login|wp-admin|\/)/',$data['streamer-ign'])){
    self::$usermeta['streamer-ign'] = $data['streamer-ign'];
  } elseif ( !isset($data['streamer-ign']) ) {
    return;
  } else {  
    self::$paerrors->add('invalid_ign', sprintf('Invalid characters in IGN %s',$data['streamer-ign']));
  }

  // IGN number
  if(isset($data['streamer-ign-number']) && !empty($data['streamer-ign-number'])):
    if (strlen($data['streamer-ign-number'])!=4 || !is_numeric($data['streamer-ign-number'])):
      self::$paerrors->add('ign_number_short', sprintf('IGN number %s should have 4 number',$data['streamer-ign-number']));
    else:  
      self::$usermeta['streamer-ign-number'] = $data['streamer-ign-number'];
    endif;  
  endif;

  // streamer-pa1
  if(isset($data['streamer-pa1']) && !empty($data['streamer-pa1'])):  
    self::$usermeta['streamer-pa1'] = $data['streamer-pa1'];
  else:  
    self::$paerrors->add('required-streamer-pa1', sprintf('Preferred Agent #1 is required field',$data['streamer-pa1']));
  endif;

  // streamer-pa2
  if(isset($data['streamer-pa2']) && !empty($data['streamer-pa2'])):
    self::$usermeta['streamer-pa2'] = $data['streamer-pa2'];
  endif;

  // streamer-pa3
  if(isset($data['streamer-pa3']) && !empty($data['streamer-pa3'])):
    self::$usermeta['streamer-pa3'] = $data['streamer-pa3'];
  endif;

  //rank
  if(isset($data['streamer-rank']) && !empty($data['streamer-rank'])):
    self::$usermeta['streamer-rank'] = $data['streamer-rank'];
  endif;

  //streamer-availability
  if(isset($data['streamer-availability']) && !empty($data['streamer-availability'])):
    self::$usermeta['streamer-availability'] = $data['streamer-availability'];
  endif;

  // check errors
  if( empty( self::$paerrors->get_error_messages() ) ) {
    wp_update_user($userdata);
    $usermeta = self::$usermeta;
    if ($usermeta):
      foreach ($usermeta as $key=>$value):
        update_user_meta($user_id, $key, $value);
      endforeach;
    endif;  
  }

}

public static function notice($context = ''){
  if(empty($_POST)){
    return;
  }

  if( ! method_exists(self::$paerrors,'get_error_messages')){
    return;
  }

  $profile_errors = self::$paerrors->get_error_messages();
  if( empty( $profile_errors ) ) {
    printf(
      '<div class="alert alert-success" role="alert">%s</div>',
      $text = __('Profile data sucsessfull update', 'wp-streamers')
    );

  } else {
    printf(
      '<div class="alert alert-danger" role="alert">%s</div>',
      $text = self::$paerrors->get_error_message()
    );
  }

}

}

WP_STREAMER_SETTINGS::init();