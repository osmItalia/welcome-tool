<style>
.ui.divided.items>.snippet.item {
    border-radius: 5px;
    padding: 10px !important;
}

.ui.divided.items>.snippet.item:hover {
    background-color: #efe;
}
</style>

<div class="ui grid">
  <div class="eight wide column">
      <form action="<?php Flight::request()->base.Flight::request()->url?>" method="post" class="ui form">
        <div class="field">
          <label>Text</label>
          <textarea rows="12" id="message" name="message"></textarea>
        </div>
        <button class="ui primary button" type="submit">
          I sent this message
      </button>
      </form>
  </div>
  <div class="eight wide column">
      <select class="ui dropdown">
        <?php
        $mLang = Flight::get('ini')['mainLanguage'];
        foreach ($languages as $lang) : ?>
        <option value="<?php echo $lang->iso;?>" <?php echo ($mLang == $lang->iso) ? 'selected': ''?>><?php echo $lang->name;?></option>
        <?php endforeach; ?>
      </select>
      <div class="ui divided items" id="snippetList">

      </div>
  </div>
</div>
<script>
function appendSnippets(response) {
    $('#snippetList').empty();
    $.each(response, function (k, v) {
        var htmlString = '<div class="snippet item" ondblclick="appendThis(this)"><div class="label"><i class="recycle icon"></i></div><div class="content">';
        htmlString += '<a class="header">'+v['part']+'</a>';
        htmlString += '<div class="description">'+v['text']+'</div>';
        htmlString += '</div></div>';
        $('#snippetList').append(htmlString);
    });
}

$('.ui.dropdown')
  .dropdown({
      onChange: function (value) {
          $.getJSON('<?php echo Flight::request()->base?>/snippets/'+value, { }, appendSnippets);
      }
  });

$.getJSON('<?php echo Flight::request()->base?>/snippets/<?php echo $mLang;?>', { }, appendSnippets);

function appendThis(obj) {
    var txt = $(obj).find('div.description')[0].innerHTML;
    $('#message').append(txt+"\n");
};
</script>
