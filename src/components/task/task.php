<bbn-splitter orientation="horizontal"
              class="appui-task-task">
  <bbn-pane :scrollable="true">
    <div class="bbn-overlay">
      <bbn-dashboard :sortable="true"
                     :order="dashboard.order"
                     code="appui-task"
                     ref="dashboard">
      <bbns-widget :title="dashboard.widgets.info.text"
                   :icon="dashboard.widgets.info.icon"
                   :component="dashboard.widgets.info.component"
                   :uid="dashboard.widgets.info.code"
                   :closable="dashboard.widgets.info.closable"
                   :source="source"
                   :showable="false"
                   :padding="true"/>
      <bbns-widget :title="dashboard.widgets.actions.text"
                   :icon="dashboard.widgets.actions.icon"
                   :component="dashboard.widgets.actions.component"
                   :uid="dashboard.widgets.actions.code"
                   :closable="dashboard.widgets.actions.closable"
                   :source="source"
                   :showable="false"
                   :padding="true"/>
      <bbns-widget v-if="(isAdmin || isDecider) && ((isClosed && source.price) || !isClosed)"
                   :hidden="!currentConfig.budget"
                   :title="dashboard.widgets.budget.text"
                   :icon="dashboard.widgets.budget.icon"
                   :component="dashboard.widgets.budget.component"
                   :uid="dashboard.widgets.budget.code"
                   :closable="dashboard.widgets.budget.closable"
                   :source="source"
                   :showable="false"
                   :buttonsRight="budgetButtons"
                   :buttonsLeft="closeButton"
                   :padding="true"/>
      <bbns-widget :hidden="!currentConfig.roles"
                   :title="dashboard.widgets.roles.text"
                   :icon="dashboard.widgets.roles.icon"
                   :component="dashboard.widgets.roles.component"
                   :uid="dashboard.widgets.roles.code"
                   :closable="dashboard.widgets.roles.closable"
                   :source="source"
                   :showable="false"
                   :buttonsLeft="closeButton"
                   :padding="true"/>
      <bbns-widget :hidden="!currentConfig.tracker"
                   :title="dashboard.widgets.tracker.text"
                   :icon="dashboard.widgets.tracker.icon"
                   :component="dashboard.widgets.tracker.component"
                   :uid="dashboard.widgets.tracker.code"
                   :closable="dashboard.widgets.tracker.closable"
                   :source="source"
                   :showable="false"
                   :buttonsLeft="closeButton"
                   :buttonsRight="trackerButtons"
                   :padding="true"/>
      <!--<bbns-widget v-if="hasComments"
                   :title="dashboard.widgets.messages.text"
                   :icon="dashboard.widgets.messages.icon"
                   :component="dashboard.widgets.messages.component"
                   :uid="dashboard.widgets.messages.code"
                   :closable="dashboard.widgets.messages.closable"
                   :source="source"/>-->
      <bbns-widget v-if="canChange"
                   :hidden="!currentConfig.subtasks"
                   :title="dashboard.widgets.subtasks.text"
                   :icon="dashboard.widgets.subtasks.icon"
                   :item-component="dashboard.widgets.subtasks.itemComponent"
                   :uid="dashboard.widgets.subtasks.code"
                   :closable="dashboard.widgets.subtasks.closable"
                   :items="source.children"
                   :padding="true"
                   :buttonsRight="tasksButtons"
                   :buttonsLeft="closeButton"
                   :showable="false"/>
      <bbns-widget :hidden="!currentConfig.logs"
                   :title="dashboard.widgets.logs.text"
                   :icon="dashboard.widgets.logs.icon"
                   :component="dashboard.widgets.logs.component"
                   :uid="dashboard.widgets.logs.code"
                   :closable="dashboard.widgets.logs.closable"
                   :source="source"
                   :padding="true"
                   :buttonsRight="logsButtons"
                   :buttonsLeft="closeButton"
                   :showable="false"/>
      <bbns-widget :hidden="!currentConfig.notes"
                   :title="dashboard.widgets.notes.text"
                   :icon="dashboard.widgets.notes.icon"
                   :component="dashboard.widgets.notes.component"
                   :uid="dashboard.widgets.notes.code"
                   :closable="dashboard.widgets.notes.closable"
                   :source="source"
                   :full="true"
                   :sortable="false"
                   :showable="false"
                   :buttonsLeft="closeButton"/>
      </bbn-dashboard>
    </div>
  </bbn-pane>
  <bbn-pane :size="200"
            :scrollable="false"
            v-if="!!widgetsAvailable && widgetsAvailable.length && !!canChange">
    <div class="bbn-flex-height bbn-alt-background bbn-bordered-left">
      <div class="bbn-spadded bbn-header bbn-c bbn-b bbn-no-border"><?=_('Add to task')?></div>
      <div class="bbn-flex-fill">
        <bbn-scroll>
          <div class="bbn-padded">
            <div v-for="(w, i) in widgetsAvailable"
                 @click="addWidgetToTask(w.code)"
                 :class="['bbn-spadded', 'bbn-c', 'bbn-background', 'bbn-m', 'bbn-p', 'bbn-radius', {
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