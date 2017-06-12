<!-- HTML Document -->

<!--<bbn-splitter orientation="vertical" class="bbn-full-screen" ref="task_splitter">
  <div class="bbn-task-search-container" style="height: 50px">
    <div class="bbn-form-full bbn-c">
      <div class="bbn-block" style="margin: 0 2em">
        <bbn-dropdown name="selection" class="bbn-xl" :source="typeSelection" v-model="typeSelected"></bbn-dropdown>
      </div>
      <div class="bbn-block bbn-full-width">
        <bbn-input name="title" class="bbn-xl bbn-full-width" placeholder="<?/*=_("Search or Title for the new task")*/?>" v-model="taskTitle"></bbn-input>
      </div>
      <div class="bbn-block" style="margin: 0 2em">
        <bbn-button class="bbn-xl" icon="fa fa-flag-checkered" @click="createTask"><?/*=_("Create a task")*/?></bbn-button>
      </div>
    </div>
  </div>
  <div class="bbn-task-results-container">-->
    <bbn-table class="bbn-w-100 bbn-full-height" :source="tableData">
      <table>
        <thead>
          <tr>
            <th field="id_user" style="width: 50px"><?=_("User")?></th>
            <th field="priority"><?=_("Priority")?></th>
            <th field="num_notes" style="width: 50px"><?=_("#Notes")?></th>
            <th field="state" source="states" render="renderState" style="width: 50px; text-align: center"><?=_("States")?></th>
            <th field="last_action" render="renderLast" style="width: 100px"><?=_("Last")?></th>
            <th field="role" render="renderRole" style="width: 80px"><?=_("Role")?></th>
            <th field="type" render="renderType" style="width: 150px; max-width: 300px"><?=_("Type")?></th>
            <th field="duration" render="renderDuration" style="width: 70px"><?=_("Duration")?></th>
            <th field="title"><?=_("Title")?></th>
            <th field="reference"><?=_("Reference")?></th>
            <th field="creation_date" render="renderCreationDate" type="date"><?=_("Creation Date")?></th>
            <th field="deadline" render="renderDeadline"><?=_("Deadline")?></th>
            <th field="id" render="renderId" style="width: 50px"></th>
          </tr>
        </thead>
      </table>
    </bbn-table>
    <!--<div class="bbn-task-gantt bbn-h-100"></div>
  </div>
</bbn-splitter>-->