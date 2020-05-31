<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SETTINGS {

  public static $usermeta;
  
  public static $errors;

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
    add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
    add_action('rest_api_init', [__CLASS__, 'rest_api_init']);
  }

  /**
   * add scripts
   *
   */  
  public static function assets(){
    global $post;
    if ( $post->post_name == self::$profile_page_slug && is_user_logged_in()):
      $current_agents = get_user_meta(get_current_user_id(), "streamer-position-required", true);

      $args = [
        'user-id'           =>  get_current_user_id(),
        'position_required' =>  $current_agents
      ];

      wp_register_script(
        'streamer-update',
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
        ['jquery','bootstrap-select', 'bootstrapjs', 'bootstrap-bundle', 'popperjs'],
        WP_STREAMERS_VERSION,
        true
      );
    endif;  
  }

  public static function rest_api_init() {
    register_rest_route('streamers/v1', '/streamer/update/(?P<id>[\d]+)', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'update_streamer_settings'],
      'args' => [
				'id' => [
					'required' => true,
					'validate_callback' => function($param, $request, $key) {
						return is_numeric( $param );
					},
				]
      ],
      'permission_callback' => function ( WP_REST_Request $request ) {
        return current_user_can('read');
      },
    ]);
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

  public static function update_streamer_settings( WP_REST_Request $request ){
    self::$errors = new \WP_Error();
    $user_id = $request['id'];

    // First name
    if(! empty(isset($_REQUEST['first_name']))){
      $first_name = trim($_REQUEST['first_name']);
      if (! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}[a-zа-яё]{2,16})?$/ui", $first_name)) {
        self::$errors->add('1', __('The first name has not been changed, the name must contain at least two letters and no more than one space.', 'wp-streamers'));
      } else {
        $userdata['first_name'] = $first_name;
      }
    }
    
    // Last Name
    if(! empty(isset($_REQUEST['last_name']))){
      $last_name = trim($_REQUEST['last_name']);
      if (! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}[a-zа-яё]{2,16})?$/ui", $last_name)) {
        self::$errors->add('1', __('The second name has not been changed, the surname must contain at least two letters and no more than one space.', 'wp-streamers'));
      } else {
        $userdata['last_name'] = $last_name;
    }
    }

    // email
    if(isset($_REQUEST['user_email'])){
      if(is_email($_REQUEST['user_email'])){
        $userdata['user_email'] = $_REQUEST['user_email'];
      } else {
        self::$errors->add('2', __('Email has not been changed. The format of the entered address is not supported by the site.', 'wp-streamers'));
      }
    }
    
    // user birthday
    if ( empty($_REQUEST['user_birthday_dd'])  || empty($_REQUEST['user_birthday_mm']) || empty($_REQUEST['user_birthday_yy'])) {
      self::$errors->add( 'user_birthday', __('Input you birthday', 'wp-streamers') );
    } elseif ( (date('Y') - $_REQUEST['user_birthday_yy']) < 15){
      self::$errors->add( 'cant_register', __('You must be at least 15 to be a member of valtzone ', 'wp-streamers') );  
    } else {
      $user_birthday = $_REQUEST['user_birthday_dd'] . '-' . $_REQUEST['user_birthday_mm'] . '-' . $_REQUEST['user_birthday_yy'];
      self::$usermeta['streamer_bday'] = $user_birthday;
    }
    
    // valorant_server
    if ( !empty ($_REQUEST['streamer_valorant_server'])) {
      self::$usermeta['streamer_valorant_server'] = $_REQUEST['streamer_valorant_server'];
    } else {  
      self::$errors->add( 'streamer_valorant_server', __('User Valorant server invalid', 'wp-streamers') );
    }
    
    // description
    if(isset($_REQUEST['description']) && !preg_match('/(wp-login|wp-admin|\/)/',$_REQUEST['description'])){
      $userdata['description'] = sanitize_textarea_field($_REQUEST['description']);
    } elseif ( !isset($_REQUEST['description']) ) {
      return;
    } else {  
      self::$errors->add('1', sprintf('Invalid characters in description %s',$_REQUEST['description']));
    }
    
    // IGN
    if(isset($_REQUEST['streamer-ign']) && !preg_match('/(wp-login|wp-admin|\/)/',$_REQUEST['streamer-ign'])){
      self::$usermeta['streamer-ign'] = $_REQUEST['streamer-ign'];
    } elseif ( !isset($_REQUEST['streamer-ign']) ) {
      return;
    } else {  
      self::$errors->add('invalid_ign', sprintf('Invalid characters in IGN %s',$_REQUEST['streamer-ign']));
    }

    // IGN number
    if(isset($_REQUEST['streamer-ign-number']) && !empty($_REQUEST['streamer-ign-number'])):
      if (strlen($_REQUEST['streamer-ign-number'])!=4 || !is_numeric($_REQUEST['streamer-ign-number'])):
        self::$errors->add('ign_number_short', sprintf('IGN number %s should have 4 number',$_REQUEST['streamer-ign-number']));
      else:  
        self::$usermeta['streamer-ign-number'] = $_REQUEST['streamer-ign-number'];
      endif;  
    endif;

    // streamer-preferred-agent
    if(isset($_REQUEST['streamer-preferred-agent-arr']) && !empty($_REQUEST['streamer-preferred-agent-arr'])):
      $positions = json_decode(stripslashes($_REQUEST['streamer-preferred-agent-arr']));
        if (!empty($positions)):
          $pos_array=array();
          foreach ($positions as $key => $value) :
            $pos_array[] = $value;
          endforeach;
          self::$usermeta['streamer-position-required'] = $pos_array;
        endif;  
    endif;

    //rank
    if(isset($_REQUEST['streamer-rank']) && !empty($_REQUEST['streamer-rank'])):
      self::$usermeta['streamer-rank'] = $_REQUEST['streamer-rank'];
    endif;

    //streamer-availability
    if(isset($_REQUEST['streamer-availability']) && !empty($_REQUEST['streamer-availability'])):
      self::$usermeta['streamer-availability'] = $_REQUEST['streamer-availability'];
    endif;

    // check password
    if( ! empty($_REQUEST['passw1']) and isset($_REQUEST['passw2']) ){
      // Validate password strength
      $uppercase = preg_match('@[A-Z]@', $_REQUEST['passw1']);
      $lowercase = preg_match('@[a-z]@', $_REQUEST['passw1']);
      $number    = preg_match('@[0-9]@', $_REQUEST['passw1']);

      if(!$uppercase || !$lowercase || !$number || strlen($_REQUEST['passw1']) < 6) {
        self::$errors->add( 'weak_password', __('Password should be at least 6 characters in length and should include at least one upper case letter, one number.', 'wp-streamer'));
      }

      if($_REQUEST['passw1'] !== $_REQUEST['passw2']){
        self::$errors->add('password_not_changed', __('The password has not been changed, the entered passwords do not match.', 'wp-streamer'));
      }

      if( empty( self::$errors->get_error_messages() ) ) {
        wp_set_password( $_REQUEST['passw1'], $user_id );
        $user_info = get_userdata($user_id);
        $auth = wp_authenticate( $user_info->user_email, $_REQUEST['passw1'] );
      }

    }

    // check errors
    if( empty( self::$errors->get_error_messages() ) ) {
      wp_update_user($userdata);
      $usermeta = self::$usermeta;
      if ($usermeta):
        foreach ($usermeta as $key=>$value):
          update_user_meta($user_id, $key, $value);
        endforeach;
      endif;
      $response = [
        'message'   => 'User settings update successfully!',
      ];
      wp_send_json_success($response);

    } else {

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
    }

  }

}

WP_STREAMER_SETTINGS::init();