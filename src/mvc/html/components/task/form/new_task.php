<bbn-form class="bbn-full-screen bbn-flex-height"
          :action="appui_tasks.root + 'actions/task/insert'"
          style="width: 100% !important;"
          ref="new_task_form"
>
  <div class="bbn-w-100 bbn-flex-fill">
    <div class="bbn-form-label"><?=_("Title")?></div>
    <div class="bbn-form-field">
      <bbn-input id="appui_task_form_title"
                 name="title"
                 class="bbn-form-field"
                 maxlength="255"
                 required="required"
                 style="width: 100%"
                 :value="taskTitle"
      ></bbn-input>
    </div>
    <div class="bbn-form-label"><?=_("Type")?></div>
    <div class="bbn-form-field">
      <bbn-dropdowntreeview id="appui_task_form_type"
                            name="type"
                            style="width: 60%"
                            required="required"
                            placeholder="<?=_('Select a type...')?>"
                            :source="appui_tasks.categories"
                            data-value-field="id"
      ></bbn-dropdowntreeview>
    </div>
  </div>
  <div class="bbn-block bbn-w-100 k-edit-buttons k-state-default" align="right" style="bottom: 0">
    <bbn-button type="submit" icon="fa fa-save"><?=_("Save")?></bbn-button>
  </div>
</bbn-form>