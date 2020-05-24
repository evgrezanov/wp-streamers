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
        'team-author'       =>  get_current_user_id(),
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
    register_rest_route('streamers/v1', '/team/add/(?P<id>[\d]+)', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'quick_team_add']
    ]);
  }

  public static function quick_team_add(){
    $user_id = get_current_user_id();
    $team_data = array();
    $team_meta = array();
    self::$errors = new \WP_Error();
    
    if(! empty($_REQUEST['team-id'])):
      $team = get_post($_REQUEST['team-id']);
      $team_data['ID'] = $_REQUEST['team-id'];
    else:
      self::$errors->add('0', 'Cannot take team object for edit!');
    endif;

    //post_title
    if(! empty($_REQUEST['team-name'])){
      $team_data['post_title'] = wp_strip_all_tags($_REQUEST['team-name']);
      $team->post_title = wp_strip_all_tags($_REQUEST['team-name']);
    } else {
      self::$errors->add('1', 'Team name is empty! Add team name!');
    }

    // description
    if(isset($_REQUEST['team-description']) && !preg_match('/(wp-login|wp-admin|\/)/',$_REQUEST['team-description'])){
      $team->post_content = sanitize_textarea_field($_REQUEST['team-description']);
      $team_data['post_content'] = sanitize_textarea_field($_REQUEST['team-description']);
    } else {  
      self::$errors->add('2', sprintf('Invalid characters in team description %s',$_REQUEST['team-description']));
    }

    // Team type
    if(isset($_REQUEST['team-type']) && !empty($_REQUEST['team-type'])){
      self::$team_terms['teams-type'] = $_REQUEST['team-type'];
    } else {  
      self::$errors->add('3', 'Input valid team type!');
    }

    // Region
    if(isset($_REQUEST['team-region']) && !empty($_REQUEST['team-region'])){
      self::$team_terms['valorant-server'] = $_REQUEST['team-region'];
    } else {  
      self::$errors->add('3', 'Input valid team region!');
    }
    
    // Rank Requirements
    if(isset($_REQUEST['team-rank-requirements']) && !empty($_REQUEST['team-rank-requirements'])){
      self::$team_terms['rank-requirement'] = $_REQUEST['team-rank-requirements'];
    } else {  
      self::$errors->add('3', 'Input valid team rank requirements region!');
    }

    // Age Requirement
    if(isset($_REQUEST['team-age-requirement']) && !empty($_REQUEST['team-age-requirement'])){
      $team_meta['age_requirement'] = $_REQUEST['team-age-requirement'];
    } else {  
      self::$errors->add('3', 'Input valid team age requirement region!');
    }

    //Positions required
    if(isset($_REQUEST['team-positions-requered-arr']) && !empty($_REQUEST['team-positions-requered-arr'])){
      $positions = json_decode(stripslashes($_REQUEST['team-positions-requered-arr']));
      if (!empty($positions)):
        $pos_array=array();
        foreach ($positions as $key => $value) :
          $pos_array[] = $value;
        endforeach;
        $team_meta['position_required'] = $pos_array;
      endif;
    } else {  
      self::$errors->add('7', 'Input valid positions requered field!');
    }

    // check errors and update team
    if( empty( self::$errors->get_error_messages() ) ) :
      // set team terms
      $team_terms = self::$team_terms;
      foreach ($team_terms as $key=>$value):
        $terms = wp_set_post_terms($team->ID, [(int)$value], $key, false);
        if (!$terms):
          self::$errors->add('5', __('$post_id не число или равно 0.', 'wp-streamers'));
        endif;
        if ( is_wp_error($terms) ):
          self::$errors->add('6', $terms->get_error_messages());
        endif;
        //error_log($terms);
      endforeach;

      // add meta 
      if (!empty($team_meta)):
        foreach ($team_meta as $key=>$value):
          update_post_meta($team->ID, $key, $value);
        endforeach;
      endif;
      
      $result = wp_update_post( $team, true );
      
      if (is_wp_error($result)):
        self::$errors->add('4', $result->get_error_messages());
      endif;

    endif;

    if ( empty( self::$errors->get_error_messages() ) ):
      $response = [
        'message' => 'Team update successfully!',
      ];
      wp_send_json_success($response);
    else:
      $response = [
        'message'    => 'Team update fail! => ' . self::$errors->get_error_messages(),
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
      'status'      => 'published',
      'numberposts' => -1,
      'orderby'     => 'date',
	    'order'       => 'DESC',
    );
    $teams = get_posts($arg);
    //var_dump($teams);
    ob_start();
    require_once plugin_dir_path(__DIR__).'templates/team-finder.php';
    wp_reset_postdata();
    return ob_get_clean();
  }

}

WP_TEAMS_FINDER::init();