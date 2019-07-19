<div class="appui-task-tab-people bbn-overlay">
  <div class="bbn-task-roles-container bbn-flex-height">
    <div class="bbn-padded">
      <div :class="['bbn-block', 'bbn-widget', 'bbn-task-assigned', {over: targetContainer === 'managers'}]"
           @mouseover="setTargetContainer('managers')"
           @mouseleave="targetContainer = false"
      >
        <div class="bbn-header bbn-b"><i class="nf nf-fa-black_tie bbn-hsmargin"></i><?=_("Supervisors")?></div>
        <div class="bbn-task-managers">
          <ul>
            <li v-for="e in managers"
                v-bind="{'id-user': e.value, 'id-group': e.id_group}"
                class="bbn-vsmargin bbn-hlmargin"
            >
              <div class="bbn-flex-width bbn-vmiddle">
                <i :class="e.icon"></i>
                <bbn-initial :user-id="e.value"
                             :height="20"
                             :width="20"
                             class="bbn-hsmargin"
                ></bbn-initial>
                <span v-text="e.text"
                      class="bbn-flex-fill"
                ></span>
                <i class="nf nf-fa-trash_alt bbn-p bbn-red"
                   v-if="isMaster && (source.id_user !== e.value)"
                   @click="removeUser(e.value, 'managers')"
                   :title="'<?=_('Remove')?> ' + e.text"
                ></i>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="bbn-spacer"></div>
      <div :class="['bbn-block', 'bbn-widget', 'bbn-task-assigned', {over: targetContainer === 'workers'}]"
           @mouseover="setTargetContainer('workers')"
           @mouseleave="targetContainer = false"
      >
        <div class="bbn-header bbn-b"><i class="nf nf-fa-user_circle_o bbn-hsmargin"></i><?=_("Workers")?></div>
        <div class="bbn-task-workers">
          <ul>
            <li v-for="e in workers"
                v-bind="{'id-user': e.value, 'id-group': e.id_group}"
                class="bbn-vsmargin bbn-hlmargin"
            >
              <div class="bbn-flex-width bbn-vmiddle">
                <i :class="e.icon"></i>
                <bbn-initial :user-id="e.value"
                             :height="20"
                             :width="20"
                             class="bbn-hsmargin"
                ></bbn-initial>
                <span v-text="e.text"
                      class="bbn-flex-fill"
                ></span>
                <i class="nf nf-fa-trash_alt bbn-p bbn-red"
                   v-if="canChange"
                   @click="removeUser(e.value, 'workers')"
                   :title="'<?=_('Remove')?> ' + e.text"
                ></i>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="bbn-spacer"> </div>
      <div :class="['bbn-block','bbn-widget', 'bbn-task-assigned', {over: targetContainer === 'viewers'}]"
           @mouseover="setTargetContainer('viewers')"
           @mouseleave="targetContainer = false"
      >
        <div class="bbn-header bbn-b"><i class="nf nf-fa-user_secret bbn-hsmargin"></i><?=_("Spectators")?></div>
        <div class="bbn-task-viewers">
          <ul>
            <li v-for="e in viewers"
                v-bind="{'id-user': e.value, 'id-group': e.id_group}"
                class="bbn-vsmargin bbn-hlmargin"
            >
              <div class="bbn-flex-width bbn-vmiddle">
                <i :class="e.icon"></i>
                <bbn-initial :user-id="e.value"
                             :height="20"
                             :width="20"
                             class="bbn-hsmargin"
                ></bbn-initial>
                <span v-text="e.text"
                      class="bbn-flex-fill"
                ></span>
                <i class="nf nf-fa-trash_alt bbn-p bbn-red"
                   v-if="canChange"
                   @click="removeUser(e.value, 'viewers')"
                   :title="'<?=_('Remove')?> ' + e.text"
                ></i>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="bbn-w-100 bbn-flex-fill bbn-flex-height bbn-padded">
      <bbn-tree v-if="canChange"
                class="bbn-task-usertree bbn-flex-fill"
                :source="groupsFiltered"
                ref="task_usertree"
                :draggable="true"
                :self-drop="false"
                @dragEnd="dragEnd"
      ></bbn-tree>
      <div class="bbn-task-roles-desc bbn-lg" v-if="!isClosed">
        <span v-if="canChange">
          <i class="nf nf-fa-question_circle"> </i>
          <?=_("Drag and drop the users into the corresponding role block")?>
        </span>
        <span v-if="!canChange">
          <i class="nf nf-fa-exclamation_circle"> </i>
          <?=_("You have no right to modify the roles in this task")?>
        </span>
      </div>
      <div class="bbn-task-roles-desc bbn-lg" v-if="isClosed">
        <i class="nf nf-fa-exclamation_circle"> </i>
        <?=_("You cannot change the roles because the task is closed")?>
      </div>
    </div>
  </div>
</div>
