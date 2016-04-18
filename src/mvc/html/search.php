<!-- HTML Document -->

<div class="appui-task-search-container">
  <div class="appui-form-full appui-c">
    <input name="title" class="k-textbox appui-lg" placeholder="Title/short description of the new task" required="required">
  </div>
  <ul data-bind="source: rows" data-template="appui_tasks_search_element">
    
  </ul>
</div>
<script type="text/x-kendo-template" id="appui_tasks_search_element">
<li>
  <a data-bind="attr: {href: link}">
    <span data-bind="text: title"></span>
    <?=_("Created on")?> <span data-bind="text: creationf"></span> <?=_("by")?> <span data-bind="text: userf"></span>
  </a>
</li>
</script>