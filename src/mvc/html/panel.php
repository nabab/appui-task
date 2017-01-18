<div class="appui-full-height" id="appui_task_tabnav"></div>

<script type="text/x-kendo-template" id="tpl-task_tab_main">
  <div class="appui-margin">
    <input type="hidden" name="id" data-bind="value: id">
    <input type="hidden" name="ref" data-bind="value: ref">

    <div class="appui-line-breaker appui-task-form-container">

      <div class="appui-task-info-ppl k-widget">
        <div class="appui-block">
          <div class="appui-block">
            <?=_("Created by")?><br>
            <?=_("On")?>
          </div>
          <div class="appui-block">
            <span data-bind="html: creator"></span><br>
            <span data-bind="text: creation"></span>
          </div>
        </div>
        <div class="appui-block" data-bind="visible: roles.workers">
          <div class="appui-block"><?=_("Assigned to")?></div>
          <ul class="appui-block" data-bind="source: roles.workers" data-template="tpl-task_info_ppl"></ul>
        </div>
        <div class="appui-block" data-bind="visible: roles.managers">
          <div class="appui-block"><?=_("Supervised by")?></div>
          <ul class="appui-block" data-bind="source: roles.managers" data-template="tpl-task_info_ppl"></ul>
        </div>
      </div>

      <div class="appui-form-full">
        <input name="title" autocomplete="off" class="k-textbox appui-lg title" placeholder="<?=_("Title/short description")?>" required="required" data-bind="value: title, events: {change: update, keydown: preventEnter}">
      </div>

      <div class="appui-form-label"><?=_("Category")?></div>
      <div class="appui-form-field">
        <input name="type" style="width: 500px" required="required" data-bind="value: type, events: {change: update}">
      </div>

      <div class="appui-form-label"><?=_("Priority")?></div>
      <div class="appui-form-field">

        <div class="appui-form-label" style="width: 140px">
          <div class="appui-block">
            <select data-role="dropdownlist" data-bind="value: priority, events: {change: update}" name="priority" style="width: 80px">
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

          <div class="appui-form-label" style="width: 140px"><?=_("Deadline")?></div>
          <div class="appui-form-field">
            <input type="date" data-role="datepicker" name="deadline" data-bind="value: deadline, events: {keydown: preventAll, change: update}" data-format="yyyy-MM-dd">
            <button class="k-button" data-bind="visible: has_deadline, click: remove_deadline">
              <i class="fa fa-times"></i>
            </button>
          </div>

        </div>
      </div>

      <div class="appui-form-label" data-bind="visible: reference"><?=_("External reference")?></div>
      <div class="appui-form-field" data-bind="visible: reference, html: reference"></div>

      <div class="appui-form-label appui-lg" class="appui-task-actions"><em data-bind="text: statef"></em></div>
      <div class="appui-form-field appui-lg" class="appui-task-actions">
        <div data-bind="visible: is_active">
          <button data-bind="visible: can_hold, click: hold" class="k-button" title="<?=_("Put on hold")?>">
            <i class="fa fa-pause"> </i>
          </button>
          <button data-bind="visible: can_resume, click: resume" class="k-button" title="<?=_("Resume")?>">
            <i class="fa fa-play"> </i>
          </button>
          <button data-bind="visible: can_close, click: close" class="k-button" title="<?=_("Close")?>">
            <i class="fa fa-check"> </i>
          </button>
          <div data-bind="style: {display: make_me_display}" style="vertical-align: middle">
            <ul data-role="menu" data-bind="events: {select: make_me}" style="vertical-align: middle">
              <li>
                <i class="fa fa-user-plus"> </i>
                <ul>
                  <li data-task-role="managers"><?=_("Make me a supervisor")?></li>
                  <li data-task-role="workers"><?=_("Make me a worker")?></li>
                  <li data-task-role="viewers"><?=_("Make me a viewer")?></li>
                </ul>
              </li>
            </ul>
          </div>
          <button data-bind="visible: can_ping, click: ping" class="k-button" title="<?=_("Ping workers")?>">
            <i class="fa fa-hand-o-up"> </i>
          </button>
          <button data-bind="visible: is_added, click: unmake_me" class="k-button" title="<?=_("Unfollow the task")?>">
            <i class="fa fa-user-times"> </i>
          </button>
        </div>
        <div data-bind="visible: is_holding">
          <button data-bind="visible: can_resume, click: resume" class="k-button" title="<?=_("Resume")?>">
            <i class="fa fa-play"> </i>
          </button>
        </div>
        <div data-bind="visible: is_closed">
          <button data-bind="visible: can_open, click: reopen" class="k-button" title="<?=_("Reopen")?>">
            <i class="fa fa-hand-o-left"> </i>
          </button>
        </div>
      </div>

      <div class="appui-line-breaker"> </div>
      <!--onclick="$(this).next().toggle().redraw().next().toggle().redraw()">-->
      <div class="appui-form-label appui-p" onclick="bbn.fn.redraw($(this).parent(), true)">
        <i class="fa fa-edit"> </i> &nbsp; <?=_("Add a comment")?>
      </div>

      <div class="appui-form-field" data-bind="invisible: has_comments">
        <div class="appui-form-label appui-p" style="width: 140px">
          <!--button class="k-button" onclick="bbn.fn.alert('Link')">
            <i class="fa fa-link"> </i> &nbsp; <?=_("Link")?>
          </button>
          &nbsp;&nbsp;
          <button class="k-button" onclick="bbn.fn.alert('Link')">
            <i class="fa fa-code"> </i> &nbsp; <?=_("Code")?>
          </button-->
        </div>
        <div class="appui-form-field">
          <div class="appui-task-upload-wrapper appui-task-files-container"> </div>
        </div>
      </div>

      <div class="appui-form-full appui-task-form-adder" data-bind="invisible: has_comments">
        <div class="appui-form-label" style="width: 220px">
          <?=_("Title")?><br>
        </div>
        <div class="appui-form-field">
          <input class="k-textbox" name="comment_title" style="width: 100%">
        </div>

        <div class="appui-form-label">
          <?=_("Comment")?><!--br>
          <select class="comment_type" data-role="dropdownlist" data-bind="events: {change: change_comment_type}">
            <option value="text"><?=_("Simple text")?></option>
            <option value="html"><?=_("Rich text")?></option>
            <option value="gfm"><?=_("Markdown")?></option>
            <option value="php"><?=_("PHP code")?></option>
            <option value="js"><?=_("JavaScript code")?></option>
            <option value="css"><?=_("CSS code")?></option>
          </select><br-->
        </div>
        <div class="appui-form-field">
          <textarea class="k-textbox" name="comment" style="width: 100%"></textarea>
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

        <div class="appui-form-label"> </div>
        <div class="appui-form-field">
          <button class="k-button appui-task-comment-button" data-bind="click: add_comment">
            <i class="fa fa-save"> </i> Save
          </button>
        </div>

      </div>
    </div>
    <div class="appui-form-full appui-task-comments ui comments" data-bind="source: notes" data-template="tpl-appui_task_comment"></div>
  </div>
