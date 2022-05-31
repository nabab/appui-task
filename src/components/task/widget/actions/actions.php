<div class="bbn-grid-fields bbn-padded">
  <label><?=_('Status')?></label>
  <div v-text="task.stateText"></div>
  <div></div>
  <div>
    <div v-if="task.isUnapproved">
      <bbn-button v-if="task.canClose"
                  @click="task.close"
                  title="<?=_("Close")?>"
                  icon="nf nf-fa-check"
                  :notext="true"
      ></bbn-button>
    </div>
    <div v-else-if="task.isActive">
      <bbn-button v-if="task.canStart"
                  @click="task.start"
                  title="<?=_("Put on ongoing")?>"
                  icon="nf nf-fa-play"
                  :notext="true"
      ></bbn-button>
      <bbn-button v-if="task.canHold"
                  @click="task.hold"
                  title="<?=_("Put on hold")?>"
                  icon="nf nf-fa-pause"
                  :notext="true"
      ></bbn-button>
      <bbn-button v-if="task.canResume"
                  @click="task.resume"
                  title="<?=_("Resume")?>"
                  icon="nf nf-fa-play"
                  :notext="true"
      ></bbn-button>
      <bbn-button v-if="task.canClose"
                  @click="task.close"
                  title="<?=_("Close") ?>"
                  icon="nf nf-fa-check"
                  :notext="true"
      ></bbn-button>
    </div>
    <div v-else-if="task.isHolding">
      <bbn-button v-if="task.canResume"
                  @click="task.resume"
                  title="<?=_("Resume")?>"
                  icon="nf nf-fa-play"
                  :notext="true"
      ></bbn-button>
    </div>
    <div v-else-if="task.isClosed">
      <bbn-button v-if="task.canOpen"
                  @click="task.reopen"
                  title="<?=_("Reopen")?>"
                  icon="nf nf-fa-hand_pointer_o"
                  :notext="true"
      ></bbn-button>
    </div>
  </div>
  <label><?=_('Actions')?></label>
  <div>
    <bbn-button v-if="task.canMakeMe && !task.isManager"
                @click="task.makeMe('managers')"
                icon="nf nf-fa-user_plus"
                title="<?=_('Make me a supervisor')?>"
                class="bbn-green"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="task.canMakeMe && !task.isWorker"
                @click="task.makeMe('workers')"
                icon="nf nf-fa-user_plus"
                title="<?=_('Make me a worker')?>"
                class="bbn-orange"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="!task.isViewer"
                @click="task.makeMe('viewers')"
                icon="nf nf-fa-user_plus"
                title="<?=_('Make me a spectator')?>"
                style="color: yellow"
                class="bbn-yellow"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="task.canPing"
                @click="task.ping"
                title="<?=_("Ping workers")?>"
                icon="nf nf-fa-hand_pointer_o"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="task.isAdded && task.canUnmakeMe"
                @click="task.unmakeMe"
                title="<?=_("Unfollow the task")?>"
                icon="nf nf-fa-user_times"
                class="bbn-red"
                :notext="true"
    ></bbn-button>
  </div>
</div>