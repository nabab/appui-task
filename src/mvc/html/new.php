<!-- HTML Document -->

<form action="<?=$root?>/insert">
  <input type="hidden" name="ref" data-bind="value: ref">
  <div class="appui-line-breaker" id="bbn_pm_form_container">

    <div class="appui-form-full">
      <input name="title" class="k-textbox appui-l title" placeholder="Title/short description of the new task" required="required">
    </div>

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

    <div class="appui-form-label"><?=_("Category")?></div>
    <div class="appui-form-field">
      <input name="type" style="width: 100%" required="required">
    </div>

    <div class="appui-form-label"><?=_("Priority")?></div>
    <div class="appui-form-field">
      <select data-role="dropdownlist">
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

    <div class="appui-form-label"><?=_("Deadline")?></div>
    <div class="appui-form-field">
      <input type="date" data-role="datepicker">
    </div>

    <div class="appui-form-label"><?=_("Files")?></div>
    <div class="appui-form-field">
      <input type="file" name="file">
    </div>
    <div class="appui-nl bbn-task-files-container"> </div>

    <div class="appui-form-label"><?=_("Links")?></div>
    <div class="appui-form-field">
      <input type="text" name="link" class="k-textbox" value="http://" style="width: 90%; margin-right: 1em">
      <button class="k-button appui-task-link-button">
        <i class="fa fa-check"> </i>
      </button>
    </div>
    <div class="appui-nl bbn-task-links-container"> </div>

    <div class="appui-form-full">
      <div class="k-block">
        <div class="k-header appui-l"><div><?=_("Roles")?></div></div>
        <div class="k-content appui-task-roles-container">
          <div class="k-block appui-task-assigned">
            <div class="k-header"><?=_("Manager")?></div>
            <div class="k-content appui-task-managers">
              <input type="hidden" name="managers">
              <ul data-role="treeview"></ul>
            </div>
          </div>
          <div class="appui-spacer"> </div>
          <div class="k-block appui-task-assigned">
            <div class="k-header"><?=_("Worker")?></div>
            <div class="k-content appui-task-doers">
              <input type="hidden" name="doers">
              <ul data-role="treeview"></ul>
            </div>
          </div>
          <div class="appui-spacer"> </div>
          <div class="k-block appui-task-assigned">
            <div class="k-header"><?=_("Spectator")?></div>
            <div class="k-content appui-task-viewers">
              <input type="hidden" name="viewers">
              <ul data-role="treeview"></ul>
            </div>
          </div>
          <div class="appui-form-full">
            <div class="appui-task-usertree"></div>
            <div class="appui-task-roles-desc appui-l">
              <i class="fa fa-question-circle"> </i>
              <?=_("Drag and drop the users into the corresponding role block")?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="appui-form-label"> </div>
    <div class="appui-form-field">
      <button class="k-button appui-l" data-bind="click: insert">
        <i class="fa fa-save"> </i>
        <?=_("Create task")?>
      </button>
    </div>

  </div>

</form>