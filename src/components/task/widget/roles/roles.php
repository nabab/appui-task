<div class="bbn-padded">
  <div v-if="source.roles.managers.length"
       class="bbn-w-100 bbn-box bbn-bottom-space"
  >
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded">
      <i class="nf nf-fa-user_tie bbn-hsmargin"></i><?=_('Supervisors')?>
    </div>
    <div v-for="r in source.roles.managers"
         class="bbn-vmiddle bbn-smargin"
    >
      <bbn-initial :user-id="r"
                   :height="25"
                   :width="25"
                   font-size="1em"
      ></bbn-initial>
      <span v-text="userName(r)"
            class="bbn-hsmargin"
      ></span>
    </div>
  </div>
  <div v-if="source.roles.workers.length"
       class="bbn-w-100 bbn-box bbn-bottom-space"
  >
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded">
      <i class="nf nf-fa-user_astronaut bbn-hsmargin"></i><?=_('Workers')?>
    </div>
    <div v-for="r in source.roles.workers"
         class="bbn-vmiddle bbn-smargin"
    >
      <bbn-initial :user-id="r"
                   :height="25"
                   :width="25"
                   font-size="1em"
      ></bbn-initial>
      <span v-text="userName(r)"
            class="bbn-hsmargin"
      ></span>
    </div>
  </div>
  <div v-if="source.roles.viewers.length"
       class="bbn-w-100 bbn-box bbn-bottom-space"
  >
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded">
      <i class="nf nf-fa-user_secret bbn-hsmargin"></i><?=_('Spectators')?>
    </div>
    <div v-for="r in source.roles.viewers"
         class="bbn-vmiddle bbn-smargin"
    >
      <bbn-initial :user-id="r"
                   :height="25"
                   :width="25"
                   font-size="1em"
      ></bbn-initial>
      <span v-text="userName(r)"
            class="bbn-hsmargin"
      ></span>
    </div>
  </div>
</div>
