<!-- HTML Document -->

<div class="appui-task-splitter appui-full-height">
  <div class="appui-task-search-container">
    <div class="appui-form-full appui-c">
      <div class="appui-block" style="margin: 0 2em">
        <select name="selection" class="appui-xl" data-role="dropdownlist" data-bind="events: {change: change_selection}">
          <option value="user"><?=_("Mine")?></option>
          <option value="group"><?=_("My groups'")?></option>
          <option value="all"><?=_("All")?></option>
        </select>
      </div>
      <div class="appui-block appui-full-width">
        <input name="title" class="appui-xl appui-full-width k-textbox" placeholder="<?=_("Search or Title for the new task")?>">
      </div>
      <div class="appui-block" style="margin: 0 2em">
        <button class="k-button appui-xl" data-bind="click: create_task">
          <i class="fa fa-flag-checkered"> </i> <?=_("Create a task")?>
        </button>
      </div>
    </div>
  </div>
  <div class="appui-task-results-container">
    <div class="appui-task-gantt appui-h-100" style=""></div>
  </div>
</div>
