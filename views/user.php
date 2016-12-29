<?php
    $date = new DateTime();
?>
<div class="ui cards">
  <div class="card">
  <div class="content">
    <div class="header"><?php echo $user->username; ?></div>
  </div>
  <div class="content">
    <h4 class="ui sub header">First edit data</h4>
    <div class="ui small feed">
      <div class="event">
        <div class="content">
          <div class="summary">
             Registration date: <?php
                echo $date->setTimestamp($user->registration_date)
                    ->format('d/m/Y H:i');
                ?>
          </div>
        </div>
      </div>
      <div class="event">
        <div class="content">
          <div class="summary">
             First edit date: <?php
                echo $date->setTimestamp($user->first_edit_date)
                    ->format('d/m/Y H:i');
                ?>
          </div>
        </div>
      </div>
    </div>
    <h4 class="ui sub header">Current data</h4>
    <div class="ui small feed">
    <div class="event">
      <div class="content">
        <div class="summary">
           <a href="<?php echo $user->username; ?>">OSM profile</a>
        </div>
      </div>
    </div>
    <div class="event">
      <div class="content">
        <div class="summary">
           <a href="<?php echo $user->username; ?>">HDYC</a>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
<div class="card">
    <div class="content">
      <h4 class="ui sub header">Actions</h4>
      <div class="ui small feed">
<?php if ($user->welcomed !== 1) { ?>
        <div class="event">
          <div class="content">
            <div class="summary">
                <a class="ui button" href="<?php echo Flight::request()->base.'/welcome/'.$user->user_id?>">Create a welcome message</a>
            </div>
          </div>
        </div>
        <?php
}?>
        <div class="event">
          <div class="content">
            <div class="summary">
               <a class="ui button" href="<?php echo Flight::request()->base.'/note/'.$user->user_id?>">Add a note</a>
            </div>
          </div>
        </div>

        <div class="event">
          <div class="content">
            <div class="summary">
                    <?php if ($user->welcomed == 1) {?>
                        <div class="ui toggle checkbox checked welcome">
                           <input type="checkbox" name="chkWelcome" checked="">
                           <label id="welcomeLabel">Welcomed by <?php echo $user->welcomed_by ?></label>
                         </div>
                    <?php } else {?>
                        <div class="ui toggle checkbox welcome">
                           <input type="checkbox" name="chkWelcome">
                           <label id="welcomeLabel">Not welcomed</label>
                         </div>
                    <?php }?>
            </div>
          </div>
        </div>

        <div class="event">
          <div class="content">
            <div class="summary">
                <?php if ($user->answered == 1) {?>
                    <div class="ui toggle checkbox checked answer">
                       <input type="checkbox" name="chkAnswer" checked="">
                       <label>Answered</label>
                     </div>
                <?php } else {?>
                    <div class="ui toggle checkbox answer">
                       <input type="checkbox" name="chkAnswer">
                       <label>Not answered</label>
                     </div>
                <?php }?>
            </div>
          </div>
        </div>
<script>
$('.checkbox.welcome').checkbox().checkbox({
    onChecked: function () {
        $.post('<?php echo Flight::request()->base?>/user/<?php echo $user->user_id ?>/welcomed', {'isWelcomed': 1}, function(response) {
            $('#welcomeLabel').html('Welcomed by <?php echo $_SESSION['display_name']?>');
        });
    },
    onUnchecked: function () {
        $.post('<?php echo Flight::request()->base?>/user/<?php echo $user->user_id ?>/welcomed', {'isWelcomed': 0}, function(response) {
            $('#welcomeLabel').html('Not welcomed');
        });
    }
});

$('.checkbox.answer').checkbox().checkbox({
    onChecked: function () {
        $.post('<?php echo Flight::request()->base?>/user/<?php echo $user->user_id ?>/answered', {'hasAnswered': 1}, function(response) {
        });
    },
    onUnchecked: function () {
        $.post('<?php echo Flight::request()->base?>/user/<?php echo $user->user_id ?>/answered', {'hasAnswered': 0}, function(response) {
        });
    }
});
</script>

      </div>
</div>
</div>
</div>

<div class="ui feed">
<?php foreach ($notes as $note) :?>
  <div class="event">
    <div class="label">
    <?php
    $icon = "pencil";
    switch ($note->type) {
        case 'note':
            $icon = "comments outline";
            break;
        case 'welcome':
            $icon = "home";
            break;
    }
    ?>
      <i class="<?php echo $icon;?> icon"></i>
    </div>
    <div class="content">
      <div class="date">
        <?php echo $date->setTimestamp($note->timestamp)->format('d/m/Y H:i'). " - ". $note->author;?>
      </div>
      <div class="summary">
        <?php echo $note->note;?>
      </div>
    </div>
  </div>
<?php endforeach;?>
</div>
