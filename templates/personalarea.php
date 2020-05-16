<?php
?>
<div class="container">
    <div class="row">
        <!-- avatar upload-->
        <div class="col-md-4 order-md-1 mb-4">
            <div class="wp-streamers-photo">
                <div class="streamer_avatar">
                    <img id="streamer_img" style="max-width:150px;" src="<?php echo $img; ?>">
                </div>
                <button class="btn btn-primary btn-small" id="uppyModalOpener"
                    data-user="<?php echo get_current_user_id(); ?>">
                    <?php echo __('Upload photo', 'wp-streamers'); ?>
                </button>
            </div>
        </div>
        <!--personal data-->
        <div class="col-md-8 order-md-2">
            <?php do_action('display_notice', $content='streamer_personal_area');?>
            <form enctype="multipart/form-data" method="post" id="streamer-edit-profile" class="streamer-edit-profile"
                class="needs-validation" novalidate>
                <?php wp_nonce_field('9f9cf458e2','1d81ecc2aa'); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name">First name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder=""
                            value="<?=$user->first_name?>" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name">Last name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder=""
                            value="<?=$user->last_name?>" required>
                        <div class="invalid-feedback">
                            Valid last name is required.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="birthday">Birthday</label>
                        <input type="date" class="form-control" id="user_birthday" name="user_birthday" placeholder=""
                            value="<?= esc_attr($data['user_birthday'][0]) ?>" required>
                        <div class="invalid-feedback">
                            Enter date of birthday
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="region">Region</label>
                        <select id="user_region" name="user_region" required>
                            <option <?php selected( $region, "NA" ); ?> value="NA">NA</option>
                            <option <?php selected( $region, "EU" ); ?> value="EU">EU</option>
                            <option <?php selected( $region, "OCE" ); ?> value="OCE">OCE</option>
                        </select>
                        <div class="invalid-feedback">
                            Enter your current region
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username">Login</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" class="form-control" id="user_login" name="user_login"
                            value="<?=$user->user_login?>" placeholder="User login" required readonly>
                        <div class="invalid-feedback" style="width: 100%;">
                            Your username is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="user_email" name="user_email"
                        value="<?=$user->user_email?>" placeholder="you@example.com">
                    <div class="invalid-feedback">
                        Please enter a valid email address for shipping updates.
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-bio">Short Bio</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="3"><?=$user->description?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="passw1">Password</label>
                        <input type="text" class="form-control" id="passw1" name="passw1" placeholder="" required>
                        <div class="invalid-feedback">
                            Enter new password
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="passw2">Confirm password</label>
                        <input type="text" class="form-control" id="passw2" name="passw2" placeholder="" required>
                        <div class="invalid-feedback">
                            Confirm new password
                        </div>
                    </div>
                </div>
                <input type='hidden' id="region" name="region" value="<?=$region?>" />
                <input class="btn btn-primary btn-lg btn-block" type="submit" value="Save">
            </form>
        </div>

    </div>
</div>
<script type="text/javascript" language="javascript">
(function($) {
    $("#user_region").change(function() {
        var region = $('option:selected', this).val();
        $('#region').val(region);
    });
})(window.jQuery);
</script>