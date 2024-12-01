<div class="appui-task-task-widget-tracker bbn-bottom-padding">
  <template bbn-if="!!progress || summary.length">
    <div bbn-if="!!progress"
          class="bbn-box bbn-bottom-space bbn-flex-width bbn-green bbn-hpadding bbn-vspadding bbn-vmiddle">
      <i class="bbn-xxxl nf nf-mdi-timer"/>
      <div class="bbn-left-space bbn-xl bbn-flex-fill"
          bbn-text="_('IN PROGRESS')"/>
      <div class="bbn-left-space bbn-xl"
          bbn-text="progress"/>
    </div>
    <div bbn-for="s in summary"
        class="bbn-vmiddle bbn-vsmargin bbn-flex-width bbn-background bbn-radius bbn-right-spadding">
      <bbn-initial :user-id="s.idUser"
                  :height="25"
                  :width="25"
                  font-size="1em"/>
      <span bbn-text="s.userName"
            class="bbn-flex-fill bbn-hsmargin"/>
      <i bbn-if="s.notes"
        class="nf nf-mdi-message_reply_text bbn-left-sspace bbn-right-xsspace bbn-orange bbn-m"
        :title="_('%s wrote %d note(s)', s.userName, s.notes)"/>
      <sub bbn-if="s.notes"
          bbn-text="s.notes"
          :title="_('%s wrote %d note(s)', s.userName, s.notes)"
          class="bbn-orange bbn-right-sspace"/>
      <i :class="['nf nf-mdi-timer', 'bbn-hsmargin', {'bbn-green': !!progress && (currentUserID === s.idUser)}]"/>
      <span bbn-text="s.total"
            :class="{'bbn-green': !!progress && (currentUserID === s.idUser)}"/>
    </div>
    <div bbn-if="totalTime.length"
         class="bbn-background bbn-top-space bbn-radius bbn-spadding">
      <div class="bbn-vmiddle"
           style="justify-content: space-between">
        <span class="bbn-b"><?=_("Total")?></span>
        <div>
          <span bbn-html="totalTime"/>
          <div bbn-if="hasTokensActive"
               class="bbn-vmiddle"
               style="justify-content: flex-end">
               <strong bbn-html="totalTokens"/>
               <span><?=_("tokens")?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="bbn-background bbn-top-space bbn-c bbn-radius"
        @click="task ? task.openTrackerDetail() : () => {}"
        :title="_('See tracker detail')">
      <i class="nf nf-mdi-dots_horizontal bbn-xl bbn-p"/>
    </div>
  </template>
  <div bbn-else
       class="bbn-radius bbn-spadding bbn-background bbn-text bbn-c"><?=_("No data")?></div>
</div>
