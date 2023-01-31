<div class="appui-task-task-widget-subtasks">
  <bbn-column-list :source="mainPage.root + 'data/list'"
                  :filters="currentFilters"
                  :filterable="true"
                  :pageable="true"
                  :sortable="true"
                  :order="currentOrder"
                  component="appui-task-item"
                  :component-options="{
                    inverted: true,
                    removeParent: true,
                    isSub: true,
                    forceCollapsed: true,
                    collapseFooter: false
                  }"
                  uid="id"
                  :scrollable="false"
                  toolbar="appui-task-item-toolbar"
                  :toolbar-source="source"
                  :limit="5"
                  ref="list"/>
</div>