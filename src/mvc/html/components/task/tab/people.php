<div class="appui-task-tab-people bbn-100">
  <div class="bbn-task-roles-container bbn-padded bbn-100">
    <div class="k-block bbn-task-assigned">
      <div class="k-header"><?=_("Supervisors")?></div>
      <div class="k-content bbn-task-managers" data-role-type="managers">
        <ul>
          <li class="k-item"
              v-for="e in managers"
              v-bind="{'id-user': e.id, 'id-groud': e.id_group}"
          >
            <span class="fancytree-node fancytree-lastsib fancytree-exp-nl fancytree-ico-c">
              <span class="fancytree-custom-icon" :class="e.icon"></span>
              <bbn-initial :user-id="e.id"></bbn-initial>
              <span class="fancytree-title" v-text="e.text"></span>
            </span>
          </li>
        </ul>
      </div>
    </div>
    <div class="bbn-spacer"></div>
    <div class="k-block bbn-task-assigned">
      <div class="k-header"><?=_("Workers")?></div>
      <div class="k-content bbn-task-workers" data-role-type="workers">
        <ul>
          <li class="k-item"
              v-for="e in workers"
              v-bind="{'id-user': e.id, 'id-groud': e.id_group}"
          >
            <span class="fancytree-node fancytree-lastsib fancytree-exp-nl fancytree-ico-c">
              <span class="fancytree-custom-icon" :class="e.icon"></span>
              <bbn-initial :user-id="e.id"></bbn-initial>
              <span class="fancytree-title" v-text="e.text"></span>
              <i class="fa fa-times bbn-p"
                 v-if="canChange()"
                 @click="removeUser(e.id, 'workers')"
              ></i>
            </span>
          </li>
        </ul>
      </div>
    </div>
    <div class="bbn-spacer"> </div>
    <div class="k-block bbn-task-assigned">
      <div class="k-header"><?=_("Spectators")?></div>
      <div class="k-content bbn-task-viewers" data-role-type="viewers">
        <ul>
          <li class="k-item"
              v-for="e in viewers"
              v-bind="{'id-user': e.id, 'id-groud': e.id_group}"
          >
            <span class="fancytree-node fancytree-lastsib fancytree-exp-nl fancytree-ico-c">
              <span class="fancytree-custom-icon" :class="e.icon"></span>
              <bbn-initial :user-id="e.id"></bbn-initial>
              <span class="fancytree-title" v-text="e.text"></span>
              <i class="fa fa-times bbn-p"
                 v-if="canChange()"
                 @click="removeUser(e.id, 'viewers')"
              ></i>
            </span>
          </li>
        </ul>
      </div>
    </div>
    <div class="bbn-form-full">
      <bbn-tree class="bbn-task-usertree"
                :source="appui_tasks.groups"
                ref="task_usertree"
                :cfg="{
                  expand: liDraggable,
                  extensions: ['filter'],
                  filter: {
                    autoExpand: false,
                    mode: 'hide',
                    counter: true,
                    hideExpandedCounter: true,
                    hideExpanders: true
                  }
                }"
      ></bbn-tree>
      <div class="bbn-task-roles-desc container-placeholder bbn-lg" v-if="!isClosed()">
        <span v-if="canChange()">
          <i class="fa fa-question-circle"> </i>
          <?=_("Drag and drop the users into the corresponding role block")?>
        </span>
        <span v-if="!canChange()">
                <i class="fa fa-exclamation-circle"> </i>
          <?=_("You have no right to modify the roles in this task")?>
              </span>
      </div>
      <div class="bbn-task-roles-desc container-placeholder bbn-lg" v-if="isClosed()">
        <i class="fa fa-exclamation-circle"> </i>
        <?=_("You cannot change the roles because the task is closed")?>
      </div>
    </div>
  </div>
</div>