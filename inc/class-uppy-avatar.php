<?php
defined( 'ABSPATH' ) || exit;

class UPPY_AVATAR {

	public static function init(){
		add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
		add_action('rest_api_init', [__CLASS__, 'rest_api_init']);
	}

	public static function assets(){
		wp_enqueue_script( 
            'uppy', 
            WP_STREAMERS_URL.'asset/uppy.min.js', 
            array('jquery'), 
            WP_STREAMERS_VERSION 
		);
		wp_enqueue_script( 
            'uppy_upload', 
            WP_STREAMERS_URL.'asset/upload.js', 
            array('jquery', 'uppy'), 
			WP_STREAMERS_VERSION,
			true
		);
		wp_enqueue_style(
            'uppy-styles', 
            WP_STREAMERS_URL . 'asset/uppy.min.css'
		);
		wp_enqueue_style(
            'bootstrap-new', 
            WP_STREAMERS_URL . 'asset/bootstrap.min.css'
        );
	}

	public static function rest_api_init(){
		register_rest_route( 'streamers/v1', '/avatar/upload/(?P<id>[\d]+)', array(
			'methods' => 'POST',
			'callback' => [__CLASS__, 'rest_avatar_upload'],
	        'args' => [
				'id' => [
					'required' => true,
					'validate_callback' => function($param, $request, $key) {
						return is_numeric( $param );
					},
				]
			],
	  	));
	}

	public static function rest_avatar_upload($request){
		$current_user_id = $request->get_param('id');
		
		if(empty((int)$current_user_id)){
			return new \WP_Error( '500', __('Current user not defined', 'wp-streamers'));
	  	}

		// move file to wp-content/upload
		if ( ! function_exists( 'wp_handle_upload' ) ) {
    		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$uploadedfile = $_FILES['my_file'];
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		$msg = '';
		if ( $movefile && ! isset( $movefile['error'] ) ) {
			$filename = $movefile['file'];
			$filetype = wp_check_filetype( basename( $filename ), null );
			$wp_upload_dir = wp_upload_dir();
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $filename );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			$msg = __('Avatar successfully updated', 'wp-streamers');
			//image_resize($filename, 200, 200, true, '200x200');
			// get image
			$user_profile = wp_get_attachment_image_url( $attach_id, 'user_profile', false );
			$thumbnail = wp_get_attachment_image_url( $attach_id, 'thumbnail', false );
			// add user meta
			update_user_meta($current_user_id, 'avatar_media_id', $attach_id);
		} else {
	    	/**
	     	* Error generated by _wp_handle_upload()
	     	* @see _wp_handle_upload() in wp-admin/includes/file.php
	     	*/
    		$msg =  $movefile['error'];
		}

	 	if ($msg) {
	   		$data = array(
		 		'profile_user_id' 	=> (int)$current_user_id,
		 		'result' 			=> $msg,
				'url' 				=> $movefile['url'],
				'thumbnail' 		=> $thumbnail,
				'user_profile' 		=> $user_profile
	   		);
			return new \WP_REST_Response( $data, 200, $movefile['url'] );
	 	} else {
	   		return new \WP_Error( '500', __('$result not true, need debug', 'wp-streamers'));
	 	}

	}

	public static function get_streamer_avatar($user_id, $size) {
		$avatar_id = get_the_author_meta('avatar_media_id', $user_id);
   		$default_url = WP_STREAMERS_URL . WP_STREAMERS_NO_IMG;
   		if (!empty($avatar_id)) {
			$avatar_url = wp_get_attachment_image_url($avatar_id, $size, false);
			return $avatar_url;
		} else {
	   		return $default_url;
		}
	}

	
}

UPPY_AVATAR::init();