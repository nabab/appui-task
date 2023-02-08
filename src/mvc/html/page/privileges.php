<div class="appui-task-privileges bbn-alt-background bbn-overlay bbn-flex-height">
  <div class="bbn-alt-background bbn-padded">
    <div :class="['bbn-spadded', 'bbn-background', 'bbn-radius', 'appui-task-box-shadow', 'bbn-vmiddle', 'bbn-nowrap', {
           'bbn-flex-width': !isMobile(),
           'bbn-flex-height': !!isMobile()
         }]">
      <div :class="['bbn-alt-background', 'bbn-vmiddle', 'bbn-radius', 'bbn-flex-fill', {
             'bbn-hspadded': !isMobile(),
             'bbn-spadded': isMobile()
           }]"
           style="min-height: 2rem; flex-wrap: wrap">
        <div :class="[{
               'bbn-vmiddle bbn-right-lspace': !isMobile(),
               'bbn-right-space': isMobile()
             }, 'bbn-vxsmargin']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': isMobile()}]"
               v-text="_('Search')"/>
          <div class="bbn-vmiddle">
            <bbn-input v-model="currentSearch"
                       :button-right="currentSearch.length ? 'nf nf-fa-close' : 'nf nf-fa-search'"
                       :action-right="clearSearch"
                       :placeholder="_('Search user')"
                       class="bbn-wide bbn-right-sspace bbn-no-border"/>
          </div>
        </div>
        <div :class="[{
               'bbn-vmiddle bbn-right-lspace': !isMobile(),
               'bbn-right-space': isMobile()
             }, 'bbn-vxsmargin']">
          <div :class="['bbn-upper', 'bbn-right-space', 'bbn-b', 'bbn-secondary-text-alt', {'bbn-bottom-xsspace': isMobile()}]"
               v-text="_('Columns')"/>
          <div class="bbn-vmiddle">
            <bbn-button icon="nf nf-mdi-arrow_collapse"
                        :text="_('Collapse all')"
                        @click="collapseAll()"
                        class="bbn-right-sspace"/>
            <bbn-button icon="nf nf-mdi-arrow_expand"
                        :text="_('Expand all')"
                        @click="expandAll()"/>
          </div>
        </div>
      </div>
      <div :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-tertiary-text-alt', {
             'bbn-left-lspace bbn-right-space': !isMobile(),
             'bbn-top-space bbn-bottom-space': !!isMobile(),
           }]"
           v-text="_('Privileges')"/>
    </div>
  </div>
  <div class="bbn-flex-fill bbn-alt-background">
    <bbn-collapsable-columns v-if="ready && !!sections && sections.length"
                            :source="sections"
                            ref="sections"
                            :component="$options.components.user"
                            :toolbar="$options.components.toolbar"
                            :pageable="true"
                            :children-filterable="true"
                            :children-filters="getFilters"
                            uid="id"
                            columnWidth="30rem"/>
    <div v-else
          class="bbn-100 bbn-alt-background bbn-padded">
      <div class="bbn-100">
        <bbn-loader class="bbn-radius"
                    style="background-color: var(--default-background)"/>
      </div>
    </div>
  </div>
</div>