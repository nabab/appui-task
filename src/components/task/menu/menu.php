<div class="appui-task-task-menu bbn-overlay bbn-background">
  <bbn-scroll axis="y">
    <div bbn-if="task"
         class="bbn-lpadding bbn-c">
      <appui-task-task-actions :source="task.source"/>
      <div bbn-if="task.widgetsAvailable.length && task.canChange"
           bbn-text="_('Widgets')"
           class="appui-task-task-menu-title bbn-upper bbn-b bbn-secondary-text-alt bbn-padding bbn-alt-background bbn-radius"/>
      <div bbn-if="task.widgetsAvailable.length && task.canChange"
           bbn-for="(w, i) in task.widgetsAvailable"
           class="bbn-vmiddle bbn-padding appui-task-task-menu-item bbn-border-bottom"
           @click="task.addWidgetToTask(w.code)">
        <i :class="['bbn-m', w.icon]"/>
        <span class="bbn-left-sspace"
              bbn-text="w.text"/>
      </div>
    </div>
  </bbn-scroll>
</div>