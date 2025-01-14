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
                    label="<?=_('Home')?>"
                    :notext="true"
                    :source="source"/>
    <bbns-container url="sessions"
                    :fixed="true"
                    :load="false"
                    component="appui-task-tracker-sessions"
                    icon="nf nf-md-chart_timeline"
                    label="<?=_('Tracker sessions editor')?>"
                    :notext="true"
                    :source="source"/>
  </bbn-router>
</div>
