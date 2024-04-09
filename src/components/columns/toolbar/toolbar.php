<div class="appui-task-columns-toolbar">
  <template v-if="columnList && columnList.collapsed">
    <bbn-button v-if="!!source
                  && !!source.canAdd
                  && !!columnsComp
                  && columnsComp.isOrderedByTypes"
                icon="nf nf-fa-plus"
                :title="_('Create task')"
                class="bbn-no-border bbn-right-sspace"
                @click="addTask"
                :notext="true"/>
    <bbn-button icon="nf nf-fa-compress"
                :title="_('Collapse all')"
                class="bbn-no-border bbn-right-sspace"
                @click="collapseAll"
                v-if="total"
                :notext="true"/>
    <bbn-button icon="nf nf-fa-expand"
                :title="_('Expand all')"
                class="bbn-no-border bbn-right-sspace"
                @click="expandAll"
                v-if="total"
                :notext="true"/>
  </template>
  <div v-if="columnList"
       :class="['bbn-radius', 'bbn-background', 'bbn-hspadded', {
          'bbn-vspadded': columnList?.collapsed,
          'bbn-vmiddle': !columnList?.collapsed,
          'bbn-flex': columnList?.collapsed,
          'verticaltext': columnList?.collapsed
        }]"
        style="height: auto; min-width: 2rem; align-items: center">
    <i class="nf nf-oct-issue_opened bbn-m bbn-middle"/>
    <div :class="{'bbn-left-xsspace': !columnList?.collapsed}"
          v-text="total"/>
  </div>
</div>
