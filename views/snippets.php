<h2>Add a snippet</h2>
<form action="<?php Flight::request()->base.Flight::request()->url?>" method="post" class="ui form">
    <div class="field">
      <label>Language</label>
      <input type="text" name="iso" placeholder="it">
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
<h2>Existing snippets</h2>
<table class="ui celled table">
<thead>
    <tr><th>Language</th><th>Part</th><th>Text</th><th>Action</th></tr>
</thead>
<tbody>
<?php foreach ($snippets as $snip) {
    $text = strlen($snip->text) > 50 ? substr($snip->text, 0, 50)."..." : $snip->text;
    echo "<tr><td>".$snip->language."</td><td>".$snip->part."</td><td>".$text."</td><td><a href='";
    echo Flight::request()->base."/admin/snippets/delete/".$snip->id."'>Delete</a></td></tr>";
}
?>
</tbody>
</table>
