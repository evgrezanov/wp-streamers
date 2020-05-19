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
                <!--_nonce-->
                <?php wp_nonce_field('9f9cf458e2','1d81ecc2aa'); ?>

                <!--first & last name-->
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

                <!-- login-->
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

                <!--email-->
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="user_email" name="user_email"
                        value="<?=$user->user_email?>" placeholder="you@example.com">
                    <div class="invalid-feedback">
                        Please enter a valid email address for shipping updates.
                    </div>
                </div>

                <!-- password-->
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

                <!--volorant server-->
                <div class="mb-3">
                    <label for="streamer_valorant_server"><?=__('Valorant server', 'wp-streamers')?></label>
                    <select id="streamer_valorant_server" class="form-control" name="streamer_valorant_server" required>
                        <?php 
                            foreach ($valorant_server as $server): ?>
                        <option <?= selected $usermeta['streamer_valorant_server']?> value="<?=$server->term_id?>">
                            <?=$server->name?>
                        </option>
                        <?php    
                            endforeach;
                        ?>
                    </select>
                </div>

                <!--birthday-->
                <?=__('Valorant server', 'wp-streamers');?>
                <?php $bd = exploid('-',$usermeta['streamer_bday']); ?>
                <div class="row">
                    <div class="col-sm-4">
                        <select name="user_birthday_dd" class="sel-wd-birth-date select" data-placeholder="<?php
                        _e( 'DD', 'wp-streamers' ); ?>">
                            <option selected value="<?=$bd[0]?>"></option>
                            <?php
                            for ( $i = 1; $i < 32; $i++ ) {
                                echo '<option value="' . $i . '" ' . '>' .
                                        ( $i < 10 ? '0' . $i : $i ) .
                                        '</option>' . "\n";
                            }
                            unset($i);
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="user_birthday_mm" class="sel-wd-birth-month select" data-placeholder="<?php
                            _e( 'MM', 'wp-streamers' ); ?>">
                            <option selected value="<?=$bd[1]?>"></option>
                            <?php
                            for ( $i = 1; $i < 13; $i++ ) {
                                echo '<option value="' . $i . '" ' . '>' .
                                        date_i18n( 'F', mktime( 0, 0, 0, $i, 10 ) ) .
                                        '</option>' . "\n";
                            }
                            unset($i);
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="user_birthday_yy" class="sel-wd-birth-year select" data-placeholder="<?php
                        _e( 'YYYY', 'wp-streamers' ); ?>">
                            <option selected value="<?=$bd[2]?>"></option>
                            <?php
                            for ( $i = intval( date('Y') ); $i > 1930; $i-- ) {
                                echo '<option value="' . $i . '" ' . '>' .
                                        $i .
                                        '</option>' . "\n";
                            }
                            unset($i);
                            ?>
                        </select>
                    </div>
                </div>

                <!-- description-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-bio">Short Bio</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="3"><?=$user->description?></textarea>
                    </div>
                </div>
                <hr class="mb-4">
                <!--IGN-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-ign">IGN</label>
                        <textarea class="form-control" id="streamer-ign" name="streamer-ign"
                            rows="3"><?=$usermeta['streamer-ign']?></textarea>
                    </div>
                </div>

                <!--IGN number-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-ign-number">IGN number<small>(4 digit number)</small></label>
                        <input id="streamer-ign-number" type="text" maxlength="4" class="form-control" name="ign-number"
                            value="<?=$usermeta['streamer-ign-number']?>">
                    </div>
                </div>

                <!--Preferred Agent #1-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-pa1">Preferred Agent #1</label>
                        <select id="streamer-pa1" class="form-control" name="streamer-pa1" required>
                            <?php 
                        foreach ($preferred_agent as $key=>$value):
                        ?>
                            <option value="<?=$key?>"><?=$value?></option>
                            <?php
                        endforeach;
                        ?>
                        </select>
                    </div>
                </div>

                <!--Preferred Agent #2-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-pa2">Preferred Agent #2</label>
                        <select id="streamer-pa2" class="form-control" name="streamer-pa2">
                            <?php 
                        foreach ($preferred_agent as $key=>$value):
                        ?>
                            <option value="<?=$key?>"><?=$value?></option>
                            <?php
                        endforeach;
                        ?>
                        </select>
                    </div>
                </div>

                <!--Preferred Agent #3-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-pa3">Preferred Agent #3</label>
                        <select id="streamer-pa3" class="form-control" name="streamer-pa3">
                            <?php 
                        foreach ($preferred_agent as $key=>$value):
                        ?>
                            <option value="<?=$key?>"><?=$value?></option>
                            <?php
                        endforeach;
                        ?>
                        </select>
                    </div>
                </div>

                <!--Rank-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-rank">Rank</label>
                        <select id="streamer-rank" class="form-control" name="streamer-rank">
                            <?php 
                        foreach ($streamer_rank as $key=>$value):
                            ?>
                            <option value="<?=$key?>"><?=$value?></option>
                            <?php
                        endforeach; 
                        ?>
                        </select>
                    </div>
                </div>

                <!--Rank verification-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-rank-verification">Rank verification</label>
                        <button>Upload</button>
                    </div>
                </div>

                <!--Availability-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-availability">Availability</label>
                        <select id="streamer-availability" class="form-control" name="streamer-availability">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>



                <input class="btn btn-primary btn-lg btn-block" type="submit" name="save_personal_data" value="Save">
            </form>
        </div>

    </div>
</div>