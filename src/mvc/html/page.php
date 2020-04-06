<div class="appui-tasks bbn-overlay">
  <bbn-router class="appui_task_nav"
              :scrollable="true"
              :autoload="true"
              :nav="true"
  >
    <bbns-container url="search"
                    :static="true"
                    :load="true"
                    icon="nf nf-fa-home"
                    title="<?=_("New task")?> / <?=_("Search")?>"
                    :notext="true"
    ></bbns-container>
  </bbn-router>
</div>
