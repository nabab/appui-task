<!-- HTML Document -->

<div id="appui_task_splitter" class="appui-full-height">
  <div class="appui-task-search-container">
    <div class="appui-form-full appui-c">
      <div class="appui-block">
        <select name="selection" class="appui-lg" data-role="dropdownlist" data-bind="events: {change: change_selection}">
          <option value="user"><?=_("Mine")?></option>
          <option value="group"><?=_("My groups'")?></option>
          <option value="all"><?=_("All")?></option>
        </select>
      </div>
      <div class="appui-block appui-full-width" style="margin: 0 2em">
        <input name="title" class="k-textbox appui-lg appui-w-100" placeholder="<?=_("Title/short description of the new task")?>">
      </div>
      <div class="appui-block">
        <button class="k-button appui-lg" data-bind="click: create_task"><i class="fa fa-flag-checkered"> </i> <?=_("Create a task")?></button>
      </div>
    </div>
  </div>
  <div class="appui-task-results-container">
    <div class="appui-task-gantt appui-full-height"></div>
  </div>
</div>
