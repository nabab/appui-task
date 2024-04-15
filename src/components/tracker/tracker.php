<div class="appui-task-tracker"
     title="<?=_('Tracker')?>">
  <div class="bbn-block bbn-p bbn-no-wrap"
       @click="openWindow">
    <span bbn-if="active && progress"
          bbn-text="progress"/>
    <i :class="['bbn-b','nf', 'nf-mdi-timer', {'bbn-green': active && progress}]"/>
  </div>
  <bbn-floater bbn-if="isVisible"
               :width="300"
               :height="300"
               :element="$el"
               :scrollable="false"
               :auto-hide="200"
               @close="isVisible = false"
               :container="$root.$el">
    <div class="bbn-overlay">
      <div class="appui-task-tracker-list bbn-w-100 bbn-flex-height">
        <div class="bbn-header bbn-spadded bbn-no-border-top bbn-no-hborder bbn-flex-width">
          <div class="bbn-flex-fill bbn-l bbn-unselectable">
            <span class="bbn-b"><?=_('Tasks list')?></span>
          </div>
          <div>
            <i class="nf nf-mdi-refresh bbn-p"
               @click="refreshList(false)"/>
            &nbsp;
            <i class="bbn-p nf nf-mdi-window_close"
               @click="openWindow"/>
          </div>
        </div>
        <div class="bbn-flex-fill bbn-flex-height">
          <div bbn-if="active"
               class="bbn-block">
            <div class="bbn-header bbn-no-border-top bbn-no-border-left bbn-no-border-right bbn-xspadded">
              <div class="bbn-flex-width">
                <div class="bbn-flex-fill bbn-b bbn-hspadded"><?=_('In progress')?></div>
                <div bbn-if="progress"
                     class="bbn-b bbn-hspadded"
                     bbn-text="progress"/>
              </div>
            </div>
            <div class="bbn-primary">
              <div class="bbn-flex-width bbn-vmiddle bbn-vxspadded">
                <div bbn-text="shorten(getField(list, 'title', 'id', active.id_task))"
                     class="bbn-flex-fill bbn-p bbn-b bbn-hspadded"
                     @click="openTask(active.id_task)"
                     title="<?=_('Open task')?>"/>
                <div class="bbn-hspadded">
                  <bbn-button @click="stop"
                              icon="nf nf-fa-stop"
                              title="<?=_('Stop tracker')?>"
                              :notext="true"
                              class="bbn-no-margin bbn-red"/>
                </div>
              </div>
            </div>
          </div>
          <div class="bbn-flex-fill">
            <bbn-scroll>
              <div bbn-for="(l,i) in realList"
                   :class="['bbn-vxspadded', 'bbn-hspadded', 'bbn-no-border-left', 'bbn-no-border-right', {'bbn-alt': i%2}]">
                <div class="bbn-flex-width bbn-vmiddle">
                  <div class="bbn-flex-fill bbn-p"
                       bbn-text="shorten(l.title)"
                       @click="openTask(l.id)"
                       title="<?=_('Open task')?>"/>
                  <bbn-button @click="start(l.id)"
                              icon="nf nf-fa-play"
                              title="<?=_('Play tracker')?>"
                              style="color: green"
                              :notext="true"/>
                </div>
              </div>
            </bbn-scroll>
          </div>
        </div>
      </div>
    </div>
  </bbn-floater>
</div>
