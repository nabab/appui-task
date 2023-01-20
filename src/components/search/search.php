<div class="appui-task-search">
  <bbn-input v-model="currentSearch"
             :button-right="currentSearch.length ? 'nf nf-fa-close' : 'nf nf-fa-search'"
             :action-right="clearSearch"
             :placeholder="_('Search')"
             class="bbn-w-100"/>
  <bbn-list/> 
</div>