<div class="appui-task-item-notes bbn-background bbn-overlay bbn-flex-height">
  <div class="bbn-spadding bbn-background bbn-radius bbn-vmiddle bbn-nowrap bbn-flex-width">
    <div :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-secondary-text-alt', 'bbn-flex-fill', 'bbn-alt-background', 'bbn-spadding', 'bbn-radius', 'bbn-ellipsis', {
            'bbn-left-sspace bbn-right-space': !mainPage.isMobile()
          }]"
          bbn-text="source.title"/>
    <div>
      <bbn-button class="bbn-no-border"
                  icon="nf nf-fa-close bbn-lg"
                  @click="currentPopup.close(currentPopup.items.length - 1, true)"/>
    </div>
  </div>
  <div class="bbn-flex-fill bbn-top-xsspace bbn-xspadding">
    <appui-task-notes :source="source"
                      class="bbn-no-border"/>
  </div>
</div>