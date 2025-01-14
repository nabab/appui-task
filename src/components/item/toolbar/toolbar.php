<div class="appui-task-item-toolbar"
     :style="{width: !!mode ? '100%' : 'auto'}">
  <template bbn-if="!mode">
    <bbn-context bbn-if="canChange"
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
                :class="['bbn-no-border', 'bbn-right-sspace', {'bbn-state-active': !!currentOrder}]"
                :notext="true"
                @click="setMode('order')"
                :disabled="!source.num_children"/>
    <bbn-button icon="nf nf-md-filter"
                :title="_('Filter')"
                :class="['bbn-no-border', 'bbn-right-sspace', {'bbn-state-active': hasFilters}]"
                :notext="true"
                @click="setMode('filter')"
                :disabled="!source.num_children"/>
    <bbn-button icon="nf nf-fa-compress"
                :title="_('Collapse all')"
                class="bbn-no-border bbn-right-sspace"
                @click="collapseAll"
                bbn-if="!!source.num_children"
                :notext="true"/>
    <bbn-button icon="nf nf-fa-expand"
                :title="_('Expand all')"
                class="bbn-no-border bbn-right-sspace"
                @click="expandAll"
                bbn-if="!!source.num_children"
                :notext="true"/>
  </template>
  <template bbn-else>
    <div class="bbn-flex-width"
         style="align-items: stretch">
      <bbn-button icon="nf nf-fa-arrow_left"
                  :label="_('Come back')"
                  :notext="true"
                  @click="setMode(null)"
                  class="bbn-right-sspace bbn-no-border"
                  style="height: auto"/>
      <template bbn-if="mode === 'search'">
        <bbn-input bbn-model="currentSearch"
                    :button-right="currentSearch.length ? 'nf nf-fa-close' : 'nf nf-fa-search'"
                    :action-right="clearSearch"
                    :placeholder="_('Search')"
                    class="bbn-flex-fill bbn-no-border"/>
      </template>
      <template bbn-else-if="mode === 'order'">
        <bbn-dropdown class="bbn-no-border bbn-flex-fill"
                      :source="orderSource"
                      bbn-model="currentOrder"/>
        <bbn-button bbn-if="currentOrderIcon"
                    :icon="currentOrderIcon"
                    :label="currentSort === 'asc' ? _('Ascending order') : _('Descending order')"
                    :notext="true"
                    @click="currentSort = currentSort === 'asc' ? 'desc' : 'asc'"
                    class="bbn-left-sspace bbn-no-border"
                    style="height: auto"/>
        <bbn-button icon="nf nf-fa-close"
                  :label="_('Remove')"
                  :notext="true"
                  @click="currentOrder = null"
                  class="bbn-left-sspace bbn-no-border"
                  style="height: auto"/>
      </template>
      <template bbn-else-if="mode === 'filter'">
        <div class="bbn-flex-fill">
          <bbn-context :source="getStatusMenuSource"
                       item-component="appui-task-item-menu"
                       class="bbn-left-sspace">
            <bbn-button bbn-label="currentFilters.status ? getField(mainPage.optionsStates, 'text', 'value', currentFilters.status) : _('Status')"
                        :style="{
                          color: currentFilters.status ? getField(mainPage.optionsStates, 'color', 'value', currentFilters.status) : '',
                          backgroundColor: currentFilters.status ? getField(mainPage.optionsStates, 'backgroundColor', 'value', currentFilters.status) : '',
                          padding: 'var(--xsspace)',
                          'line-height': 'inherit',
                          'min-height': 'unset'
                        }"
                        class="bbn-upper bbn-s bbn-no-border"
                        :title="_('Status')"/>
          </bbn-context>
          <bbn-context :source="getPriorityMenuSource"
                       item-component="appui-task-item-menu"
                       class="bbn-left-sspace">
            <bbn-button bbn-label="currentFilters.priority ? getField(mainPage.priorities, 'text', 'value', currentFilters.priority) : _('Priority')"
                        :style="{
                          color: currentFilters.priority ? getField(mainPage.priorities, 'color', 'value', currentFilters.priority) : '',
                          backgroundColor: currentFilters.priority ? getField(mainPage.priorities, 'backgroundColor', 'value', currentFilters.priority) : '',
                          padding: 'var(--xsspace)',
                          'line-height': 'inherit',
                          'min-height': 'unset'
                        }"
                        class="bbn-upper bbn-s bbn-no-border"
                        :title="_('Priority')"/>
          </bbn-context>
          <bbn-context :source="getMyRoleMenuSource"
                       item-component="appui-task-item-menu"
                       class="bbn-left-sspace">
            <bbn-button bbn-label="currentFilters.role ? getField(mainPage.optionsRoles, 'text', 'value', currentFilters.role) : _('Role')"
                        :style="{
                          color: currentFilters.role ? getField(mainPage.optionsRoles, 'color', 'value', currentFilters.role) : '',
                          backgroundColor: currentFilters.role ? getField(mainPage.optionsRoles, 'backgroundColor', 'value', currentFilters.role) : '',
                          padding: 'var(--xsspace)',
                          'line-height': 'inherit',
                          'min-height': 'unset'
                        }"
                        class="bbn-upper bbn-s bbn-no-border"
                        :title="_('Role')"/>
          </bbn-context>
        </div>
        <bbn-button icon="nf nf-fa-close"
                  :label="_('Remove')"
                  :notext="true"
                  @click="removeFilters"
                  class="bbn-left-sspace bbn-no-border"
                  style="height: auto"/>
      </template>
    </div>
  </template>
</div>
