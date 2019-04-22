<div>
  <div v-if="main.isOngoing && (main.isWorker || main.isManager)"
       class="k-block"
  >
    <div class="k-header bbn-b"><?=_('CURRENT SESSION')?></div>
    <div class="bbn-spadded">
      <div class="bbn-flex-width bbn-vmiddle">
        <bbn-button v-if="source.tracker"
                    icon="nf nf-fa-stop"
                    title="<?=_('Stop tracker')?>"
                    style="color: red"
                    @click="trackerComp.stop"
        ></bbn-button>
        <bbn-button v-else
                    icon="nf nf-fa-play"
                    title="<?=_('Start tracker')?>"
                    style="color: green"
                    @click="start"
        ></bbn-button>
        <span v-if="source.tracker"
              class="bbn-flex-fill bbn-green"
        ><?=_('IN PROGRESS')?></span>
        <span v-else
              class="bbn-flex-fill"
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
  <div class="k-block">
    <div class="k-header bbn-b"><?=_('SUMMARY')?></div>
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
