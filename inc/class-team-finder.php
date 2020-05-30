<?php
defined( 'ABSPATH' ) || exit;
class WP_TEAMS_FINDER {

  public static $errors;

  public static $team_finder_slug = 'team-finder';
  
  public static $age_requirement_list = array(
    '15'  =>  '15+',
    '16'  =>  '16+',
    '17'  =>  '17+',
    '18'  =>  '18+',
    '19'  =>  '19+',
    '20'  =>  '20+',
    '21'  =>  '21+',
    '22'  =>  '22+',
    '23'  =>  '23+'
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
    // render shortcode
    add_shortcode('display_team_finder', [__CLASS__, 'display_team_finder']);
    // init rest
    add_action('rest_api_init', [__CLASS__, 'rest_api_init']);
    // assets
    add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
  }

  /**
   * add scripts
   *
   */  
  public static function assets(){
    global $post;
    //if ( is_singular('teams') && is_user_logged_in() && $post->post_author == get_current_user_id() ):
      wp_enqueue_script('wp-api');
      
      wp_enqueue_script(
        'datatables',
        WP_STREAMERS_URL.('asset/DataTables/datatables.min.js'),
        ['jquery'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrap-js',
        WP_STREAMERS_URL.('asset/bootstrap/bootstrap.min.js'),
        ['jquery'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'popper-js',
        WP_STREAMERS_URL.('asset/bootstrap-select/js/popper.min.js'),
        ['jquery', 'bootstrap-js'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrap-select',
        WP_STREAMERS_URL.('asset/bootstrap-select/js/bootstrap-select.min.js'),
        ['jquery', 'bootstrap-js', 'popper-js'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_style(
        'bootstrap-select', 
        WP_STREAMERS_URL . ('asset/bootstrap-select/css/bootstrap-select.min.css')
      );

      $args = [
        'user-id'       =>  get_current_user_id(),
      ];

      wp_register_script(
        'team-finder',
        WP_STREAMERS_URL.('asset/team-finder-script.js')
      );

      wp_localize_script(
        'team-finder',
        'endpointTeamFinder',
        $args
      );

      wp_enqueue_script(
        'team-finder',
        WP_STREAMERS_URL.('asset/team-finder-script.js'),
        ['jquery','bootstrap-select', 'bootstrap-js', 'popper-js'],
        WP_STREAMERS_VERSION,
        true
      );
      
    //endif;
  }
  
  /**
   * init REST API by WP
   *
   * @url /wp-json/sstreamers/v1/team/update/
   */
  public static function rest_api_init() {
    register_rest_route('streamers/v1', '/team/quick_add_new/(?P<id>[\d]+)', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'quick_add_new'],
      'args' => [
				'id' => [
					'required' => true,
					'validate_callback' => function($param, $request, $key) {
						return is_numeric( $param );
					},
				]
			],
    ]);
  }

  public static function quick_add_new(){
     
    $team_data = array();
    $team_meta = array();
    self::$errors = new \WP_Error();
    
    if( !empty($_REQUEST['user-id']) ):
      $userID = $_REQUEST['user-id'];
    else:
      self::$errors->add('0', 'User can not be empty!');
    endif;

    //post_title
    if(! empty($_REQUEST['team-name'])){
      $teamTitle = wp_strip_all_tags($_REQUEST['team-name']);
    } else {
      self::$errors->add('1', 'Team name is empty! Add team name!');
    }

    // Team type
    if(isset($_REQUEST['team-type']) && !empty($_REQUEST['team-type'])){
      $teamsTypeArray = array();
      $teamsTypeArray[] = (int)$_REQUEST['team-type'];
    } else {  
      self::$errors->add('2', 'Input valid team type!');
    }

    // Region
    if(isset($_REQUEST['team-region']) && !empty($_REQUEST['team-region'])){
      //$teamsRegionArray = array();
      $teamsRegion = (int)$_REQUEST['team-region'];
    } else {  
      self::$errors->add('3', 'Input valid team region!');
    }
    
    // Rank Requirements
    if(isset($_REQUEST['team-rank']) && !empty($_REQUEST['team-rank'])){
      $teamsRank = (int)$_REQUEST['team-rank'];
    } else {  
      self::$errors->add('4', 'Input valid team rank requirements!');
    }

    // Age Requirement
    if(isset($_REQUEST['team-age-requirement']) && !empty($_REQUEST['team-age-requirement'])){
      $ageRequirement = $_REQUEST['team-age-requirement'];
    } else {  
      self::$errors->add('5', 'Input valid team age requirement region!');
    }

    //Positions required
    if(isset($_REQUEST['team-agent']) && !empty($_REQUEST['team-agent'])):
      $pos_array=array();
      $pos_array[] = $_REQUEST['team-agent'];
      $positionRequired = $pos_array;
    else:  
      self::$errors->add('7', 'Input valid positions requered field!');
    endif;
  
    // check errors and update team
    if( empty( self::$errors->get_error_messages() ) ) :
      // вставляем запись в базу данных
      $post_id = wp_insert_post(  wp_slash( array(
        'post_status'   => 'draft',
        'post_title'    => $teamTitle,
        'post_type'     => 'teams',
        'post_author'   => $userID,
        'tax_input'     => array( 
          'teams-type'      => $teamsTypeArray
        ), 
        'meta_input'    => [ 
          'age_requirement' => $ageRequirement,
          'position_required' => $positionRequired,
        ],
      ) ) );

      if (is_wp_error($post_id)):
        self::$errors->add('4', $result->get_error_messages());
      else: 
        //set current terms rank-requirement and valorant-server
        wp_set_object_terms( $post_id, array($teamsRank), 'rank-requirement' );
        wp_set_object_terms( $post_id, array($teamsRegion), 'valorant-server' );
      endif;

    endif;

    if ( empty( self::$errors->get_error_messages() ) ):
      $response = [
        'message' => 'Team insert successfully!',
      ];
      wp_send_json_success($response);
    else:
      $all_errors = self::$errors->get_error_messages();
      $msg = '';
        foreach ($all_errors as $key => $value) {
          $msg .= $value.' / ';
        }
      $response = [
        'message'    => 'Team insert fail! => ' . $msg,
      ];
      wp_send_json_error($response, 500);
    endif;
  }

  /**
   * Save team metadata
   *
   * @return void
   */
  public static function save_team_meta( $post_id ) {
        
    if ( wp_is_post_revision( $post_id ) ){
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ):
        return $post_id;
    endif;

    if ( ! isset( $_POST['age_requirement'] ) || ! wp_verify_nonce( $_POST['teams_fields'], basename(__FILE__) ) ):
    //if ( ! isset( $_POST['age_requirement'] ) || ! isset( $_POST['amount_players_needed'] ) || ! isset( $_POST['positions_equired'] ) || ! wp_verify_nonce( $_POST['teams_fields'], basename(__FILE__) ) ):
        return $post_id;
    endif;
    
    $events_meta['age_requirement'] = esc_textarea( $_POST['age_requirement'] );
    //$events_meta['amount_players_needed'] = esc_textarea( $_POST['amount_players_needed'] );
    //$events_meta['positions_equired'] = esc_textarea( $_POST['positions_equired'] );

    foreach ( $events_meta as $key => $value ) :

      if ( get_post_meta( $post_id, $key, false ) ) {
        update_post_meta( $post_id, $key, $value );
      } else {
        add_post_meta( $post_id, $key, $value);
      }

      if ( ! $value ) {
        delete_post_meta( $post_id, $key );
      }

    endforeach;
  }

  /**
   * Shortcode for display team data
   *
   * @return void
   */
  public static function display_team_finder() {
    $arg = array(
      'post_type'   => 'teams',
      'post_status' => 'any',
      'numberposts' => -1,
      'orderby'     => 'date',
	    'order'       => 'DESC',
    );
    $teams = get_posts($arg);

    $all_team_type = get_terms(array(
      'taxonomy'    => 'teams-type',
      'hide_empty'  => false
    ));

    $all_region = get_terms(array(
      'taxonomy'    => 'valorant-server',
      'hide_empty'  => false
    ));

    $all_ranks = get_terms(array(
      'taxonomy'    => 'rank-requirement',
      'hide_empty'  => false
    ));
    
    $ages = WP_STREAMERS_TEAMS::$age_requirement_list;
    $agents = WP_STREAMERS_TEAMS::$streamer_preferred_agent;
    ob_start();
    require_once plugin_dir_path(__DIR__).'templates/team-finder.php';
    wp_reset_postdata();
    return ob_get_clean();
  }

}

WP_TEAMS_FINDER::init();