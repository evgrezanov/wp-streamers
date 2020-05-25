<?php
defined( 'ABSPATH' ) || exit;
//Breach, Brimstone, Cypher, Jett, Omen, Phoenix, Raze, Sage, Sova, Viper
class WP_STREAMERS_TEAMS {

  public static $errors;

  public static $team_terms;
  
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
    // register
    add_action('init', [ __CLASS__, 'wp_streamers_register_taxonomy'], 20);
    add_action('init', [ __CLASS__, 'wp_streamers_register_cpt'], 30);
    // save metadata from wp-admin
    add_action('save_post_teams', [__CLASS__, 'save_post_teams'] );
    // render shortcode
    add_shortcode('display_team', [__CLASS__, 'display_team']);
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
    if ( is_singular('teams') && is_user_logged_in() && $post->post_author == get_current_user_id() ):
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

      $position_required = get_post_meta($post->ID, 'position_required', true);

      $args = [
        'team-author'       =>  get_current_user_id(),
        'team-id'           =>  $post->ID,
        'position_required' =>  $position_required
      ];

      wp_register_script(
        'team-update',
        WP_STREAMERS_URL.('asset/team-script.js')
      );

      wp_localize_script(
        'team-update',
        'endpointTeamUpdateProperties',
        $args
      );

