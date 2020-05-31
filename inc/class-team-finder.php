<?php
defined( 'ABSPATH' ) || exit;
class WP_TEAMS_FINDER {

  public static $errors;

  public static $team_finder_slug = 'team-finder';
  
  public static $team_default_age = '15';

  public static $team_default_agent = 'breach';
  
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
    if ( $post->post_name == self::$team_finder_slug ):
      
      wp_enqueue_script(
        'datatables',
        WP_STREAMERS_URL.('asset/DataTables/datatables.min.js'),
        ['jquery', 'bootstrapjs'],
        WP_STREAMERS_VERSION,
        false
      );

      $args = [
        'user-id'  =>  get_current_user_id(),
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
        ['jquery','bootstrapjs', 'popperjs', 'datatables'],
        WP_STREAMERS_VERSION,
        true
      );
      
    endif;
  }
  
  /**
   * init REST API by WP
   *
   * @url /wp-json/sstreamers/v1/team/update/
   */
  public static function rest_api_init() {
    register_rest_route('streamers/v1', '/team/quick_add_new/(?P<id>[\d]+)', [
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => [__CLASS__, 'quick_add_new'],
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
      }
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
      $ageRequirement = (int)$_REQUEST['team-age-requirement'];
    } else {  
      $ageRequirement = self::$team_default_age;
    }

    //Positions required
    $pos_array=array();
    if(isset($_REQUEST['team-agent']) && !empty($_REQUEST['team-agent'])):
      $pos_array[] = $_REQUEST['team-agent'];
      $positionRequired = $pos_array;
    else:  
      $pos_array[] = self::$team_default_agent;
      $positionRequired = $pos_array;
    endif;
  
    // check errors and update team
    if( empty( self::$errors->get_error_messages() ) ) :
      // вставляем запись в базу данных
      $post_id = wp_insert_post(  wp_slash( array(
        'post_status'   => 'publish',
        'post_title'    => $teamTitle,
        'post_type'     => 'teams',
        'post_author'   => $userID,
        'tax_input'     => array( 
          'teams-type'      => $teamsTypeArray
        ), 
        'meta_input'    => [ 
          'age_requirement'   => $ageRequirement,
          'position_required' => $positionRequired,
          'is_draft'          => true,
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
      $cur_team = get_post($post_id);
      $cur_type = get_term((int)$_REQUEST['team-type'], 'teams-type');
      $cur_region = get_term((int)$_REQUEST['team-region'], 'valorant-server');
      $cur_rank = get_term((int)$_REQUEST['team-rank'], 'rank-requirement');
      $logo = UPPY_AVATAR::get_team_logo($post_id,'tumbnail');
      $team_logo = '<img class="team_finder_team_logo" style="max-width:50px;" src="'.$logo.'">';
      $response = [
        'message'     => 'Team <a href="'.get_permalink($cur_team).'"><strong>'.$cur_team->post_title.'</strong></a> add successfully! <a href="'.get_permalink($cur_team).'">Edit</a> your team, for have verified status.',
        'team_name'   => $cur_team->post_title,
        'team_id'     => $post_id,
        'team_logo'   => $team_logo,
        'team_type'   => $cur_type->name,
        'team_region' => $cur_region->name,
        'team_rank'   => $cur_rank->name,
        'team_age'    => $ageRequirement.'+',
        'team_agents' => '<span class="badge badge-dark">'.$_REQUEST['team-agent'].'</span>',
        'team_link'   => get_permalink($cur_team),
        'team_date'   => get_the_date('d/m/Y H:i', $post_id),
        'team_status' => '<span class="badge badge-secondary">draft</span>',
        'team_button' => '<button type="button" class="btn btn-danger btn-sm">Send invite</button><a type="button" class="btn btn-info btn-sm" href="'.get_permalink($cur_team).'">More info</a>',
      ];
      wp_send_json_success($response);
    else:
      $all_errors = self::$errors->get_error_messages();
      $msg = '';
        foreach ($all_errors as $key => $value) {
          $msg .= $value.' / ';
        }
      $response = [
        'message'    => 'Team insert fail! => ',
        'details'    => $msg,
      ];
      wp_send_json_error($response, 500);
    endif;
  }

  /**
   * Shortcode for display team data
   *
   * @return void
   */
  public static function display_team_finder() {
    global $post;
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
    $current_team = self::get_current_streamer_team();
    $is_draft = get_post_meta('is_draft', $post->ID, true);
    ob_start();
    require_once plugin_dir_path(__DIR__).'templates/team-finder.php';
    wp_reset_postdata();
    return ob_get_clean();
  }

  /**
   * Get current streamer team
   *
   * @return void
   */
  public static function get_current_streamer_team(){
    if (is_user_logged_in()):
      $teams = get_posts(array(
        'post_type'   => 'teams',
        'post_status' => 'any',
        'author'      => get_current_user_id()
      ));
      if (!empty($teams)):
        return $teams;
      else:
        return;
      endif;
    else: 
      return;
    endif;    
  }

}

WP_TEAMS_FINDER::init();