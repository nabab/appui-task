<div class="appui-task-task-widget-roles">
  <div v-if="source.roles.managers.length"
       class="bbn-w-100 bbn-box bbn-bottom-space">
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded"
         :style="{
           backgroundColor: getRoleBgColor('managers'),
           color: getRoleColor('managers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-flex-fill"><i class="nf nf-mdi-account_star bbn-hsmargin"/><?=_('Supervisors')?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
           @click="addRole('workers')"
           v-if="task.canChange && (source.roles.managers.length > 1)"/>
      </div>
    </div>
    <div v-for="r in source.roles.managers"
         class=" bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-initial :user-id="r"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span v-text="userName(r)"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red"
           v-if="task.canChange"
           @click="removeRole(r, 'managers')"
           title="<?=_('Remove')?>"/>
      </div>
    </div>
  </div>
  <div class="bbn-w-100 bbn-box bbn-bottom-space">
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded"
         :style="{
          backgroundColor: getRoleBgColor('workers'),
          color: getRoleColor('workers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-flex-fill"><i class="nf nf-mdi-worker bbn-hsmargin"/><?=_('Workers')?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
           @click="addRole('workers')"
           v-if="task.canChange"/>
      </div>
    </div>
    <div v-for="r in source.roles.workers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-initial :user-id="r"
                    :height="25"
                    :width="25"
                    font-size="1em"/>
        <span v-text="userName(r)"
              class="bbn-hsmargin"/>
        <i class="nf nf-fa-trash bbn-p bbn-red"
           v-if="task.canChange"
           @click="removeRole(r, 'workers')"
           title="<?=_('Remove')?>"/>
      </div>
    </div>
    <div v-if="!source.roles.workers.length"
         class="bbn-spadded bbn-c">
      <?=_('Not set')?>
    </div>
  </div>
  <div class="bbn-w-100 bbn-box">
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded"
         :style="{
           backgroundColor: getRoleBgColor('viewers'),
           color: getRoleColor('viewers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-flex-fill"><i class="nf nf-fa-user_secret bbn-hsmargin"/><?=_('Spectators')?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
          @click="addRole('viewers')"
          v-if="task.canChange"/>
      </div>
    </div>
    <div v-for="r in source.roles.viewers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-initial :user-id="r"
                    :height="25"
                    :width="25"
                    font-size="1em"/>
        <span v-text="userName(r)"
              class="bbn-hsmargin"/>
        <i class="nf nf-fa-trash bbn-p bbn-red"
            v-if="task.canChange"
            @click="removeRole(r, 'viewers')"
            title="<?=_('Remove')?>"/>
      </div>
    </div>
    <div v-if="!source.roles.viewers.length"
         class="bbn-spadded bbn-c">
      <?=_('Not set')?>
    </div>
  </div>
</div>
