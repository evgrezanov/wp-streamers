<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMERS_TEAMS {

  public static function init(){
    add_action('init', [ __CLASS__, 'wp_streamers_register_taxonomy'], 20);
    add_action('init', [ __CLASS__, 'wp_streamers_register_cpt'], 30);
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
      ]
    );
  }

}

WP_STREAMERS_TEAMS::init();