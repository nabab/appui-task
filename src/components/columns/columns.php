<div class="appui-task-columns bbn-overlay">
  <bbn-collapsable-columns v-if="ready && !!sections && sections.length"
                           :source="sections"
                           :data="currentData"
                           ref="sections"
                           component="appui-task-columns-task"
                           toolbar="appui-task-columns-toolbar"
                           :pageable="true"
                           :children-filterable="true"
                           :children-filters="getFilters"
                           :children-sortable="true"
                           :children-order="[{field: 'last_action', dir: 'DESC'}]"
                           :collapse-empty="true"/>
  <div v-else
        class="bbn-100 bbn-alt-background bbn-padded">
    <div class="bbn-100">
      <bbn-loader class="bbn-radius"
                  style="background-color: var(--default-background)"/>
    </div>
  </div>
</div>