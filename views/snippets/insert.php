<form action="<?php Flight::request()->base.Flight::request()->url?>" method="post" class="ui form">
    <div class="field">
      <label>Language</label>
      <select class="ui dropdown"  name="iso">
        <?php
        foreach ($languages as $lang) : ?>
        <option value="<?php echo $lang->iso;?>"><?php echo $lang->name;?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>Part</label>
      <input type="text" name="part" placeholder="header">
    </div>
    <div class="field">
      <label>Text</label>
      <textarea name="text"></textarea>
    </div>
    <button class="ui button" type="submit">Submit</button>
</form>
