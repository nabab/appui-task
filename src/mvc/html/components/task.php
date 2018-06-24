<div class="bbn-100">
  <bbn-tabnav class="info-tabstrip"
              :scrollable="false"
              :autoload="false"
              root="tasks/<?=$id?>/"
  >
    <bbns-tab url="main"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-main"
             icon="fa fa-eye"
             title="<?=_("Global view")?>"
    ></bbns-tab>
    <bbns-tab url="people"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-people"
             icon="fa fa-users"
             title="<?=_("Roles")?>"
    ></bbns-tab>
    <bbns-tab url="messages"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-messages"
             icon="fa fa-comments"
             title="<?=_("Messages")?>"
    ></bbns-tab>
    <bbns-tab url="tracker"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-tracker"
             icon="fa fa-hourglass-half"
             title="<?=_("Tracker")?>"
             :disabled="true"
    ></bbns-tab>
    <bbns-tab url="logs"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-logs"
             icon="fa fa-history"
             title="<?=_("Events journal")?>"
    ></bbns-tab>
  </bbn-tabnav>
</div>
