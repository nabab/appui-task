<div>
  <div v-if="source.roles.managers.length"
       class="bbn-w-100 bbn-box"
  >
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top">
      <i class="nf nf-fa-user_tie bbn-hsmargin"></i><?=_('Supervisors')?>
    </div>
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
       class="bbn-w-100 bbn-box"
  >
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top">
      <i class="nf nf-fa-user_astronaut bbn-hsmargin"></i><?=_('Workers')?>
    </div>
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
       class="bbn-w-100 bbn-box"
  >
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top">
      <i class="nf nf-fa-user_secret bbn-hsmargin"></i><?=_('Spectators')?>
    </div>
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
