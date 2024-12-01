<div class="appui-task-item-title bbn-background bbn-overlay bbn-flex-height">
  <div :class="['bbn-spadding', 'bbn-background', 'bbn-radius', 'bbn-vmiddle', 'bbn-nowrap', {
          'bbn-flex-width': !mainPage.isMobile(),
          'bbn-flex-height': !!mainPage.isMobile()
        }]">
    <div :class="['bbn-upper', 'bbn-b', 'bbn-lg', 'bbn-secondary-text-alt', 'bbn-flex-fill', 'bbn-alt-background', 'bbn-spadding', 'bbn-radius', {
            'bbn-left-sspace bbn-right-space': !mainPage.isMobile(),
            'bbn-top-space bbn-bottom-space': !!mainPage.isMobile(),
          }]"
          bbn-text="_('Edit title')"/>
    <div>
      <bbn-button class="bbn-no-border"
                  icon="nf nf-fa-close bbn-lg"
                  @click="currentPopup.close(currentPopup.items.length - 1, true)"/>
    </div>
  </div>
  <div class="bbn-flex-fill bbn-top-xsspace bbn-xspadding">
    <div class="bbn-100 bbn-radius bbn-alt-background">
      <bbn-form class="bbn-100"
                :source="formSource"
                @submit.prevent="onSubmit">
        <div class="bbn-overlay">
          <div class="bbn-100 bbn-spadding">
            <div class="bbn-100">
              <bbn-textarea class="bbn-overlay bbn-no-border"
                            bbn-model="formSource.title"
                            :resizable="false"
                            maxlength="255"/>
            </div>
          </div>
        </div>
      </bbn-form>
    </div>
  </div>
</div>