<?php
?>
<div class="row">
    <div class="col-12">
        <div id="teamFinderAddResponse">
        </div>
        <div>
            <form id="team-finder-add-new" type="post">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <h8>Team type</h8>
                        <select class="form-control" id="team-type" name="team-type">
                            <option value></option>
                            <?php 
                    foreach ($all_team_type as $type): ?>
                            <option value="<?=$type->term_id?>"><?=$type->name?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <h8>Region</h8>
                        <select class="form-control" id="team-region" name="team-region">
                            <option value></option>
                            <?php 
                    foreach ($all_region as $region): ?>
                            <option value="<?=$region->term_id?>"><?=$region->name?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <h8>Rank</h8>
                        <select class="form-control" id="team-rank" name="team-rank">
                            <option value></option>
                            <?php 
                    foreach ($all_ranks as $rank): ?>
                            <option value="<?=$rank->term_id?>"><?=$rank->name?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <h8>Age</h8>
                        <select class="form-control" id="team-age-requirement" name="team-age-requirement">
                            <option value></option>
                            <?php 
                    foreach ($ages as $key=>$value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <h8>Agents</h8>
                        <select class="form-control" id="team-agent" name="team-agent">
                            <option value></option>
                            <?php 
                    foreach ($agents as $key=>$value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="team-name" name="team-name" value="" required>
                    </div>
                    <div class="col-auto">
                        <input type="hidden" id="user-id" name="user-id"
                            value="<?php echo is_user_logged_in() ? get_current_user_id() : ''?>">
                        <input class="btn btn-primary" id="add-new-team-finder" type="submit"
                            value="<?=__('Add new team', 'wp-streamer')?>">
                        <input class="btn btn-primary" id="clear-filter-team-finder" type="submit"
                            value="<?=__('Clear filter', 'wp-streamer')?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <table id="team-finder" class="display" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th><?=__('Team name', 'wp-streamer')?></th>
                <th><?=__('Type', 'wp-streamer')?></th>
                <th><?=__('Region', 'wp-streamer')?></th>
                <th><?=__('Rank', 'wp-streamer')?></th>
                <th><?=__('Age', 'wp-streamer')?></th>
                <th><?=__('Position', 'wp-streamer')?></th>
                <th><?=__('Date', 'wp-streamer')?></th>
                <th><?=__('Status', 'wp-streamer')?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach( $teams as $team ):
                $type = get_the_terms($team->ID, 'teams-type');
                $region = get_the_terms($team->ID, 'valorant-server');
                $rank = get_the_terms($team->ID, 'rank-requirement');
                $age_requirement = get_post_meta($team->ID, 'age_requirement', true);
                $position_required = get_post_meta($team->ID, 'position_required', true);
                $team_logo = UPPY_AVATAR::get_team_logo($team->ID,'tumbnail');
                $position_str='';
                if (is_array($position_required)):
                    foreach ($position_required as $key=>$value):
                        $position_str .= '<span class="badge badge-dark">'.$value.'</span> ';
                    endforeach;
                endif;
	        ?>
            <tr>
                <td><img class="team_finder_team_logo" style="max-width:50px;" src="<?php echo $team_logo; ?>"></td>
                <td><?=$team->post_title?></td>
                <td><?=$type[0]->name?></td>
                <td><?=$region[0]->name?></td>
                <td><?=$rank[0]->name?></td>
                <td><?=$age_requirement?>+</td>
                <td><?=$position_str?></td>
                <td><?=get_the_date('d/m/Y H:i', $team->ID)?></td>
                <td><?php echo $team->post_status != 'publish' ?  '<span class="badge badge-secondary">draft</span>' :'<span class="badge badge-success">verified</span>';?>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"><?=__('Send invite', 'wp-streamer')?></button>
                    <a type="button" class="btn btn-info btn-sm"
                        href="<?=get_permalink($team->ID)?>"><?=__('More info', 'wp-streamer')?></a>
                </td>
            </tr>
            <?php
            endforeach;
            ?>

        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th><?=__('Team name', 'wp-streamer')?></th>
                <th><?=__('Type', 'wp-streamer')?></th>
                <th><?=__('Region', 'wp-streamer')?></th>
                <th><?=__('Rank', 'wp-streamer')?></th>
                <th><?=__('Age', 'wp-streamer')?></th>
                <th><?=__('Position', 'wp-streamer')?></th>
                <th><?=__('Date', 'wp-streamer')?></th>
                <th><?=__('Status', 'wp-streamer')?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>