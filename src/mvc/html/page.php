<div class="appui-tasks bbn-full-screen">
  <bbn-tabnav class="appui_task_tabnav"
              :scrollable="true"
              :autoload="true"
  >
    <bbns-tab url="search"
              :static="true"
              :load="true"
              icon="fas fa-home"
              title="<?=_("New task")?> / <?=_("Search")?>"
    ></bbns-tab>
  </bbn-tabnav>
</div>
