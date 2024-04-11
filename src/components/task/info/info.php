<div class="appui-task-task-info">
  <div class="bbn-flex-width bbn-bottom-sspace">
    <div class="bbn-vmiddle bbn-flex-fill">
      <span class="bbn-right-sspace bbn-radius bbn-xspadded bbn-s bbn-background bbn-text"
            bbn-text="source.ref"
            :title="_('Reference number')"/>
      <span class="bbn-vmiddle bbn-right-spadded bbn-radius bbn-background bbn-text">
        <bbn-initial :user-name="userName(source.id_user)"
                    width="1.2rem"
                    height="1.2rem"
                    font-size="0.7rem"
                    style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important"/>
        <span class="bbn-left-xsspace bbn-s bbn-unselectable"
              bbn-text="isYou(source.id_user) ? _('You') : userName(source.id_user)"
              :title="_('Created by') + ' ' + userName(source.id_user)"/>
      </span>
    </div>
    <div bbn-text="task.creation"
         :title="_('Created at')"
         class="bbn-s bbn-background bbn-text bbn-xspadded bbn-radius"/>
  </div>
  <bbn-textarea bbn-model="source.title"
                class="bbn-lg"
                :readonly="!task.canChange"
                :maxlength="255"
                :rows="3"
                style="width: 100%"
                :placeholder="_('Title')"/>
  <bbn-rte bbn-model="source.content"
           class="bbn-top-sspace"
           :readonly="!task.canChange"
           :placeholder="_('Description')"
           style="width: 100%; min-height: 20rem"
           height="auto"/>
  <div class="bbn-grid-fields bbn-top-space bbn-l"
       :style="{
         display: 'grid !important',
         'grid-row-gap': '0.5rem',
         'grid-template-columns': !!mainPage.isMobile() ? 'auto' : 'minmax(auto, max-content) auto minmax(auto, max-content) auto'
       }">
    <template bbn-if="!!source.id_parent && !!source.parent">
      <label><?= _('Parent task') ?></label>
      <a bbn-text="source.parent.title"
          :href="root + 'page/task/' + source.id_parent"/>
    </template>
    <label bbn-if="source.reference"><?= _('External reference') ?></label>
    <div bbn-if="source.reference"
          bbn-html="source.reference"/>
    <label><?= _('Category') ?></label>
    <bbn-dropdown :source="categories"
                  bbn-model="source.type"
                  :value="source.type"
                  :disabled="!task.canChange"
                  group="group"
                  :groupable="true"/>
    <label><?= _('Priority') ?></label>
    <bbn-dropdown bbn-model="source.priority"
                  style="width: 80px"
                  :source="mainPage.priorities"
                  source-cls="class"
                  :component="$options.components.priority"
                  :disabled="!task.canChange"/>
    <label><?= _('Deadline') ?></label>
    <div>
      <bbn-datetimepicker bbn-model="source.deadline"
                          @keydown="task.preventAll($event)"
                          :disabled="!task.canChange"
                          :min="minDate()"/>
      <bbn-button bbn-if="source.deadline && task.canChange"
                  @click="task.removeDeadline"
                  icon="nf nf-fa-times"
                  :notext="true"/>
    </div>
    <template bbn-if="!!source.documents && source.documents.length">
      <label><?= _('Documents') ?></label>
      <div class="bbn-vmiddle"
            style="flex-wrap: wrap">
        <div bbn-for="(doc, i) in source.documents"
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
          <span bbn-text="shorten(doc.name, 10)"/>
        </div>
      </div>
    </template>
  </div>
</div>
