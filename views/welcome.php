<style>
.ui.divided.items>.snippet.item {
    border-radius: 5px;
    padding: 10px !important;
}

.ui.divided.items>.snippet.item:hover {
    background-color: #efe;
}

.hidden_description {
    display: none;
}

.italic {
    font-style: italic;
}
</style>
<div class="ui grid">
    <p class="italic"><?php echo __('WELCOME_INSTRUCTIONS');?></p>
</div>
<div class="ui grid">
  <div class="eight wide column">
      <form action="<?php echo Flight::get('base').Flight::request()->url?>" method="post" class="ui form">
        <div class="field">
          <label><?php echo __('WELCOME_LABEL_TEXT');?> (<a href="#" onclick="preview()"><?php echo __('WELCOME_LABEL_PREVIEW');?></a>)</label>
          <textarea rows="12" id="message" name="message"
                    ondragover="event.preventDefault()"
                    ondrop='$("#message").val($("#message").val()+event.dataTransfer.getData("text/plain")+"\n")'
                    ></textarea>
        </div>
        <button class="ui primary button" type="submit">
          <?php echo __('WELCOME_BUTTON_SENT');?>
      </button>
      </form>
      <div class="ui modal">
        <i class="close icon"></i>
        <div class="header">
          <?php echo __('WELCOME_TITLE_PREVIEW');?>
        </div>
        <div class="content" id="modalPreview">
        </div>
      </div>
<script>
function preview() {
    var converter = new showdown.Converter(),
        text      = $('#message').val(),
        html      = converter.makeHtml(text);
    $('#modalPreview').html(html);
    $('.ui.modal').modal('show');
}
</script>
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
        var htmlString = '<div class="snippet item" draggable="true" ';
        htmlString += 'ondragstart="event.dataTransfer.setData(\'text/plain\', $(this).find(\'div.hidden_description\')[0].innerHTML)" ';
        htmlString += 'ondblclick="appendThis(this)"><div class="label"><i class="recycle icon"></i></div><div class="content">';
        htmlString += '<a class="header">'+v['part']+'</a>';
        htmlString += '<div class="description">'+v['text']+'</div>';
        htmlString += '<div class="hidden_description">'+v['hidden_text']+'</div>';
        htmlString += '</div></div>';
        $('#snippetList').append(htmlString);
    });
}

$('.ui.dropdown')
  .dropdown({
      onChange: function (value) {
          $.getJSON('<?php echo Flight::get('base')?>/snippets/'+value, { }, appendSnippets);
      }
  });

  $.getJSON('<?php echo Flight::get('base')?>/snippets/<?php echo $mLang;?>', { }, appendSnippets);
  function appendThis(obj) {
      var txt = $(obj).find('div.hidden_description')[0].innerHTML;
      $('#message').val($('#message').val()+txt+"\n");
  };

</script>
