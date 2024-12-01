<div class="appui-task-item-description bbn-background bbn-overlay bbn-flex-height">
  <div :class="['bbn-spadding', 'bbn-background', 'bbn-radius', 'bbn-vmiddle', 'bbn-nowrap', {
          'bbn-flex-width': !mainPage.isMobile(),
          'bbn-flex-height': !!mainPage.isMobile()
        }]">
    <div :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-secondary-text-alt', 'bbn-flex-fill', 'bbn-alt-background', 'bbn-spadding', 'bbn-radius', {
            'bbn-left-sspace bbn-right-space': !mainPage.isMobile(),
            'bbn-top-space bbn-bottom-space': !!mainPage.isMobile(),
          }]"
          bbn-text="source.title"/>
    <div>
      <bbn-button class="bbn-no-border"
                  icon="nf nf-fa-close bbn-lg"
                  @click="currentPopup.close(currentPopup.items.length - 1, true)"/>
    </div>
  </div>
  <div class="bbn-flex-fill bbn-top-xsspace bbn-xspadding">
    <div class="bbn-100 bbn-radius bbn-alt-background">
      <bbn-scroll axis="y">
        <div bbn-html="source.content"
             class="bbn-padding bbn-radius"/>
      </bbn-scroll>
    </div>
  </div>
</div>