<div :class="['appui-task-item', 'bbn-radius', 'bbn-spadded', {
       'bbn-alt-background': !inverted,
       'bbn-background': inverted
     }]">
  <div class="bbn-flex-width">
    <div class="bbn-vmiddle bbn-flex-fill">
      <span :class="['bbn-vmiddle', 'bbn-right-spadded', 'bbn-radius', {
              'bbn-alt-background': inverted,
              'bbn-background': !inverted
            }]">
        <bbn-initial :user-name="author"
                     width="1.2rem"
                     height="1.2rem"
                     font-size="0.7rem"
                     style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important"/>
        <span class="bbn-left-xsspace bbn-s bbn-unselectable"
              v-text="mainPage.isYou(source.id_user) ? _('You') : author"
              :title="author"/>
      </span>
    </div>
    <bbn-context v-if="(!!source.price || !!source.children_price) && (isAdmin || isGlobal || isProjectManager || isDecider)"
                 :source="getBudgetMenuSource"
                 item-component="appui-task-item-menu"
                 class="bbn-right-sspace">
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
                  :title="_('Budget')">
          <span v-text="money(source.price || source.children_price)"/>
          <i v-if="!isApproved && !!source.children_price && !!source.num_children_noprice"
             class="nf nf-fa-info_circle bbn-left-sspace"
             :title="_('%d sub-tasks need their price to be set', source.num_children_noprice)"/>
      </bbn-button>
    </bbn-context>
    <div v-if="source.creation_date === source.last_action"
         v-text="mainPage.formatDate(source.creation_date)"
         :title="_('Created at')"
         :class="['bbn-s', 'bbn-vmiddle', 'bbn-radius', 'bbn-hspadded', {
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]"/>
    <div v-else
         v-text="mainPage.formatDate(source.last_action)"
         :title="_('Updated at')"
         :class="['bbn-s', 'bbn-vmiddle', 'bbn-radius', 'bbn-hspadded', {
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]"/>
    <bbn-context v-if="!columnsComp || !columnsComp.isOrderedByPriority || isSub"
                 :source="getPriorityMenuSource"
                 item-component="appui-task-item-menu"
                 class="bbn-left-sspace">
      <bbn-button v-text="source.priority"
                  :style="{
                    color: 'white',
                    backgroundColor: 'var(--appui-task-pr' + source.priority + ')',
                    padding: 'var(--xsspace)',
                    'line-height': 'inherit',
                    'min-height': 'unset',
                    'cursor': !canChange ? 'default !important' : ''
                  }"
                  class="bbn-upper bbn-s bbn-no-border"
                  :title="_('Priority')"/>
    </bbn-context>
    <bbn-context v-if="!columnsComp || !columnsComp.isOrderedByStatus || isSub"
                 :source="getStatusMenuSource"
                 item-component="appui-task-item-menu"
                 class="bbn-left-sspace">
      <bbn-button v-text="statusText"
                  :style="{
                    color: statusColor,
                    backgroundColor: statusBgColor,
                    padding: 'var(--xsspace)',
                    'line-height': 'inherit',
                    'min-height': 'unset',
                    'cursor': !canChangeStatus ? 'default !important' : ''
                  }"
                  class="bbn-upper bbn-s bbn-no-border"
                  :title="_('Status')"/>
    </bbn-context>
  </div>
  <div class="bbn-flex-width appui-task-item-titlebar">
    <bbn-button :icon="isCollapsed ? 'nf nf-fa-expand' : 'nf nf-fa-compress'"
                :title="isCollapsed ? _('Expand') : _('Collapse')"
                @click="toggleCollapsed"
                :notext="true"
                class="bbn-no-border bbn-right-sspace"/>
    <div :class="['bbn-middle', 'bbn-vsmargin', 'bbn-radius', 'bbn-spadded', 'bbn-flex-fill', {
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]">
      <i class="nf nf-mdi-lock bbn-red bbn-right-sspace"
         :title="_('Private')"
         v-if="!!source.private"/>
      <div class="bbn-b bbn-secondary-text-alt bbn-upper bbn-p"
           v-html="source.title"
           @click="seeTask"
           style="white-space: normal !important"/>
    </div>
    <bbn-context :source="getMenuSource"
                 class="bbn-left-sspace"
                 item-component="appui-task-item-menu"
                 v-if="editable">
      <bbn-button icon="nf nf-mdi-dots_vertical"
                  :title="_('Menu')"
                  :notext="true"
                  class="bbn-no-border"/>
    </bbn-context>
  </div>
  <div v-if="!!showParent && source.parent"
       :class="['bbn-middle', 'bbn-vsmargin', 'bbn-radius', 'bbn-spadded', {
         'bbn-alt-background': inverted,
         'bbn-background': !inverted
       }]">
    <i class="nf nf-mdi-subdirectory_arrow_right bbn-right-sspace"
       :title="_('Parent task')"/>
    <div class="bbn-b bbn-tertiary-text-alt bbn-upper bbn-p"
          v-html="source.parent.title"
          @click="seeParentTask"/>
  </div>
  <div v-if="!isCollapsed"
       class="bbn-vsmargin bbn-w-100"
       ref="description">
    <div v-html="source.content"
          class="appui-task-item-content bbn-w-100"/>
    <div class="bbn-c bbn-top-space bbn-w-100"
          v-if="showOpenContent">
      <bbn-button class="bbn-no-border bbn-upper bbn-xs"
                  :text="_('Show more content')"
                  @click="openDescription"/>
    </div>
  </div>
  <template v-if="!isCollapsed || !collapseFooter">
    <div v-if="source.reference"
         :class="['appui-task-item-reference', 'bbn-vsmargin', 'bbn-w-100', 'bbn-spadded', 'bbn-radius',{
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]"
         v-html="source.reference"/>
    <div class="bbn-vmiddle"
         style="width: 100%; justify-content: space-between">
      <div class="bbn-vmiddle">
        <div v-if="role"
             class="bbn-radius bbn-right-sspace bbn-spadded bbn-upper bbn-s"
             v-text="role.text"
             :style="{
               backgroundColor: role.backgroundColor + ' !important',
               color: role.color + ' !important',
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
            <sub v-text="!!source.roles.managers ? source.roles.managers.length : 0"/>
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
            <sub v-text="!!source.roles.workers ? source.roles.workers.length : 0"/>
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
                    @click="manageRole('viewers')">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-account_eye bbn-lg"/>
            <sub v-text="!!source.roles.viewers ? source.roles.viewers.length : 0"/>
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
                    v-if="canChangeDecider">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-account_cash bbn-lg"/>
            <sub v-text="!!source.roles.deciders ? source.roles.deciders.length : 0"
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
                    style="padding-left: 0.5rem; padding-right: 0.5rem"
                    @click="openNotes">
          <div class="bbn-vmiddle">
            <i class="nf nf-md-comment_text_multiple_outline bbn-lg"/>
            <sub v-text="source.num_notes"
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
            <template v-if="source.num_children">
              <i class="nf nf-md-playlist_check bbn-xl bbn-green"/>
              <sub v-text="closedChildren.length"
                   class="bbn-left-xxsspace bbn-right-sspace bbn-green"/>
              <i class="nf nf-md-playlist_remove bbn-lg bbn-red"/>
              <sub v-text="source.num_children - closedChildren.length"
                   class="bbn-left-xxsspace bbn-red"/>
            </template>
            <template v-else>
              <i class="nf nf-md-list_status bbn-lg"/>
              <sub v-text="0"
                   class="bbn-left-xxsspace"/>
            </template>
          </div>
        </bbn-button>
      </div>
    </div>
    <bbn-column-list v-if="showSubtasks && !!source.num_children"
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