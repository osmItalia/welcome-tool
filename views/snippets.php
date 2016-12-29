<ul>
    <li><a href="<?php echo Flight::request()->base;?>/admin/snippets/insert">New snippet</a></li>
</ul>
<h2>Existing snippets</h2>
<table class="ui celled table">
<thead>
    <tr><th>Language</th><th>Part</th><th>Text</th><th>Action</th></tr>
</thead>
<tbody>
<?php foreach ($snippets as $snip) {
    $text = strlen($snip->text) > 50 ? substr($snip->text, 0, 50)."..." : $snip->text;
    echo "<tr><td>".$snip->language."</td><td>".$snip->part."</td><td>".$text."</td><td><a href='";
    echo Flight::request()->base."/admin/snippets/modify/".$snip->id."'>Modify</a> or <a href='";
    echo Flight::request()->base."/admin/snippets/delete/".$snip->id."'>Delete</a></td></tr>";
}
?>
</tbody>
</table>
