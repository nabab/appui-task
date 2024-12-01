<div class="appui-task-search bbn-flex-height bbn-overlay">
  <div class="bbn-left-sspace bbn-top-sspace bbn-right-sspace bbn-spadding bbn-radius bbn-alt-background">
    <bbn-input bbn-model="currentSearch"
               :button-right="currentSearch.length ? 'nf nf-fa-close' : 'nf nf-fa-search'"
               :action-right="clearSearch"
               :placeholder="_('Search')"
               class="bbn-w-100"/>
  </div>
  <div class="bbn-flex-fill">
    <bbn-scroll>
      <div class="bbn-spadding"
           style="padding-top: 0 !important">
        <bbn-list :source="mainPage.root + 'data/list'"
                  :component="$options.components.item"
                  :componentOptions="{
                    parent: source,
                    idParent: idParent
                  }"
                  :filters="filters"
                  :filterable="true"/>
      </div>
    </bbn-scroll>
  </div>
</div>