</script>

<script type="text/x-kendo-template" id="tpl-appui_task_comment">
	<div class="ui comment">
  	<a class="avatar">
    	<img src="#= apst.userAvatar(id_user) #" alt="#= apst.userName(id_user)#" title="#= apst.userName(id_user)#">
    </a>
    <div class="content">
    	<a class="author">#= apst.userName(id_user) #</a>
      <div class="metadata">
        <div class="date" data-bind="text: since"></div>
        <!--<div class="rating">
          <i class="star icon"></i>
          5 Faves
        </div>-->
      </div>
      <div class="title appui-lg" data-bind="visible: title, html: title"></div>
      <div class="text">
        <div data-bind="visible: content, html: content"></div>
        <p data-bind="source: medias" data-template="tpl-appui_task_media"></p>
  		</div>
    </div>
  </div>
</script>

<script type="text/x-kendo-template" id="tpl-appui_task_media">
  <span style="margin-right: 2em">
    <a class="media" data-bind="text: title" onclick="bbn.tasks.download_media(#=id#)"></a>
  </span>
</script>

<script type="text/x-kendo-template" id="tpl-task_tab_roles">
  <div class="appui-margin appui-task-roles-container">
    <div class="k-content">
      <div class="k-block appui-task-assigned">
        <div class="k-header"><?=_("Supervisors")?></div>
        <div class="k-content appui-task-managers">
          <input type="hidden" name="managers">
          <ul></ul>
        </div>
      </div>
      <div class="appui-spacer"> </div>
      <div class="k-block appui-task-assigned">
        <div class="k-header"><?=_("Workers")?></div>
        <div class="k-content appui-task-workers">
          <input type="hidden" name="workers">
          <ul></ul>
      </div>
    </div>
    <div class="appui-spacer"> </div>
    <div class="k-block appui-task-assigned">
      <div class="k-header"><?=_("Spectators")?></div>
        <div class="k-content appui-task-viewers">
          <input type="hidden" name="viewers">
          <ul></ul>
        </div>
      </div>
      <div class="appui-form-full">
        <div class="appui-task-usertree"></div>
        <div class="appui-task-roles-desc container-placeholder appui-lg" data-bind="invisible: is_closed">
        	<span data-bind="visible: can_change">
            <i class="fa fa-question-circle"> </i>
            <?=_("Drag and drop the users into the corresponding role block")?>
          </span>
        	<span data-bind="invisible: can_change">
            <i class="fa fa-exclamation-circle"> </i>
            <?=_("You have no right to modify the roles in this task")?>
          </span>
        </div>
        <div class="appui-task-roles-desc container-placeholder appui-lg" data-bind="visible: is_closed">
          <i class="fa fa-exclamation-circle"> </i>
          <?=_("You cannot change the roles because the task is closed")?>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/x-kendo-template" id="tpl-task_info_ppl">
<li>#= apst.userFull(data) #</li>
</script>
<script type="text/x-kendo-template" id="tpl-task_form_new">
	<form method="post">
    <label class="appui-form-label" for="appui_task_form_title"><?=_("Title")?></label>
    <input id="appui_task_form_title" name="title" class="k-textbox appui-form-field" maxlength="255" required="required" style="width: 100%">

    <label class="appui-form-label" for="appui_task_form_type"><?=_("Type")?></label>
    <div class="appui-form-field">
      <input name="type" required="required" id="appui_task_form_type" style="width: 100%">
    </div>
    <div class="appui-form-label">&nbsp;</div>
    <div class="appui-form-field">
      <button class="k-button" type="submit"><i class="fa fa-save"> </i> &nbsp; <?=_("Save")?></button>
    </div>
  </form>
</script>