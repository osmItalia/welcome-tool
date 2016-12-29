<form action="<?php Flight::request()->base.Flight::request()->url?>" method="post" class="ui form">
    <div class="field">
      <label>Language</label>
      <select class="ui dropdown"  name="iso">
        <?php
        $mLang = $snippets->language;
        foreach ($languages as $lang) : ?>
        <option value="<?php echo $lang->iso;?>" <?php echo ($mLang == $lang->iso) ? 'selected': ''?>><?php echo $lang->name;?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>Part</label>
      <input type="text" name="part" value="<?php echo $snippets->part;?>">
    </div>
    <div class="field">
      <label>Text</label>
      <textarea name="text"><?php echo $snippets->text;?></textarea>
    </div>
    <button class="ui button" type="submit">Modify</button>
</form>
