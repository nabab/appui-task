<!-- HTML Document -->

<form action="<?=$root?>/insert">
  <div class="appui-line-breaker" id="bbn_pm_form_container">

    <input name="title" class="title k-textbox" placeholder="Title/short description of the new task" required="required">

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
      <textarea class="k-textbox" name="comment"></textarea>
    </div>

    <div class="appui-form-label"><?=_("Category")?></div>
    <div class="appui-form-field">
      <input type="hidden" data-option-label="<?=_("Choisissez")?>" data-role="dropdownlist">
    </div>

    <div class="appui-form-label"><?=_("Deadline")?></div>
    <div class="appui-form-field">
      <input type="date" data-role="datepicker">
    </div>

    <div class="appui-form-label"><?=_("Ajout d'éléments")?></div>
    <div class="appui-form-field">
      <button class="k-button"><i class="fa fa-file-image-o"> </i></button>
      &nbsp;
      <button class="k-button"><i class="fa fa-file-o"> </i></button>
      &nbsp;
      <button class="k-button"><i class="fa fa-file-archive-o"> </i></button>
      &nbsp;
      <button class="k-button"><i class="fa fa-link"> </i></button>
      &nbsp;
    </div>

    <div class="appui-form-full">
      <div class="k-block">
        <div class="k-header">
          <div>
            <?=_("Roles")?> &nbsp;
            <input name="role" data-role="dropdownlist" data-bind="source: roles, events: {change: change_role}" data-option-label="<?=_("Pick a role")?>" data-text-field="text" data-value-field="value">
            <input name="id_user" type="hidden" data-bind="events: {change: add_user}">
          </div>
        </div>
        <div class="k-content appui-task-roles-container">
          <div class="k-block" style="width: 30%">
            <div class="k-header"><?=_("Manager")?></div>
            <div class="k-content bbn-task-manager"></div>
          </div>
          <div class="k-block" style="width: 30%">
            <div class="k-header"><?=_("Worker")?></div>
            <div class="k-content appui-task-doer"></div>
          </div>
          <div class="k-block" style="width: 30%">
            <div class="k-header"><?=_("Spectator")?></div>
            <div class="k-content appui-task-viewer"></div>
          </div>
          <div class="appui-form-full">
            <div class="bbn_usergroup_tree"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="appui-form-label"> </div>
    <div class="appui-form-field">
      <button class="k-button" data-bind="click: insert">
        <i class="fa fa-save"> </i>
        <?=_("Create task")?>
      </button>
    </div>

  </div>

</form>