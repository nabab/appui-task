<div class="appui-task-tab-logs">
  <bbn-table class="bbn-100"
             :source="tasks.source.root + 'page/logs'"
             :data="{id_task: source.id}"
  >
    <bbns-column title="<i class='nf nf-fa-user bbn-hsmargin'></i><?=_("User")?>"
                 field="id_user"
                 :width="190"
                 :component="$options.components['appui-tasks-user-avatar']"
    ></bbns-column>
    <bbns-column title="<i class='nf nf-fa-calendar bbn-hsmargin'></i><?=_("Date")?>"
                 field="chrono"
                 :width="150"
                 :render="renderDate"
                 cls="bbn-c"
    ></bbns-column>
    <bbns-column title="<i class='nf nf-fa-history bbn-hsmargin'></i><?=_("Action")?>"
                 field="action"
    ></bbns-column>
  </bbn-table>
</div>
