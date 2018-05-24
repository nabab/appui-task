<div class="bbn-full-screen">
	<bbn-dashboard class="appui-task-tab-main"
	               :sortable="true"
	>
	  <bbn-widget title="<?=_("Info")?>"
	              component="appui-task-widget-info"
	              uid="info"
	              :closable="false"
	              :source="source"
	  ></bbn-widget>
	  <bbn-widget title="<?=_("Roles")?>"
	              component="appui-task-widget-roles"
	              uid="roles"
	              :closable="false"
	              :source="source"
	  ></bbn-widget>
	  <bbn-widget title="<?=_("Tracker")?>"
	              component="appui-task-widget-tracker"
	              uid="tracker"
	              :closable="false"
	              :source="source"
	  ></bbn-widget>
	  <bbn-widget title="<?=_("Last messages")?>"
	              component="appui-task-widget-messages"
	              uid="messages"
	              :closable="false"
	              :source="source"
	  ></bbn-widget>
	</bbn-dashboard>
</div>
