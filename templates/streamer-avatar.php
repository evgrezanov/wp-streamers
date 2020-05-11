<?php
$img = UPPY_AVATAR::get_streamer_avatar(get_current_user_id(), 'tumbnail');
?>
<div class="wp-streamers-photo">
    <div class="streamer_avatar">
        <img id="streamer_img" src="<?php echo $img; ?>">
    </div>
    <button id="uppyModalOpener" data-user="<?php echo get_current_user_id(); ?>">
        <?php echo __('Upload photo', 'wp-streamers'); ?>
    </button>
</div>