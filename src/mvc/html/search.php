<!-- HTML Document -->

<div id="appui_task_splitter" class="appui-full-height">
  <div class="appui-task-search-container">
    <div class="appui-form-full appui-c">
    	<select name="selection" class="appui-lg" data-role="dropdownlist" data-bind="events: {change: change_selection}">
      	<option value="user">Mine</option>
      	<option value="group">My groups'</option>
      	<option value="all">All</option>
      </select>
     &nbsp;
      <input name="title" class="k-textbox appui-lg" placeholder="Title/short description of the new task">
      <button class="k-button appui-lg" data-bind="click: create_task"><i class="fa fa-flag-checkered"> </i> <?=_("Create a task")?></button>
    </div>
  </div>
  <div class="appui-task-results-container">
    <div class="appui-task-gantt appui-full-height"></div>
  </div>
</div>
