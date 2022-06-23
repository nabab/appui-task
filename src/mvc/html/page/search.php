<!-- HTML Document -->
<bbn-splitter orientation="vertical"
              ref="task_splitter"
>
  <bbn-pane class="appui-task-search-container" :size="50">
    <div class="appui-task-toolbar bbn-middle bbn-h-100 bbn-flex-width bbn-padded">
      <bbn-dropdown name="selection"
                    class="bbn-xl bbn-right-space"
                    :source="typeSelection"
                    v-model="typeSelected"
                    v-if="typeSelection.length > 1"/>
      <bbn-input name="title"
                 class="bbn-xxl bbn-flex-fill"
                 ref="title"
                 :placeholder="_('Search or Title for the new task')"
                 v-model="taskTitle"/>
      <bbn-button class="bbn-xl bbn-left-space"
                  icon="nf nf-fa-flag_checkered"
                  @click="createTask"
                  :disabled="!taskTitle"
                  v-text="_('Create a task')"/>
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
               class="appui-task-search-table"
               :filterable="true"
               :filters="filters">
      <bbns-column title="<i class='nf nf-fa-user'></i>"
                   ftitle="<?=_('User')?>"
                   field="id_user"
                   :component="$options.components.useravatar"
                   :width="38"
                   :source="users"/>
      <bbns-column title="<i class='nf nf-fa-exclamation'></i>"
                   ftitle="<?=_('Priority')?>"
                   field="priority"
                   :cls="priorityClass"
                   :width="40"
                   :source="priority"/>
      <bbns-column title="<i class='nf nf-fa-comment'></i>"
                   ftitle="<?=_('#Notes')?>"
                   field="num_notes"
                   :width="50"
                   cls="bbn-c"
                   :filterable="false"/>
      <bbns-column title="<i class='nf nf-fa-tasks'></i>"
                   ftitle="<?=_("States")?>"
                   field="state"
                   :render="renderState"
                   :width="50"
                   cls="bbn-h-100 bbn-c"
                   :source="mainPage.source.options.states"/>
      <bbns-column title="<?=_("Last")?>"
                   field="last_action"
                   :render="renderLast"
                   :width="100"
                   type="datetime"/>
      <bbns-column title="<?=_("Role")?>"
                   field="role"
                   :render="renderRole"
                   :width="80"
                   :source="mainPage.source.options.roles"/>
      <bbns-column title="<?=_("Type")?>"
                   field="type"
                   :render="renderType"
                   :width="150"
                   style="max-width: 300px"
                   :source="mainPage.source.options.cats"/>
      <bbns-column title="<?=_("Duration")?>"
                   field="duration"
                   :render="renderDuration"
                   :width="70"
                   :filterable="false"/>
      <bbns-column title="<?=_("Title")?>"
                   field="title"/>
      <bbns-column title="<?=_("Reference")?>"
                   field="reference"
                   :filterable="false"/>
      <bbns-column title="<?=_("Creation Date")?>"
                   field="creation_date"
                   :render="renderCreationDate"
                   type="datetime"/>
      <bbns-column title="<?=_("Deadline")?>"
                   field="deadline"
                   :render="renderDeadline"
                   type="datetime"/>
      <bbns-column title=""
                   :width="50"
                   :buttons="[{
                     title: '<?=_('See task')?>',
                     icon: 'nf nf-fa-eye',
                     action: openTask,
                     notext: true
                   }]"
                   fixed="right"/>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>