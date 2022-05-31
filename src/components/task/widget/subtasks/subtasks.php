<div class="appui-task-task-widget-subtasks bbn-box bbn-flex-width">
  <div class="bbn-spadded bbn-flex-fill">
    <div class="bbn-grid-fields">
      <label class="bbn-label"><?=_('Title')?></label>
      <a class="bbn-flex-fill"
         v-text="source.title"
         :href="root + 'page/task/' + source.id"/>
      <label class="bbn-label"><?=_('Created on')?></label>
      <div v-text="creationDate"/>
      <label class="bbn-label"><?=_('Status')?></label>
      <div v-text="state"/>
      <label class="bbn-label"><?=_('Role')?></label>
      <div v-text="role"/>
    </div>
  </div>
  <div class="">
    <bbn-button icon="nf nf-fa-eye bbn-lg"
                :notext="true"
                class="bbn-h-100 bbn-no-border-top bbn-no-border-right bbn-no-border-bottom"
                @click="openTask"/>
  </div>
</div>