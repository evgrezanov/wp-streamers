<?php
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="streamerSettingsResponse"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 order-md-1 mb-4">
            <!-- avatar upload-->
            <div class="wp-streamers-photo">
                <div class="streamer_avatar">
                    <img id="streamer_img" style="max-width:150px;" src="<?php echo $img; ?>">
                </div>
                <button class="btn btn-primary btn-small" id="uppyModalOpener"
                    data-user="<?php echo get_current_user_id(); ?>">
                    <?php echo __('Upload photo', 'wp-streamers'); ?>
                </button>
            </div>

            <!--Rank verification-->
            <div class="wp-streamers-rank-verification">
                <div class="rank-verification">
                    <img id="rank_verification_img" style="max-width:150px;" src="<?php echo $rank_img; ?>">
                </div>
                <button class="btn btn-primary btn-small" id="uppyModalOpenerRankVerify"
                    data-user="<?php echo get_current_user_id(); ?>">
                    <?php echo __('Rank verification', 'wp-streamers'); ?>
                </button>
            </div>
        </div>

        <!--personal data-->
        <div class="col-md-8 order-md-2">
            <form method="post" id="streamer-edit-profile" class="streamer-edit-profile">
                <!--_nonce-->
                <?php wp_nonce_field('9f9cf458e2','1d81ecc2aa'); ?>

                <!--first & last name-->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name">First name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder=""
                            value="<?=$user->first_name?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name">Last name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder=""
                            value="<?=$user->last_name?>" required>
                    </div>
                </div>

                <!--login & email-->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username">Login</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@</span>
                            </div>
                            <input type="text" class="form-control" id="user_login" name="user_login"
                                value="<?=$user->user_login?>" placeholder="User login" required readonly>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email"
                            value="<?=$user->user_email?>" placeholder="you@example.com">
                    </div>
                </div>

                <!--server & bio-->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="streamer_valorant_server"><?=__('Valorant server', 'wp-streamers')?></label>
                        <select id="streamer_valorant_server" class="form-control" name="streamer_valorant_server"
                            required>
                            <?php 
                            $current_server = get_term_by( 'id', $usermeta['streamer_valorant_server'][0], 'valorant-server');
                                foreach ($valorant_server as $server): 
                                    ?>
                            <option <?=selected($current_server->term_id, $server->term_id )?>
                                value="<?=$server->term_id?>">
                                <?=$server->name?></option>
                            <?php    
                                endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="streamer-bio">Short Bio</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="3"><?=esc_textarea($user->description)?></textarea>
                        </div>
                    </div>
                </div>


                <!-- password-->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="passw1">Password</label>
                        <input type="text" class="form-control" id="passw1" name="passw1" placeholder="">
                        <div class="invalid-feedback">
                            Enter new password
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="passw2">Confirm password</label>
                        <input type="text" class="form-control" id="passw2" name="passw2" placeholder="">
                        <div class="invalid-feedback">
                            Confirm new password
                        </div>
                    </div>
                </div>

                <!--birthday-->
                <?=__('Date of birthday', 'wp-streamers');?>
                <?php $bd = explode('-', $usermeta['streamer_bday'][0]); ?>
                <div class="row">
                    <div class="col-sm-4">
                        <select name="user_birthday_dd" id="user_birthday_dd" class="sel-wd-birth-date select">
                            <option selected value="<?=$bd[0]?>"><?=$bd[0]?></option>
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
                        <select name="user_birthday_mm" id="user_birthday_mm" class="sel-wd-birth-month select">
                            <option selected value="<?=$bd[1]?>"><?=date_i18n( 'F', mktime( 0, 0, 0, $bd[1], 10 ) )?>
                            </option>
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
                        <select name="user_birthday_yy" id="user_birthday_yy" class="sel-wd-birth-year select">
                            <option selected value="<?=$bd[2]?>"><?=$bd[2]?></option>
                            <?php
                            for ( $i = intval( date('Y') ); $i > 1970; $i-- ) {
                                echo '<option value="' . $i . '" ' . '>' .
                                        $i .
                                        '</option>' . "\n";
                            }
                            unset($i);
                            ?>
                        </select>
                    </div>
                </div>

                <hr class="mb-4">

                <!--IGN-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-ign">IGN</label>
                        <textarea class="form-control" id="streamer-ign" name="streamer-ign"
                            rows="3"><?=esc_attr($usermeta['streamer-ign'][0])?></textarea>
                    </div>
                </div>

                <!--IGN number-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-ign-number">IGN number<small>(4 digit number)</small></label>
                        <input id="streamer-ign-number" type="text" maxlength="4" class="form-control"
                            name="streamer-ign-number" value="<?=esc_attr($usermeta['streamer-ign-number'][0])?>">
                    </div>
                </div>

                <!--Preferred Agent #1-->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label
                                for="streamer-preferred-agent"><?=__('Preferred Agent (maximum 3 item)','wp-streamers')?></label>
                            <select class="form-control" tabindex="-98"
                                data-header="<?=__('Select a preferred agent (max 3 items)','wp-streamers')?>"
                                id="streamer-preferred-agent" name="streamer-preferred-agent" multiple
                                data-actions-box="true" data-max-options="3">
                                <?php foreach (WP_STREAMER_SETTINGS::$streamer_preferred_agent as $key=>$value): ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="streamer-preferred-agent-arr" name="streamer-preferred-agent-arr"
                                value="">
                        </div>
                    </div>
                </div>

                <!--Rank-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-rank">Rank</label>
                        <select id="streamer-rank" class="form-control" name="streamer-rank">
                            <?php 
                            isset($usermeta['streamer-rank'][0]) ? $rank = $usermeta['streamer-rank'][0] : $rank='';
                            foreach ($streamer_rank as $key=>$value):
                            ?>
                            <option <?=selected($usermeta['streamer-availability'][0], $key )?> value="<?=$key?>">
                                <?=$value?></option>
                            <?php
                        endforeach; 
                        ?>
                        </select>
                    </div>
                </div>

                <!--Availability-->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="streamer-availability">Availability</label>
                        <select id="streamer-availability" class="form-control" name="streamer-availability">
                            <option <?= selected($usermeta['streamer-availability'][0], 'yes' ) ?> value="yes">Yes
                            </option>
                            <option <?= selected($usermeta['streamer-availability'][0], 'no' ) ?>value="no">No</option>
                        </select>
                    </div>
                </div>

                <input class="btn btn-primary btn-lg btn-block" type="submit" name="save_personal_data" value="Save">
            </form>
        </div>
    </div>
</div>