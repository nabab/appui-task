<div class="appui-task-task-widget-subtasks bbn-radius bbn-background bbn-spadded">
  <div class="bbn-flex-width bbn-vmiddle">
    <div class="bbn-flex-fill bbn-upper bbn-b bbn-spadded bbn-radius bbn-c bbn-right-sspace bbn-s"
         v-text="state"
         :style="{
           color: getStatusColor(stateCode) + '!important',
           backgroundColor: getStatusBgColor(stateCode) + '!important'
         }"
         :title="_('Status')"/>
    <div v-if="!!role"
         class="bbn-alt-background bbn-radius bbn-spadded bbn-right-sspace bbn-alt-text bbn-s bbn-b bbn-vmiddle"
         :style="{color: !!roleCode ? getRoleBgColor(roleCode) + '!important' : false}"
         :title="_('Role')">
      <i class="nf nf-fa-users bbn-right-xsspace"/>
      <span v-text="role"/>
    </div>
    <div class="bbn-alt-background bbn-radius bbn-spadded bbn-right-sspace bbn-secondary-text-alt bbn-s bbn-b bbn-vmiddle"
         :title="_('Created on')">
      <i class="nf nf-mdi-calendar bbn-right-xsspace"/>
      <span v-text="creationDate"/>
    </div>
    <bbn-button icon="nf nf-fa-eye"
                :notext="true"
                class="bbn-no-border bbn-primary"
                @click="openTask"
                :title="_('Open task')"/>
  </div>
  <div class="bbn-radius bbn-top-sspace"
       v-html="source.title"/>
</div>