<div class="bbn-100">
  <bbn-tabnav class="info-tabstrip"
              :scrollable="false"
              :autoload="false"
              root="tasks/<?=$id?>/"
  >
    <bbn-tab class="appui-task-tab-main"
              url="main"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-main"
             icon="fa fa-eye"
             title="<?=_("Global view")?>"
    ></bbn-tab>
    <bbn-tab class="appui-task-tab-people"
             url="people"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-people"
             icon="fa fa-users"
             title="<?=_("Roles")?>"
    ></bbn-tab>
    <bbn-tab class="appui-task-tab-logs"
             url="logs"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-logs"
             icon="fa fa-list"
             title="<?=_("Events journal")?>"
    ></bbn-tab>
  </bbn-tabnav>
</div>
