<div class="appui-task-widget-task bbn-flex-width">
  <span v-if="source.title"
        :class="[source.bugclass, 'bbn-flex-fill']"
        :title="source.status"
  >
    <a :href="tasksRoot + 'page/task/' + source.id"
       v-html="source.title"
    ></a>
  </span>
  <span v-else
        :class="[source.bugclass, 'bbn-flex-fill']"
        :title="source.status"
  >
    <?= _('Untitled') ?>
  </span>
  <span v-text="date"></span>
</div>