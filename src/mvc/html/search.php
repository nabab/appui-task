<!-- HTML Document -->

<div id="appui_task_splitter" class="appui-full-height">
  <div class="appui-task-search-container">
    <div class="appui-form-full appui-c">
      <input name="title" class="k-textbox appui-lg" placeholder="Title/short description of the new task" required="required">
      <button class="k-button appui-lg" data-bind="click: create_task"><i class="fa fa-flag-checkered"> </i> <?=_("GO!")?></button>
    </div>
  </div>
  <div class="appui-task-results-container"><div class="appui-full-height"></div></div>
</div>
<script type="text/x-kendo-template" id="appui_tasks_search_element">
<li>
  <a data-bind="attr: {href: link}" class="appui-form-full">
  	<div data-bind="text: title" class="appui-block appui-task-item-title appui-full-width"></div>
  	<div class="appui-block appui-task-item-created" style="width: 200px"><?=_("Created on")?> <span data-bind="text: creationf"></span></div>
  	<div class="appui-block appui-task-item-creator" style="width: 200px"><?=_("by")?> <span data-bind="text: userf"></span></div>
  </a>
  <div>
</li>
</script>