<?php
global $post;
$age_requirement = get_post_meta($post->ID, 'age_requirement', true) . '+';
$team_type = get_the_terms($post->ID, 'teams-type');
$regions = get_the_terms($post->ID, 'valorant-server');
$ranks = get_the_terms($post->ID, 'rank-requirement');
?>
<div class="container">
    <div class="row">
        <h1 class="team-title"><?=$post->post_title?></h1>
    </div>
    <div class="row">
        <div class="col-md-4 order-md-1 mb-4">
            <!-- avatar upload-->
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
                        Add by user: <?php echo '<strong>'.$author->first_name.' '.$author->last_name.'</strong>';?>
                    </li>
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
        </div>
    </div>
    <div class="row">
        <div class="col-12 team description">
            <?php echo $post->post_content; ?>
        </div>
    </div>
</div>