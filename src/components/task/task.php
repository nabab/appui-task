<div class="appui-task-task bbn-background bbn-overlay bbn-flex-width">
  <div class="bbn-flex-fill bbn-flex-height">
    <div class="bbn-background bbn-padded">
      <div :class="['bbn-spadded', 'bbn-alt-background', 'bbn-radius', 'appui-vcs-box-shadow', 'bbn-vmiddle', 'bbn-nowrap', {
             'bbn-flex-width': !mainPage.isMobile(),
             'bbn-flex-height': !!mainPage.isMobile()
           }]">
        <div class="bbn-background bbn-vmiddle bbn-hspadded bbn-radius bbn-flex-fill"
             style="min-height: 2rem; flex-wrap: wrap">
          <appui-task-task-actions :source="source"/>
        </div>
        <div :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-spadded', 'bbn-radius', {
               'bbn-left-lspace bbn-right-space': !mainPage.isMobile(),
               'bbn-top-space bbn-bottom-space': !!mainPage.isMobile(),
             }]"
             v-text="stateText"
             :style="{
               color: getStatusColor(getStatusCode(source.state)),
               backgroundColor: getStatusBgColor(getStatusCode(source.state))
             }"/>
      </div>
    </div>
    <div class="bbn-flex-fill"
         v-if="!!widgetsAvailable && widgetsAvailable.length && !!canChange">
      <div class="bbn-w-100 bbn-hpadded">
        <appui-task-task-info :source="source"
                              class="bbn-padded bbn-alt-background bbn-radius"/>
      </div>
      <bbn-dashboard :sortable="true"
                    :scrollable="false"
                    :order="dashboard.order"
                    code="appui-task"
                    ref="dashboard"
                    class="bbn-w-100">
        <!--<bbns-widget :title="dashboard.widgets.info.text"
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
                    :padding="true"/>-->
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
  </div>
  <div class="bbn-rel"
       style="width: 200px;">
    <div class="bbn-overlay bbn-padded bbn-background">
      <div class="bbn-flex-height bbn-alt-background bbn-radius">
        <div class="bbn-spadded bbn-header bbn-c bbn-b bbn-no-border bbn-radius-top bbn-secondary-text-alt bbn-upper"><?=_('Add to task')?></div>
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
    </div>
  </div>
</div>
