<table class="ui celled table selectable">
<thead>
<tr>
    <th><?php echo __('USER_ID')?></th>
    <th><?php echo __('USER_USERNAME')?></th>
    <th><div class="ui" data-tooltip="<?php echo __('USER_WELCOMED_TOOLTIP')?>"><?php echo __('USER_WELCOMED')?></div></th>
    <th><div class="ui" data-tooltip="<?php echo __('USER_ANSWERED_TOOLTIP')?>"><?php echo __('USER_ANSWERED_SHORT')?></div></th>
    <th><?php echo __('USER_LAST_NOTE')?></th>
    <th><?php echo __('USER_CHANGESETS_SHORT')?></th>
    <th><?php echo __('USER_FIRST_EDITOR')?></th>
    <th><?php echo __('USER_FIRST_EDIT_LOCATION')?></th>
</tr>
</thead>
<tbody>
<?php foreach ($results as $row) : ?>
<tr>
    <?php if (!isset($_SESSION['display_name'])) :?>
        <td><?php echo $row->user_id;?></td>
        <td><?php echo $row->username;?></td>
    <?php else :?>
        <td><a href="<?php echo Flight::get('base').'/user/'.$row->user_id ?>"><?php echo $row->user_id;?></a></td>
        <td><a href="<?php echo Flight::get('base').'/user/'.$row->user_id ?>"><?php echo $row->username;?></a></td>
    <?php endif;?>
    <?php
        $welcome = ($row->welcomed == 1) ? 'green checkmark' : 'red remove';
    ?>
    <td><i class="large <?php echo $welcome;?> icon"></i>&nbsp;<?php echo $row->welcomed_by;?></td>
    <?php
        $answered = ($row->answered == 1) ? 'green checkmark' : 'red remove';
    ?>
    <td><i class="large <?php echo $answered;?> icon"></i></td>
    <td><?php echo (strlen($row->note) > 150)? substr($row->note, 0, 150)."..." : $row->note;?></td>
    <td><?php echo ($row->total_changesets > 0)? $row->total_changesets: "";?></td>
    <td><?php echo $row->first_changeset_editor;?></td>
    <td><?php echo preg_replace("/\s\([0-9.]* km\)\s*\(\w*\)$/", "", $row->first_edit_location);?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<?php
if (isset($day)) {
    $date = DateTime::createFromFormat('Ymd', $day);
    $previous = $date->sub(new DateInterval('P1D'))->format('Ymd');
    $next = $date->add(new DateInterval('P2D'))->format('Ymd');

    $prev_link = '/day/'.($previous);
    $next_link = '/day/'.($next);
} else {
    $previous = $page-1;
    $next = $page+1;
    if ($previous < 1) {
        $previous = 1;
    }
    if ($function == 'byMe') {
        $bUrl = '/welcomedByMe/';
    } else {
        $bUrl = '/list/';
    }
    $prev_link = $bUrl.($previous);
    $next_link = $bUrl.($next);
}

?>
<tr><th colspan="9">
  <div class="ui right floated pagination menu">
    <a class="icon item" href="<?php echo Flight::get('base').$prev_link ?>">
      <i class="left chevron icon"></i>
    </a>
    <a class="icon item" href="<?php echo Flight::get('base').$next_link ?>">
      <i class="right chevron icon"></i>
    </a>
  </div>
</th>
</tr></tfoot>
</table>
