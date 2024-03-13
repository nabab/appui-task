<div class="appui-task-task-widget-logs bbn-bottom-padded">
  <div v-html="_('There are %s logs', '<b>' + source.totLogs + '</b>')"
       class="bbn-spadded bbn-background bbn-bottom-space bbn-c bbn-radius"/>
  <div v-for="(log, i) in source.lastLogs"
        :class="['bbn-background', 'bbn-radius', 'bbn-spadded', {'bbn-bottom-sspace': !!source.lastLogs[i+1]}]">
    <div class="bbn-vmiddle bbn-flex-width bbn-bottom-xspadded">
      <bbn-initial :user-id="log.id_user"
                   height="1.2rem"
                   width="1.2rem"
                   font-size="0.7em"/>
      <span v-text="userName(log.id_user)"
            class="bbn-flex-fill bbn-hsmargin bbn-s"/>
      <i class="nf nf-mdi-calendar bbn-hsmargin bbn-s bbn-secondary-text-alt"/>
      <span class="bbn-s bbn-secondary-text-alt"
            v-text="fdatetime(log.chrono)"/>
    </div>
    <div v-html="log.action"
        class="bbn-top-xspadded"/>
  </div>
  <div v-if="source.totLogs > 5"
       class="bbn-background bbn-top-space bbn-c bbn-radius"
       @click="task.openAllLogs"
       :title="_('See all logs')">
    <i class="nf nf-mdi-dots_horizontal bbn-xl bbn-p"/>
  </div>
</div>