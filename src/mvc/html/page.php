<div class="appui-task bbn-overlay">
  <bbn-router class="appui-task-nav"
              :scrollable="true"
              :autoload="true"
              :nav="true">
    <bbns-container url="home"
                    :static="true"
                    :load="true"
                    icon="nf nf-fa-home"
                    title="<?=_('Home')?>"
                    :notext="true"/>
    <bbns-container url="search"
                    :static="true"
                    :load="true"
                    icon="nf nf-fa-home"
                    title="<?=_("New task")?> / <?=_("Search")?>"
                    :notext="true"/>
  </bbn-router>
</div>
