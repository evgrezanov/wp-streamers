<?php
$img = UPPY_AVATAR::get_streamer_avatar(get_current_user_id(), 'tumbnail');
$data = get_userdata(get_current_user_id());
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
                        <input type="text" class="form-control" id="firstName" placeholder="" value="" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="" value="" required>
                        <div class="invalid-feedback">
                            Valid last name is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" class="form-control" id="username" placeholder="Username" required>
                        <div class="invalid-feedback" style="width: 100%;">
                            Your username is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="you@example.com">
                    <div class="invalid-feedback">
                        Please enter a valid email address for shipping updates.
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-bio">Short Bio</label>
                        <textarea class="form-control" id="streamer-bio" rows="3"></textarea>
                    </div>
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Save</button>
            </form>
        </div>
    </div>
</div>