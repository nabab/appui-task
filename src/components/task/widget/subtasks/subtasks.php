<div class="appui-task-task-widget-subtasks bbn-radius bbn-background bbn-spadded">
  <div class="bbn-flex-width bbn-vmiddle">
    <div class="bbn-vmiddle bbn-flex-fill">
      <span class="bbn-vmiddle bbn-right-spadded bbn-radius bbn-alt-background">
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
    <div v-if="!!role"
         class="bbn-alt-background bbn-radius bbn-xspadded bbn-right-sspace bbn-alt-text bbn-s bbn-vmiddle bbn-upper"
         :style="{
           backgroundColor: !!roleCode ? getRoleBgColor(roleCode) + '!important' : false,
           color: !!roleCode ? getRoleColor(roleCode) + '!important' : false
         }"
         :title="_('Role')"
         v-text="role"/>
    <div class="bbn-s bbn-vmiddle bbn-xspadded bbn-alt-background bbn-radius"
         :title="_('Created ot')"
         v-text="creationDate"/>
    <div class="bbn-upper bbn-xspadded bbn-radius bbn-c bbn-left-sspace bbn-s"
         v-text="state"
         :style="{
           color: getStatusColor(stateCode) + '!important',
           backgroundColor: getStatusBgColor(stateCode) + '!important'
         }"
         :title="_('Status')"/>
  </div>
  <div class="bbn-radius bbn-top-sspace bbn-c bbn-spadded bbn-alt-background bbn-b bbn-secondary-text-alt bbn-upper">
    <span v-html="source.title"
          class="bbn-p"
          @click="openTask"/>
  </div>
</div>