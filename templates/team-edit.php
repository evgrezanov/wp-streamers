<?php
?>
<div class="container">
    <div class="row">
        <?php do_action('display_notice', $content='single_team');?>
    </div>
    <div class="row">
        <div class="col-md-4 order-md-1 mb-4">
            <!-- logo upload-->
            <div class="team-logo">
                <div class="team_avatar">
                    <?php echo $logo; ?>
                </div>
                <button class="btn btn-primary btn-small" id="uppyModalOpenerTeamLogo"
                    data-user="<?php echo get_current_user_id(); ?>">
                    <?php echo __('Upload team logo', 'wp-streamers'); ?>
                </button>
            </div>
        </div>
        <div class="col-md-8 order-md-2">
            <form enctype="multipart/form-data" name="edit-team" method="post" id="edit-team" class="edit-team"
                class="needs-validation" novalidate>
                <!--_nonce-->
                <?php wp_nonce_field('9f9cf458e2','1d81ecc2aa'); ?>
                <!--Team name-->
                <div class="row">
                    <div class="col-12">
                        <label for="team-name"><?=__('Team name','wp-streamers')?></label>
                        <input style="width:100%;" type="text" class="form-control" id="team-name" name="team-name"
                            value="<?=$post->post_title?>" required>

                    </div>
                </div>
                <div class="row">
                    <!-- Team type -->
                    <label for="team-type"><?=__('Team type','wp-streamers')?>
                        <select class="form-control" id="team-type" name="team-type">
                            <?php foreach ($all_team_type as $type): ?>
                            <option <?=selected($team_type->term_id, $type->term_id)?> value="<?=$type->term_id?>">
                                <?=$type->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <!-- Region -->
                <label for="team-type"><?=__('Region','wp-streamers')?>
                    <select class="form-control" id="team-type" name="team-type">
                        <?php foreach ($all_region as $region): ?>
                        <option <?=selected($regions->term_id, $region->term_id)?> value="<?=$region->term_id?>">
                            <?=$region->name?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <!-- Rank Requirements: -->
                <label for="team-type"><?=__('Rank Requirements:','wp-streamers')?>
                    <select class="form-control" id="team-type" name="team-type">
                        <?php foreach ($all_ranks as $rank): ?>
                        <option <?=selected($ranks->term_id, $rank->term_id)?> value="<?=$rank->term_id?>">
                            <?=$rank->name?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <!-- Age Requirement: -->
                <label for="team-age-requirement"><?=__('Age Requirement:','wp-streamers')?>
                    <select class="form-control" id="tteam-age-requirement" name="team-age-requirement">

                        <?php foreach (WP_STREAMERS_TEAMS::$age_requirement_list as $key=>$value): ?>
                        <option <?=selected($key, $age_requirement)?> value="<?=$key?>">
                            <?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <!-- About: -->
                <div class="row">
                    <h3><?=__('About team:', 'wp-streamers')?></h3>
                    <textarea class="team-description" name="team-description" rows="5">
                        <?=esc_textarea($post->post_content)?></textarea>
                </div>
                <br>
                <input class="btn btn-primary btn-lg btn-block" type="submit" name="save_team_data" value="Update team">
            </form>
        </div>
    </div>

</div>