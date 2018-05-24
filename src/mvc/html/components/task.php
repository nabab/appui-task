<div class="bbn-100">
  <bbn-tabnav class="info-tabstrip"
              :scrollable="false"
              :autoload="false"
              root="tasks/<?=$id?>/"
  >
    <bbn-tab url="main"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-main"
             icon="fa fa-eye"
             title="<?=_("Global view")?>"
    ></bbn-tab>
    <bbn-tab url="people"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-people"
             icon="fa fa-users"
             title="<?=_("Roles")?>"
    ></bbn-tab>
    <bbn-tab url="messages"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-messages"
             icon="fa fa-comments"
             title="<?=_("Messages")?>"
    ></bbn-tab>
    <bbn-tab url="tracker"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-tracker"
             icon="fa fa-hourglass-half"
             title="<?=_("Tracker")?>"
             :disabled="true"
    ></bbn-tab>
    <bbn-tab url="logs"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-logs"
             icon="fa fa-history"
             title="<?=_("Events journal")?>"
    ></bbn-tab>
  </bbn-tabnav>
</div>
