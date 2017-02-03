<form method="post" action="<?php echo Flight::get('base').Flight::request()->url?>">
<div class="ui form">
  <div class="field">
    <label><?php echo __('WELCOME_LABEL_TEXT');?></label>
    <textarea rows="4" name="note"></textarea>
  </div>
  <button class="ui primary button" type="submit">
    <?php echo __('NOTE_SAVE');?>
  </button>
</div>
</form>
