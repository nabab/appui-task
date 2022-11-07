<div class="appui-task-task-widget-info bbn-grid-fields">
  <label><?=_('Title')?></label>
  <bbn-textarea v-model="source.title"
              class="bbn-flex-fill bbn-xl"
              :readonly="!task.canChange"
              :maxlength="100"
              :rows="4"/>
  <label><?=_('Created by')?></label>
  <div class="bbn-vmiddle">
    <bbn-initial :user-id="source.id_user"
                  :height="25"
                  :width="25"
                  font-size="1em"/>
    <span v-text="userName(source.id_user)"
          style="margin-left: 5px"/>
  </div>
  <label><?=_('Created on')?></label>
  <div v-text="task.creation"/>
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