      wp_enqueue_script(
        'team-update',
        WP_STREAMERS_URL.('asset/team-script.js'),
        ['jquery','bootstrap-select', 'bootstrap-js', 'bootstrap-bundle', 'popper-js'],
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
    register_rest_route('streamers/v1', '/team/update/(?P<id>[\d]+)', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'update_team'],
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

  public static function update_team(){
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
   * Register custom taxonomy
   *
   * @return void
   */
  public static function wp_streamers_register_taxonomy(){
    register_taxonomy('teams-type', array('teams'), array(
      'label'                 => __( 'Sport types', 'wp-streamer' ),
      'labels'                => array(
        'name'                => __( 'Team types', 'wp-streamer' ),
        'singular_name'       => __( 'Team types', 'wp-streamer' ),
        'search_items'        => __( 'Find team type', 'wp-streamer' ),
        'all_items'           => __( 'All team type', 'wp-streamer' ),
        'view_item '          => __( 'View team type', 'wp-streamer' ),
        'parent_item'         => __( 'Parent team type', 'wp-streamer' ),
        'parent_item_colon'   => __( 'Parent team type:', 'wp-streamer' ),
        'edit_item'           => __( 'Edit team type', 'wp-streamer' ),
        'update_item'         => __( 'Update team type', 'wp-streamer' ),
        'add_new_item'        => __( 'Add team type', 'wp-streamer' ),
        'new_item_name'       => __( 'Name of team type', 'wp-streamer' ),
        'menu_name'           => __( 'Team types', 'wp-streamer' ),
      ),
      'description'           => __( 'All team type', 'wp-streamer' ),
      'public'                => true,
      'publicly_queryable'    => true,
      'show_in_nav_menus'     => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'show_tagcloud'         => true,
      'show_in_rest'          => true, 
      'hierarchical'          => true,
      'rewrite'               => true,
      'capabilities'          => array(),
      'meta_box_cb'           => null,
      'show_admin_column'     => true,
      //'_builtin'              => false,
      'show_in_quick_edit'    => null,
    ) );
    register_taxonomy('valorant-server', array('teams'), array(
      'label'                 => __( 'Valorant servers', 'wp-streamer' ),
      'labels'                => array(
        'name'                => __( 'Valorant servers', 'wp-streamer' ),
        'singular_name'       => __( 'Valorant server', 'wp-streamer' ),
        'search_items'        => __( 'Find valorant server', 'wp-streamer' ),
        'all_items'           => __( 'All valorant server', 'wp-streamer' ),
        'view_item '          => __( 'View valorant server', 'wp-streamer' ),
        'parent_item'         => __( 'Parent valorant server', 'wp-streamer' ),
        'parent_item_colon'   => __( 'Parent valorant server:', 'wp-streamer' ),
        'edit_item'           => __( 'Edit valorant server', 'wp-streamer' ),
        'update_item'         => __( 'Update valorant server', 'wp-streamer' ),
        'add_new_item'        => __( 'Add valorant server', 'wp-streamer' ),
        'new_item_name'       => __( 'Name of valorant server', 'wp-streamer' ),
        'menu_name'           => __( 'Valorant server', 'wp-streamer' ),
      ),
      'description'           => __( 'All valorant server', 'wp-streamer' ),
      'public'                => true,
      'publicly_queryable'    => true,
      'show_in_nav_menus'     => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'show_tagcloud'         => true,
      'show_in_rest'          => true, 
      'hierarchical'          => false,
      'rewrite'               => true,
      'capabilities'          => array(),
      'meta_box_cb'           => null,
      'show_admin_column'     => true,
      //'_builtin'              => false,
      'show_in_quick_edit'    => null,
    ) );
    register_taxonomy('rank-requirement', array('teams'), array(
      'label'                 => __( 'Rank Requirement', 'wp-streamer' ),
      'labels'                => array(
        'name'                => __( 'Rank Requirements', 'wp-streamer' ),
        'singular_name'       => __( 'Rank Requirement', 'wp-streamer' ),
        'search_items'        => __( 'Find rank requirement', 'wp-streamer' ),
        'all_items'           => __( 'All rank requirements', 'wp-streamer' ),
        'view_item '          => __( 'View rank requirement', 'wp-streamer' ),
        'parent_item'         => __( 'Parent rank requirement', 'wp-streamer' ),
        'parent_item_colon'   => __( 'Parent rank requirement:', 'wp-streamer' ),
        'edit_item'           => __( 'Edit rank requirement', 'wp-streamer' ),
        'update_item'         => __( 'Update rank requirement', 'wp-streamer' ),
        'add_new_item'        => __( 'Add rank requirement', 'wp-streamer' ),
        'new_item_name'       => __( 'Name of rank requirement', 'wp-streamer' ),
        'menu_name'           => __( 'Rank requirement', 'wp-streamer' ),
      ),
      'description'           => __( 'All rank requirement', 'wp-streamer' ),
      'public'                => true,
      'publicly_queryable'    => true,
      'show_in_nav_menus'     => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'show_tagcloud'         => true,
      'show_in_rest'          => true, 
      'hierarchical'          => false,
      'rewrite'               => true,
      'capabilities'          => array(),
      'meta_box_cb'           => null,
      'show_admin_column'     => true,
      //'_builtin'              => false,
      'show_in_quick_edit'    => null,
    ) );
  }

  /**
   * Register custom post type
   *
   * @return void
   */
  public static function wp_streamers_register_cpt(){
    register_post_type( 'teams', [
        'label'  => null,
        'labels' => [
          'name'               => __('Teams', 'wp_streamers'),
          'singular_name'      => __('Team', 'wp_streamers'),
          'add_new'            => __('Add new team', 'wp_streamers'),
          'add_new_item'       => __('Add team', 'wp_streamers'),
          'edit_item'          => __('Edit team', 'wp_streamers'),
          'new_item'           => __('New team', 'wp_streamers'),
          'view_item'          => __('View team', 'wp_streamers'),
          'search_items'       => __('Find team', 'wp_streamers'),
          'not_found'          => __('Not found', 'wp_streamers'),
          'not_found_in_trash' => __('Not found in trash', 'wp_streamers'),
          'menu_name'          => __('Teams', 'wp_streamers'),
        ],
        'show_in_rest'        => true,
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'menu_icon'           => 'dashicons-groups',
        'hierarchical'        => false,
        'supports'            => array('title','editor','comments','author', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'          => array('sports'),
        'has_archive'         => 'teams',
        'query_var'           => true,
        'exclude_from_search' => false,
        'register_meta_box_cb'  => [__CLASS__, 'add_teams_metaboxes'],
      ]
    );
  }

  /**
   * Add metabox
   *
   * @return void
   */
  public static function add_teams_metaboxes(){
    $screens = array('teams');
    add_meta_box(
        'teams_fields',
        __('Team meta fields', 'wp-streamers'),
        [__CLASS__, 'display_teams_fields'],
        'teams',
        'normal',
        'default',
        $screens
    );
  }

  /**
   * Display team meta data in metabox
   *
   * @return void
   */
  public static function display_teams_fields($post, $meta) {
    wp_nonce_field( basename( __FILE__ ), 'teams_fields' );
    $age_requirement = get_post_meta( $post->ID, 'age_requirement', true );
    $amount_players_needed = get_post_meta( $post->ID, 'amount_players_needed', true );
    $positions_required = get_post_meta( $post->ID, 'positions_required', true );
    //print_r($positions_required, false);
    // age required
    $age_requirement_list = self::$age_requirement_list;
    $age_select =   '<label>Age Requirement</label>';
    $age_select .=  '<select class="widefat" name="age_requirement">';
    foreach ($age_requirement_list as $key=>$value):
      $age_select .= "<option". selected($age_requirement, $key, false ) . 'value="'.$key.'">'.$value."</option>";
    endforeach;
    $age_select .='</select>';
    echo $age_select;
    
    // position required
    /*$position_required_list = self::$streamer_preferred_agent;
    $pr_select  = '<label>Positions required</label>';
    $pr_select  .= '<select class="widefat" multiple="multiple" name="position_required">';
    foreach ($position_required_list as $key=>$value):
      if (isset($positions_required[$key])):
        $pr_select .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
      else:
        $pr_select .= '<option value="'.$key.'">'.$value.'</option>';
      endif;
    endforeach;
    $pr_select  .= '</select>';
    echo $pr_select;*/
  }

  /**
   * Save team metadata
   *
   * @return void
   */
  public static function save_post_teams( $post_id ) {
        
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
  public static function display_team() {
    global $post;
    $age_requirement = get_post_meta($post->ID, 'age_requirement', true);
    
    $team_type = get_the_terms($post->ID, 'teams-type');
    $all_team_type = get_terms(array(
      'taxonomy'    => 'teams-type',
      'hide_empty'  => false
    ));

    $regions = get_the_terms($post->ID, 'valorant-server');
    $all_region = get_terms(array(
      'taxonomy'    => 'valorant-server',
      'hide_empty'  => false
    ));

    $ranks = get_the_terms($post->ID, 'rank-requirement');
    $all_ranks = get_terms(array(
      'taxonomy'    => 'rank-requirement',
      'hide_empty'  => false
    ));

    $position_required = get_post_meta($post->ID, 'position_required', true);
    
    $author = get_userdata($post->post_author);
    $logo = UPPY_AVATAR::get_team_logo($post->ID, 'tumbnail');
    ob_start();
      if ( is_user_logged_in() && $post->post_author == get_current_user_id() ):
        require_once plugin_dir_path(__DIR__).'templates/team-edit.php';
      else :
        require_once plugin_dir_path(__DIR__).'templates/team-view.php';
      endif;  
    return ob_get_clean();
  }

}

WP_STREAMERS_TEAMS::init();