<bbn-form :action="root + 'actions/task/insert'"
          :source="source"
          @success="onSuccess">
  <div class="bbn-padding bbn-grid-fields">
    <label class="bbn-label"
           bbn-text="_('Title')"/>
    <bbn-input maxlength="255"
               required="required"
               bbn-model="source.title"
               class="bbn-w-100"/>
    <label class="bbn-label"
           bbn-text="_('Type')"/>
    <bbn-dropdown :source="fullCategories"
                  bbn-model="source.type"
                  group="group"
                  required="required"
                  placeholder="<?= _('Select a type...') ?>"
                  class="bbn-w-100"
                  :groupable="true"/>
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