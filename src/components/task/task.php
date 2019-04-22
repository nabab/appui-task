<div class="bbn-100">
  <bbn-tabnav class="info-tabstrip"
              :scrollable="false"
              :autoload="false"
              :root="'tasks/' + source.id + '/'"
  >
    <bbns-container url="main"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-main"
             icon="nf nf-fa-eye"
             title="<?=_("Global view")?>"
    ></bbns-container>
    <bbns-container url="people"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-people"
             icon="nf nf-fa-users"
             title="<?=_("Roles")?>"
    ></bbns-container>
    <bbns-container url="messages"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-messages"
             icon="nf nf-fa-comments"
             title="<?=_("Messages")?>"
    ></bbns-container>
    <bbns-container url="tracker"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-tracker"
             icon="nf nf-fa-hourglass_half"
             title="<?=_("Tracker")?>"
    ></bbns-container>
    <bbns-container url="logs"
             :static="true"
             :load="false"
             :source="source"
             component="appui-task-tab-logs"
             icon="nf nf-fa-history"
             title="<?=_("Events journal")?>"
    ></bbns-container>
  </bbn-tabnav>
</div>