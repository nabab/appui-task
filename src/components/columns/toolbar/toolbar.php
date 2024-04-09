<div class="appui-task-columns-toolbar">
  
  <div :class="['bbn-radius', 'bbn-background', 'bbn-hspadded', {
          'bbn-vspadded': columnList.collapsed,
          'bbn-vmiddle': !columnList.collapsed,
          'bbn-flex': columnList.collapsed,
          'verticaltext': columnList.collapsed
        }]"
        style="height: auto; min-width: 2rem; align-items: center">
    <i class="nf nf-oct-issue_opened bbn-m bbn-middle"/>
    <div :class="{'bbn-left-xsspace': !columnList.collapsed}"
          v-text="total"/>
  </div>
</div>
