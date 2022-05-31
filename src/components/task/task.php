<bbn-splitter>
  <bbn-pane>
    <bbn-dashboard :sortable="true"
                   :order="dashboard.order"
                   code="appui-task">
    <bbns-widget :title="dashboard.widgets.info.text"
                 :icon="dashboard.widgets.info.icon"
                 :component="dashboard.widgets.info.component"
                 :uid="dashboard.widgets.info.code"
                 :closable="dashboard.widgets.info.closable"
                 :source="source"/>
    <bbns-widget :title="dashboard.widgets.actions.text"
                 :icon="dashboard.widgets.actions.icon"
                 :component="dashboard.widgets.actions.component"
                 :uid="dashboard.widgets.actions.code"
                 :closable="dashboard.widgets.actions.closable"
                 :source="source"/>
    <bbns-widget v-if="(isAdmin || isDecider) && ((isClosed && source.price) || !isClosed)"
                 :title="dashboard.widgets.budget.text"
                 :icon="dashboard.widgets.budget.icon"
                 :component="dashboard.widgets.budget.component"
                 :uid="dashboard.widgets.budget.code"
                 :closable="dashboard.widgets.budget.closable"
                 :source="source"
								 :buttonsRight="budgetButtons"/>
	  <bbns-widget :title="dashboard.widgets.roles.text"
                 :icon="dashboard.widgets.roles.icon"
                 :component="dashboard.widgets.roles.component"
                 :uid="dashboard.widgets.roles.code"
                 :closable="dashboard.widgets.roles.closable"
                 :source="source"/>
	  <bbns-widget :title="dashboard.widgets.tracker.text"
                 :icon="dashboard.widgets.tracker.icon"
                 :component="dashboard.widgets.tracker.component"
                 :uid="dashboard.widgets.tracker.code"
                 :closable="dashboard.widgets.tracker.closable"
                 :source="source"/>
	  <bbns-widget v-if="hasComments"
                 :title="dashboard.widgets.messages.text"
                 :icon="dashboard.widgets.messages.icon"
                 :component="dashboard.widgets.messages.component"
                 :uid="dashboard.widgets.messages.code"
                 :closable="dashboard.widgets.messages.closable"
                 :source="source"/>
    <bbns-widget v-if="canChange"
                 :title="dashboard.widgets.subtasks.text"
                 :icon="dashboard.widgets.subtasks.icon"
                 :item-component="dashboard.widgets.subtasks.itemComponent"
                 :uid="dashboard.widgets.subtasks.code"
                 :closable="dashboard.widgets.subtasks.closable"
                 :items="source.children"
                 :padding="true"
                 :buttonsRight="tasksButtons"/>
    </bbn-dashboard>
  </bbn-pane>
  <bbn-pane :size="200"
            :scrollable="false">
    <div class="bbn-flex-height bbn-alt-background bbn-bordered-left">
      <div class="bbn-spadded bbn-header bbn-c bbn-b bbn-no-border"><?=_('Add to task')?></div>
      <div class="bbn-flex-fill">
        <bbn-scroll>
          <div class="bbn-padded">
            <div v-for="(w, i) in widgetsAvailable"
                 @click="addToTask(w.code)"
                 :class="['bbn-spadded', 'bbn-c', 'bbn-background', 'bbn-m', 'bbn-p', {
                   'bbn-bottom-space': !!widgetsAvailable[i+1]
                 }]">
              <i :class="w.icon"/>
              <span v-text="w.text"/>
            </div>
          </div>
        </bbn-scroll>
      </div>
    </div>
  </bbn-pane>
</bbn-splitter>