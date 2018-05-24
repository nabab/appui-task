<div>
  <div v-for="r in source.roles.workers"
       class="bbn-vmiddle bbn-vsmargin bbn-flex-width"
  >
    <bbn-initial :user-id="r"
                 :height="20"
                 :width="20"
    ></bbn-initial>
    <span v-text="tasks.userName(r)"
          class="bbn-flex-fill bbn-hsmargin"
    ></span>
    <span>0 <?=_('hours')?></span>
  </div>
</div>