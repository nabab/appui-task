<div class="appui-task-item-toolbar"
     :style="{width: !!mode ? '100%' : 'auto'}">
  <template v-if="!mode">
    <bbn-context v-if="canChange"
                 :source="[{
                   text: _('Create new'),
                   icon: 'nf nf-fa-plus',
                   action: addTask
                 }, {
                   text: _('Search existing'),
                   icon: 'nf nf-fa-search',
                   action: searchTask
                 }]">
      <bbn-button icon="nf nf-fa-plus"
                  :title="_('New task')"
                  class="bbn-no-border bbn-right-sspace"
                  :notext="true"/>
    </bbn-context>
    <bbn-button icon="nf nf-fa-search"
                :title="_('Search')"
                :class="['bbn-no-border', 'bbn-right-sspace', {'bbn-state-active': currentSearch.length}]"
                :notext="true"
                @click="setMode('search')"
                :disabled="!source.num_children"/>
    <bbn-button icon="nf nf-mdi-sort_alphabetical"
                :title="_('Order')"
                class="bbn-no-border bbn-right-sspace"
                :notext="true"
                @click="setMode('order')"
                :disabled="true"/>
    <bbn-button icon="nf nf-md-filter"
                :title="_('Filter')"
                class="bbn-no-border bbn-right-sspace"
                :notext="true"
                @click="setMode('filter')"
                :disabled="true"/>
    <bbn-button icon="nf nf-fa-compress"
                :title="_('Collapse all')"
                class="bbn-no-border bbn-right-sspace"
                @click="collapseAll"
                v-if="!!source.num_children"
                :notext="true"/>
    <bbn-button icon="nf nf-fa-expand"
                :title="_('Expand all')"
                class="bbn-no-border bbn-right-sspace"
                @click="expandAll"
                v-if="!!source.num_children"
                :notext="true"/>
  </template>
  <template v-else>
    <div class="bbn-flex-width"
         style="align-items: stretch">
      <bbn-button icon="nf nf-fa-arrow_left"
                  :text="_('Come back')"
                  :notext="true"
                  @click="setMode(null)"
                  class="bbn-right-sspace bbn-no-border"
                  style="height: auto"/>
      <template v-if="mode === 'search'">
        <bbn-input v-model="currentSearch"
                    :button-right="currentSearch.length ? 'nf nf-fa-close' : 'nf nf-fa-search'"
                    :action-right="clearSearch"
                    :placeholder="_('Search')"
                    class="bbn-flex-fill bbn-no-border"/>
      </template>
      <template v-else-if="mode === 'order'"></template>
      <template v-else-if="mode === 'filter'"></template>
    </div>
  </template>
</div>
