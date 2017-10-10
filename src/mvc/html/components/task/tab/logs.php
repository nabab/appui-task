<div class="appui-task-tab-logs">
  <bbn-table class="bbn-100"
             :source="root + 'panel/logs'"
             :data="{
              id_task: source.id
             }"
  >
    <bbn-column field="id_user"
                :component="$options.components['appui-tasks-user-avatar']"
                :width="38"
    ></bbn-column>
    <bbn-column title="<?=_("User")?>"
                field="id_user"
                :width="150"
                :render="renderUser"
    ></bbn-column>
    <bbn-column title="<?=_("Date")?>"
                field="chrono"
                :width="150"
                :render="renderDate"
    ></bbn-column>
    <bbn-column title="<?=_("Action")?>"
                field="action"
    ></bbn-column>
  </bbn-table>
</div>