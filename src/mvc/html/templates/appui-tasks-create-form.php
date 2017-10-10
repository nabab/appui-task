<bbn-form :action="root + 'actions/task/insert'"
          :source="source"
          @success="refreshTable"
          class="bbn-full-screen"
>
  <div class="bbn-padded bbn-grid-fields">
    <div><?=_("Title")?></div>
    <bbn-input maxlength="255"
               required="required"
               v-model="source.title"
               class="bbn-w-100"
    ></bbn-input>
    <div><?=_("Type")?></div>
    <bbn-dropdown :source="categories"
                  v-model="source.type"
                  group="group"
                  :cfg="{
                      dataValueField: 'id'
                    }"
                  required="required"
                  placeholder="<?=_('Select a type...')?>"
                  class="bbn-w-100"
    ></bbn-dropdown>
  </div>
</bbn-form>