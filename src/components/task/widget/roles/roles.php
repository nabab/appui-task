<div class="appui-task-task-widget-roles">
  <div v-if="managers.length"
       class="bbn-w-100 bbn-box bbn-bottom-space"
       :style="{
         backgroundColor: getRoleBgColor('managers')
       }">
    <div class="bbn-header bbn-b bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded"
         :style="{
           backgroundColor: getRoleBgColor('managers'),
           color: getRoleColor('managers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-flex-fill"><i class="nf nf-mdi-account_star bbn-hsmargin"/><?=_('Supervisors')?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
           @click="addRole('managers')"
           v-if="task.canChange"/>
      </div>
    </div>
    <div v-for="r in managers"
         class=" bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-background"
           style="border-radius: 3px">
        <bbn-initial :user-id="r.idUser"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span v-text="r.userName"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
           v-if="task.canChange && (managers.length > 1) && (r.idUser !== source.id_user)"
           @click="removeRole(r.idUser, 'managers')"
           title="<?=_('Remove')?>"/>
      </div>
    </div>
  </div>
  <div class="bbn-w-100 bbn-box bbn-bottom-space"
       :style="{
         backgroundColor: getRoleBgColor('workers')
       }">
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
    <div v-for="r in workers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-background"
           style="border-radius: 3px">
        <bbn-initial :user-id="r.idUser"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span v-text="r.userName"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
           v-if="task.canChange"
           @click="removeRole(r.idUser, 'workers')"
           title="<?=_('Remove')?>"/>
      </div>
    </div>
    <div v-if="!workers.length"
         class="bbn-spadded bbn-c"
         :style="{
           color: getRoleColor('workers')
         }">
      <?=_('Not set')?>
    </div>
  </div>
  <div class="bbn-w-100 bbn-box"
       :style="{
         backgroundColor: getRoleBgColor('viewers')
       }">
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
    <div v-for="r in viewers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-background"
           style="border-radius: 3px">
        <bbn-initial :user-id="r.idUser"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span v-text="r.userName"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
            v-if="task.canChange"
            @click="removeRole(r.idUser, 'viewers')"
            title="<?=_('Remove')?>"/>
      </div>
    </div>
    <div v-if="!viewers.length"
         class="bbn-spadded bbn-c"
         :style="{
           color: getRoleColor('viewers')
         }">
      <?=_('Not set')?>
    </div>
  </div>
</div>
