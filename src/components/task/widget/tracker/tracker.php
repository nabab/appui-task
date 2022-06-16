<div class="appui-task-task-widget-tracker">
  <div v-if="!!progress"
        class="bbn-box bbn-bottom-space bbn-flex-width bbn-green bbn-hpadded bbn-vspadded bbn-vmiddle">
    <i class="bbn-xxxl nf nf-mdi-timer"/>
    <div class="bbn-left-space bbn-xl bbn-flex-fill"
         v-text="_('IN PROGRESS')"/>
    <div class="bbn-left-space bbn-xl"
         v-text="progress"/>
  </div>
  <div v-for="s in summary"
       class="bbn-vmiddle bbn-vsmargin bbn-flex-width bbn-background bbn-radius bbn-right-spadded">
    <bbn-initial :user-id="s.idUser"
                 :height="25"
                 :width="25"
                 font-size="1em"/>
    <span v-text="s.userName"
          class="bbn-flex-fill bbn-hsmargin"/>
    <i v-if="s.notes"
       class="nf nf-mdi-message_reply_text bbn-left-sspace bbn-right-xsspace bbn-orange bbn-m"
       :title="_('%s wrote %d note(s)', s.userName, s.notes)"/>
    <sub v-if="s.notes"
         v-text="s.notes"
         :title="_('%s wrote %d note(s)', s.userName, s.notes)"
         class="bbn-orange bbn-right-sspace"/>
    <i :class="['nf nf-mdi-timer', 'bbn-hsmargin', {'bbn-green': !!progress && (currentUserID === s.idUser)}]"/>
    <span v-text="s.total"
          :class="{'bbn-green': !!progress && (currentUserID === s.idUser)}"/>
  </div>
</div>
