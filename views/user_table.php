<table class="ui celled table selectable">
<thead>
<tr>
    <th>UserID</th>
    <th>Username</th>
    <th>Welcomed?</th>
    <th>Welcomed by</th>
    <th>Answered?</th>
    <th>Last note</th>
    <th>First Editor</th>
</tr>
</thead>
<tbody>
<?php foreach ($results as $row) : ?>
<tr>
    <?php if (!isset($_SESSION['display_name'])) :?>
        <td><?php echo $row->user_id;?></td>
        <td><?php echo $row->username;?></td>
    <?php else :?>
        <td><a href="<?php echo Flight::request()->base.'/user/'.$row->user_id ?>"><?php echo $row->user_id;?></a></td>
        <td><a href="<?php echo Flight::request()->base.'/user/'.$row->user_id ?>"><?php echo $row->username;?></a></td>
    <?php endif;?>
    <?php
        $welcome = ($row->welcomed == 1) ? 'green checkmark' : 'red remove';
    ?>
    <td><i class="large <?php echo $welcome;?> icon"></i></td>
    <td><?php echo $row->welcomed_by;?></td>
    <?php
        $answered = ($row->answered == 1) ? 'green checkmark' : 'red remove';
    ?>
    <td><i class="large <?php echo $answered;?> icon"></i></td>
    <td><?php echo $row->note;?></td>
    <td><?php echo $row->first_changeset_editor;?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<?php
$date = DateTime::createFromFormat('Ymd', $day);
$previous = $date->sub(new DateInterval('P1D'))->format('Ymd');
$next = $date->add(new DateInterval('P2D'))->format('Ymd');
?>
<tr><th colspan="7">
  <div class="ui right floated pagination menu">
    <a class="icon item" href="<?php echo Flight::request()->base.'/day/'.($previous)?>">
      <i class="left chevron icon"></i>
    </a>
    <a class="icon item" href="<?php echo Flight::request()->base.'/day/'.($next)?>">
      <i class="right chevron icon"></i>
    </a>
  </div>
</th>
</tr></tfoot>
</table>
