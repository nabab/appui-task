<div class="appui-task-task-info">
  <div class="bbn-flex-width bbn-bottom-space">
    <div class="bbn-vmiddle bbn-flex-fill">
      <bbn-initial :user-name="userName(source.id_user)"
                   width="1.2rem"
                   height="1.2rem"
                   font-size="0.7rem"/>
      <span class="bbn-left-xsspace bbn-s bbn-unselectable"
            v-text="isYou(source.id_user) ? _('You') : userName(source.id_user)"
            :title="_('Created by') + ' ' + userName(source.id_user)"/>
    </div>
    <div v-text="task.creation"
         :title="_('Created at')"
         class="bbn-s"/>
  </div>
  <!--<bbn-textarea v-model="source.title"
                class="bbn-lg"
                :readonly="!task.canChange"
                :maxlength="255"
                :rows="3"
                style="width: 100%"
                :placeholder="_('Title')"/>-->
  <bbn-rte v-model="source.title"
           :readonly="!task.canChange"
           :maxlength="255"
           style="width: 100%"
           :placeholder="_('Title')"
           height="12rem"/>
  <bbn-rte v-model="source.content"
           class="bbn-top-sspace"
           :readonly="!task.canChange"
           :placeholder="_('Description')"
           style="width: 100%"
           height="20rem"/>
  <div class="bbn-grid-fields bbn-top-space bbn-l"
       :style="{
         display: 'grid !important',
         'grid-row-gap': '0.5rem',
         'grid-template-columns': !!mainPage.isMobile() ? 'auto' : 'minmax(auto, max-content) auto minmax(auto, max-content) auto'
       }">
    <template v-if="!!source.id_parent && !!source.parent">
      <label><?=_('Parent task')?></label>
      <a v-text="source.parent.title"
          :href="root + 'page/task/' + source.id_parent"/>
    </template>
    <label v-if="source.reference"><?=_('External reference')?></label>
    <div v-if="source.reference"
          v-html="source.reference"/>
    <label><?=_('Category')?></label>
    <bbn-dropdown :source="categories"
                  v-model="source.type"
                  :value="source.type"
                  :disabled="!task.canChange"
                  group="group"
                  :groupable="true"/>
    <label><?=_('Priority')?></label>
    <bbn-dropdown v-model="source.priority"
                  style="width: 80px"
                  :source="[1,2,3,4,5,6,7,8,9]"
                  :disabled="!task.canChange"/>
    <label><?=_('Deadline')?></label>
    <div>
      <bbn-datetimepicker v-model="source.deadline"
                          @keydown="task.preventAll($event)"
                          :disabled="!task.canChange"
                          :min="minDate()"/>
      <bbn-button v-if="source.deadline && task.canChange"
                  @click="task.removeDeadline"
                  icon="nf nf-fa-times"
                  :notext="true"/>
    </div>
    <template v-if="!!source.documents && source.documents.length">
      <label><?=_('Documents')?></label>
      <div class="bbn-vmiddle"
            style="flex-wrap: wrap">
        <div v-for="(doc, i) in source.documents"
              :class="[
                'bbn-background',
                'bbn-radius',
                'bbn-hspadded',
                'bbn-vxspadded',
                'bbn-bordered',
                'bbn-p',
                'bbn-c',
                'bbn-top-sspace',
                {'bbn-right-sspace': !!source.documents[i+1]}
              ]"
              :title="doc.name"
              @click="downloadDoc(doc.id)">
          <i class="nf nf-fa-file_text"/>
          <span v-text="shorten(doc.name, 10)"/>
        </div>
      </div>
    </template>
  </div>
</div>
