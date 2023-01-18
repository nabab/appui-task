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
    <div v-if="!columnsComp || !columnsComp.isOrderedByPriority"
         v-text="source.priority"
         :class="['bbn-xspadded', 'bbn-radius', 'bbn-s', 'bbn-left-sspace', 'bbn-vmiddle', 'appui-task-pr' + source.priority]"
         :title="_('Priority')"/>
    <bbn-context :source="getStatusSource"
                 item-component="appui-task-item-menu">
      <bbn-button v-if="!columnsComp || !columnsComp.isOrderedByStatus"
                  v-text="statusText"
                  :style="{
                    color: statusColor,
                    backgroundColor: statusBgColor,
                    padding: 'var(--xsspace)',
                    'line-height': 'inherit',
                    'min-height': 'unset',
                    'cursor': !canChangeStatus ? 'default !important' : ''
                  }"
                  class="bbn-upper bbn-s bbn-left-sspace bbn-no-border"
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
           @click="seeTask"/>
    </div>
    <bbn-context :source="getMenuSource"
                 class="bbn-left-sspace"
                 item-component="appui-task-item-menu">
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
  <template v-if="!isCollapsed">
    <div class="bbn-vsmargin bbn-w-100"
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
    <div v-if="source.reference"
         :class="['bbn-vsmargin', 'bbn-w-100', 'bbn-spadded', 'bbn-radius',{
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]"
         v-html="source.reference"/>
    <div class="bbn-vmiddle"
         style="width: 100%; justify-content: space-between">
      <div class="bbn-vmiddle">
        <div v-if="role"
             class="bbn-radius bbn-right-space bbn-spadded bbn-upper bbn-s"
             v-text="role.text"
             :style="{
               backgroundColor: role.backgroundColor + ' !important',
               color: role.color + ' !important',
               cursor: 'default !important'
             }"/>
        <div :style="{
               color: mainPage.getRoleBgColor('managers'),
               cursor: !canChange ? 'default !important' : ''
             }"
             :title="managersTitle"
             :class="['bbn-no-border', 'bbn-button', {
               'bbn-alt-background': inverted,
               'bbn-background': !inverted
             }]"
             @click="manageRole('managers')">
          <i class="nf nf-mdi-account_star bbn-xl"/>
          <span v-text="!!source.roles.managers ? source.roles.managers.length : 0"/>
        </div>
        <div :style="{
               color: mainPage.getRoleBgColor('workers'),
               cursor: !canChange ? 'default !important' : ''
             }"
             :title="workersTitle"
             :class="['bbn-left-space', 'bbn-no-border', 'bbn-button', {
               'bbn-alt-background': inverted,
               'bbn-background': !inverted
             }]"
             @click="manageRole('workers')">
          <i class="nf nf-mdi-worker"/>
          <span v-text="!!source.roles.workers ? source.roles.workers.length : 0"/>
        </div>
      </div>
      <div>
        <bbn-button :title="_('Notes')"
                    :class="['bbn-no-border', {
                      'bbn-alt-background': inverted,
                      'bbn-background': !inverted
                    }]"
                    style="padding-left: 0.5rem; padding-right: 0.5rem"
                    @click="openNotes">
          <div class="bbn-vmiddle">
            <i class="nf nf-mdi-comment_multiple_outline bbn-lg"/>
            <span v-text="source.num_notes"
                  class="bbn-left-sspace"/>
          </div>
        </bbn-button>
      </div>
      <div>
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
              <i class="nf nf-mdi-playlist_check bbn-xl bbn-green"/>
              <span v-text="closedChildren.length"
                    class="bbn-left-sspace bbn-right-space bbn-green"/>
              <i class="nf nf-mdi-playlist_remove bbn-lg bbn-red"/>
              <span v-text="source.num_children - closedChildren.length"
                    class="bbn-left-sspace bbn-red"/>
            </template>
            <template v-else>
              <i class="bbn-right-sspace nf nf-mdi-format_list_checks bbn-lg"/>
              <span v-text="_('No sub-tasks')"
                    class="bbn-upper bbn-xs"/>
            </template>
          </div>
        </bbn-button>
      </div>
    </div>
    <div v-if="showSubtasks && !!source.num_children"
         :class="['bbn-spadded', 'bbn-top-space', 'bbn-radius', {
           'bbn-alt-background': inverted,
           'bbn-background': !inverted
         }]">
      <div class="bbn-radius bbn-c bbn-upper bbn-xspadded bbn-xsspace bbn-tertiary-text-alt bbn-b"
           v-text="_('Sub-Tasks')"/>
      <appui-task-item v-for="(child, cidx) in source.children"
                       :source="child"
                       :inverted="inverted"
                       :key="cidx"
                       :class="['bbn-alt-background', 'bbn-radius', 'bbn-spadded', 'bbn-radius', {
                         'bbn-bottom-space': !!source.children[cidx + 1]
                       }]"/>
    </div>
  </template>
</div>