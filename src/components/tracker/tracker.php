<div class="appui-task-tracker bbn-iblock"
     title="<?=_('Tracker')?>"
>
  <div class="bbn-block bbn-p"
       @click="openWindow"
  >
    <i :class="['bbn-lg', 'bbn-b','nf', 'nf-mdi-timer', {'bbn-green': active && progress}]"></i>
    <span v-if="active && progress"
          v-text="progress"
    ></span>
  </div>
  <div v-if="isVisible"
       class="appui-task-tracker-list bbn-block bbn-flex-height bbn-box"
  >
    <div class="bbn-header bbn-c"
         style="margin-bottom: 0"
    >
      <span class="window-icons-left">
        <i class="nf nf-fa-window_minimize bbn-p"
           @click="openWindow"
        ></i>
      </span>
      <span class="bbn-b"><?=_('Tasks list')?></span>
      <span class="window-icons-right">
        <i class="nf nf-fa-sync bbn-p"
           @click="refreshList(false)"
        ></i>
      </span>
    </div>
    <div class="bbn-flex-fill bbn-flex-height">
      <div v-if="active"
           class="bbn-block"
      >
        <div class="bbn-header active-header">
          <div class="bbn-flex-width">
            <div class="bbn-flex-fill bbn-b progress-text"><?=_('In progress')?></div>
            <div v-if="progress"
                 class="progress-time bbn-b"
                 v-text="progress"
            ></div>
          </div>
        </div>
        <div class="active-container">
          <div class="bbn-flex-width bbn-vmiddle">
            <div v-text="shorten(get_field(list, 'id', active.id_task, 'title'))"
                 class="bbn-flex-fill bbn-p bbn-b"
                 @click="openTask(active.id_task)"
                 title="<?=_('Open task')?>"
            ></div>
            <bbn-button @click="stop"
                        icon="nf nf-fa-stop"
                        title="<?=_('Stop tracker')?>"
                        style="color: red; margin-right: 0"
            ></bbn-button>
          </div>
        </div>
      </div>
      <div class="bbn-flex-fill">
        <bbn-scroll>
          <div v-for="(l,i) in realList"
               :class="['real-list-item', {'bbn-alt': i%2}]">
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
              ></bbn-button>
            </div>
          </div>
        </bbn-scroll>
      </div>
    </div>
  </div>
</div>
