<div class="appui-task-task-widget-subtasks bbn-box bbn-flex-width">
  <div class="bbn-spadded bbn-flex-fill">
    <div class="bbn-grid-fields">
      <label class="bbn-label"
             v-text="_('Title')"/>
      <a class="bbn-flex-fill bbn-bordered-bottom"
         v-text="source.title"
         :href="root + 'page/task/' + source.id"/>
      <label class="bbn-label"
             v-text="_('Created on')"/>
      <div v-text="creationDate"
           class="bbn-bordered-bottom"/>
      <label class="bbn-label"
             v-text="_('Status')"/>
      <div v-text="state"
           :style="{color: !!stateCode ? getStatusBgColor(stateCode) : false}"
           :class="{'bbn-bordered-bottom': !!role}"/>
      <label v-if="!!role"
             class="bbn-label"
             v-text="_('Role')"/>
      <div v-if="!!role"
           v-text="role"
           :style="{color: !!roleCode ? getRoleBgColor(roleCode) : false}"/>
    </div>
  </div>
  <div class="bbn-left-sspace">
    <bbn-button icon="nf nf-fa-eye bbn-lg"
                :notext="true"
                class="bbn-h-100 bbn-no-border-top bbn-no-border-right bbn-no-border-bottom bbn-primary"
                @click="openTask"
                style="width: 2em"/>
  </div>
</div>