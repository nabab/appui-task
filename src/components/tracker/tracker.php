<div class="appui-task-tracker"
     title="<?=_('Tracker')?>"
>
  <div class="bbn-block bbn-p bbn-no-wrap"
       @click="openWindow"
  >
    <span v-if="active && progress"
          v-text="progress"
    ></span>
    <i :class="['bbn-lg', 'bbn-b','nf', 'nf-mdi-timer', {'bbn-green': active && progress}]"></i>
  </div>
  <bbn-floater v-if="isVisible"
               :width="300"
               :height="300"
               :element="$el"
               :scrollable="false"
               :auto-hide="200"
               @close="isVisible = false"
  >
    <div class="bbn-overlay">
      <div class="appui-task-tracker-list bbn-w-100 bbn-flex-height">
        <div class="bbn-header bbn-c bbn-no-hborder bbn-no-border-top bbn-no-margin bbn-flex-width bbn-spadded">
          <span class="bbn-hsmargin">
            <i class="nf nf-fa-refresh bbn-p bbn-lg"
               @click="refreshList(false)"
            ></i>
          </span>
          <span class="bbn-b bbn-flex-fill"><?=_('Tasks list')?></span>
          <span class="bbn-hsmargin">
            <i class="nf nf-fa-window_close bbn-p bbn-lg"
               @click="openWindow"
            ></i>
          </span>
        </div>
        <div class="bbn-flex-fill bbn-flex-height">
          <div v-if="active"
               class="bbn-block"
          >
            <div class="bbn-header bbn-bottom-xspace">
              <div class="bbn-flex-width">
                <div class="bbn-flex-fill bbn-b bbn-hspadded"><?=_('In progress')?></div>
                <div v-if="progress"
                     class="bbn-b bbn-hspadded"
                     v-text="progress"
                ></div>
              </div>
            </div>
            <div class="bbn-primary">
              <div class="bbn-flex-width bbn-vmiddle bbn-vxspadded">
                <div v-text="shorten(getField(list, 'title', 'id', active.id_task))"
                     class="bbn-flex-fill bbn-p bbn-b bbn-hspadded"
                     @click="openTask(active.id_task)"
                     title="<?=_('Open task')?>"
                ></div>
                <div class="bbn-hspadded">
                  <bbn-button @click="stop"
                              icon="nf nf-fa-stop"
                              title="<?=_('Stop tracker')?>"
                              :notext="true"
                              class="bbn-no-margin bbn-red"
                  ></bbn-button>
                </div>
              </div>
            </div>
          </div>
          <div class="bbn-flex-fill">
            <bbn-scroll>
              <div v-for="(l,i) in realList"
                   :class="['bbn-vxspadded', 'bbn-hspadded', {'bbn-alt': i%2}]">
                <div class="bbn-flex-width bbn-vmiddle">
                  <div class="bbn-flex-fill bbn-p"
                       v-text="shorten(l.title)"
                       @click="openTask(l.id)"
                       title="<?=_('Open task')?>"
                  ></div>
                  <bbn-button @click="start(l.id)"
                              icon="nf nf-fa-play"
                              title="<?=_('Play tracker')?>"
                              style="color: green"
                              :notext="true"
                  ></bbn-button>
                </div>
              </div>
            </bbn-scroll>
          </div>
        </div>
      </div>
    </div>
  </bbn-floater>
</div>
