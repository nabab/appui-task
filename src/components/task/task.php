<div class="appui-task-task bbn-background bbn-overlay">
  <div class="bbn-overlay bbn-flex-width">
    <div class="bbn-flex-fill">
      <div class="bbn-flex-height">
        <div class="bbn-background bbn-padding appui-task-task-toolbar">
          <div :class="['bbn-alt-background', 'bbn-radius', 'bbn-nowrap', 'bbn-border', 'bbn-flex-width', 'bbn-vmiddle', {'bbn-spadding': !mainPage.isMobile()}]">
            <div class="bbn-background bbn-vmiddle bbn-hspadding bbn-radius bbn-flex-fill"
                 style="min-height: 2rem; flex-wrap: wrap"
                 bbn-if="!mainPage.isMobile()">
              <appui-task-task-actions :source="source"/>
            </div>
            <div bbn-if="mainPage.isMobile()"
                 class="bbn-spadding bbn-radius bbn-flex-width bbn-vmiddle"
                 :style="{
                   color: getStatusColor(getStatusCode(source.state)),
                   backgroundColor: getStatusBgColor(getStatusCode(source.state))
                 }">
              <div class="bbn-upper bbn-b bbn-lg bbn-hspadding bbn-flex-fill"
                   bbn-text="statusText"/>
              <i bbn-if="isActive && !isUnapproved && canStart"
                 @click="start"
                 title="<?= _("Put on ongoing") ?>"
                 class="nf nf-fa-play bbn-p bbn-xxxl"
                 :style="{color: getStatusBgColor('ongoing')}"/>
              <i bbn-if="isActive && !isUnapproved && canHold"
                 @click="hold"
                 title="<?= _("Put on hold") ?>"
                 class="nf nf-fa-pause bbn-p bbn-left-space bbn-xxxl"
                 :style="{color: getStatusBgColor('holding')}"/>
              <i bbn-if="(isActive || isHolding) && !isUnapproved && canResume"
                 @click="resume"
                 title="<?= _("Resume") ?>"
                 class="nf nf-fa-play bbn-p bbn-left-space bbn-xxxl"
                 :style="{color: getStatusBgColor('ongoing')}"/>
              <i bbn-if="(isActive || isUnapproved) && canClose"
                 @click="close"
                 title="<?= _("Close")  ?>"
                 class="nf nf-fa-check bbn-p bbn-left-space bbn-xxxl"
                 :style="{color: getStatusBgColor('closed')}"/>
              <i bbn-if="isClosed && canReopen"
                 @click="reopen"
                 title="<?= _("Reopen") ?>"
                 class="nf nf-fa-hand_pointer_o bbn-p bbn-left-space bbn-xxxl"/>
              <i @click="toggleMobileMenu"
                 title="<?= _("Menu") ?>"
                 class="nf nf-md-menu bbn-p bbn-left-space bbn-xxxl"/>
            </div>
            <div bbn-else
                 :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-spadding', 'bbn-radius', 'bbn-c', {
                   'bbn-left-lspace bbn-right-space': !mainPage.isMobile(),
                   'bbn-flex-fill': mainPage.isMobile()
                 }]"
                 bbn-text="statusText"
                 :style="{
                   color: getStatusColor(getStatusCode(source.state)),
                   backgroundColor: getStatusBgColor(getStatusCode(source.state))
                 }"/>
          </div>
        </div>
        <div class="bbn-flex-fill">
          <div class="bbn-w-100 bbn-hpadding">
            <appui-task-task-info :source="source"
                                  class="bbn-w-100 bbn-padding bbn-alt-background bbn-radius bbn-border"/>
          </div>
          <bbn-dashboard bbn-if="dashboard"
                         :sortable="true"
                        :scrollable="false"
                        :order="dashboard.order"
                        code="appui-task"
                        ref="dashboard"
                        class="bbn-w-100"
                        :max="3">
            <bbns-widget bbn-if="dashboard?.widgets?.budget && budgetIsVisible"
                        :invisible="!currentWidgets.budget"
                        :label="dashboard.widgets.budget.text"
                        :icon="dashboard.widgets.budget.icon"
                        :component="dashboard.widgets.budget.component"
                        :uid="dashboard.widgets.budget.code"
                        :closable="dashboard.widgets.budget.closable"
                        :source="source"
                        :showable="false"
                        :buttonsLeft="closeButton"
                        :padding="true"/>
            <bbns-widget bbn-if="dashboard?.widgets?.roles"
                        :invisible="!currentWidgets.roles"
                        :label="dashboard.widgets.roles.text"
                        :icon="dashboard.widgets.roles.icon"
                        :component="dashboard.widgets.roles.component"
                        :uid="dashboard.widgets.roles.code"
                        :closable="dashboard.widgets.roles.closable"
                        :source="source"
                        :showable="false"
                        :buttonsLeft="closeButton"
                        :padding="true"/>
            <bbns-widget bbn-if="dashboard?.widgets?.tracker"
                        :invisible="!currentWidgets.tracker"
                        :label="dashboard.widgets.tracker.text"
                        :icon="dashboard.widgets.tracker.icon"
                        :component="dashboard.widgets.tracker.component"
                        :uid="dashboard.widgets.tracker.code"
                        :closable="dashboard.widgets.tracker.closable"
                        :source="source"
                        :showable="false"
                        :buttonsLeft="closeButton"
                        :buttonsRight="trackerButtons"
                        :padding="true"/>
            <bbns-widget bbn-if="dashboard?.widgets?.subtasks"
                        :invisible="!currentWidgets.subtasks"
                        :label="dashboard.widgets.subtasks.text"
                        :icon="dashboard.widgets.subtasks.icon"
                        :component="dashboard.widgets.subtasks.component"
                        :uid="dashboard.widgets.subtasks.code"
                        :closable="dashboard.widgets.subtasks.closable"
                        :source="source"
                        :padding="false"
                        :buttonsLeft="closeButton"
                        :showable="false"/>
            <bbns-widget bbn-if="dashboard?.widgets?.logs && (isAdmin || isAccountManager || isGlobal)"
                        :invisible="!currentWidgets.logs"
                        :label="dashboard.widgets.logs.text"
                        :icon="dashboard.widgets.logs.icon"
                        :component="dashboard.widgets.logs.component"
                        :uid="dashboard.widgets.logs.code"
                        :closable="dashboard.widgets.logs.closable"
                        :source="source"
                        :padding="true"
                        :buttonsRight="logsButtons"
                        :buttonsLeft="closeButton"
                        :showable="false"/>
            <bbns-widget bbn-if="dashboard?.widgets?.notes"
                        :invisible="!currentWidgets.notes"
                        :label="dashboard.widgets.notes.text"
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
         style="width: 300px; min-width: 300px"
         bbn-if="widgetsAvailable.length && !mainPage.isMobile()">
      <div class="bbn-overlay bbn-padding bbn-background"
           style="padding-left: 0">
        <div class="bbn-flex-height bbn-radius">
          <div class="bbn-border-top bbn-spadding bbn-radius-top bbn-alt-background bbn-border-left bbn-border-right">
            <div class="bbn-spadding bbn-background bbn-c bbn-b bbn-radius bbn-tertiary-text-alt bbn-upper bbn-bottom-sspace bbn-lg"
                 bbn-text="_('Widgets')"/>
          </div>
          <div class="bbn-flex-fill">
            <bbn-scroll>
              <div class="bbn-padding bbn-background bbn-border-left bbn-border-right bbn-border-bottom bbn-radius-bottom">
                <div bbn-for="(w, i) in widgetsAvailable"
                     @click="addWidgetToTask(w.code)"
                     :key="w.code"
                     :class="['bbn-spadding', 'bbn-c', 'bbn-alt-background', 'bbn-m', 'bbn-p', 'bbn-radius', {
                       'bbn-bottom-space': !!widgetsAvailable[i+1]
                     }]">
                  <i :class="w.icon"/>
                  <span bbn-text="w.text"/>
                </div>
              </div>
            </bbn-scroll>
          </div>
        </div>
      </div>
    </div>
  </div>
  <bbn-slider bbn-if="mainPage.isMobile()"
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
