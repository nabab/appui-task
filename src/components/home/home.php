<div class="appui-task-home bbn-alt-background bbn-overlay bbn-flex-height">
  <div class="bbn-alt-background bbn-padding appui-task-home-toolbar">
    <div :class="['bbn-spadding', 'bbn-background', 'bbn-radius', 'appui-task-box-shadow', 'bbn-vmiddle', 'bbn-nowrap', {
           'bbn-flex-width': !mainPage.isMobile(),
           'bbn-flex-height': !!mainPage.isMobile()
         }]">
      <div :class="['bbn-alt-background', 'bbn-vmiddle', 'bbn-radius', 'bbn-flex-fill', {
             'bbn-hspadding': !mainPage.isMobile(),
             'bbn-spadding': mainPage.isMobile()
           }]"
           style="min-height: 2rem; flex-wrap: wrap">
        <div :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
               'bbn-right-space': mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': mainPage.isMobile()}]"
               bbn-text="_('Search')"/>
          <div class="bbn-vmiddle">
            <bbn-input bbn-model="currentSearch"
                       :button-right="currentSearch.length ? 'nf nf-fa-close' : 'nf nf-fa-search'"
                       :action-right="clearSearch"
                       :placeholder="_('Search or Title for the new task')"
                       class="bbn-wide bbn-right-sspace bbn-no-border"/>
             <bbn-button @click="addTask"
                         :disabled="!currentSearch.length"
                         :label="_('Create a task')"
                         icon="nf nf-fa-plus"
                         :notext="mainPage.isMobile()"/>
          </div>
        </div>
        <div bbn-if="filterTypes.length > 1"
             :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
               'bbn-right-space': mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': mainPage.isMobile()}]"
               bbn-text="_('Filter')"/>
          <div class="bbn-vmiddle">
            <bbn-radiobuttons :source="filterTypes"
                              bbn-model="currentFilter"/>
          </div>
        </div>
        <div bbn-if="isColumnsView"
             :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
               'bbn-right-space': mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': mainPage.isMobile()}]"
               bbn-text="_('Order')"/>
          <div class="bbn-vmiddle">
            <bbn-radiobuttons :source="orderTypes"
                              bbn-model="currentOrder"/>
          </div>
        </div>
        <div bbn-if="isColumnsView && !!roles && roles.length"
             :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
               'bbn-right-space': mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': mainPage.isMobile()}]"
               bbn-text="_('Role')"/>
          <div class="bbn-vmiddle">
            <bbn-radiobuttons :source="roles"
                              bbn-model="currentRole"/>
          </div>
        </div>
        <div bbn-if="(isColumnsView || isListView) && !!currentComponent"
             :class="[{
               'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
               'bbn-right-space': mainPage.isMobile()
             }, 'bbn-vxsmargin']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': mainPage.isMobile()}]"
               bbn-text="isColumnsView ? _('Columns') : _('Rows')"/>
          <div class="bbn-vmiddle">
            <bbn-button icon="nf nf-md-arrow_collapse"
                        :label="_('Collapse all')"
                        @click="currentComponent.collapseAll()"
                        class="bbn-right-sspace"/>
            <bbn-button icon="nf nf-md-arrow_expand"
                        :label="_('Expand all')"
                        @click="currentComponent.expandAll()"/>
          </div>
        </div>
        <div :class="[{
               'bbn-vmiddle': !mainPage.isMobile()
             }, 'bbn-vxsmargin', 'bbn-right-space']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': mainPage.isMobile()}]"
               bbn-text="_('Mode')"/>
          <div class="bbn-vmiddle">
            <bbn-radiobuttons :source="viewModes"
                              bbn-model="currentViewMode"/>
          </div>
        </div>
        <bbn-button :title="_('Hierarchy')"
                    :class="{'bbn-state-active': currentHierarchy}"
                    @click="currentHierarchy = !currentHierarchy"
                    icon="nf nf-md-file_tree"/>
      </div>
      <div :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-tertiary-text-alt', {
             'bbn-left-lspace bbn-right-space': !mainPage.isMobile(),
             'bbn-top-space bbn-bottom-space': !!mainPage.isMobile(),
           }]"
           bbn-text="_('Tasks')"/>
    </div>
  </div>
  <div class="bbn-flex-fill bbn-alt-background">
    <appui-task-columns bbn-if="isColumnsView"
                        :source="source"
                        ref="columnsComponent"
                        :order="currentOrder"
                        :filter="currentFilter"
                        :search="currentSearch"
                        :user-role="currentRole"
                        :hierarchy="currentHierarchy"
                        @hook:mounted="currentComponent = getRef('columnsComponent')"/>
    <appui-task-list bbn-else-if="isListView"
                     :source="source"
                     :filter="currentFilter"
                     :search="currentSearch"
                     ref="listComponent"
                     :hierarchy="currentHierarchy"
                     @hook:mounted="currentComponent = getRef('listComponent')"/>
    <appui-task-table bbn-else-if="isTableView"
                     :source="source"
                     :filter="currentFilter"
                     :search="currentSearch"
                     ref="tableComponent"
                     :hierarchy="currentHierarchy"
                     @hook:mounted="currentComponent = getRef('tableComponent')"/>
  </div>
</div>