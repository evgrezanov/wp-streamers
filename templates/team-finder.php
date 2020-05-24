<?php?>
<table id="team-finder" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Team name</th>
            <th>Type</th>
            <th>Region</th>
            <th>Rank Requirements</th>
            <th>Age Requirement</th>
            <th>Positions required</th>
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
                $position_str='';
                foreach ($position_required as $key=>$value):
                    $position_str .= $value.', ';
                endforeach;
	        ?>
        <tr>
            <td><?=$team->post_title?></td>
            <td><?=$type[0]->name?></td>
            <td><?=$region[0]->name?></td>
            <td><?=$rank[0]->name?></td>
            <td><?=$age_requirement?>+</td>
            <td><?=$position_str?></td>
        </tr>
        <?php
            endforeach;
            ?>

    </tbody>
    <tfoot>
        <tr>
            <th>Team name</th>
            <th>Type</th>
            <th>Region</th>
            <th>Rank Requirements</th>
            <th>Age Requirement</th>
            <th>Positions required</th>
        </tr>
    </tfoot>
</table>