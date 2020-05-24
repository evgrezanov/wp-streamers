<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SETTINGS {

  public static $usermeta;
  
  public static $paerrors;

  public static $profile_page_slug = 'me';
  
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
    add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
  }

  /**
   * add scripts
   *
   */  
  public static function assets(){
    global $post;
    if ( $post->post_name == self::$profile_page_slug):
      wp_enqueue_script('wp-api');
      
      wp_enqueue_script(
        'popper-js',
        WP_STREAMERS_URL.('asset/bootstrap-select/js/popper.min.js'),
        ['jquery'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrap-js',
        WP_STREAMERS_URL.('asset/bootstrap/bootstrap.min.js'),
        ['jquery', 'popper-js'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrap-bundle',
        WP_STREAMERS_URL.('asset/bootstrap/bootstrap.bundle.min.js'),
        ['jquery', 'bootstrap-js'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrap-select',
        WP_STREAMERS_URL.('asset/bootstrap-select/js/bootstrap-select.min.js'),
        ['jquery', 'bootstrap-js', 'bootstrap-bundle', 'popper-js'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_style(
        'bootstrap-select', 
        WP_STREAMERS_URL . ('asset/bootstrap-select/css/bootstrap-select.min.css')
      );

      $current_agents = get_user_meta(get_current_user_id(), "streamer-position-required", true);

      $args = [
        'user-id'           =>  get_current_user_id(),
        'position_required' =>  $current_agents
      ];

      wp_register_script(
        'streamer-script',
        WP_STREAMERS_URL.('asset/streamer-script.js')
      );

      wp_localize_script(
        'streamer-update',
        'endpointStreamerUpdateSettings',
        $args
      );

      wp_enqueue_script(
        'streamer-update',
        WP_STREAMERS_URL.('asset/streamer-script.js'),
        ['jquery','bootstrap-select', 'bootstrap-js', 'bootstrap-bundle', 'popper-js'],
        WP_STREAMERS_VERSION,
        true
      );
    endif;  
  }


public static function personal_area(){
  ob_start();
  if ( is_user_logged_in() ):
    $user_id = get_current_user_id();
    $img = UPPY_AVATAR::get_streamer_avatar($user_id, 'tumbnail');
    $rank_img = UPPY_AVATAR::get_streamer_rank_verify($user_id, 'tumbnail');
    $user = get_userdata($user_id);
    $usermeta = get_user_meta($user_id);
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
    $userdata['description'] = sanitize_textarea_field($data['description']);
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

  // streamer-preferred-agent
  if(isset($data['streamer-preferred-agent-arr']) && !empty($data['streamer-preferred-agent-arr'])):
    $positions = json_decode(stripslashes($data['streamer-preferred-agent-arr']));
      if (!empty($positions)):
        $pos_array=array();
        foreach ($positions as $key => $value) :
          $pos_array[] = $value;
        endforeach;
        self::$usermeta['streamer-position-required'] = $pos_array;
      endif;  
  //else:  
    //self::$paerrors->add('required-streamer-pa1', __('Preferred Agent #1 is required field', 'wp-streamer'));
  endif;

  //rank
  if(isset($data['streamer-rank']) && !empty($data['streamer-rank'])):
    self::$usermeta['streamer-rank'] = $data['streamer-rank'];
  endif;

  //streamer-availability
  if(isset($data['streamer-availability']) && !empty($data['streamer-availability'])):
    self::$usermeta['streamer-availability'] = $data['streamer-availability'];
  endif;

  // check password
  if( ! empty($data['passw1']) and isset($data['passw2']) ){
    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $data['passw1']);
    $lowercase = preg_match('@[a-z]@', $data['passw1']);
    $number    = preg_match('@[0-9]@', $data['passw1']);

    if(!$uppercase || !$lowercase || !$number || strlen($data['passw1']) < 6) {
      self::$paerrors->add( 'weak_password', __('Password should be at least 6 characters in length and should include at least one upper case letter, one number.', 'wp-streamer'));
    }

    if($data['passw1'] !== $data['passw2']){
      self::$paerrors->add('password_not_changed', __('The password has not been changed, the entered passwords do not match.', 'wp-streamer'));
    }

    if( empty( self::$paerrors->get_error_messages() ) ) {
      wp_set_password( $data['passw1'], $user_id );
      $user_info = get_userdata($user_id);
      $auth = wp_authenticate( $user_info->user_email, $data['passw1'] );
    }

  }

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