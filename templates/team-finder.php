<?php
//https://agora-file-storage-prod.s3.us-west-1.amazonaws.com/workplace/attachment/7378606440264422621?response-content-disposition=inline%3B%20filename%3D%22specifications.PNG%22%3B%20filename%2A%3Dutf-8%27%27specifications.PNG&X-Amz-Security-Token=IQoJb3JpZ2luX2VjECYaCXVzLXdlc3QtMSJGMEQCIGSxyVLQOS%2FiA9cGmymwqFUivOczdDN8CPLVhOzS8AFGAiASaCfDOKhmPWGxPWmisnowAb1h5KHv7wKDBET27fwiFSqQBAh%2BEAAaDDczOTkzOTE3MzgxOSIMwqIgDj%2BNtU1%2BUyqkKu0DcJiiYWOEwDypC%2Bbs2QZ9zj1JwtDOVdbvRfFpWGJ8buoUtPQ4XSFQ%2BTosXY37eb%2Bl%2BISRP1Zm8YrFbr5Ma1YhXwphk0l1rOlKpbYZE7jWEiJnTbATqS%2BuUV1CF1ToLo7Xby3kpv%2F31K%2FzaUHpC6jcz3EzXnyzudgmjxzfgxep6%2BjdjnAQN6NCG2nvTjRF9kXuY6fnqkoMDMS%2FBZLnqDFr83DjXdytORAACIeBOkFHhKCQzDVbRXKDLDsnTlKFkjz50TbALbCWXWs%2BBNmdIstSJcMx449ucquImY%2BrzDZPo5kWWg3EpcV7g9XPdZ8jmaIX%2B%2BN00RraqyLA4J4pSMN1ufLI2F3clBLoMZlbgyFeIMxKJaoewNC3YiVbeDyE0OTq41pNNh0XL8ZOBKGKHaGDm5tQ7eOoCDQlDpKT%2BX%2BPPrqyxgUXoLHGrxqkzrpIyOiA%2FWC8tMj%2Fg363zfzHTJqakYrAMPUsC8Ut%2FpVwNqKETtjbtPDnAQnn2rNP64HyUJ2kAB6mh6ivP%2B2q1JywMh7lyC660qkIcEObIHFJxtGuxD9NXzvlgOY5oyUcDky51HK%2FXpZfm3VpbLwHOXhgdchfYcEfb8Ho0Xo1WibFbWWT3cZ%2FI9Bcjalb8CZbf5iQk%2F0g3hNChUNoWAF4UvHvBTD90av2BTrwAdI%2FnNqkCDq%2FJaKKKTy9CbcKYgPE5DVS5H4XR0uuoKIfDBmQvBT%2BVQJl%2BvysilIM3Fofiu1nu6KLHZtt4sGISYraeqjO4fc%2FgXtED7uor8mUEEd3L39D355SfYyfGoAOALklLqa8mc4JB2f9GR4zL3T5OJALAxUc3BglSMGQG30p89hyJnpZ5160IIRLHkVhr4ja7esgft%2FdvHAF1%2FZbzmneUpWD%2ByscaJ5nRHH2BBMfX2P9Ug7JOAYdYAwQ%2Fcm2LxSDw3IRcYhGXxRP3r%2B%2FXbRFjbIjUts16mbzQEXIRrz4Yi3UDlDcOr5Bf0RRm2SIug%3D%3D&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20200524T233022Z&X-Amz-SignedHeaders=host&X-Amz-Expires=599&X-Amz-Credential=ASIA2YR6PYW5V2UW4PZT%2F20200524%2Fus-west-1%2Fs3%2Faws4_request&X-Amz-Signature=3ff1c814f7f26af03fd5671c9c1f2874fcbb831fc30ece20d89036fd6d9bf535
?>
<div class="row">
    <div class="col-12">
        <div class="float-left">
            <form>
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="team-type" class="sr-only">Team type</label>
                        <select class="form-control" id="team-type" name="team-type">
                            <?php 
                    foreach ($all_team_type as $type): ?>
                            <option value="<?=$type->term_id?>"><?=$type->name?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="team-type" class="sr-only">Region</label>
                        <select class="form-control" id="team-region" name="team-type">
                            <?php 
                    foreach ($all_region as $region): ?>
                            <option value="<?=$region->term_id?>"><?=$region->name?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="team-type" class="sr-only">Rank</label>
                        <select class="form-control" id="team-region" name="team-type">
                            <?php 
                    foreach ($all_ranks as $rank): ?>
                            <option value="<?=$rank->term_id?>"><?=$rank->name?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="team-age_requirement" class="sr-only">Age</label>
                        <select class="form-control" id="team-age_requirement" name="team-age_requirement">
                            <?php 
                    foreach ($ages as $key=>$value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                            <?php 
                    endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="team-preferred-agent" class="sr-only">Agents</label>
                        <select class="form-control" id="team-preferred-agent" name="team-preferred-agent">
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
                        <button class="btn btn-primary" type="button">
                            <?=__('Find team', 'wp-streamer')?>
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse"
                            data-target="#collapseAddNewTeam" aria-expanded="false" aria-controls="collapseAddNewTeam">
                            <?=__('Add new team', 'wp-streamer')?>
                        </button>
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
                foreach ($position_required as $key=>$value):
                    $position_str .= '<span class="badge badge-dark">'.$value.'</span> ';
                endforeach;
	        ?>
            <tr>
                <td><img class="team_finder_team_logo" style="max-width:50px;" src="<?php echo $team_logo; ?>"></td>
                <td><?=$team->post_title?></td>
                <td><?=$type[0]->name?></td>
                <td><?=$region[0]->name?></td>
                <td><?=$rank[0]->name?></td>
                <td><?=$age_requirement?>+</td>
                <td><?=$position_str?></td>
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
                <th>Name</th>
                <th>Type</th>
                <th>Region</th>
                <th>Rank </th>
                <th>Age</th>
                <th>Position</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>