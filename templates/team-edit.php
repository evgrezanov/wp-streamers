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
                <label for="team-name"><?=__('Team name','wp-streamers')?>
                    <input type="text" class="form-control" id="team-name" name="team-name"
                        value="<?=$post->post_title?>" required>
                </label>
                <!-- Team type -->
                <label for="team-type"><?=__('Team type','wp-streamers')?>
                    <select class="form-control" id="team-type" name="team-type">
                        <?php foreach ($team_type as $type): ?>
                        <option <?=selected($type->term_id, $key )?> value="<?=$key?>">
                            <?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <!-- About team -->
                <label for="team-description"><?=__('About team','wp-streamers')?>
                    <textarea id="team-description" class="form-control" name="team-description">
                        <?=$post->post_content?>
                    </textarea>
                </label>
                <!-- Region -->
                <label for="team-type"><?=__('Team type','wp-streamers')?>
                    <select class="form-control" id="team-type" name="team-type">
                        <?php foreach ($team_type as $type): ?>
                        <option <?=selected($type->term_id, $key )?> value="<?=$key?>">
                            <?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <!-- Rank Requirements: -->
                <label for="team-type"><?=__('Team type','wp-streamers')?>
                    <select class="form-control" id="team-type" name="team-type">
                        <?php foreach ($team_type as $type): ?>
                        <option <?=selected($type->term_id, $key )?> value="<?=$key?>">
                            <?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <!-- Age Requirement: -->
                <div class="team-properties">
                    <ul>
                        <li>
                            Team type:
                            <?php foreach ($team_type as $type): ?>
                            <strong><?=$type->name?></strong>
                            <?php endforeach; ?>
                        </li>
                        <li>Region:
                            <?php foreach ($regions as $region): ?>
                            <strong><?=$region->name?></strong>
                            <?php endforeach; ?>
                        </li>
                        <li>Rank Requirements:
                            <?php foreach ($ranks as $rank): ?>
                            <strong><?=$rank->name?></strong>
                            <?php endforeach; ?>
                        </li>
                        <li>Age Requirement: <strong><?=$age_requirement?></strong></li>
                    </ul>
                </div>
                <div class="row">
                    <h3><?=__('About team:', 'wp-streamers')?></h3>
                    < div class="col-12 team description">
                        <?php echo $post->post_content; ?>
                </div>
                <input class="btn btn-primary btn-lg btn-block" type="submit" name="save_team_data" value="Update team">
            </form>
        </div>
    </div>

</div>