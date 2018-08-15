<div class="appui-task-tracker bbn-iblock"
     title="<?=_('Tracker')?>"
>
  <div class="bbn-block bbn-p"
       @click="openWindow"
  >
    <i class="bbn-lg fas fa-stopwatch"></i>
    <span v-if="active && progress"
          v-text="progress"
    ></span>
  </div>
  <div v-if="isVisible"
       class="appui-task-tracker-list k-block bbn-flex-height"
  >
    <div class="k-header bbn-c"
         style="margin-bottom: 0"
    >
      <span class="bbn-b"><?=_('Tasks list')?></span>
      <span class="window-icons">
        <i class="fas fa-sync bbn-p"
           @click="refreshList(false)"
        ></i>
        <i class="fas fa-times bbn-p"
           @click="openWindow"
        ></i>
      </span>
    </div>
    <div class="bbn-flex-fill bbn-flex-height">
      <div v-if="active"
           class="k-block"
      >
        <div class="k-header active-header">
          <div class="bbn-flex-width">
            <div class="bbn-flex-fill bbn-b progress-text"><?=_('In progress')?></div>
            <div class="progress-time bbn-b"
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
                        icon="fa fa-stop"
                        title="<?=_('Stop tracker')?>"
                        style="color: red; margin-right: 0"
            ></bbn-button>
          </div>
        </div>
      </div>
      <div class="bbn-flex-fill">
        <bbn-scroll>
          <div v-for="(l,i) in realList"
               :class="['real-list-item', {'k-alt': i%2}]">
            <div class="bbn-flex-width bbn-vmiddle">
              <div class="bbn-flex-fill bbn-p"
                   v-text="shorten(l.title)"
                   @click="openTask(l.id)"
                   title="<?=_('Open task')?>"
              ></div>
              <bbn-button @click="start(l.id)"
                          icon="fa fa-play"
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
