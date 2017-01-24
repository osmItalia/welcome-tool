<h2>Add a language</h2>
<form action="<?php echo Flight::get('base').'/admin/languages' ?>" method="post" class="ui form">
    <div class="field">
      <label>ISO Code</label>
      <input type="text" name="iso" placeholder="it">
    </div>
    <div class="field">
      <label>Name</label>
      <input type="text" name="name" placeholder="Italian">
    </div>
    <button class="ui button" type="submit">Submit</button>
</form>
<h2>Existing languages</h2>
<table class="ui celled table">
<thead>
    <tr><th>ISO code</th><th>Name</th><th>Action</th></tr>
</thead>
<tbody>
<?php foreach ($languages as $langs) {
    echo "<tr><td>".$langs->iso."</td><td>".$langs->name."</td><td><a href='";
    echo Flight::get('base')."/admin/languages/delete/".$langs->iso."'>Delete</a></td></tr>";
}
?>
</tbody>
</table>
