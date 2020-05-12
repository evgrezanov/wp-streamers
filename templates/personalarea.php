<?php
$img = UPPY_AVATAR::get_streamer_avatar(get_current_user_id(), 'tumbnail');
$user = get_userdata(get_current_user_id());
?>
<div class="container">
    <div class="row">

        <div class="col-md-4 order-md-1 mb-4">
            <div class="wp-streamers-photo">
                <div class="streamer_avatar">
                    <img id="streamer_img" style="max-width:150px;" src="<?php echo $img; ?>">
                </div>
                <button id="uppyModalOpener" data-user="<?php echo get_current_user_id(); ?>">
                    <?php echo __('Upload photo', 'wp-streamers'); ?>
                </button>
            </div>
        </div>

        <div class="col-md-8 order-md-2">
            <!--personal data-->
            <form class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" id="firstName" placeholder=""
                            value="<?=$user->user_firstname?>" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" id="lastName" placeholder=""
                            value="<?=$user->user_lastname?>" required>
                        <div class="invalid-feedback">
                            Valid last name is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username">Nickname</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" class="form-control" id="username" value="<?=$user->nickname?>"
                            placeholder="Username" required>
                        <div class="invalid-feedback" style="width: 100%;">
                            Your username is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" value="<?=$user->user_email?>"
                        placeholder="you@example.com">
                    <div class="invalid-feedback">
                        Please enter a valid email address for shipping updates.
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-bio">Short Bio</label>
                        <textarea class="form-control" id="streamer-bio"
                            rows="3"><?=$user->user_description?></textarea>
                    </div>
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Save</button>
            </form>
        </div>
    </div>
</div>