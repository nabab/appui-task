<bbn-form :action="root + 'actions/task/insert'"
          :source="source"
          @success="onSuccess">
  <div class="bbn-padding bbn-grid-fields">
    <label class="bbn-label"
           bbn-text="_('Title')"/>
    <bbn-input maxlength="255"
               :required="true"
               bbn-model="source.title"
               class="bbn-w-100"/>
    <label class="bbn-label"
           bbn-text="_('Type')"/>
    <bbn-dropdown :source="fullCategories"
                  bbn-model="source.type"
                  group="group"
                  :required="true"
                  placeholder="<?= _('Select a type...') ?>"
                  class="bbn-w-100"
                  :groupable="true"/>
    <template bbn-if="subtask">
      <label class="bbn-label"
             bbn-text="_('Subtask of')"/>
      <bbn-autocomplete :source="root + 'data/list'"
                        bbn-model="source.id_parent"
                        :required="true"
                        placeholder="<?= _('Search and select a task...') ?>"
                        class="bbn-w-100"
                        source-text="title"
                        source-value="id"/>
    </template>
    <label class="bbn-label"
           bbn-text="_('Private')"/>
    <bbn-checkbox bbn-model="source.private"
                  :value="1"
                  :novalue="0"/>
    <template bbn-if="roles">
      <label class="bbn-label"
             bbn-text="_('Copy roles from parent task')"/>
      <bbn-checkbox bbn-model="copyRoles"
                    :value="true"
                    :novalue="false"/>
    </template>
    <label class="bbn-label"
           bbn-text="_('Open after creation')"/>
    <bbn-checkbox bbn-model="openAfterCreation"
                  :value="true"
                  :novalue="false"/>
  </div>
</bbn-form>