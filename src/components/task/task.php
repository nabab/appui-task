<div class="appui-task-task bbn-background bbn-overlay">
  <div class="bbn-overlay bbn-flex-width">
    <div class="bbn-flex-fill">
      <div class="bbn-flex-height">
        <div class="bbn-background bbn-padded appui-task-task-toolbar">
          <div :class="['bbn-alt-background', 'bbn-radius', 'bbn-nowrap', 'bbn-bordered', 'bbn-flex-width', 'bbn-vmiddle', {'bbn-spadded': !mainPage.isMobile()}]">
            <div class="bbn-background bbn-vmiddle bbn-hspadded bbn-radius bbn-flex-fill"
                 style="min-height: 2rem; flex-wrap: wrap"
                 v-if="!mainPage.isMobile()">
              <appui-task-task-actions :source="source"/>
            </div>
            <div v-if="mainPage.isMobile()"
                 class="bbn-spadded bbn-radius bbn-flex-width bbn-vmiddle"
                 :style="{
                   color: getStatusColor(getStatusCode(source.state)),
                   backgroundColor: getStatusBgColor(getStatusCode(source.state))
                 }">
              <div class="bbn-upper bbn-b bbn-lg bbn-hspadded bbn-flex-fill"
                   v-text="statusText"/>
              <i v-if="isActive && !isUnapproved && canStart"
                 @click="start"
                 title="<?=_("Put on ongoing")?>"
                 class="nf nf-fa-play bbn-p bbn-xxxl"
                 :style="{color: getStatusBgColor('ongoing')}"/>
              <i v-if="isActive && !isUnapproved && canHold"
                 @click="hold"
                 title="<?=_("Put on hold")?>"
                 class="nf nf-fa-pause bbn-p bbn-left-space bbn-xxxl"
                 :style="{color: getStatusBgColor('holding')}"/>
              <i v-if="(isActive || isHolding) && !isUnapproved && canResume"
                 @click="resume"
                 title="<?=_("Resume")?>"
                 class="nf nf-fa-play bbn-p bbn-left-space bbn-xxxl"
                 :style="{color: getStatusBgColor('ongoing')}"/>
              <i v-if="(isActive || isUnapproved) && canClose"
                 @click="close"
                 title="<?=_("Close") ?>"
                 class="nf nf-fa-check bbn-p bbn-left-space bbn-xxxl"
                 :style="{color: getStatusBgColor('closed')}"/>
              <i v-if="isClosed && canReopen"
                 @click="reopen"
                 title="<?=_("Reopen")?>"
                 class="nf nf-fa-hand_pointer_o bbn-p bbn-left-space bbn-xxxl"/>
              <i @click="toggleMobileMenu"
                 title="<?=_("Menu")?>"
                 class="nf nf-mdi-menu bbn-p bbn-left-space bbn-xxxl"/>
            </div>
            <div v-else
                 :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-spadded', 'bbn-radius', 'bbn-c', {
                   'bbn-left-lspace bbn-right-space': !mainPage.isMobile(),
                   'bbn-flex-fill': mainPage.isMobile()
                 }]"
                 v-text="statusText"
                 :style="{
                   color: getStatusColor(getStatusCode(source.state)),
                   backgroundColor: getStatusBgColor(getStatusCode(source.state))
                 }"/>
          </div>
        </div>
        <div class="bbn-flex-fill">
          <div class="bbn-w-100 bbn-hpadded">
            <appui-task-task-info :source="source"
                                  class="bbn-padded bbn-alt-background bbn-radius bbn-bordered"/>
          </div>
          <bbn-dashboard :sortable="true"
                        :scrollable="false"
                        :order="dashboard.order"
                        code="appui-task"
                        ref="dashboard"
                        class="bbn-w-100">
            <bbns-widget v-if="(isAdmin || isDecider || isGlobal || isProjectManager) && ((isClosed && source.price) || !isClosed)"
                        :hidden="!currentWidgets.budget"
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
            <bbns-widget :hidden="!currentWidgets.roles"
                        :title="dashboard.widgets.roles.text"
                        :icon="dashboard.widgets.roles.icon"
                        :component="dashboard.widgets.roles.component"
                        :uid="dashboard.widgets.roles.code"
                        :closable="dashboard.widgets.roles.closable"
                        :source="source"
                        :showable="false"
                        :buttonsLeft="closeButton"
                        :padding="true"/>
            <bbns-widget :hidden="!currentWidgets.tracker"
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
            <bbns-widget :hidden="!currentWidgets.subtasks"
                        :title="dashboard.widgets.subtasks.text"
                        :icon="dashboard.widgets.subtasks.icon"
                        :item-component="dashboard.widgets.subtasks.itemComponent"
                        :uid="dashboard.widgets.subtasks.code"
                        :closable="dashboard.widgets.subtasks.closable"
                        :items="source.children"
                        :padding="true"
                        :buttonsRight="tasksButtons"
                        :buttonsLeft="closeButton"
                        :showable="false"
                        :options="dashboard.widgets.subtasks.options"/>
            <bbns-widget v-if="isAdmin || isManager || isGlobal"
                        :hidden="!currentWidgets.logs"
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
            <bbns-widget :hidden="!currentWidgets.notes"
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
    </div>
    <div class="bbn-rel"
         style="width: 300px"
         v-if="widgetsAvailable.length && !mainPage.isMobile()">
      <div class="bbn-overlay bbn-padded bbn-background"
           style="padding-left: 0">
        <div class="bbn-flex-height bbn-radius">
          <div class="bbn-bordered-top bbn-spadded bbn-radius-top bbn-alt-background bbn-bordered-left bbn-bordered-right">
            <div class="bbn-spadded bbn-background bbn-c bbn-b bbn-radius bbn-tertiary-text-alt bbn-upper bbn-bottom-sspace bbn-lg"
                 v-text="_('Widgets')"/>
          </div>
          <div class="bbn-flex-fill">
            <bbn-scroll>
              <div class="bbn-padded bbn-background bbn-bordered-left bbn-bordered-right bbn-bordered-bottom bbn-radius-bottom">
                <div v-for="(w, i) in widgetsAvailable"
                     @click="addWidgetToTask(w.code)"
                     :class="['bbn-spadded', 'bbn-c', 'bbn-alt-background', 'bbn-m', 'bbn-p', 'bbn-radius', {
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
  <bbn-slider v-if="mainPage.isMobile()"
              orientation="left"
              ref="slider"
              :style="{
                width: '100%',
                zIndex: 100,
                maxWidth: '100%'
              }"
              close-button="top-right">
    <appui-task-task-menu/>
  </bbn-slider>
</div>
