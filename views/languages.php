<h2><?php echo __('ADMIN_LANGUAGE_TITLE')?></h2>
<form action="<?php echo Flight::get('base').'/admin/languages' ?>" method="post" class="ui form">
    <div class="field">
      <label><?php echo __('ADMIN_LABEL_ISO')?></label>
      <input type="text" name="iso" placeholder="it">
    </div>
    <div class="field">
      <label><?php echo __('ADMIN_LABEL_NAME')?></label>
      <input type="text" name="name" placeholder="Italian">
    </div>
    <button class="ui button" type="submit"><?php echo __('ADMIN_BUTTON_SUBMIT')?></button>
</form>
<h2><?php echo __('ADMIN_EXISTING_LANGUAGE_TITLE')?></h2>
<table class="ui celled table">
<thead>
    <tr><th><?php echo __('ADMIN_LABEL_ISO')?></th><th><?php echo __('ADMIN_LABEL_NAME')?></th><th><?php echo __('ADMIN_LABEL_ACTION')?></th></tr>
</thead>
<tbody>
<?php foreach ($languages as $langs) {
    echo "<tr><td>".$langs->iso."</td><td>".$langs->name."</td><td><a href='";
    echo Flight::get('base')."/admin/languages/delete/".$langs->iso."'>".__('ADMIN_LINK_DELETE')."</a></td></tr>";
}
?>
</tbody>
</table>
