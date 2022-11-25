<div class="appui-task-home bbn-alt-background bbn-overlay bbn-flex-height">
  <div class="bbn-alt-background bbn-padded appui-task-home-toolbar">
    <div :class="['bbn-spadded', 'bbn-background', 'bbn-radius', 'appui-task-box-shadow', 'bbn-vmiddle', 'bbn-nowrap', {
           'bbn-flex-width': !mainPage.isMobile(),
           'bbn-flex-height': !!mainPage.isMobile()
         }]">
      <div class="bbn-alt-background bbn-vmiddle bbn-hspadded bbn-radius bbn-flex-fill"
           style="min-height: 2rem; flex-wrap: wrap">
        <div :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
             }, 'bbn-vxsmargin']">
          <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
               v-text="_('Search')"/>
          <div class="bbn-vmiddle">
            <bbn-input v-model="currentSearch"
                       :button-right="currentSearch.length ? 'nf nf-fa-close' : 'nf nf-fa-search'"
                       :action-right="clearSearch"
                       :placeholder="_('Search or Title for the new task')"
                       class="bbn-wide bbn-right-sspace bbn-no-border"/>
             <bbn-button @click="addTask"
                         :disabled="!currentSearch.length"
                         :text="_('Create a task')"
                         icon="nf nf-fa-plus"/>
          </div>
        </div>
        <div :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
               v-text="_('Filter')"/>
          <div class="bbn-vmiddle">
            <bbn-radiobuttons :source="filterTypes"
                              v-model="currentFilter"/>
          </div>
        </div>
        <div v-if="isColumnsView"
             :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
               v-text="_('Order')"/>
          <div class="bbn-vmiddle">
            <bbn-radiobuttons :source="orderTypes"
                              v-model="currentOrder"/>
          </div>
        </div>
        <div v-if="isColumnsView"
             :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
               v-text="_('Columns')"/>
          <div class="bbn-vmiddle">
            <bbn-button icon="nf nf-mdi-arrow_collapse"
                        :text="_('Collapse all')"
                        @click="getRef('columnsComponent').collapseAll()"
                        class="bbn-right-sspace"/>
            <bbn-button icon="nf nf-mdi-arrow_expand"
                        :text="_('Expand all')"
                        @click="getRef('columnsComponent').expandAll()"/>
          </div>
        </div>
        <div :class="[{'bbn-vmiddle': !mainPage.isMobile()}, 'bbn-vxsmargin']">
          <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
               v-text="_('Mode')"/>
          <div class="bbn-vmiddle">
            <bbn-radiobuttons :source="viewModes"
                              v-model="currentViewMode"/>
          </div>
        </div>
      </div>
      <div :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-tertiary-text-alt', {
             'bbn-left-lspace bbn-right-space': !mainPage.isMobile(),
             'bbn-top-space bbn-bottom-space': !!mainPage.isMobile(),
           }]"
           v-text="_('Tasks')"/>
    </div>
  </div>
  <div class="bbn-flex-fill">
    <appui-task-columns v-if="isColumnsView"
                        :source="source"
                        ref="columnsComponent"
                        :order="currentOrder"
                        :filter="currentFilter"
                        :search="currentSearch"/>
    <appui-task-list v-else
                     :source="source"
                     :filter="currentFilter"
                     :search="currentSearch"
                     ref="listComponent"/>
  </div>
</div>