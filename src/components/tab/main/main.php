<div class="bbn-full-screen">
	<bbn-dashboard class="appui-task-tab-main"
	               :sortable="true"
	>
	  <bbns-widget title="<i class='fas fa-info bbn-hmargin'></i><?=_("Info")?>"
                 component="appui-task-widget-info"
                 uid="info"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
    <bbns-widget title="<i class='fas fa-money-bill-alt bbn-hmargin'></i><?=_("Budget")?>"
                 component="appui-task-widget-budget"
                 uid="budget"
                 :closable="false"
                 :source="source"
								 :buttonsRight="budgetButtons"
    ></bbns-widget>
	  <bbns-widget title="<i class='fas fa-users bbn-hmargin'></i><?=_("Roles")?>"
                 component="appui-task-widget-roles"
                 uid="roles"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
	  <bbns-widget title="<i class='fas fa-hourglass-half bbn-hmargin'></i><?=_("Tracker")?>"
                 component="appui-task-widget-tracker"
                 uid="tracker"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
	  <bbns-widget title="<i class='fas fa-comments bbn-hmargin'></i><?=_("Last messages")?>"
                 component="appui-task-widget-messages"
                 uid="messages"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
	</bbn-dashboard>
</div>