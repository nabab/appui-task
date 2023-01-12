<div class="appui-task-columns-toolbar bbn-vmiddle"
     :style="{'flex-direction': !source.opened ? 'column': ''}">
  <template v-if="!!source.opened">
    <bbn-button v-if="!!source.data.canAdd
                  && !!columnsComp
                  && columnsComp.isOrderedByTypes"
                icon="nf nf-fa-plus"
                :title="_('Create task')"
                class="bbn-no-border bbn-right-sspace"
                @click="addTask"/>
    <bbn-button icon="nf nf-fa-compress"
                :title="_('Collapse all')"
                class="bbn-no-border bbn-right-sspace"
                @click="collapseAll"/>
    <bbn-button icon="nf nf-fa-expand"
                :title="_('Expand all')"
                class="bbn-no-border bbn-right-sspace"
                @click="expandAll"/>
  </template>
  <div :class="['bbn-radius', 'bbn-background', 'bbn-hspadded', {
          'bbn-vspadded': !source.opened,
          'bbn-vmiddle': !!source.opened,
          'bbn-flex': !source.opened,
          'verticaltext': !source.opened
        }]"
        style="min-height: 1.9rem; min-width: 1.9rem; align-items: center">
    <i class="nf nf-oct-issue_opened bbn-m bbn-middle"/>
    <div :class="{'bbn-left-xsspace': !!source.opened}"
          v-text="total"/>
  </div>
</div>
