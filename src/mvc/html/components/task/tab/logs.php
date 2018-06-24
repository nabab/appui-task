<div class="appui-task-tab-logs">
  <bbn-table class="bbn-100"
             :source="tasks.source.root + 'page/logs'"
             :data="{id_task: source.id}"
  >
    <bbns-column title=" "
                field="id_user"
                :component="$options.components['appui-tasks-user-avatar']"
                :width="38"
    ></bbns-column>
    <bbns-column title="<?=_("User")?>"
                field="id_user"
                :width="150"
                :render="renderUser"
    ></bbns-column>
    <bbns-column title="<?=_("Date")?>"
                field="chrono"
                :width="150"
                :render="renderDate"
                cls="bbn-c"
    ></bbns-column>
    <bbns-column title="<?=_("Action")?>"
                field="action"
    ></bbns-column>
  </bbn-table>
</div>