<?php
/**
 * Plugin Name: WP Streamers
 * Description: Add custom functional for streamers and gambling sites.
 * Plugin URI:  https://github.com/evgrezanov/wp-streamers
 * Author URI:  https://www.upwork.com/freelancers/~01ea58721977099d53
 * Author:      <a href="https://www.upwork.com/freelancers/~01ea58721977099d53" target="_blank">Evgeniy Rezanov</a>
 * Version:     1.5.3
 * GitHub Plugin URI: evgrezanov/wp-streamers
 * GitHub Plugin URI: https://github.com/evgrezanov/wp-streamers
 * Text Domain: wp-streamers
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'WP_STREAMERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_STREAMERS_FILE', __FILE__ );
define( 'WP_STREAMERS_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_STREAMERS_VERSION', '1.5.3' );
define( 'WP_STREAMERS_NO_IMG', 'img/no_avatar.png');

class WP_STREAMERS {

    public static function init() {
        // All active plugins loaded
        add_action('plugins_loaded', function() {
            // load translate files
	        load_plugin_textdomain( 'wp-streamers', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
        }, 999);
        
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
}

WP_STREAMERS::init();

?>