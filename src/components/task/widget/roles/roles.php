<div class="appui-task-task-widget-roles">
  <div v-if="managers.length"
       class="bbn-w-100 bbn-radius bbn-bordered bbn-bottom-space bbn-background"
       :style="{
         borderColor: getRoleBgColor('managers') + '!important'
       }">
    <div :class="['bbn-b', 'bbn-no-border', 'bbn-xspadded', {
           'bbn-radius-top': isManagerOpen,
           'bbn-radius': !isManagerOpen
         }]"
         :style="{
           backgroundColor: getRoleColor('managers'),
           color: getRoleBgColor('managers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-button :icon="isManagerOpen ? 'nf nf-mdi-arrow_collapse' : 'nf nf-mdi-arrow_expand'"
                    class="bbn-right-sspace bbn-no-border"
                    :title="isManagerOpen ? _('Collapse') : _('Expand')"
                    @click="isManagerOpen = !isManagerOpen"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('managers')
                    }"/>
        <span class="bbn-flex-fill bbn-c bbn-upper">
          <i class="nf nf-mdi-account_star bbn-lg bbn-hsmargin"/>
          <?=_('Supervisors')?>
        </span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="addRole('managers')"
                    v-if="task.canChange"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('managers')
                    }"/>
      </div>
    </div>
    <div v-if="isManagerOpen"
         v-for="r in managers"
         class=" bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-radius bbn-alt-background bbn-alt-text">
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
  <div class="bbn-w-100 bbn-radius bbn-bordered bbn-bottom-space bbn-background"
       :style="{
         borderColor: getRoleBgColor('workers') + '!important'
       }">
    <div :class="['bbn-b', 'bbn-no-border', 'bbn-xspadded', {
           'bbn-radius-top': isWorkerOpen,
           'bbn-radius': !isWorkerOpen
         }]"
         :style="{
           backgroundColor: getRoleColor('workers'),
           color: getRoleBgColor('workers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-button class="bbn-right-sspace bbn-no-border"
                    :icon="isWorkerOpen ? 'nf nf-mdi-arrow_collapse' : 'nf nf-mdi-arrow_expand'"
                    :title="isWorkerOpen ? _('Collapse') : _('Expand')"
                    @click="isWorkerOpen = !isWorkerOpen"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('workers')
                    }"/>
        <span class="bbn-flex-fill bbn-c bbn-upper">
          <i class="nf nf-mdi-worker bbn-hsmargin"/>
          <?=_('Workers')?>
        </span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="addRole('workers')"
                    v-if="task.canChange"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('workers')
                    }"/>
      </div>
    </div>
    <div v-if="isWorkerOpen"
         v-for="r in workers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-alt-background bbn-alt-text bbn-radius">
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
    <div v-if="!workers.length && isWorkerOpen"
         class="bbn-spadded bbn-c"
         :style="{
           color: getRoleColor('workers'),
           backgroundColor: getRoleBgColor('workers')
         }">
      <?=_('Not set')?>
    </div>
  </div>
  <div class="bbn-w-100 bbn-radius bbn-bordered bbn-background"
       :style="{
         borderColor: getRoleBgColor('viewers') + '!important'
       }">
    <div :class="['bbn-b', 'bbn-no-border', 'bbn-xspadded', {
           'bbn-radius-top': isViewerOpen,
           'bbn-radius': !isViewerOpen
         }]"
         :style="{
           backgroundColor: getRoleColor('viewers'),
           color: getRoleBgColor('viewers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-button class="bbn-right-sspace bbn-no-border"
                    :icon="isViewerOpen ? 'nf nf-mdi-arrow_collapse' : 'nf nf-mdi-arrow_expand'"
                    :title="isViewerOpen ? _('Collapse') : _('Expand')"
                    @click="isViewerOpen = !isViewerOpen"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('viewers')
                    }"/>
        <span class="bbn-flex-fill bbn-c bbn-upper"><i class="nf nf-fa-user_secret bbn-hsmargin"/><?=_('Spectators')?></span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="addRole('viewers')"
                    v-if="task.canChange"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('viewers')
                    }"/>
      </div>
    </div>
    <div v-if="isViewerOpen"
         v-for="r in viewers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-alt-background bbn-alt-text bbn-radius">
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
    <div v-if="!viewers.length && isViewerOpen"
         class="bbn-spadded bbn-c"
         :style="{
           color: getRoleColor('viewers'),
           backgroundColor: getRoleBgColor('viewers')
         }">
      <?=_('Not set')?>
    </div>
  </div>
</div>
