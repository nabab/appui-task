<!-- HTML Document -->
<div class="appui-task-list bbn-overlay">
  <bbn-column-list :source="mainPage.root + 'data/list'"
                   :data="currentData"
                   ref="tasksList"
                   :scrollable="true"
                   :pageable="true"
                   :sortable="true"
                   :order="[{field: 'last_action', dir: 'DESC'}]"
                   :filterable="true"
                   :filters="filters"
                   component="appui-task-item"
                   :component-options="{
                     collapseFooter: false,
                     forceCollapsed: true
                   }"
                   uid="id"/>
</div>
