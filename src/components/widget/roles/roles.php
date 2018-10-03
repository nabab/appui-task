<div>
  <div v-if="source.roles.managers.length"
       class="k-block"
  >
    <div class="k-header bbn-b"><i class="fas fa-user-tie bbn-hsmargin"></i><?=_('Supervisors')?></div>
    <div v-for="r in source.roles.managers"
         class="bbn-vmiddle bbn-smargin"
    >
      <bbn-initial :user-id="r"
                   :height="20"
                   :width="20"
      ></bbn-initial>
      <span v-text="main.tasks.userName(r)"
            class="bbn-hsmargin"
      ></span>
    </div>
  </div>
  <div v-if="source.roles.workers.length"
       class="k-block"
  >
    <div class="k-header bbn-b"><i class="fas fa-user-astronaut bbn-hsmargin"></i><?=_('Workers')?></div>
    <div v-for="r in source.roles.workers"
         class="bbn-vmiddle bbn-smargin"
    >
      <bbn-initial :user-id="r"
                   :height="20"
                   :width="20"
      ></bbn-initial>
      <span v-text="main.tasks.userName(r)"
            class="bbn-hsmargin"
      ></span>
    </div>
  </div>
  <div v-if="source.roles.viewers.length"
       class="k-block"
  >
    <div class="k-header bbn-b"><i class="fas fa-user-secret bbn-hsmargin"></i><?=_('Spectators')?></div>
    <div v-for="r in source.roles.viewers"
         class="bbn-vmiddle bbn-smargin"
    >
      <bbn-initial :user-id="r"
                   :height="20"
                   :width="20"
      ></bbn-initial>
      <span v-text="main.tasks.userName(r)"
            class="bbn-hsmargin"
      ></span>
    </div>
  </div>
</div>