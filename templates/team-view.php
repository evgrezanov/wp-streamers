<?php
?>
<div class="container">
    <div class="row">
        <h1 class="team-title"><?=$post->post_title?></h1>
    </div>
    <div class="row">
        <div class="col-md-4 order-md-1 mb-4">
            <div class="team-logo">
                <div class="team_avatar">
                    <?php echo $logo; ?>
                </div>
            </div>
        </div>
        <div class="col-md-8 order-md-2">
            <div class="team-properties">
                <ul>
                    <li>
                        <?php echo __('Add by user:', 'wp-streamers').' <strong>'.$author->first_name.' '.$author->last_name.'</strong>';?>
                    </li>
                    <li>
                        <?=__('Team type:', 'wp-streamers')?>
                        <?php foreach ($team_type as $type): ?>
                        <strong><?=$type->name?></strong>
                        <?php endforeach; ?>
                    </li>
                    <li><?=__('Region:', 'wp-streamers')?>
                        <?php foreach ($regions as $region): ?>
                        <strong><?=$region->name?></strong>
                        <?php endforeach; ?>
                    </li>
                    <li><?=__('Rank Requirements:', 'wp-streamers')?>
                        <?php foreach ($ranks as $rank): ?>
                        <strong><?=$rank->name?></strong>
                        <?php endforeach; ?>
                    </li>
                    <li><?=__('Age Requirement:', 'wp-streamers')?>
                        <strong><?=$age_requirement?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <h3><?=__('About team:', 'wp-streamers')?></h3>
        <div class="col-12 team description">
            <?php echo $post->post_content; ?>
        </div>
    </div>
</div>