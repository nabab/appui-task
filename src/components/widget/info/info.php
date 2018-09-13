<div class="bbn-grid-fields">
  <label><?=_('Title')?></label>
  <bbn-input v-model="source.title"
             class="bbn-flex-fill"
             :readonly="!main.canChange"
  ></bbn-input>
  <label><?=_('Created by')?></label>
  <div class="bbn-vmiddle">
    <bbn-initial :user-id="source.id_user"
                 :height="20"
                 :width="20"
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
                  title="<?=_("Close") ?>"
                  icon="fas fa-check"
      ></bbn-button>
    </div>
    <div v-else-if="main.isActive">
      <bbn-button v-if="main.canStart"
                  @click="main.start"
                  title="<?=_("Put on ongoing")?>"
                  icon="fas fa-play"
      ></bbn-button>
      <bbn-button v-if="main.canHold"
                  @click="main.hold"
                  title="<?=_("Put on hold")?>"
                  icon="fas fa-pause"
      ></bbn-button>
      <bbn-button v-if="main.canResume"
                  @click="main.resume"
                  title="<?=_("Resume")?>"
                  icon="fas fa-play"
      ></bbn-button>
      <bbn-button v-if="main.canClose"
                  @click="main.close"
                  title="<?=_("Close") ?>"
                  icon="fas fa-check"
      ></bbn-button>
    </div>
    <div v-else-if="main.isHolding">
      <bbn-button v-if="main.canResume"
                  @click="main.resume"
                  title="<?=_("Resume")?>"
                  icon="fas fa-play"
      ></bbn-button>
    </div>
    <div v-else-if="main.isClosed">
      <bbn-button v-if="main.canOpen"
                  @click="main.reopen"
                  title="<?=_("Reopen")?>"
                  icon="far fa-hand-left"
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
                icon="fas fa-times"
    ></bbn-button>
  </div>
  <label><?=_('Actions')?></label>
  <div>
    <bbn-button v-if="main.canMakeMe && !main.isManager"
                @click="main.makeMe('managers')"
                icon="fas fa-user-plus"
                title="<?=_('Make me a supervisor')?>"
                style="color: green"
    ></bbn-button>
    <bbn-button v-if="main.canMakeMe && !main.isWorker"
                @click="main.makeMe('workers')"
                icon="fas fa-user-plus"
                title="<?=_('Make me a worker')?>"
                style="color: orange"
    ></bbn-button>
    <bbn-button v-if="!main.isViewer"
                @click="main.makeMe('viewers')"
                icon="fas fa-user-plus"
                title="<?=_('Make me a spectator')?>"
                style="color: yellow"
    ></bbn-button>
    <bbn-button v-if="main.canPing"
                @click="main.ping"
                title="<?=_("Ping workers")?>"
                icon="far fa-hand-pointer"
    ></bbn-button>
    <bbn-button v-if="main.isAdded && main.canUnmakeMe"
                @click="main.unmakeMe"
                title="<?=_("Unfollow the task")?>"
                icon="fas fa-user-times"
                style="color: red"
    ></bbn-button>
  </div>
</div>
