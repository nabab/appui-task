<div class="appui-full-height" style="width: 100%">
  <div id="appui_task_tabnav"></div>
</div>
<script type="text/x-kendo-template" id="tpl-task_tab_main">
  <input type="hidden" name="ref" data-bind="value: ref">
  <div class="appui-line-breaker appui-task-form-container k-content">

    <div class="appui-form-full">
      <input name="title" class="k-textbox appui-lg title" placeholder="Title/short description of the new task" required="required" data-bind="value: title">
    </div>

    <div class="appui-form-label"><?=_("Category")?></div>
    <div class="appui-form-field">
      <input name="type" style="width: inherit" required="required" data-bind="value: type">
    </div>

    <div class="appui-form-label"><?=_("Priority")?></div>
    <div class="appui-form-field">
    	<div class="appui-form-label">
        <div class="appui-block">
          <select data-role="dropdownlist" data-bind="value: priority" name="priority">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5" selected>5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
          </select>
        </div>
		  </div>
      <div class="appui-form-field">
        <div class="appui-form-label"><?=_("Deadline")?></div>
        <div class="appui-form-field">
          <input type="date" data-role="datepicker" name="target_date" data-bind="value: target_date">
        </div>
      </div>
    </div>

    <div class="appui-form-label"> </div>
    <div class="appui-form-field appui-p" onclick="$(this).next().toggle().redraw()">
    	<i class="fa fa-edit"> </i> &nbsp; <?=_("Add a comment")?>
  	</div>

    <div class="appui-form-full appui-task-form-adder">
      <div class="appui-form-label">
        <?=_("Comment")?><br>
        <select class="comment_type" data-role="dropdownlist" data-bind="events: {change: change_comment_type}">
          <option value="text"><?=_("Simple text")?></option>
          <option value="html"><?=_("Rich text")?></option>
          <option value="gfm"><?=_("Markdown")?></option>
          <option value="php"><?=_("PHP code")?></option>
          <option value="js"><?=_("JavaScript code")?></option>
          <option value="css"><?=_("CSS code")?></option>
        </select><br>
      </div>
      <div class="appui-form-field">
        <textarea class="k-textbox" name="comment" style="width: 100%"></textarea>
      </div>

      <div class="appui-form-label"><?=_("Files")?></div>
      <div class="appui-form-field">
        <div class="appui-task-upload-wrapper appui-task-files-container"> </div>
      </div>

      <div class="appui-form-label"><?=_("Links")?></div>
      <div class="appui-form-field">
        <div class="k-widget k-upload k-header">
          <div class="k-dropzone">
            <input type="text" name="link" class="k-textbox" style="width: 100%" placeholder="<?=_("Type or paste your URL and press Enter to valid")?>">
          </div>
          <table class="k-upload-files appui-task-links-container">
          </table>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div class="appui-line-breaker appui-task-comments" data-bind="source: notes" data-template="tpl-appui_task_comment"></div>
</script>
<script type="text/x-kendo-template" id="tpl-appui_task_comment">
	<div data-bind="html: content" class="ui message"></div>
</script>
