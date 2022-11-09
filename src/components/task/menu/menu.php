<div class="appui-task-task-menu bbn-overlay bbn-background">
  <bbn-scroll axis="y">
    <div class="bbn-lpadded bbn-c">
      <appui-task-task-actions :source="task.source"/>
      <div v-if="task.widgetsAvailable.length"
           v-text="_('Widgets')"
           class="appui-task-task-menu-title bbn-upper bbn-b bbn-secondary-text-alt bbn-padded bbn-alt-background bbn-radius"/>
      <div v-if="task.widgetsAvailable.length"
           v-for="(w, i) in task.widgetsAvailable"
           class="bbn-vmiddle bbn-padded appui-task-task-menu-item bbn-bordered-bottom"
           @click="task.addWidgetToTask(w.code)">
        <i :class="['bbn-m', w.icon]"/>
        <span class="bbn-left-sspace"
              v-text="w.text"/>
      </div>
    </div>
  </bbn-scroll>
</div>