<?php
    $date = new DateTime();
?>
<div class="ui cards">
  <div class="card">
  <div class="content">
    <div class="header"><?php echo $user->username; ?></div>
  </div>
  <div class="content">
    <h4 class="ui sub header"><?php echo __('USER_FIRST_EDIT_DATA')?></h4>
    <div class="ui small feed">
      <div class="event">
        <div class="content">
          <div class="summary">
             <?php echo __('USER_REGISTRATION_DATE')?>: <?php
                echo $date->setTimestamp($user->registration_date)
                    ->format('d/m/Y H:i');
                ?>
          </div>
        </div>
      </div>
      <div class="event">
        <div class="content">
          <div class="summary">
             <?php echo __('USER_FIRST_EDIT_DATE')?>: <?php
                echo $date->setTimestamp($user->first_edit_date)
                    ->format('d/m/Y H:i');
                ?>
          </div>
        </div>
      </div>
      <div class="event">
        <div class="content">
          <div class="summary">
             <?php echo __('USER_FIRST_EDIT_LOCATION')?>: <?php
                echo $user->first_edit_location;
                ?>
          </div>
        </div>
      </div>
    </div>
    <h4 class="ui sub header"><?php echo __('USER_CURRENT_DATA')?></h4>
    <div class="ui small feed">
    <div class="event">
      <div class="content">
        <div class="summary">
           <a href="http://www.openstreetmap.org/user/<?php echo $user->username; ?>"><?php echo __('USER_OSM_PROFILE')?></a>
        </div>
      </div>
    </div>
    <div class="event">
      <div class="content">
        <div class="summary">
           <a href="http://www.openstreetmap.org/user/<?php echo $user->username; ?>/history"><?php echo __('USER_HISTORY')?></a>
        </div>
      </div>
    </div>
    <div class="event">
      <div class="content">
        <div class="summary">
           <a href="http://hdyc.neis-one.org/?<?php echo $user->username; ?>">HDYC</a>
        </div>
      </div>
    </div>
    <div class="event">
      <div class="content">
        <div class="summary">
           <a href="http://whosthat.osmz.ru/?id=<?php echo $user->user_id; ?>">Whosthat</a>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
<div class="card">
    <div class="content">
      <h4 class="ui sub header"><?php echo __('TITLE_ACTIONS')?></h4>
      <div class="ui small feed">
<?php if ($user->welcomed !== 1) { ?>
        <div class="event">
          <div class="content">
            <div class="summary">
                <a class="ui button" href="<?php echo Flight::get('base').'/welcome/'.$user->user_id?>"><?php echo __('USER_CREATE_WELCOME')?></a>
            </div>
          </div>
        </div>
        <?php
}?>
        <div class="event">
          <div class="content">
            <div class="summary">
               <a class="ui button" href="<?php echo Flight::get('base').'/note/'.$user->user_id?>"><?php echo __('TITLE_ADD_NOTE')?></a>
            </div>
          </div>
        </div>

        <div class="event">
          <div class="content">
            <div class="summary">
                    <?php if ($user->welcomed == 1) {?>
                        <div class="ui toggle checkbox checked welcome">
                           <input type="checkbox" name="chkWelcome" checked="">
                           <label id="welcomeLabel"><?php echo __('USER_WELCOMED_TOGGLE_TRUE')?> <?php echo $user->welcomed_by ?></label>
                         </div>
                    <?php } else {?>
                        <div class="ui toggle checkbox welcome">
                           <input type="checkbox" name="chkWelcome">
                           <label id="welcomeLabel"><?php echo __('USER_WELCOMED_TOGGLE_FALSE')?></label>
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
                       <label id="answerLabel"><?php echo __('USER_ANSWERED_TOGGLE_TRUE')?></label>
                     </div>
                <?php } else {?>
                    <div class="ui toggle checkbox answer">
                       <input type="checkbox" name="chkAnswer">
                       <label id="answerLabel"><?php echo __('USER_ANSWERED_TOGGLE_FALSE')?></label>
                     </div>
                <?php }?>
            </div>
          </div>
        </div>
<script>
$('.checkbox.welcome').checkbox().checkbox({
    onChecked: function () {
        $.post('<?php echo Flight::get('base')?>/user/<?php echo $user->user_id ?>/welcomed', {'isWelcomed': 1}, function(response) {
            $('#welcomeLabel').html('<?php echo __('USER_WELCOMED_TOGGLE_TRUE')?> <?php echo addcslashes($_SESSION['display_name'], "'")?>');
        });
    },
    onUnchecked: function () {
        $.post('<?php echo Flight::get('base')?>/user/<?php echo $user->user_id ?>/welcomed', {'isWelcomed': 0}, function(response) {
            $('#welcomeLabel').html('<?php echo __('USER_WELCOMED_TOGGLE_FALSE')?>');
        });
    }
});

$('.checkbox.answer').checkbox().checkbox({
    onChecked: function () {
        $.post('<?php echo Flight::get('base')?>/user/<?php echo $user->user_id ?>/answered', {'hasAnswered': 1}, function(response) {
            $('#answerLabel').html('<?php echo __('USER_ANSWERED_TOGGLE_TRUE')?>');
        });
    },
    onUnchecked: function () {
        $.post('<?php echo Flight::get('base')?>/user/<?php echo $user->user_id ?>/answered', {'hasAnswered': 0}, function(response) {
            $('#answerLabel').html('<?php echo __('USER_ANSWERED_TOGGLE_FALSE')?>');
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
            $icon = "quote left";
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
        <?php
        if ($note->type != "welcome") {
            echo $note->note;
        } else {
            echo "<span id='note".$note->timestamp."'>".$note->note."</span> (<a href='#' onclick='preview(\"note".$note->timestamp."\")'>".__('WELCOME_LABEL_PREVIEW')."</a>)";
        }?>
      </div>
    </div>
  </div>
<?php endforeach;?>
</div>
<div class="ui modal">
  <i class="close icon"></i>
  <div class="header">
    <?php echo __('WELCOME_TITLE_PREVIEW');?>
  </div>
  <div class="content" id="modalPreview">
  </div>
</div>
<script>
function preview(id) {
    var converter = new showdown.Converter(),
        iid       = '#'+id,
        text      = $(iid).text(),
        html      = converter.makeHtml(text);
    $('#modalPreview').html(html);
    $('.ui.modal').modal('show');
}
</script>
