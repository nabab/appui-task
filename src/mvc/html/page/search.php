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
                  icon="nf nf-fa-flag_checkered"
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
               :filterable="true"
               :filters="filters"
    >
      <bbns-column title="<i class='nf nf-fa-user'></i>"
                   ftitle="<?=_('User')?>"
                   field="id_user"
                   :component="$options.components['appui-tasks-user-avatar']"
                   :width="38"
                   :source="users"
      ></bbns-column>
      <bbns-column title="<i class='nf nf-fa-exclamation'></i>"
                   ftitle="<?=_('Priority')?>"
                   field="priority"
                   :cls="priorityClass"
                   :width="40"
                   :source="priority"
      ></bbns-column>
      <bbns-column title="<i class='nf nf-fa-comment'></i>"
                   ftitle="<?=_('#Notes')?>"
                   field="num_notes"
                   :width="50"
                   cls="bbn-c"
                   :filterable="false"
      ></bbns-column>
      <bbns-column title="<i class='nf nf-fa-tasks'></i>"
                   ftitle="<?=_("States")?>"
                   field="state"
                   :render="renderState"
                   :width="50"
                   cls="bbn-h-100"
                   :source="tasks.source.options.states"
      ></bbns-column>
      <bbns-column title="<?=_("Last")?>"
                   field="last_action"
                   :render="renderLast"
                   :width="100"
                   type="datetime"
      ></bbns-column>
      <bbns-column title="<?=_("Role")?>"
                   field="role"
                   :render="renderRole"
                   :width="80"
                   :source="tasks.source.options.roles"
      ></bbns-column>
      <bbns-column title="<?=_("Type")?>"
                   field="type"
                   :render="renderType"
                   :width="150"
                   style="max-width: 300px"
                   :source="tasks.source.options.cats"
      ></bbns-column>
      <bbns-column title="<?=_("Duration")?>"
                   field="duration"
                   :render="renderDuration"
                   :width="70"
                   :filterable="false"
      ></bbns-column>
      <bbns-column title="<?=_("Title")?>"
                   field="title"
      ></bbns-column>
      <bbns-column title="<?=_("Reference")?>"
                   field="reference"
                   :filterable="false"
      ></bbns-column>
      <bbns-column title="<?=_("Creation Date")?>"
                   field="creation_date"
                   :render="renderCreationDate"
                   :width="100"
                   type="datetime"
      ></bbns-column>
      <bbns-column title="<?=_("Deadline")?>"
                   field="deadline"
                   :render="renderDeadline"
                   type="datetime"
      ></bbns-column>
      <bbns-column title=""
                   :width="40"
                   :buttons="[{
                     title: '<?=_('See task')?>',
                     icon: 'nf nf-fa-eye',
                     command: openTask,
                     notext: true
                   }]"
                   fixed="right"
      ></bbns-column>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>