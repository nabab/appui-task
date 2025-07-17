<div class="appui-task-task-widget-logs bbn-bottom-padding">
  <div bbn-html="_('There are %s logs', '<b>' + source.totLogs + '</b>')"
       class="bbn-spadding bbn-alt-background bbn-bottom-space bbn-c bbn-radius"/>
  <div bbn-for="(log, i) in source.lastLogs"
        :class="['bbn-alt-background', 'bbn-radius', 'bbn-spadding', {'bbn-bottom-sspace': !!source.lastLogs[i+1]}]">
    <div class="bbn-vmiddle bbn-flex-width bbn-bottom-xspadding">
      <bbn-initial :user-id="log.id_user"
                   height="1.2rem"
                   width="1.2rem"
                   font-size="0.7em"/>
      <span bbn-text="userName(log.id_user)"
            class="bbn-flex-fill bbn-hsmargin bbn-s"/>
      <i class="nf nf-md-calendar bbn-hsmargin bbn-s bbn-secondary-text-alt"/>
      <span class="bbn-s bbn-secondary-text-alt"
            bbn-text="fdatetime(log.chrono)"/>
    </div>
    <div bbn-html="log.action"
        class="bbn-top-xspadding"/>
  </div>
  <div bbn-if="source.totLogs > 5"
       class="bbn-background bbn-top-space bbn-c bbn-radius"
       @click="task ? task.openAllLogs() : () => {}"
       :title="_('See all logs')">
    <i class="nf nf-md-dots_horizontal bbn-xl bbn-p"/>
  </div>
</div>