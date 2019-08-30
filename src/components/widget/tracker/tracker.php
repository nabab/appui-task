<div>
  <div v-if="main.isOngoing && (main.isWorker || main.isManager)"
       class="bbn-w-100 bbn-box"
       style="margin-bottom: .5em"
  >
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top"><?=_('CURRENT SESSION')?></div>
    <div class="bbn-spadded">
      <div class="bbn-flex-width bbn-vmiddle">
        <bbn-button v-if="source.tracker"
                    icon="nf nf-fa-stop"
                    title="<?=_('Stop tracker')?>"
                    style="color: red"
                    @click="trackerComp.stop"
                    :notext="true"
        ></bbn-button>
        <bbn-button v-else
                    icon="nf nf-fa-play"
                    title="<?=_('Start tracker')?>"
                    style="color: green"
                    @click="start"
                    :notext="true"
        ></bbn-button>
        <span v-if="source.tracker"
              class="bbn-flex-fill bbn-green bbn-hsmargin"
        ><?=_('IN PROGRESS')?></span>
        <span v-else
              class="bbn-flex-fill  bbn-hsmargin"
        ><?=_('Inactive')?></span>
        <div v-if="progress">
          <i class="nf nf-fa-clock bbn-hsmargin"></i>
          <span v-text="progress"
                class="bbn-green"
          ></span>
        </div>
      </div>
    </div>
  </div>
  <div class="bbn-w-100 bbn-box">
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top"><?=_('SUMMARY')?></div>
    <div class="bbn-spadded">
      <div v-for="s in summary"
           class="bbn-vmiddle bbn-vsmargin bbn-flex-width"
      >
        <bbn-initial :user-id="s.idUser"
                     :height="20"
                     :width="20"
        ></bbn-initial>
        <span v-text="s.userName"
              class="bbn-flex-fill bbn-hsmargin"
        ></span>
        <i class="nf nf-fa-clock bbn-hsmargin"></i>
        <span v-text="s.summary"></span>
      </div>
    </div>
  </div>
</div>
