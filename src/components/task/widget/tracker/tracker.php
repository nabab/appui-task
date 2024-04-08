<div class="appui-task-task-widget-tracker bbn-bottom-padded">
  <template v-if="!!progress || summary.length">
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
    <div v-if="totalTime.length"
         class="bbn-background bbn-top-space bbn-radius bbn-spadded">
      <div class="bbn-vmiddle"
           style="justify-content: space-between">
        <span class="bbn-b"><?=_("Total")?></span>
        <div>
          <span v-html="totalTime"/>
          <div v-if="hasTokensActive"
               class="bbn-vmiddle"
               style="justify-content: flex-end">
               <strong v-html="totalTokens"/>
               <span><?=_("tokens")?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="bbn-background bbn-top-space bbn-c bbn-radius"
        @click="task.openTrackerDetail"
        :title="_('See tracker detail')">
      <i class="nf nf-mdi-dots_horizontal bbn-xl bbn-p"/>
    </div>
  </template>
  <div v-else
       class="bbn-radius bbn-spadded bbn-background bbn-text bbn-c"><?=_("No data")?></div>
</div>
