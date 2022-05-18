<div class="bbn-overlay">
	<bbn-dashboard class="appui-task-tab-main"
	               :sortable="true">
	  <bbns-widget title="<?=_("Info")?>"
                 icon="nf nf-fa-info"
                 component="appui-task-widget-info"
                 uid="info"
                 :closable="false"
                 :source="source"
                 :padding="true"/>
    <bbns-widget v-if="(isAdmin || isDecider) && ((isClosed && source.price) || !isClosed)"
                 title="<?=_("Budget")?>"
                 icon="nf nf-fa-money"
                 component="appui-task-widget-budget"
                 uid="budget"
                 :closable="false"
                 :source="source"
								 :buttonsRight="budgetButtons"
                 :padding="true"/>
	  <bbns-widget title="<?=_("Roles")?>"
                 icon="nf nf-fa-users"
                 component="appui-task-widget-roles"
                 uid="roles"
                 :closable="false"
                 :source="source"
                 :padding="true"/>
	  <bbns-widget title="<?=_("Tracker")?>"
                 icon="nf nf-fa-hourglass_half"
                 component="appui-task-widget-tracker"
                 uid="tracker"
                 :closable="false"
                 :source="source"
                 :padding="true"/>
	  <bbns-widget v-if="hasComments"
                 title="<?=_("Last messages")?>"
                 icon="nf nf-fa-comments"
                 component="appui-task-widget-messages"
                 uid="messages"
                 :closable="false"
                 :source="source"
                 :padding="true"/>
    <bbns-widget v-if="canChange"
                 title="<?=_("Tasks")?>"
                 icon="nf nf-oct-tasklist"
                 item-component="appui-task-widget-tasks"
                 uid="tasks"
                 :closable="false"
                 :items="source.children"
                 :padding="true"
                 :buttonsRight="tasksButtons"/>
	</bbn-dashboard>
</div>
