<!-- HTML Document -->
<bbn-splitter orientation="vertical"
              ref="task_splitter"
>
  <bbn-pane class="bbn-task-search-container" :size="50">
    <div class="bbn-task-toolbar bbn-middle bbn-h-100 bbn-flex-width">
      <bbn-dropdown name="selection"
                    class="bbn-xl"
                    :source="typeSelection"
                    v-model="typeSelected"
                    style="margin: 0 2em"
      ></bbn-dropdown>
      <bbn-input name="title"
                 class="bbn-xl bbn-flex-fill"
                 ref="title"
                 placeholder="<?=_("Search or Title for the new task")?>"
                 v-model="taskTitle"
      ></bbn-input>
      <bbn-button class="bbn-xl"
                  icon="fa fa-flag-checkered"
                  @click="createTask"
                  style="margin: 0 2em"
                  :disabled="!taskTitle"
      >
        <?=_("Create a task")?>
      </bbn-button>
    </div>
  </bbn-pane>
  <bbn-pane>
    <bbn-table :source="source.root + 'list'"
               :data="{
                 selection: typeSelected,
                 title: taskTitle
               }"
               ref="tasksTable"
               :info="true"
               :pageable="true"
               :sortable="true"
               :order="[{field: 'last_action', dir: 'DESC'}]"
               class="appui-tasks-search-table"
    >
      <bbn-column title="<i class='fa fa-user'></i>"
                  ftitle="<?=_('User')?>"
                  field="id_user"
                  :component="$options.components['appui-tasks-user-avatar']"
                  :width="38"
      ></bbn-column>
      <bbn-column title="<i class='fa fa-exclamation'></i>"
                  ftitle="<?=_('Priority')?>"
                  field="priority"
                  :render="renderPriority"
                  :cls="priorityClass"
                  :width="40"
      ></bbn-column>
      <bbn-column title="<i class='fa fa-comment'></i>"
                  ftitle="<?=_('#Notes')?>"
                  field="num_notes"
                  :width="50"
                  cls="bbn-c"
      ></bbn-column>
      <bbn-column title="<i class='fa fa-tasks'></i>"
                  ftitle="<?=_("States")?>"
                  field="state"
                  :render="renderState"
                  :width="50"
                  cls="bbn-h-100"
      ></bbn-column>
      <bbn-column title="<?=_("Last")?>"
                  field="last_action"
                  :render="renderLast"
                  :width="100"
      ></bbn-column>
      <bbn-column title="<?=_("Role")?>"
                  field="role"
                  :render="renderRole"
                  :width="80"
      ></bbn-column>
      <bbn-column title="<?=_("Type")?>"
                  field="type"
                  :render="renderType"
                  :width="150"
                  style="max-width: 300px"
      ></bbn-column>
      <bbn-column title="<?=_("Duration")?>"
                  field="duration"
                  :render="renderDuration"
                  :width="70"
      ></bbn-column>
      <bbn-column title="<?=_("Title")?>"
                  field="title"
      ></bbn-column>
      <bbn-column title="<?=_("Reference")?>"
                  field="reference"
      ></bbn-column>
      <bbn-column title="<?=_("Creation Date")?>"
                  field="creation_date"
                  :render="renderCreationDate"
                  :width="100"
      ></bbn-column>
      <bbn-column title="<?=_("Deadline")?>"
                  field="deadline"
                  :render="renderDeadline"
      ></bbn-column>
      <bbn-column title=""
                  :width="40"
                  :buttons="[{
                    title: '<?=_('See task')?>',
                    icon: 'fa fa-eye',
                    command: openTask,
                    notext: true
                  }]"
                  fixed="right"
      ></bbn-column>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>