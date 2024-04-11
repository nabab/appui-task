<div class="appui-task-widget-task bbn-flex-width">
  <span bbn-if="source.title"
        :class="[source.bugclass, 'bbn-flex-fill']"
        :title="source.status"
  >
    <a :href="tasksRoot + 'page/task/' + source.id"
       bbn-html="source.title"
    ></a>
  </span>
  <span bbn-else
        :class="[source.bugclass, 'bbn-flex-fill']"
        :title="source.status"
  >
    <?= _('Untitled') ?>
  </span>
  <span bbn-text="date"></span>
</div>