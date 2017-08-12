<!-- HTML Document -->

<bbn-splitter orientation="vertical" class="bbn-full-screen" ref="task_splitter">
  <div class="bbn-task-search-container" style="height: 50px">
    <div class="bbn-form-full bbn-c">
      <div class="bbn-block" style="margin: 0 2em">
        <bbn-dropdown name="selection" class="bbn-xl" :source="typeSelection" v-model="typeSelected"></bbn-dropdown>
      </div>
      <div class="bbn-block bbn-full-width">
        <bbn-input name="title" class="bbn-xl bbn-full-width" ref="title" placeholder="<?=_("Search or Title for the new task")?>" v-model="taskTitle"></bbn-input>
      </div>
      <div class="bbn-block" style="margin: 0 2em">
        <bbn-button class="bbn-xl" icon="fa fa-flag-checkered" @click="createTask"><?=_("Create a task")?></bbn-button>
      </div>
    </div>
  </div>
  <div class="bbn-task-results-container">
    <bbn-table class="bbn-w-100 bbn-full-height" :source="tableData">
      <bbn-column field="id_user"
                  :render="renderUserAvatar"
                  :width="50"
      ><?=_("User")?></bbn-column>
      <bbn-column field="priority"
                  :render="renderPriority"
      ><?=_("Priority")?></bbn-column>
      <bbn-column field="num_notes"
                  :width="50"
      ><?=_("#Notes")?></bbn-column>
      <bbn-column field="state"
                  :render="renderState"
                  cls="bbn-c"
                  :width="50"
      ><?=_("States")?></bbn-column>
      <bbn-column field="last_action"
                  :render="renderLast"
                  style="width: 100px"
      ><?=_("Last")?></bbn-column>
      <bbn-column field="role"
                  :render="renderRole"
                  style="width: 80px"
      ><?=_("Role")?></bbn-column>
      <bbn-column field="type"
                  :render="renderType"
                  style="width: 150px; max-width: 300px"
      ><?=_("Type")?></bbn-column>
      <bbn-column field="duration"
                  :render="renderDuration"
                  :width="70"
      ><?=_("Duration")?></bbn-column>
      <bbn-column field="title"><?=_("Title")?></bbn-column>
      <bbn-column field="reference"><?=_("Reference")?></bbn-column>
      <bbn-column field="creation_date"
                  :render="renderCreationDate"
      ><?=_("Creation Date")?></bbn-column>
      <bbn-column field="deadline"
                  :render="renderDeadline"
      ><?=_("Deadline")?></bbn-column>
      <bbn-column field="id"
                  :render="renderId"
                  :width="50"
      > </bbn-column>
    </bbn-table>
    <!--<div class="bbn-task-gantt bbn-h-100"></div>-->
  </div>
</bbn-splitter>