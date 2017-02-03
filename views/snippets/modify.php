<form action="<?php Flight::get('base').Flight::request()->url?>" method="post" class="ui form">
    <div class="field">
      <label><?php echo __('ADMIN_LANGUAGE');?></label>
      <select class="ui dropdown"  name="iso">
        <?php
        $mLang = $snippets->language;
        foreach ($languages as $lang) : ?>
        <option value="<?php echo $lang->iso;?>" <?php echo ($mLang == $lang->iso) ? 'selected': ''?>><?php echo $lang->name;?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label><?php echo __('ADMIN_PART');?></label>
      <input type="text" name="part" value="<?php echo $snippets->part;?>">
    </div>
    <div class="field">
      <label><?php echo __('WELCOME_LABEL_TEXT');?> (<a href="#" onclick="preview()"><?php echo __('WELCOME_LABEL_PREVIEW');?></a>)</label>
      <textarea id="text" name="text"><?php echo $snippets->text;?></textarea>
    </div>
    <button class="ui button" type="submit"><?php echo __('ADMIN_LINK_MODIFY');?></button>
</form>
<div class="ui modal">
  <i class="close icon"></i>
  <div class="header">
    <?php echo __('ADMIN_SNIPPET_PREVIEW');?>
  </div>
  <div class="content" id="modalPreview">
  </div>
</div>
<script>
function preview() {
    var converter = new showdown.Converter(),
        text      = $('#text').val(),
        html      = converter.makeHtml(text);
    $('#modalPreview').html(html);
    $('.ui.modal').modal('show');
}
</script>
