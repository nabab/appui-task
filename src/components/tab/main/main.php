<div class="bbn-overlay">
	<bbn-dashboard class="appui-task-tab-main"
	               :sortable="true"
	>
	  <bbns-widget title="<i class='nf nf-fa-info bbn-hmargin'></i><?=_("Info")?>"
                 component="appui-task-widget-info"
                 uid="info"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
    <bbns-widget v-if="(isAdmin || isDecider) && ((isClosed && source.price) || isOpened)"
                 title="<i class='nf nf-fa-money bbn-hmargin'></i><?=_("Budget")?>"
                 component="appui-task-widget-budget"
                 uid="budget"
                 :closable="false"
                 :source="source"
								 :buttonsRight="budgetButtons"
    ></bbns-widget>
	  <bbns-widget title="<i class='nf nf-fa-users bbn-hmargin'></i><?=_("Roles")?>"
                 component="appui-task-widget-roles"
                 uid="roles"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
	  <bbns-widget title="<i class='nf nf-fa-hourglass_half bbn-hmargin'></i><?=_("Tracker")?>"
                 component="appui-task-widget-tracker"
                 uid="tracker"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
	  <bbns-widget v-if="hasComments"
                 title="<i class='nf nf-fa-comments bbn-hmargin'></i><?=_("Last messages")?>"
                 component="appui-task-widget-messages"
                 uid="messages"
                 :closable="false"
                 :source="source"
	  ></bbns-widget>
	</bbn-dashboard>
</div>
