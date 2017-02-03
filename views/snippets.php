<ul>
    <li><a href="<?php echo Flight::get('base');?>/admin/snippets/insert"><?php echo __('ADMIN_NEW_SNIPPET');?></a></li>
</ul>
<h2><?php echo __('ADMIN_EXISTING_SNIPPETS');?></h2>
<table class="ui celled table">
<thead>
    <tr><th><?php echo __('ADMIN_LANGUAGE');?></th><th><?php echo __('ADMIN_PART');?></th><th><?php echo __('WELCOME_LABEL_TEXT');?></th><th><?php echo __('ADMIN_LABEL_ACTION');?></th></tr>
</thead>
<tbody>
<?php foreach ($snippets as $snip) {
    $text = strlen($snip->text) > 50 ? substr($snip->text, 0, 50)."..." : $snip->text;
    echo "<tr><td>".$snip->language."</td><td>".$snip->part."</td><td>".$text."</td><td><a href='";
    echo Flight::get('base')."/admin/snippets/modify/".$snip->id."'>".__('ADMIN_LINK_MODIFY')."</a> ".__('ADMIN_OR')." <a href='";
    echo Flight::get('base')."/admin/snippets/delete/".$snip->id."'>".__('ADMIN_LINK_DELETE')."</a></td></tr>";
}
?>
</tbody>
</table>
