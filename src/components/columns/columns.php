<div class="appui-task-columns bbn-overlay">
  <bbn-kanban bbn-if="ready && !!sections && sections.length"
                           :source="sections"
                           :data="currentData"
                           ref="sections"
                           component="appui-task-item"
                           :component-options="{showParent: true, uid: 'id'}"
                           toolbar="appui-task-columns-toolbar"
                           :pageable="true"
                           :children-filterable="true"
                           :children-filters="getFilters"
                           :children-sortable="true"
                           :children-order="[{field: 'last_action', dir: 'DESC'}]"
                           :collapse-empty="true"
                           uid="id"
                           column-width="45rem"/>
  <div bbn-else
        class="bbn-100 bbn-alt-background bbn-padding">
    <div class="bbn-100">
      <bbn-loader class="bbn-radius"
                  style="background-color: var(--default-background)"/>
    </div>
  </div>
</div>