<div :class="['appui-task-task-actions', {'bbn-vmiddle': !mainPage.isMobile()}]"
     style="flex-wrap: wrap">
  <template bbn-if="mainPage.isMobile()">
    <template bbn-if="showBudget">
      <div class="appui-task-task-actions-title bbn-upper bbn-b bbn-secondary-text-alt bbn-padded bbn-alt-background bbn-radius"
           bbn-text="_('Budget')"/>
      <div bbn-if="task.canApprove"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.approve() : () => {}"
           :style="{
             color: getStatusBgColor('approved')
           }">
        <i class="bbn-m nf nf-fa-thumbs_up"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Approve')"/>
      </div>
      <div bbn-if="task.canChangeBudget && !source.price && !source.parent_has_price && !source.children_price"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.addPrice() : () => {}">
        <i class="bbn-m nf nf-md-cash_plus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Add price')"/>
      </div>
      <div bbn-if="task.canChangeBudget && !!source.lastChangePrice && !!source.price"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.editPrice() : () => {}">
        <i class="bbn-m nf nf-md-credit_card_edit"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Edit price')"/>
      </div>
      <div bbn-if="task.canChangeBudget && !!source.lastChangePrice && !!source.price"
           class="bbn-vmiddle bbn-red bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.removePrice() : () => {}">
        <i class="bbn-m nf nf-md-trash_can"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Remove price')"/>
      </div>
    </template>
    <template bbn-if="showStatus">
      <div class="appui-task-task-actions-title bbn-upper bbn-b bbn-secondary-text-alt bbn-padded bbn-alt-background bbn-radius"
           bbn-text="_('Status')"/>
      <div bbn-if="task.isActive && !task.isUnapproved && task.canStart"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.start() : () => {}"
           :style="{
             color: getStatusBgColor('ongoing')
           }">
        <i class="bbn-m nf nf-fa-play"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Put on ongoing')"/>
      </div>
      <div bbn-if="task.isActive && !task.isUnapproved && task.canHold"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.hold() : () => {}"
           :style="{
             color: getStatusBgColor('holding')
           }">
        <i class="bbn-m nf nf-fa-pause"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Put on hold')"/>
      </div>
      <div bbn-if="(task.isActive || task.isHolding) && !task.isUnapproved && task.canResume"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.resume() : () => {}"
           :style="{
             color: getStatusBgColor('ongoing')
           }">
        <i class="bbn-m nf nf-fa-play"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Resume')"/>
      </div>
      <div bbn-if="(task.isActive || task.isUnapproved) && task.canClose"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.close() : () => {}"
           :style="{
             color: getStatusBgColor('closed')
           }">
        <i class="bbn-m nf nf-fa-check"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Close')"/>
      </div>
      <div bbn-if="task.isClosed && task.canReopen"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.reopen() : () => {}"
           :style="{
             color: getStatusBgColor('opened')
           }">
        <i class="bbn-m nf nf-oct-issue_reopened"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Reopen')"/>
      </div>
      <div bbn-if="task.isActive && task.canCancel"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.cancel() : () => {}"
           :style="{
             color: getStatusBgColor('canceled')
           }">
        <i class="bbn-m nf nf nf-fa-remove"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Cancel')"/>
      </div>
      <div bbn-if="task.canRemoveTask"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.removeTask : () => {}"
           :style="{
             color: getStatusBgColor('deleted')
           }">
        <i class="bbn-m nf nf-fa-trash"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Delete')"/>
      </div>
    </template>
    <template bbn-if="showRoles">
      <div class="appui-task-task-actions-title bbn-upper bbn-b bbn-secondary-text-alt bbn-padded bbn-alt-background bbn-radius"
           bbn-text="_('Roles')"/>
      <div bbn-if="task.canBecomeManager"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.makeMe('managers') : () => {}"
           :style="{
             color: getRoleBgColor('managers')
           }">
        <i class="bbn-m nf nf-mdi-account_plus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Make me a supervisor')"/>
      </div>
      <div bbn-if="task.canRemoveHimselfManager"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.unmakeMe('managers') : () => {}"
           :style="{
             color: getRoleBgColor('managers')
           }">
        <i class="bbn-m nf nf-mdi-account_minus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Remove me from supervisors')"/>
      </div>
      <div bbn-if="task.canBecomeWorker"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.makeMe('workers') : () => {}"
           :style="{
             color: getRoleBgColor('workers')
           }">
        <i class="bbn-m nf nf-mdi-account_plus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Make me a worker')"/>
      </div>
      <div bbn-if="task.canRemoveHimselfWorker"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.unmakeMe('workers') : () => {}"
           :style="{
             color: getRoleBgColor('workers')
           }">
        <i class="bbn-m nf nf-mdi-account_minus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Remove me from workers')"/>
      </div>
      <div bbn-if="task.canBecomeViewer"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.makeMe('viewers') : () => {}"
           :style="{
             color: getRoleBgColor('viewers')
           }">
        <i class="bbn-m nf nf-mdi-account_plus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Make me a spectator')"/>
      </div>
      <div bbn-if="task.canRemoveHimselfViewer"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.unmakeMe('viewers') : () => {}"
           :style="{
             color: getRoleBgColor('viewers')
           }">
        <i class="bbn-m nf nf-mdi-account_minus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Remove me from viewers')"/>
      </div>
      <div bbn-if="task.canBecomeDecider"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.makeMe('deciders') : () => {}"
           :style="{
             color: getRoleBgColor('deciders')
           }">
        <i class="bbn-m nf nf-mdi-account_plus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Make me a decider')"/>
      </div>
      <div bbn-if="task.canRemoveHimselfDecider"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.unmakeMe('deciders') : () => {}"
           :style="{
             color: getRoleBgColor('deciders')
           }">
        <i class="bbn-m nf nf-mdi-account_minus"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Remove me from deciders')"/>
      </div>
    </template>
    <template bbn-if="showTracker">
      <div class="appui-task-task-actions-title bbn-upper bbn-b bbn-secondary-text-alt bbn-padded bbn-alt-background bbn-radius"
           bbn-text="_('Tracker')"/>
      <div bbn-if="source.tracker"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom bbn-red"
           @click="task ? task.stopTracker() : () => {}">
        <i class="bbn-m nf nf-mdi-timer_off"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Stop tracker')"/>
      </div>
      <div bbn-else
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom bbn-green"
           @click="task ? task.startTracker() : () => {}">
        <i class="bbn-m nf nf-mdi-timer"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Start tracker')"/>
      </div>
    </template>
    <template bbn-if="showOther">
      <div class="appui-task-task-actions-title bbn-upper bbn-b bbn-secondary-text-alt bbn-padded bbn-alt-background bbn-radius"
           bbn-text="_('Other')"/>
      <div bbn-if="task.canPing"
           class="bbn-vmiddle bbn-padded appui-task-task-actions-item bbn-bordered-bottom"
           @click="task ? task.ping() : () => {}">
        <i class="bbn-m nf nf-fa-hand_pointer_o"/>
        <span class="bbn-left-sspace"
              bbn-text="_('Ping workers')"/>
      </div>
    </template>
  </template>
  <template bbn-else>
    <div bbn-if="showBudget"
         class="bbn-vmiddle bbn-right-lspace bbn-vxsmargin">
      <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
           bbn-text="_('Budget')"/>
      <div class="bbn-vmiddle">
        <div>
          <bbn-button bbn-if="task.canApprove"
                      @click="task ? task.approve() : () => {}"
                      title="<?= _('Approve') ?>"
                      icon="nf nf-fa-thumbs_up"
                      :notext="true"
                      :style="{
                        backgroundColor: getStatusBgColor('approved'),
                        color: getStatusColor('approved')
                      }"/>
          <bbn-button bbn-if="task.canChangeBudget && !source.price && !source.parent_has_price && !source.children_price"
                      icon="nf nf-md-cash_plus"
                      title="<?= _('Add price') ?>"
                      @click="task ? task.editPrice() : () => {}"
                      class="bbn-hsmargin"
                      :notext="true"/>
          <bbn-button bbn-if="task.canChangeBudget && !!source.lastChangePrice && !!source.price"
                      icon="nf nf-md-credit_card_edit"
                      title="<?= _('Edit price') ?>"
                      @click="task ? task.editPrice() : () => {}"
                      class="bbn-hsmargin"
                      :notext="true"/>
          <bbn-button bbn-if="task.canChangeBudget && !task.isClosed && !!source.lastChangePrice && !!source.price"
                      icon="nf nf-md-trash_can"
                      title="<?= _('Remove price') ?>"
                      @click="task ? task.removePrice() : () => {}"
                      :notext="true"
                      class="bbn-white bbn-bg-red"/>
        </div>
      </div>
    </div>
    <div bbn-if="showStatus"
         class="bbn-vmiddle bbn-right-lspace bbn-vxsmargin">
      <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
           bbn-text="_('Status')"/>
      <div class="bbn-vmiddle">
        <div>
          <bbn-button bbn-if="task.isActive && !task.isUnapproved && task.canStart"
                      @click="task ? task.start() : () => {}"
                      title="<?= _('Put on ongoing') ?>"
                      icon="nf nf-fa-play"
                      :notext="true"
                      :style="{
                        backgroundColor: getStatusBgColor('ongoing'),
                        color: getStatusColor('ongoing')
                      }"/>
          <bbn-button bbn-if="task.isActive && !task.isUnapproved && task.canHold"
                      @click="task ? task.hold() : () => {}"
                      title="<?= _('Put on hold') ?>"
                      icon="nf nf-fa-pause"
                      :notext="true"
                      :style="{
                        backgroundColor: getStatusBgColor('holding'),
                        color: getStatusColor('holding')
                      }"/>
          <bbn-button bbn-if="(task.isActive || task.isHolding) && !task.isUnapproved && task.canResume"
                      @click="task ? task.resume() : () => {}"
                      title="<?= _('Resume') ?>"
                      icon="nf nf-fa-play"
                      :notext="true"
                      :style="{
                        backgroundColor: getStatusBgColor('ongoing'),
                        color: getStatusColor('ongoing')
                      }"/>
          <bbn-button bbn-if="(task.isActive || task.isUnapproved) && task.canClose"
                      @click="task ? task.close : () => {}"
                      title="<?= _('Close')  ?>"
                      icon="nf nf-fa-check"
                      :notext="true"
                      :style="{
                        backgroundColor: getStatusBgColor('closed'),
                        color: getStatusColor('closed')
                      }"/>
          <bbn-button bbn-if="task.isClosed && task.canReopen"
                      @click="task ? task.reopen() : () => {}"
                      title="<?= _('Reopen') ?>"
                      icon="nf nf-oct-issue_reopened"
                      :notext="true"
                      :style="{
                        backgroundColor: getStatusBgColor('opened'),
                        color: getStatusColor('opened')
                      }"/>
          <span bbn-if="(task.isActive && task.canCancel) || task.canRemoveTask"
                class="bbn-left-space">
            <bbn-button bbn-if="task.isActive && task.canCancel"
                        @click="task ? task.cancel() : () => {}"
                        title="<?= _('Cancel')  ?>"
                        icon="nf nf-fa-remove"
                        :notext="true"
                        :style="{
                          backgroundColor: getStatusBgColor('canceled'),
                          color: getStatusColor('canceled')
                        }"/>
            <bbn-button bbn-if="task.canRemoveTask"
                        @click="task ? task.removeTask() : () => {}"
                        title="<?= _('Delete')  ?>"
                        icon="nf nf-fa-trash"
                        :notext="true"
                        class="bbn-bg-red bbn-white"/>
          </span>
        </div>
      </div>
    </div>
    <div bbn-if="showRoles"
         class="bbn-vmiddle bbn-right-lspace bbn-vxsmargin">
      <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
           bbn-text="_('Roles')"/>
      <div class="bbn-vmiddle">
        <div>
          <bbn-button bbn-if="task.canBecomeManager"
                      @click="task ? task.makeMe('managers') : () => {}"
                      icon="nf nf-mdi-account_plus"
                      title="<?= _('Make me a supervisor') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('managers'),
                        color: getRoleColor('managers')
                      }"/>
          <bbn-button bbn-if="task.canRemoveHimselfManager"
                      @click="task ? task.unmakeMe('managers') : () => {}"
                      icon="nf nf-mdi-account_minus"
                      title="<?= _('Remove me from supervisors') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('managers'),
                        color: getRoleColor('managers')
                      }"/>
          <bbn-button bbn-if="task.canBecomeWorker"
                      @click="task ? task.makeMe('workers') : () => {}"
                      icon="nf nf-mdi-account_plus"
                      title="<?= _('Make me a worker') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('workers'),
                        color: getRoleColor('workers')
                      }"/>
          <bbn-button bbn-if="task.canRemoveHimselfWorker"
                      @click="task ? task.unmakeMe('workers') : () => {}"
                      icon="nf nf-mdi-account_minus"
                      title="<?= _('Remove me from workers') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('workers'),
                        color: getRoleColor('workers')
                      }"/>
          <bbn-button bbn-if="task.canBecomeViewer"
                      @click="task ? task.makeMe('viewers') : () => {}"
                      icon="nf nf-mdi-account_plus"
                      title="<?= _('Make me a spectator') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('viewers'),
                        color: getRoleColor('viewers')
                      }"/>
          <bbn-button bbn-if="task.canRemoveHimselfViewer"
                      @click="task ? task.unmakeMe('viewers') : () => {}"
                      icon="nf nf-mdi-account_minus"
                      title="<?= _('Remove me from viewers') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('viewers'),
                        color: getRoleColor('viewers')
                      }"/>
          <bbn-button bbn-if="task.canBecomeDecider"
                      @click="task ? task.makeMe('deciders') : () => {}"
                      icon="nf nf-mdi-account_plus"
                      title="<?= _('Make me a decider') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('deciders'),
                        color: getRoleColor('deciders')
                      }"/>
          <bbn-button bbn-if="task.canRemoveHimselfDecider"
                      @click="task ? task.unmakeMe('deciders') : () => {}"
                      icon="nf nf-mdi-account_minus"
                      title="<?= _('Remove me from deciders') ?>"
                      :notext="true"
                      :style="{
                        backgroundColor: getRoleBgColor('deciders'),
                        color: getRoleColor('deciders')
                      }"/>
        </div>
      </div>
    </div>
    <div bbn-if="showTracker"
         class="bbn-vmiddle bbn-right-lspace bbn-vxsmargin">
      <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
           bbn-text="_('Tracker')"/>
      <div class="bbn-vmiddle">
        <div>
          <bbn-button bbn-if="source.tracker"
                      @click="task ? task.stopTracker() : () => {}"
                      :title="_('Stop tracker')"
                      icon="nf nf-mdi-timer_off"
                      class="bbn-bg-red bbn-white"
                      :notext="true"/>
          <bbn-button bbn-else
                      @click="task ? task.startTracker() : () => {}"
                      :title="_('Start tracker')"
                      icon="nf nf-mdi-timer"
                      class="bbn-bg-green bbn-white"
                      :notext="true"/>
        </div>
      </div>
    </div>
    <div bbn-if="showOther"
         class="bbn-vmiddle bbn-right-lspace bbn-vxsmargin">
      <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
           bbn-text="_('Other')"/>
      <div class="bbn-vmiddle">
        <div>
          <bbn-button bbn-if="task.canPing"
                      @click="task ? task.ping() : () => {}"
                      title="<?= _('Ping workers') ?>"
                      icon="nf nf-fa-hand_pointer_o"
                      :notext="true"/>
        </div>
      </div>
    </div>
  </template>
</div>