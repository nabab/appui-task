<div class="bbn-margin appui-task-tab-people">
  <div class="bbn-task-roles-container">
    <div class="k-content">
      <div class="k-block bbn-task-assigned">
        <div class="k-header"><?=_("Supervisors")?></div>
        <div class="k-content bbn-task-managers">
          <bbn-input type="hidden" name="managers"></bbn-input>
          <ul></ul>
        </div>
      </div>
      <div class="bbn-spacer"> </div>
      <div class="k-block bbn-task-assigned">
        <div class="k-header"><?=_("Workers")?></div>
        <div class="k-content bbn-task-workers">
          <bbn-input type="hidden" name="workers"></bbn-input>
          <ul></ul>
        </div>
      </div>
      <div class="bbn-spacer"> </div>
      <div class="k-block bbn-task-assigned">
        <div class="k-header"><?=_("Spectators")?></div>
        <div class="k-content bbn-task-viewers">
          <bbn-input type="hidden" name="viewers"></bbn-input>
          <ul></ul>
        </div>
      </div>
      <div class="bbn-form-full">
        <div class="bbn-task-usertree"></div>
        <div class="bbn-task-roles-desc container-placeholder bbn-lg" v-if="!isClosed">
          <span v-if="canChange">
            <i class="fa fa-question-circle"> </i>
            <?=_("Drag and drop the users into the corresponding role block")?>
          </span>
          <span v-if="!canChange">
                  <i class="fa fa-exclamation-circle"> </i>
            <?=_("You have no right to modify the roles in this task")?>
                </span>
        </div>
        <div class="bbn-task-roles-desc container-placeholder bbn-lg" v-if="isClosed">
          <i class="fa fa-exclamation-circle"> </i>
          <?=_("You cannot change the roles because the task is closed")?>
        </div>
      </div>
    </div>
  </div>
</div>