<!-- HTML Document -->

<div class="bbn-task-splitter bbn-full-height">
  <div class="bbn-task-search-container">
    <div class="bbn-form-full bbn-c">
      <div class="bbn-block" style="margin: 0 2em">
        <select name="selection" class="bbn-xl" data-role="dropdownlist" data-bind="events: {change: change_selection}">
          <option value="user"><?=_("Mine")?></option>
          <option value="group"><?=_("My groups'")?></option>
          <option value="all"><?=_("All")?></option>
        </select>
      </div>
      <div class="bbn-block bbn-full-width">
        <input name="title" class="bbn-xl bbn-full-width k-textbox" placeholder="<?=_("Search or Title for the new task")?>">
      </div>
      <div class="bbn-block" style="margin: 0 2em">
        <button class="k-button bbn-xl" data-bind="click: create_task">
          <i class="fa fa-flag-checkered"> </i> <?=_("Create a task")?>
        </button>
      </div>
    </div>
  </div>
  <div class="bbn-task-results-container">
    <div class="bbn-task-gantt bbn-h-100" style=""></div>
  </div>
</div>
