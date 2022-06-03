<div class="appui-task-task-widget-tracker">
  <div v-if="task.isOngoing && (task.isWorker || task.isManager)"
       class="bbn-w-100 bbn-box bbn-bottom-space">
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded"><?=_('CURRENT SESSION')?></div>
    <div class="bbn-spadded">
      <div class="bbn-flex-width bbn-vmiddle">
        <bbn-button :icon="source.tracker ? 'nf nf-fa-stop' : 'nf nf-fa-play'"
                    :title="source.tracker ? '<?=_('Stop tracker')?>' : '<?=_('Start tracker')?>'"
                    :class="{
                      'bbn-green': !source.tracker,
                      'bbn-red': source.tracker
                    }"
                    @click="source.tracker ? trackerComp.stop() : start()"
                    :notext="true"/>
        <span :class="['bbn-flex-fill', 'bbn-hsmargin', {'bbn-green': source.tracker}]"
              v-text="source.tracker ? '<?=_('IN PROGRESS')?>' : '<?=_('Inactive')?>'"/>
        <div v-if="progress">
          <i class="nf nf-fa-clock bbn-hsmargin"/>
          <span v-text="progress"
                class="bbn-green"/>
        </div>
      </div>
    </div>
  </div>
  <div class="bbn-w-100 bbn-box">
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded"><?=_('SUMMARY')?></div>
    <div class="bbn-spadded">
      <div v-for="s in summary"
           class="bbn-vmiddle bbn-vsmargin bbn-flex-width">
        <bbn-initial :user-id="s.idUser"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span v-text="s.userName"
              class="bbn-flex-fill bbn-hsmargin"/>
        <i class="nf nf-fa-clock bbn-hsmargin"/>
        <span v-text="s.summary"/>
      </div>
    </div>
  </div>
</div>
