<bbn-form :action="root + 'actions/task/insert'"
          :source="source"
          @success="onSuccess">
  <div class="bbn-padded bbn-grid-fields">
    <label class="bbn-label"
           v-text="_('Title')"/>
    <bbn-input maxlength="255"
               required="required"
               v-model="source.title"
               class="bbn-w-100"/>
    <label class="bbn-label"
           v-text="_('Type')"/>
    <bbn-dropdown :source="fullCategories"
                  v-model="source.type"
                  group="group"
                  source-value="id"
                  required="required"
                  placeholder="<?=_('Select a type...')?>"
                  class="bbn-w-100"
                  :groupable="true"/>
    <label class="bbn-label"
           v-text="_('Private')"/>
    <bbn-checkbox v-model="source.private"
                  :value="1"
                  :novalue="0"/>
    <label class="bbn-label"
           v-text="_('Open after creation')"/>
    <bbn-checkbox v-model="openAfterCreation"
                  :value="true"
                  :novalue="false"/>
  </div>
</bbn-form>