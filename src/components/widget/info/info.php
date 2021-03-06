<div class="bbn-grid-fields">
  <label><?=_('Title')?></label>
  <bbn-input v-model="source.title"
             class="bbn-flex-fill"
             :readonly="!main.canChange"
  ></bbn-input>
  <label><?=_('Created by')?></label>
  <div class="bbn-vmiddle">
    <bbn-initial :user-id="source.id_user"
                 :height="25"
                 :width="25"
                 font-size="1em"
    ></bbn-initial>
    <span v-text="main.tasks.userName(source.id_user)"
          style="margin-left: 5px"
    ></span>
  </div>
  <label><?=_('Created on')?></label>
  <div v-text="main.creation"></div>
  <label><?=_('Status')?></label>
  <div v-text="main.stateText"></div>
  <div></div>
  <div>
    <div v-if="main.isUnapproved">
      <bbn-button v-if="main.canClose"
                  @click="main.close"
                  title="<?=_("Close")?>"
                  icon="nf nf-fa-check"
                  :notext="true"
      ></bbn-button>
    </div>
    <div v-else-if="main.isActive">
      <bbn-button v-if="main.canStart"
                  @click="main.start"
                  title="<?=_("Put on ongoing")?>"
                  icon="nf nf-fa-play"
                  :notext="true"
      ></bbn-button>
      <bbn-button v-if="main.canHold"
                  @click="main.hold"
                  title="<?=_("Put on hold")?>"
                  icon="nf nf-fa-pause"
                  :notext="true"
      ></bbn-button>
      <bbn-button v-if="main.canResume"
                  @click="main.resume"
                  title="<?=_("Resume")?>"
                  icon="nf nf-fa-play"
                  :notext="true"
      ></bbn-button>
      <bbn-button v-if="main.canClose"
                  @click="main.close"
                  title="<?=_("Close") ?>"
                  icon="nf nf-fa-check"
                  :notext="true"
      ></bbn-button>
    </div>
    <div v-else-if="main.isHolding">
      <bbn-button v-if="main.canResume"
                  @click="main.resume"
                  title="<?=_("Resume")?>"
                  icon="nf nf-fa-play"
                  :notext="true"
      ></bbn-button>
    </div>
    <div v-else-if="main.isClosed">
      <bbn-button v-if="main.canOpen"
                  @click="main.reopen"
                  title="<?=_("Reopen")?>"
                  icon="nf nf-fa-hand_pointer_o"
                  :notext="true"
      ></bbn-button>
    </div>
  </div>
  <label v-if="source.reference"><?=_("External reference")?></label>
  <div v-if="source.reference"
       v-html="source.reference"
  ></div>
  <label><?=_("Category")?></label>
  <bbn-dropdown :source="main.categories"
                v-model="source.type"
                :value="source.type"
                :disabled="!main.canChange"
                group="group"
  ></bbn-dropdown>
  <label><?=_("Priority")?></label>
  <bbn-dropdown v-model="source.priority"
                style="width: 80px"
                :source="[1,2,3,4,5,6,7,8,9]"
                :disabled="!main.canChange"
  ></bbn-dropdown>
  <label><?=_("Deadline")?></label>
  <div>
    <bbn-datetimepicker v-model="source.deadline"
                        @keydown="main.preventAll($event)"
                        :disabled="!main.canChange"
                        :min="minDate()"
    ></bbn-datetimepicker>
    <bbn-button v-if="source.deadline && main.canChange"
                @click="main.removeDeadline"
                icon="nf nf-fa-times"
                :notext="true"
    ></bbn-button>
  </div>
  <label><?=_('Actions')?></label>
  <div>
    <bbn-button v-if="main.canMakeMe && !main.isManager"
                @click="main.makeMe('managers')"
                icon="nf nf-fa-user_plus"
                title="<?=_('Make me a supervisor')?>"
                class="bbn-green"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="main.canMakeMe && !main.isWorker"
                @click="main.makeMe('workers')"
                icon="nf nf-fa-user_plus"
                title="<?=_('Make me a worker')?>"
                class="bbn-orange"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="!main.isViewer"
                @click="main.makeMe('viewers')"
                icon="nf nf-fa-user_plus"
                title="<?=_('Make me a spectator')?>"
                style="color: yellow"
                class="bbn-yellow"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="main.canPing"
                @click="main.ping"
                title="<?=_("Ping workers")?>"
                icon="nf nf-fa-hand_pointer_o"
                :notext="true"
    ></bbn-button>
    <bbn-button v-if="main.isAdded && main.canUnmakeMe"
                @click="main.unmakeMe"
                title="<?=_("Unfollow the task")?>"
                icon="nf nf-fa-user_times"
                class="bbn-red"
                :notext="true"
    ></bbn-button>
  </div>
</div>
