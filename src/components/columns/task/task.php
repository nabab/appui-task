<div class="appui-task-columns-task">
  <div class="bbn-flex-width">
    <div class="bbn-vmiddle bbn-flex-fill">
      <bbn-initial :user-name="author"
                   width="1.2rem"
                   height="1.2rem"
                   font-size="0.7rem"/>
      <span class="bbn-left-xsspace bbn-s bbn-unselectable"
            v-text="mainPage.isYou(source.id_user) ? _('You') : author"
            :title="author"/>
    </div>
    <div v-if="source.creation_date === source.last_action"
         v-text="mainPage.formatDate(source.creation_date)"
         :title="_('Created at')"
         class="bbn-s"/>
    <div v-else
         v-text="mainPage.formatDate(source.last_action)"
         :title="_('Updated at')"
         class="bbn-s"/>
  </div>
  <div class="bbn-flex-width appui-task-columns-task-titlebar">
    <bbn-button :icon="colObj.collapsed ? 'nf nf-fa-expand' : 'nf nf-fa-compress'"
                :title="colObj.collapsed ? _('Expand') : _('Collapse')"
                @click="$set(colObj, 'collapsed', !!colObj.collapsed ? false : true)"
                :notext="true"
                class="bbn-no-border bbn-right-sspace"/>
    <div class="bbn-middle bbn-vsmargin bbn-background bbn-radius bbn-spadded bbn-flex-fill">
      <i class="nf nf-mdi-lock bbn-red bbn-right-sspace"
         :title="_('Private')"
         v-if="!!source.private"/>
      <div class="bbn-b bbn-secondary-text-alt bbn-upper bbn-p"
           v-html="source.title"
           @click="seeTask"/>
    </div>
    <bbn-context :source="getMenuSource"
                  class="bbn-left-sspace">
      <bbn-button icon="nf nf-mdi-dots_vertical"
                  :title="_('Menu')"
                  :notext="true"
                  class="bbn-no-border"/>
    </bbn-context>
  </div>
  <template v-if="!colObj.collapsed">
    <div class="bbn-vsmargin bbn-w-100"
         ref="description">
      <div v-html="source.content"
           class="appui-task-columns-task-content"/>
      <div class="bbn-c bbn-top-space"
            v-if="showOpenContent">
        <bbn-button class="bbn-no-border bbn-upper bbn-xs"
                    :text="_('Show more content')"
                    @click="openDescription"/>
      </div>
    </div>
    <div v-if="source.reference"
         class="bbn-vsmargin bbn-w-100 bbn-spadded bbn-radius bbn-background"
         v-html="source.reference"/>
    <div class="bbn-grid"
         style="grid-template-columns: repeat(3, 1fr)">
      <div>
        
      </div>
      <div class="bbn-flex"
          style="justify-content: center">
        <bbn-button :title="_('Notes')"
                    class="bbn-background bbn-no-border"
                    style="padding-left: 0.5rem; padding-right: 0.5rem"
                    @click="openNotes">
          <div class="bbn-vmiddle">
            <i class="nf nf-mdi-comment_multiple_outline bbn-lg"/>
            <span v-text="source.num_notes"
                  class="bbn-left-sspace"/>
          </div>
        </bbn-button>
      </div>
      <div class="bbn-flex"
          style="justify-content: flex-end">
        <bbn-button class="bbn-background bbn-no-border"
                    style="padding-left: 0.5rem; padding-right: 0.5rem"
                    :title="_('Sub-Tasks')">
          <div class="bbn-vmiddle">
            <template v-if="source.num_children">
              <!--<i class="nf nf-mdi-playlist_check bbn-xl bbn-green"/>
              <span v-text="source.tasks.completed"
                    class="bbn-left-sspace bbn-right-space bbn-green"/>
              <i class="nf nf-mdi-playlist_remove bbn-lg bbn-red"/>
              <span v-text="source.tasks.count - source.tasks.completed"
                    class="bbn-left-sspace bbn-red"/>-->
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
  </template>
</div>