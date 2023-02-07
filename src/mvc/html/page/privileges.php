<bbn-splitter orientation="horizontal">
  <bbn-pane :size="300" class="bbn-bordered-right">
    <div class="bbn-flex-height">
      <div class="bbn-b bbn-upper bbn-c bbn-spadded bbn-alt-background"
           v-text="_('Privileges')"/>
      <div class="bbn-flex-fill">
        <bbn-scroll>
          <bbn-list :source="privileges"
                    :component="$options.components.privilege"
                    :alternate-background="true"
                    uid="id"
                    source-value="id"
                    @select="d => selected = d"/>
        </bbn-scroll>
      </div>
    </div>
  </bbn-pane>
  <bbn-pane>
    <div class="bbn-flex-height">
      <div class="bbn-b bbn-upper bbn-c bbn-spadded bbn-alt-background"
           v-text="_('Users')"/>
      <div v-if="selected"
           class="bbn-spadded bbn-b bbn-upper bbn-primary bbn-c"
           v-text="selected.text"/>
      <div class="bbn-flex-fill">
        <bbn-panelbar class="bbn-overlay"
                      :flex="true"
                      @select="idx => currentSection = idx"
                      :opened="currentSection"
                      :source="panelSource"
                      v-if="selected"/>
        <div v-else
             class="bbn-overlay bbn-middle">
          <div class="bbn-xl bbn-block"><?=_("Select an item...")?></div>
        </div>
      </div>
    </div>
  </bbn-pane>
</bbn-splitter>