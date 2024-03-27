<div class="appui-task bbn-overlay">
  <bbn-router class="appui-task-nav"
              :scrollable="true"
              :autoload="true"
              :nav="true"
              :storage="true"
              storage-full-name="appui-task-ui">
    <bbns-container url="home"
                    :fixed="true"
                    :load="false"
                    component="appui-task-home"
                    icon="nf nf-fa-home"
                    title="<?= _('Home') ?>"
                    :notext="true"
                    :source="source"/>
  </bbn-router>
</div>
