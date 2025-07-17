<div class="appui-task-task-widget-subtasks">
  <bbn-kanban-element :source="mainPage.root + 'data/list'"
                  :filters="currentFilters"
                  :filterable="true"
                  :pageable="true"
                  :sortable="true"
                  :order="currentOrder"
                  component="appui-task-item"
                  :component-options="{
                    inverted: false,
                    removeParent: true,
                    isSub: true,
                    forceCollapsed: true,
                    collapseFooter: false
                  }"
                  uid="id"
                  :scrollable="false"
                  toolbar="appui-task-item-toolbar"
                  :toolbar-source="source"
                  :toolbar-options="{
                    inverted: true
                  }"
                  :limit="5"
                  ref="list"/>
</div>