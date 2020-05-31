<?php
/**
 * Plugin Name: WP Streamers
 * Description: Add custom functional for streamers and gambling sites.
 * Plugin URI:  https://github.com/evgrezanov/wp-streamers
 * Author URI:  https://www.upwork.com/freelancers/~01ea58721977099d53
 * Author:      <a href="https://www.upwork.com/freelancers/~01ea58721977099d53" target="_blank">Evgeniy Rezanov</a>
 * Version:     1.6.3
 * GitHub Plugin URI: evgrezanov/wp-streamers
 * GitHub Plugin URI: https://github.com/evgrezanov/wp-streamers
 * Text Domain: wp-streamers
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'WP_STREAMERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_STREAMERS_FILE', __FILE__ );
define( 'WP_STREAMERS_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_STREAMERS_VERSION', '1.6.3' );
define( 'WP_STREAMERS_NO_IMG', 'img/no_avatar.png');

class WP_STREAMERS {

    public static function init() {
        // All active plugins loaded
        add_action('plugins_loaded', function() {
            // load translate files
	        load_plugin_textdomain( 'wp-streamers', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
        }, 999);

        // assets
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        
        // require uppy avatar for streamer
        require_once('inc/class-uppy-avatar.php');

        // require uppy avatar for streamer
        require_once('inc/class-settings.php');

        // require signup form for streamer
        require_once('inc/class-signup.php');

        // require signin form for streamer
        require_once('inc/class-signin.php');

        // require team functions
        require_once('inc/class-teams.php');

         // require team functions
         require_once('inc/class-team-finder.php');

        // add Streamers role based subscribers role capabilities
        register_activation_hook( __FILE__, function(){
            $subscriber = get_role('subscriber');
	        add_role(
                'streamers', 
                __('Streamers', 'wp-streamers'),
                $subscriber->capabilities
            );
        });  

        // remove role at deactivation
        register_deactivation_hook( __FILE__, function (){
	        remove_role( 'streamers' );
        });
    }

    public static function assets(){
      wp_enqueue_script('wp-api');
      
      wp_enqueue_script(
        'popperjs',
        WP_STREAMERS_URL.('asset/bootstrap-select/js/popper.min.js'),
        ['jquery'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrapjs',
        WP_STREAMERS_URL.('asset/bootstrap/bootstrap.min.js'),
        ['jquery', 'popperjs'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrap-bundle',
        WP_STREAMERS_URL.('asset/bootstrap/bootstrap.bundle.min.js'),
        ['jquery', 'bootstrapjs', 'popperjs'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'bootstrap-select',
        WP_STREAMERS_URL.('asset/bootstrap-select/js/bootstrap-select.min.js'),
        ['jquery', 'bootstrapjs', 'bootstrap-bundle', 'popperjs'],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_script(
        'micromodal',
        WP_STREAMERS_URL.('asset/micromodal/micromodal.min.js'),
        [],
        WP_STREAMERS_VERSION,
        false
      );

      wp_enqueue_style(
        'bootstrap-select', 
        WP_STREAMERS_URL . ('asset/bootstrap-select/css/bootstrap-select.min.css')
      );

      wp_enqueue_style(
        'bootstrap-css', 
        WP_STREAMERS_URL . ('asset/bootstrap/bootstrap.min.css')
      );
    }
}

WP_STREAMERS::init();

?>