<bbn-form :action="root + 'actions/task/insert'"
          :source="source"
          @success="onSuccess">
  <div class="bbn-padded bbn-grid-fields">
    <div><?=_('Title')?></div>
    <bbn-input maxlength="255"
               required="required"
               v-model="source.title"
               class="bbn-w-100"/>
    <div><?=_('Type')?></div>
    <bbn-dropdown :source="fullCategories"
                  v-model="source.type"
                  group="group"
                  source-value="id"
                  required="required"
                  placeholder="<?=_('Select a type...')?>"
                  class="bbn-w-100"/>
  </div>
</bbn-form>