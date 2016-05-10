<!-- HTML Document -->

<div id="appui_task_splitter" class="appui-full-height">
  <div class="appui-task-search-container">
    <div class="appui-form-full appui-c">
      <input name="title" class="k-textbox appui-lg" placeholder="Title/short description of the new task">
      <button class="k-button appui-lg" data-bind="click: create_task"><i class="fa fa-flag-checkered"> </i> <?=_("Create a task")?></button>
    </div>
  </div>
  <div class="appui-task-results-container">
    <div class="appui-task-gantt appui-full-height"></div>
  </div>
</div>
