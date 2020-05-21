<?php
//namespace Streamers\Teams;
defined( 'ABSPATH' ) || exit;

class WP_STREAMERS_TEAMS {
  
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
  
  public static function init(){
    add_action('init', [ __CLASS__, 'wp_streamers_register_taxonomy'], 20);
    add_action('init', [ __CLASS__, 'wp_streamers_register_cpt'], 30);
    add_action('save_post_teams', [__CLASS__, 'save_post_teams'] );
    
    add_shortcode('display_team', [__CLASS__, 'display_team']);
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

  public static function display_teams_fields($post, $meta) {
    wp_nonce_field( basename( __FILE__ ), 'teams_fields' );
    $age_requirement = get_post_meta( $post->ID, 'age_requirement', true );
    $amount_players_needed = get_post_meta( $post->ID, 'amount_players_needed', true );
    $positions_equired = get_post_meta( $post->ID, 'positions_equired', true );
    
    // age required
    $age_requirement_list = self::$age_requirement_list;
    $age_select =   '<label>Age Requirement</label>';
    $age_select .=  '<select class="widefat" name="age_requirement">';
    foreach ($age_requirement_list as $key=>$value):
      $age_select .= '<option '. selected($age_requirement, $key ) . 'value="'.$key.'">'.$value.'</option>';
    endforeach;
    $age_select .='</select>';
    echo $age_select;

    // position required
    //$preferred_agent = WP_STREAMER_SETTINGS::$streamer_preferred_agent();
    //echo '<label>Amount of players needed</label><input type="text" name="amount_players_needed" value="' . esc_html( $amount_players_needed )  . '" class="widefat">';
    //echo '<label>Positions required</label><input type="text" name="positions_equired" value="' . esc_html( $positions_equired )  . '" class="widefat">';
  }

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

  public static function display_team() {
    global $post;
    $age_requirement = get_post_meta($post->ID, 'age_requirement', true) . '+';
    $team_type = get_the_terms($post->ID, 'teams-type');
    $regions = get_the_terms($post->ID, 'valorant-server');
    $ranks = get_the_terms($post->ID, 'rank-requirement');
    $author = get_userdata($post->post_author);
    $logo = get_the_post_thumbnail($post->ID, array(150,150));
    ob_start();
      if ( is_user_logged_in() && $post->post_author == get_current_user_id() ):
        require_once plugin_dir_path(__DIR__).'templates/team-view.php';
      else :
        require_once plugin_dir_path(__DIR__).'templates/team-edit.php';
      endif;  
    return ob_get_clean();
  }

}

WP_STREAMERS_TEAMS::init();