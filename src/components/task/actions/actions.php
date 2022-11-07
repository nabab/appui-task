<div class="appui-task-task-widget-actions bbn-vmiddle">
  <div v-if="showBudget"
       :class="[{
         'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
       }, 'bbn-vxsmargin']">
    <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
         v-text="_('Budget')"/>
    <div class="bbn-vmiddle">
      <bbn-button v-if="task.canApprove"
                  @click="task.approve"
                  title="<?=_("Approve")?>"
                  icon="nf nf-fa-thumbs_up"
                  :notext="true"
                  :style="{
                    backgroundColor: getStatusBgColor('approved'),
                    color: getStatusColor('approved')
                  }"/>
      <bbn-button v-if="task.isAdmin && !task.isClosed && !!source.lastChangePrice"
                  icon="nf nf-fa-edit"
                  title="<?=_('Edit price')?>"
                  @click="task.editPrice"
                  class="bbn-hsmargin"
                  :notext="true"/>
      <bbn-button v-if="task.isAdmin && !task.isClosed && !!source.lastChangePrice"
                  icon="nf nf-fa-trash"
                  title="<?=_('Remove price')?>"
                  @click="task.removePrice"
                  :notext="true"
                  class="bbn-white bbn-bg-red"/>
    </div>
  </div>
  <div v-if="showStatus"
       :class="[{
         'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
       }, 'bbn-vxsmargin']">
    <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
         v-text="_('Status')"/>
    <div class="bbn-vmiddle">
      <div v-if="task.isUnapproved">
        <bbn-button v-if="task.canClose"
                    @click="task.close"
                    title="<?=_("Close")?>"
                    icon="nf nf-fa-check"
                    :notext="true"
                    :style="{
                      backgroundColor: getStatusBgColor('closed'),
                      color: getStatusColor('closed'),
                      width: '6rem !important'
                    }"/>
      </div>
      <div v-else-if="task.isActive">
        <bbn-button v-if="task.canStart"
                    @click="task.start"
                    title="<?=_("Put on ongoing")?>"
                    icon="nf nf-fa-play"
                    :notext="true"
                    :style="{
                      backgroundColor: getStatusBgColor('ongoing'),
                      color: getStatusColor('ongoing')
                    }"/>
        <bbn-button v-if="task.canHold"
                    @click="task.hold"
                    title="<?=_("Put on hold")?>"
                    icon="nf nf-fa-pause"
                    :notext="true"
                    :style="{
                      backgroundColor: getStatusBgColor('holding'),
                      color: getStatusColor('holding')
                    }"/>
        <bbn-button v-if="task.canResume"
                    @click="task.resume"
                    title="<?=_("Resume")?>"
                    icon="nf nf-fa-play"
                    :notext="true"
                    :style="{
                      backgroundColor: getStatusBgColor('ongoing'),
                      color: getStatusColor('ongoing')
                    }"/>
        <bbn-button v-if="task.canClose"
                    @click="task.close"
                    title="<?=_("Close") ?>"
                    icon="nf nf-fa-check"
                    :notext="true"
                    :style="{
                      backgroundColor: getStatusBgColor('closed'),
                      color: getStatusColor('closed'),
                      width: '6rem !important'
                    }"/>
      </div>
      <div v-else-if="task.isHolding">
        <bbn-button v-if="task.canResume"
                    @click="task.resume"
                    title="<?=_("Resume")?>"
                    icon="nf nf-fa-play"
                    :notext="true"
                    :style="{
                      backgroundColor: getStatusBgColor('ongoing'),
                      color: getStatusColor('ongoing')
                    }"/>
      </div>
      <div v-else-if="task.isClosed">
        <bbn-button v-if="task.canOpen"
                    @click="task.reopen"
                    title="<?=_("Reopen")?>"
                    icon="nf nf-fa-hand_pointer_o"
                    :notext="true"/>
      </div>
    </div>
  </div>
  <div v-if="showRoles"
       :class="[{
         'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
       }, 'bbn-vxsmargin']">
    <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
         v-text="_('Roles')"/>
    <div class="bbn-vmiddle">
      <bbn-button v-if="task.canBecomeManager"
                  @click="task.makeMe('managers')"
                  icon="nf nf-mdi-account_plus"
                  title="<?=_('Make me a supervisor')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('managers'),
                    color: getRoleColor('managers')
                  }"/>
      <bbn-button v-if="task.canRevemoHimselfManager"
                  @click="task.unmakeMe('managers')"
                  icon="nf nf-mdi-account_minus"
                  title="<?=_('Remove me from supervisors')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('managers'),
                    color: getRoleColor('managers')
                  }"/>
      <bbn-button v-if="task.canBecomeWorker"
                  @click="task.makeMe('workers')"
                  icon="nf nf-mdi-account_plus"
                  title="<?=_('Make me a worker')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('workers'),
                    color: getRoleColor('workers')
                  }"/>
      <bbn-button v-if="task.canRevemoHimselfWorker"
                  @click="task.unmakeMe('workers')"
                  icon="nf nf-mdi-account_minus"
                  title="<?=_('Remove me from workers')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('workers'),
                    color: getRoleColor('workers')
                  }"/>
      <bbn-button v-if="task.canBecomeViewer"
                  @click="task.makeMe('viewers')"
                  icon="nf nf-mdi-account_plus"
                  title="<?=_('Make me a spectator')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('viewers'),
                    color: getRoleColor('viewers')
                  }"/>
      <bbn-button v-if="task.canRevemoHimselfViewer"
                  @click="task.unmakeMe('viewers')"
                  icon="nf nf-mdi-account_minus"
                  title="<?=_('Remove me from viewers')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('viewers'),
                    color: getRoleColor('viewers')
                  }"/>
      <bbn-button v-if="task.canBecomeDecider"
                  @click="task.makeMe('deciders')"
                  icon="nf nf-mdi-account_plus"
                  title="<?=_('Make me a decider')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('deciders'),
                    color: getRoleColor('deciders')
                  }"/>
      <bbn-button v-if="task.canRevemoHimselfDecider"
                  @click="task.unmakeMe('deciders')"
                  icon="nf nf-mdi-account_minus"
                  title="<?=_('Remove me from deciders')?>"
                  :notext="true"
                  :style="{
                    backgroundColor: getRoleBgColor('deciders'),
                    color: getRoleColor('deciders')
                  }"/>
    </div>
  </div>
  <div v-if="showTracker"
       :class="[{
         'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
       }, 'bbn-vxsmargin']">
    <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
         v-text="_('Tracker')"/>
    <div class="bbn-vmiddle">
      <bbn-button v-if="source.tracker"
                  @click="task.stopTracker"
                  :title="_('Stop tracker')"
                  icon="nf nf-mdi-timer_off"
                  class="bbn-bg-red bbn-white"
                  :notext="true"/>
      <bbn-button v-else
                  @click="task.startTracker"
                  :title="_('Start tracker')"
                  icon="nf nf-mdi-timer"
                  class="bbn-bg-green bbn-white"
                  :notext="true"/>
    </div>
  </div>
  <div v-if="showOther"
       :class="[{
         'bbn-vmiddle bbn-right-lspace': !mainPage.isMobile(),
       }, 'bbn-vxsmargin']">
    <div class="bbn-upper bbn-right-space bbn-b bbn-secondary-text-alt"
         v-text="_('Other')"/>
    <div class="bbn-vmiddle">
      <bbn-button v-if="task.canPing"
                  @click="task.ping"
                  title="<?=_("Ping workers")?>"
                  icon="nf nf-fa-hand_pointer_o"
                  :notext="true"/>
    </div>
  </div>
</div>