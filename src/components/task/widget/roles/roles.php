<div class="appui-task-task-widget-roles bbn-bottom-padding bbn-w-100">
  <div class="bbn-w-100 bbn-radius bbn-border bbn-bottom-space bbn-background"
       :style="{
         borderColor: getRoleBgColor('managers') + '!important'
       }">
    <div :class="['bbn-b', 'bbn-no-border', 'bbn-xspadding', {
           'bbn-radius-top': isManagerOpen,
           'bbn-radius': !isManagerOpen
         }]"
         :style="{
           backgroundColor: getRoleColor('managers'),
           color: getRoleBgColor('managers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-button :icon="isManagerOpen ? 'nf nf-md-arrow_collapse' : 'nf nf-md-arrow_expand'"
                    class="bbn-right-sspace bbn-no-border"
                    :title="isManagerOpen ? _('Collapse') : _('Expand')"
                    @click="isManagerOpen = !isManagerOpen"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('managers')
                    }"/>
        <span class="bbn-flex-fill bbn-c bbn-upper bbn-p bbn-middle"
              @click="isManagerOpen = !isManagerOpen">
          <i class="nf nf-md-account_star bbn-lg bbn-hsmargin"/>
          <?= _('Supervisors') ?>
          <span bbn-if="managers.length"
                class="bbn-badge bbn-s bbn-left-sspace"
                bbn-text="managers.length"
                :style="{
                  'background-color': getRoleBgColor('managers'),
                  color: getRoleColor('managers')
                }"/>
        </span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="() => {isManagerOpen = true; addRole('managers')}"
                    bbn-if="task.canChange"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('managers')
                    }"/>
      </div>
    </div>
    <div bbn-if="isManagerOpen"
         bbn-for="r in managers"
         class=" bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-radius bbn-alt-background bbn-alt-text">
        <bbn-initial :user-id="r.idUser"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span bbn-text="r.userName"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
           bbn-if="task.canChange && (managers.length > 1) && (r.idUser !== source.id_user) && (task.isAdmin || task.isGlobal)"
           @click="removeRole(r.idUser, 'managers')"
           title="<?= _('Remove') ?>"/>
      </div>
    </div>
    <div bbn-if="!managers.length && isManagerOpen"
         class="bbn-spadding bbn-c"
         :style="{
           color: getRoleColor('managers'),
           backgroundColor: getRoleBgColor('managers')
         }">
      <?= _('Not set') ?>
    </div>
  </div>
  <div class="bbn-w-100 bbn-radius bbn-border bbn-bottom-space bbn-background"
       :style="{
         borderColor: getRoleBgColor('workers') + '!important'
       }">
    <div :class="['bbn-b', 'bbn-no-border', 'bbn-xspadding', {
           'bbn-radius-top': isWorkerOpen,
           'bbn-radius': !isWorkerOpen
         }]"
         :style="{
           backgroundColor: getRoleColor('workers'),
           color: getRoleBgColor('workers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-button class="bbn-right-sspace bbn-no-border"
                    :icon="isWorkerOpen ? 'nf nf-md-arrow_collapse' : 'nf nf-md-arrow_expand'"
                    :title="isWorkerOpen ? _('Collapse') : _('Expand')"
                    @click="isWorkerOpen = !isWorkerOpen"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('workers')
                    }"/>
        <span class="bbn-flex-fill bbn-c bbn-upper bbn-p bbn-middle"
              @click="isWorkerOpen = !isWorkerOpen">
          <i class="nf nf-md-worker bbn-hsmargin"/>
          <?= _('Workers') ?>
          <span bbn-if="workers.length"
                class="bbn-badge bbn-s bbn-left-sspace"
                bbn-text="workers.length"
                :style="{
                  'background-color': getRoleBgColor('workers'),
                  color: getRoleColor('workers')
                }"/>
        </span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="() => {isWorkerOpen = true; addRole('workers')}"
                    bbn-if="task.canChange"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('workers')
                    }"/>
      </div>
    </div>
    <div bbn-if="isWorkerOpen"
         bbn-for="r in workers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-alt-background bbn-alt-text bbn-radius">
        <bbn-initial :user-id="r.idUser"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span bbn-text="r.userName"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
           bbn-if="task.canChange"
           @click="removeRole(r.idUser, 'workers')"
           title="<?= _('Remove') ?>"/>
      </div>
    </div>
    <div bbn-if="!workers.length && isWorkerOpen"
         class="bbn-spadding bbn-c"
         :style="{
           color: getRoleColor('workers'),
           backgroundColor: getRoleBgColor('workers')
         }">
      <?= _('Not set') ?>
    </div>
  </div>
  <div class="bbn-w-100 bbn-radius bbn-border bbn-background"
       :style="{
         borderColor: getRoleBgColor('viewers') + '!important'
       }"
       bbn-if="task.canSeeViewers">
    <div :class="['bbn-b', 'bbn-no-border', 'bbn-xspadding', {
           'bbn-radius-top': isViewerOpen,
           'bbn-radius': !isViewerOpen
         }]"
         :style="{
           backgroundColor: getRoleColor('viewers'),
           color: getRoleBgColor('viewers')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <bbn-button class="bbn-right-sspace bbn-no-border"
                    :icon="isViewerOpen ? 'nf nf-md-arrow_collapse' : 'nf nf-md-arrow_expand'"
                    :title="isViewerOpen ? _('Collapse') : _('Expand')"
                    @click="isViewerOpen = !isViewerOpen"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('viewers')
                    }"/>
        <span class="bbn-flex-fill bbn-c bbn-upper bbn-p bbn-middle"
              @click="isViewerOpen = !isViewerOpen">
          <i class="nf nf-fa-user_secret bbn-hsmargin"/><?= _('Spectators') ?>
          <span bbn-if="viewers.length"
                class="bbn-badge bbn-s bbn-left-sspace"
                bbn-text="viewers.length"
                :style="{
                  'background-color': getRoleBgColor('viewers'),
                  color: getRoleColor('viewers')
                }"/>
        </span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="() => {isViewerOpen = true; addRole('viewers')}"
                    bbn-if="task.canChange"
                    :notext="true"
                    :style="{
                      color: getRoleBgColor('viewers')
                    }"/>
      </div>
    </div>
    <div bbn-if="isViewerOpen"
         bbn-for="r in viewers"
         class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-alt-background bbn-alt-text bbn-radius">
        <bbn-initial :user-id="r.idUser"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span bbn-text="r.userName"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
            bbn-if="task.canChange"
            @click="removeRole(r.idUser, 'viewers')"
            title="<?= _('Remove') ?>"/>
      </div>
    </div>
    <div bbn-if="!viewers.length && isViewerOpen"
         class="bbn-spadding bbn-c"
         :style="{
           color: getRoleColor('viewers'),
           backgroundColor: getRoleBgColor('viewers')
         }">
      <?= _('Not set') ?>
    </div>
  </div>
</div>
