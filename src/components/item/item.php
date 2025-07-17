<div :class="['appui-task-item', 'bbn-radius', 'bbn-spadding', {
       'bbn-alt-background': !inverted,
       'bbn-background': inverted
     }]">
  <div class="bbn-flex-width">
    <div class="bbn-vmiddle bbn-flex-fill">
      <span :class="['bbn-right-sspace', 'bbn-radius', 'bbn-xspadding', 'bbn-s', {
              'bbn-alt-background': inverted,
              'bbn-background': !inverted
            }]"
            bbn-text="source.ref"
            :title="_('Reference number')"/>
      <span :class="['bbn-vmiddle', 'bbn-right-spadding', 'bbn-radius', {
              'bbn-alt-background': inverted,
              'bbn-background': !inverted
            }]">
        <bbn-initial :user-name="author"
                     width="1.2rem"
                     height="1.2rem"
                     font-size="0.7rem"
                     style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important"/>
        <span class="bbn-left-xsspace bbn-s bbn-unselectable"
              bbn-text="mainPage.isYou(source.id_user) ? _('You') : author"
              :title="author"/>
      </span>
    </div>
    <bbn-context bbn-if="(!!source.price || !!source.children_price) && canSeeBudget"
                 :source="getBudgetMenuSource"
                 item-component="appui-task-item-menu"
                 class="bbn-right-sspace"
                 ref="budgetContext"
                 :attach="budgetContextButton">
      <bbn-button :style="{
                    color: 'white',
                    padding: 'var(--xsspace)',
                    'line-height': 'inherit',
                    'min-height': 'unset',
                    cursor: !getBudgetMenuSource().length ? 'default !important' : ''
                  }"
                  :class="['bbn-upper', 'bbn-s', 'bbn-left-sspace', 'bbn-no-border', 'appui-task-item-btn-budget', {
                    'bbn-bg-green': isApproved,
                    'bbn-bg-orange': !isApproved && (!!source.price || (!!source.children_price && !source.num_children_noprice)),
                    'bbn-bg-red': !isApproved && !!source.children_price && !!source.num_children_noprice
                  }]"
                  :title="_('Budget')"
                  @click="getRef('budgetContext').click()"
                  ref="budgetContextButton"
                  @hook:mounted="budgetContextButton = getRef('budgetContextButton')">
          <span bbn-text="money(source.price || source.children_price)"/>
          <i bbn-if="!isApproved && !!source.children_price && !!source.num_children_noprice"
             class="nf nf-fa-info_circle bbn-left-sspace"
             :title="_('%d sub-tasks need their price to be set', source.num_children_noprice)"/>
      </bbn-button>
    </bbn-context>
    <div bbn-if="source.creation_date === source.last_action"
         bbn-text="mainPage.formatDate(source.creation_date)"
         :title="_('Created at')"
         :class="['bbn-s', 'bbn-vmiddle', 'bbn-radius', 'bbn-hspadding', {
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]"/>
    <div bbn-else
         bbn-text="mainPage.formatDate(source.last_action)"
         :title="_('Updated at')"
         :class="['bbn-s', 'bbn-vmiddle', 'bbn-radius', 'bbn-hspadding', {
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]"/>
    <bbn-context bbn-if="!columnsComp || !columnsComp.isOrderedByPriority || isSub"
                 :source="getPriorityMenuSource"
                 item-component="appui-task-item-menu"
                 class="bbn-left-sspace"
                 ref="priorityContext"
                 :attach="priorityContextButton">
      <bbn-button bbn-text="source.priority"
                  :style="{
                    color: 'white',
                    backgroundColor: 'var(--appui-task-pr' + source.priority + ')',
                    padding: 'var(--xsspace)',
                    'line-height': 'inherit',
                    'min-height': 'unset',
                    'cursor': !canChange ? 'default !important' : ''
                  }"
                  class="bbn-upper bbn-s bbn-no-border"
                  :title="_('Priority')"
                  @click="getRef('priorityContext').click()"
                  ref="priorityContextButton"
                  @hook:mounted="priorityContextButton = getRef('priorityContextButton')"/>
    </bbn-context>
    <bbn-context bbn-if="!columnsComp || !columnsComp.isOrderedByStatus || isSub"
                 :source="getStatusMenuSource"
                 item-component="appui-task-item-menu"
                 class="bbn-left-sspace"
                 ref="statusContext"
                 :attach="statusContextButton">
      <bbn-button bbn-text="statusText"
                  :style="{
                    color: statusColor,
                    backgroundColor: statusBgColor,
                    padding: 'var(--xsspace)',
                    'line-height': 'inherit',
                    'min-height': 'unset',
                    'cursor': !canChangeStatus ? 'default !important' : ''
                  }"
                  class="bbn-upper bbn-s bbn-no-border"
                  :title="_('Status')"
                  @click="getRef('statusContext').click()"
                  ref="statusContextButton"
                  @hook:mounted="statusContextButton = getRef('statusContextButton')"/>
    </bbn-context>
  </div>
  <div class="bbn-flex-width appui-task-item-titlebar">
    <bbn-button :icon="isCollapsed ? 'nf nf-fa-expand' : 'nf nf-fa-compress'"
                :title="isCollapsed ? _('Expand') : _('Collapse')"
                @click="toggleCollapsed"
                :notext="true"
                class="bbn-no-border bbn-right-sspace"/>
    <div :class="['bbn-middle', 'bbn-vsmargin', 'bbn-radius', 'bbn-spadding', 'bbn-flex-fill', {
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]">
      <i bbn-if="!!source.private"
         class="nf nf-md-lock bbn-red bbn-right-sspace"
         :title="_('Private')"/>
      <div class="bbn-b bbn-secondary-text-alt bbn-upper bbn-p"
           bbn-html="source.title"
           @click="seeTask"
           style="white-space: normal !important"/>
    </div>
    <bbn-context bbn-if="editable"
                 :source="getMenuSource"
                 class="bbn-left-sspace"
                 item-component="appui-task-item-menu"
                 ref="menuContext"
                 :attach="menuContextButton">
      <bbn-button icon="nf nf-md-dots_vertical"
                  :title="_('Menu')"
                  :notext="true"
                  class="bbn-no-border"
                  :style="{'cursor': isDeleted ? 'default !important' : ''}"
                  ref="menuContextButton"
                  @hook:mounted="menuContextButton = getRef('menuContextButton')"
                  @click="getRef('menuContext').click()"/>
    </bbn-context>
  </div>
  <div bbn-if="!!showParent && source.parent"
       :class="['bbn-middle', 'bbn-vsmargin', 'bbn-radius', 'bbn-spadding', {
         'bbn-alt-background': inverted,
         'bbn-background': !inverted
       }]">
    <i class="nf nf-md-subdirectory_arrow_right bbn-right-sspace"
       :title="_('Parent task')"/>
    <div class="bbn-b bbn-tertiary-text-alt bbn-upper bbn-p"
          bbn-html="source.parent.title"
          @click="seeParentTask"/>
  </div>
  <div bbn-if="!isCollapsed"
       class="bbn-vsmargin bbn-w-100"
       ref="description">
    <div bbn-html="source.content"
          class="appui-task-item-content bbn-w-100"/>
    <div class="bbn-c bbn-top-space bbn-w-100"
          bbn-if="showOpenContent">
      <bbn-button class="bbn-no-border bbn-upper bbn-xs"
                  :label="_('Show more content')"
                  @click="openDescription"/>
    </div>
  </div>
  <template bbn-if="!isCollapsed || !collapseFooter">
    <div bbn-if="source.reference"
         :class="['appui-task-item-reference', 'bbn-vsmargin', 'bbn-w-100', 'bbn-spadding', 'bbn-radius',{
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]"
         bbn-html="source.reference"/>
    <div class="bbn-vmiddle"
         style="width: 100%; justify-content: space-between">
      <div class="bbn-vmiddle">
        <div bbn-if="userRole"
             class="bbn-radius bbn-right-sspace bbn-spadding bbn-upper bbn-s"
             bbn-text="userRole.text"
             :style="{
               backgroundColor: userRole.backgroundColor + ' !important',
               color: userRole.color + ' !important',
               cursor: 'default !important'
             }"/>
        <bbn-button :title="managersTitle"
                    :class="['bbn-no-border', {
                      'bbn-alt-background': inverted,
                      'bbn-background': !inverted,
                    }]"
                    :style="{
                      'padding-left': '0.5rem',
                      'padding-right': '0.5rem',
                      color: mainPage.getRoleBgColor('managers'),
                      cursor: !canChange ? 'default !important' : ''
                    }"
                    @click="manageRole('managers')">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-account_tie bbn-lg"/>
            <sub bbn-text="!!source.roles.managers ? source.roles.managers.length : 0"/>
          </div>
        </bbn-button>
        <bbn-button :title="workersTitle"
                    :class="['bbn-no-border', 'bbn-left-sspace', {
                      'bbn-alt-background': inverted,
                      'bbn-background': !inverted,
                    }]"
                    :style="{
                      'padding-left': '0.5rem',
                      'padding-right': '0.5rem',
                      color: mainPage.getRoleBgColor('workers'),
                      cursor: !canChange ? 'default !important' : ''
                    }"
                    @click="manageRole('workers')">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-account_hard_hat bbn-lg"/>
            <sub bbn-text="!!source.roles.workers ? source.roles.workers.length : 0"/>
          </div>
        </bbn-button>
        <bbn-button :title="viewersTitle"
                    :class="['bbn-no-border', 'bbn-left-sspace', {
                      'bbn-alt-background': inverted,
                      'bbn-background': !inverted,
                    }]"
                    :style="{
                      'padding-left': '0.5rem',
                      'padding-right': '0.5rem',
                      color: mainPage.getRoleBgColor('viewers'),
                      cursor: !canChange ? 'default !important' : ''
                    }"
                    @click="manageRole('viewers')"
                    bbn-if="canSeeViewers">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-account_eye bbn-lg"/>
            <sub bbn-text="!!source.roles.viewers ? source.roles.viewers.length : 0"/>
          </div>
        </bbn-button>
        <bbn-button :title="decidersTitle"
                    :class="['bbn-no-border', 'bbn-left-sspace', {
                      'bbn-alt-background': inverted,
                      'bbn-background': !inverted,
                    }]"
                    :style="{
                      'padding-left': '0.5rem',
                      'padding-right': '0.5rem',
                      color: mainPage.getRoleBgColor('deciders'),
                      cursor: !canChange ? 'default !important' : ''
                    }"
                    @click="manageRole('deciders')"
                    bbn-if="canChangeDecider">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-account_cash bbn-lg"/>
            <sub bbn-text="!!source.roles.deciders ? source.roles.deciders.length : 0"
                  class="bbn-left-xxsspace"/>
          </div>
        </bbn-button>
      </div>
      <div>
        <bbn-button :title="_('Notes')"
                    :class="['bbn-no-border', 'bbn-right-sspace', {
                      'bbn-alt-background': inverted,
                      'bbn-background': !inverted
                    }]"
                    :style="{
                      'padding-left': '0.5rem',
                      'padding-right': '0.5rem',
                      'cursor': isDeleted ? 'default !important' : ''
                    }"
                    @click="openNotes">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-comment_text_multiple_outline bbn-lg"/>
            <sub bbn-text="source.num_notes"
                 class="bbn-left-xxsspace"/>
          </div>
        </bbn-button>
        <bbn-button :class="['bbn-no-border', {
                      'bbn-alt-background': inverted,
                      'bbn-background': !inverted
                    }]"
                    :style="{
                      'padding-left': '0.5rem',
                      'padding-right': '0.5rem',
                      'cursor': !source.num_children ? 'default !important' : ''
                    }"
                    :title="_('Sub-Tasks')"
                    @click="toggleSubtasks">
          <div class="bbn-vmiddle">
            <template bbn-if="source.num_children">
              <i class="nf nf-md-playlist_check bbn-xl bbn-green"/>
              <sub bbn-text="closedChildren.length"
                   class="bbn-left-xxsspace bbn-right-sspace bbn-green"/>
              <i class="nf nf-md-playlist_remove bbn-lg bbn-red"/>
              <sub bbn-text="source.num_children - closedChildren.length"
                   class="bbn-left-xxsspace bbn-red"/>
            </template>
            <template bbn-else>
              <i class="nf nf-md-list_status bbn-lg"/>
              <sub bbn-text="0"
                   class="bbn-left-xxsspace"/>
            </template>
          </div>
        </bbn-button>
      </div>
    </div>
    <bbn-kanban-element bbn-if="showSubtasks && !!source.num_children"
                     :class="['bbn-top-space', {
                       'bbn-alt-background': inverted,
                       'bbn-background': !inverted
                     }]"
                     :title="_('Sub-Tasks')"
                     :source="mainPage.root + 'data/list'"
                     :filters="currentFilters"
                     :filterable="true"
                     :pageable="true"
                     :sortable="true"
                     :order="currentOrder"
                     component="appui-task-item"
                     :component-options="{
                       inverted: inverted,
                       removeParent: true,
                       isSub: true,
                       collapseFooter: collapseFooter,
                       forceCollapsed: forceCollapsed
                     }"
                     uid="id"
                     :scrollable="false"
                     toolbar="appui-task-item-toolbar"
                     :toolbar-source="source"
                     :limit="5"/>
  </template>
</div>