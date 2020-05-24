<?php

?>
<div id="teamUpdateResponse" class="response">
</div>
<div class="container">

    <div class="row">
        <div class="col-md-4 order-md-1 mb-4">
            <!-- logo upload-->
            <div class="team-logo">
                <div class="team_avatar">
                    <img id="team_logo_img" style="max-width:150px;" src="<?php echo $team_logo; ?>">
                </div>
                <button class="btn btn-primary btn-small" id="uppyModalOpenerTeamLogo"
                    data-team-id="<?php echo get_the_ID(); ?>">
                    <?php echo __('Upload team logo', 'wp-streamers'); ?>
                </button>
            </div>
        </div>
        <div class="col-md-8 order-md-2">
            <form method="post" id="streamer-edit-team" class="edit-team" class="needs-validation" novalidate>
                <!--_nonce-->
                <?php wp_nonce_field('9f9cf458e2','1d81ecc2aa'); ?>

                <!--Team name-->
                <div class="row">
                    <div class="col-12">
                        <h1><label for="team-name"><?=__('Team name','wp-streamers')?></label></h1>
                        <input style="width:100%;" type="text" class="form-control" id="team-name" name="team-name"
                            value="<?=$post->post_title?>" required>
                        <input type="text" name="team-id" hidden value="<?=the_id()?>" requered>
                    </div>
                </div>

                <!-- Type and region -->
                <div class="row">

                    <!-- Team type -->
                    <div class="col-md-6 mb-3">
                        <label for="team-type"><?=__('Team type','wp-streamers')?>
                            <select class="form-control" id="team-type" name="team-type">
                                <?php foreach ($all_team_type as $type): ?>
                                <option <?=selected($team_type[0]->term_id, $type->term_id)?>
                                    value="<?=$type->term_id?>">
                                    <?=$type->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <!-- Team Region -->
                    <div class="col-md-6 mb-3">
                        <!-- Region -->
                        <label for="team-region"><?=__('Region','wp-streamers')?>
                            <select class="form-control" id="team-region" name="team-region">
                                <?php foreach ($all_region as $region): ?>
                                <option <?=selected($regions[0]->term_id, $region->term_id)?>
                                    value="<?=$region->term_id?>">
                                    <?=$region->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                </div>

                <!-- Rank and Age-->
                <div class="row">

                    <!-- Rank Requirements: -->
                    <div class="col-md-6 mb-3">
                        <label for="team-rank-requirements"><?=__('Rank Requirements:','wp-streamers')?>
                            <select class="form-control" id="team-rank-requirements" name="team-rank-requirements">
                                <?php foreach ($all_ranks as $rank): ?>
                                <option <?=selected($ranks[0]->term_id, $rank->term_id)?> value="<?=$rank->term_id?>">
                                    <?=$rank->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <!-- Age Requirement: -->
                    <div class="col-md-6 mb-3">
                        <label for="team-age-requirement"><?=__('Age Requirement:','wp-streamers')?>
                            <select class="form-control" id="team-age-requirement" name="team-age-requirement">
                                <?php foreach (WP_STREAMERS_TEAMS::$age_requirement_list as $key=>$value): ?>
                                <option <?=selected($key, $age_requirement)?> value="<?=$key?>">
                                    <?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                </div>

                <!--Positions required-->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="team-positions-requered"><?=__('Positions required','wp-streamers')?></label>
                            <select class="form-control" tabindex="-98" data-header="Select a required positions"
                                id="team-positions-requered" name="team-positions-requered" multiple
                                data-actions-box="true">
                                <?php foreach (WP_STREAMERS_TEAMS::$streamer_preferred_agent as $key=>$value): ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="team-positions-requered-arr" name="team-positions-requered-arr"
                                value="">
                        </div>
                    </div>

                </div>
                <!-- About: -->
                <div class="row">
                    <div class="col-12">
                        <h3><?=__('About team:', 'wp-streamers')?></h3>
                        <textarea class="team-description" id="team-description" name="team-description"
                            rows="5"><?=$post->post_content?></textarea>
                    </div>
                </div>
                <br>

                <input type="submit" id="save_team_data" class="btn btn-primary btn-lg btn-block" name="save_team_data"
                    value="Update team">
            </form>
        </div>
    </div>

</div>