<form method="post" action="<?php echo Flight::get('base').Flight::request()->url?>">
<div class="ui form">
  <div class="field">
    <label>Text</label>
    <textarea rows="4" name="note"></textarea>
  </div>
  <button class="ui primary button" type="submit">
    Save
  </button>
</div>
</form>
