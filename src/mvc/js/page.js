// Javascript Document
(() => {
  if (!window.appuiTaskMixin) {
    window.appuiTaskMixin = {
      data(){
        return {
          userId: appui.user.id,
          isAdmin: appui.user.isAdmin
        }
      },
      computed: {
        statusText(){
          return !!this.mainPage ? bbn.fn.getField(this.mainPage.optionsStates, 'text', 'value', this.source.state) : '';
        },
        statusBgColor(){
          return !!this.mainPage ? this.mainPage.getStatusBgColor(this.mainPage.getStatusCode(this.source.state)) : '';
        },
        statusColor(){
          return !!this.mainPage ? this.mainPage.getStatusColor(this.mainPage.getStatusCode(this.source.state)) : '';
        },
        isGlobal(){
          return !!this.mainPage.privileges.global;
        },
        isAccountManager(){
          return !!this.mainPage.privileges.account_manager;
        },
        isAccountViewer(){
          return !!this.mainPage.privileges.account_viewer;
        },
        isProjectManager(){
          return !!this.mainPage.privileges.project_manager;
        },
        isProjectViewer(){
          return !!this.mainPage.privileges.project_viewer;
        },
        isAssigner(){
          return !!this.mainPage.privileges.assigner;
        },
        isFinancialManager(){
          return !!this.mainPage.privileges.financial_manager;
        },
        isFinancialViewer(){
          return !!this.mainPage.privileges.financial_viewer;
        },
        isProjectSupervisor(){
          return !!this.mainPage.privileges.project_supervisor;
        },
        isMaster() {
          return this.userId === this.source.id_user;
        },
        isManager() {
          return this.isMaster
            || (!!this.source.roles.managers && this.source.roles.managers.includes(this.userId));
        },
        isWorker() {
          return !!this.source.roles.workers && this.source.roles.workers.includes(this.userId);
        },
        isViewer() {
          return !!this.source.roles.viewers && this.source.roles.viewers.includes(this.userId);
        },
        isDecider() {
          return !!this.source.roles.deciders && this.source.roles.deciders.includes(this.userId);
        },
        isAdded() {
          return this.isManager || this.isWorker || this.isViewer;
        },
        isClosed() {
          return this.source.state === this.mainPage.source.states.closed;
        },
        isOpened() {
          return this.source.state === this.mainPage.source.states.opened;
        },
        isOngoing() {
          return this.source.state === this.mainPage.source.states.ongoing;
        },
        isHolding() {
          return this.source.state === this.mainPage.source.states.holding;
        },
        isOpenedOrOngoing() {
          return this.isOngoing || this.isOpened;
        },
        isHoldingOrOpened() {
          return this.isHolding || this.isOpened;
        },
        isCanceled(){
          return this.source.state === this.mainPage.source.states.canceled;
        },
        isDeleted(){
          return this.source.state === this.mainPage.source.states.deleted;
        },
        isActive() {
          return !this.isClosed && !this.isHolding && !this.isCanceled && !this.isDeleted;
        },
        isApproved() {
          return !this.isUnapproved
            && !!this.source.approved
            && ((this.source.price
                && !!this.source.lastChangePrice
                && (this.source.approved.chrono !== undefined)
                && (this.source.lastChangePrice.chrono !== undefined)
                && (this.source.approved.chrono > this.source.lastChangePrice.chrono))
              || this.source.children_price)
            ;
        },
        isUnapproved() {
          return this.source.state === this.mainPage.source.states.unapproved;
        },
        canChange() {
          return !this.isClosed
            && !this.isCanceled
            && !this.isDeleted
            && (this.isMaster
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectManager
              || this.isProjectSupervisor
              || (!this.source.private && this.isManager));
        },
        canChangeBudget(){
          return !this.isClosed
            && !this.isCanceled
            && !this.isDeleted
            && (this.isAdmin || this.isGlobal || this.isAccountManager);
        },
        canSeeBudget(){
          return this.isAdmin
            || this.isGlobal
            || this.isDecider
            || this.isAccountManager
            || this.isAccountViewer
            || this.isFinancialManager
            || this.isFinancialViewer;
        },
        canStart() {
          return this.isOpened
            && (this.isManager
              || this.isWorker
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canClose() {
          return !this.isClosed
            && !this.isCanceled
            && !this.isDeleted
            && (this.isManager
              || this.isWorker
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canHold() {
          return (this.isOngoing || this.isOpened)
            && (this.isManager
              || this.isWorker
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canResume() {
          return (this.isHolding && !this.isOpened)
            && (this.isManager
              || this.isWorker
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canReopen() {
          return this.isClosed
            && (this.isManager
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canCancel(){
          return !this.isClosed
            && !this.isCanceled
            && !this.isDeleted
            && (this.isManager
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canRemoveTask(){
          return !this.isClosed
            && !this.isDeleted
            && (this.isManager
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canPing() {
          return !this.isClosed
            && !this.isCanceled
            && !this.isDeleted
            && (this.isManager
              || this.isGlobal
              || this.isAccountManager
              || this.isProjectSupervisor);
        },
        canApprove() {
          return (this.isDecider || this.isFinancialManager)
            && !this.isClosed
            && !this.isCanceled
            && !this.isDeleted
            && !!this.isUnapproved
            && !this.source.parent_has_price
            && ((!!this.source.price && !this.isApproved)
              || !!this.source.children_price);
        },
        canChangeDecider() {
          return (this.isDecider || this.isAdmin || this.isGlobal || this.isAccountManager)
            && (!!this.source.price || !!this.source.children_price)
            && !this.isClosed
            && !this.isCanceled
            && !this.isDeleted;
        },
        canBecomeManager(){
          return !this.isManager
            && !this.isCanceled
            && !this.isDeleted
            && (!!this.mainPage.privileges.manager || this.isGlobal);
        },
        canBecomeWorker(){
          return (!!this.mainPage.privileges.worker || this.isGlobal)
            && !this.isWorker
            && (!this.isManager || (this.source.roles.managers.length > 1))
            && !this.isCanceled
            && !this.isDeleted;
        },
        canBecomeViewer(){
          return (!!this.mainPage.privileges.viewer || this.isGlobal)
            && !this.isViewer
            && (!this.isManager || (this.source.roles.managers.length > 1))
            && !this.isCanceled
            && !this.isDeleted;
        },
        canBecomeDecider(){
          return (!!this.mainPage.privileges.decider || this.isGlobal)
            && !this.isDecider
            && (!this.isManager
              || (this.source.roles.managers.length > 1))
            && !this.isCanceled
            && !this.isDeleted;
        },
        canRemoveHimselfManager(){
          return (!!this.mainPage.privileges.manager || this.isGlobal)
            && !!this.isManager
            && !this.isMaster
            && (this.source.roles.managers.length > 1)
            && !this.isCanceled
            && !this.isDeleted;
        },
        canRemoveHimselfWorker(){
          return (!!this.mainPage.privileges.worker || this.isGlobal)
            && !!this.isWorker
            && !this.isCanceled
            && !this.isDeleted;
        },
        canRemoveHimselfViewer(){
          return (!!this.mainPage.privileges.viewer || this.isGlobal)
            && !!this.isViewer
            && !this.isCanceled
            && !this.isDeleted;
        },
        canRemoveHimselfDecider(){
          return (!!this.mainPage.privileges.decider || this.isGlobal)
            && !!this.isDecider
            && !this.isCanceled
            && !this.isDeleted;
        },
        canUnmakeMe() {
          return this.canRemoveHimselfManager
            || this.canRemoveHimselfWorker
            || this.canRemoveHimselfViewer
            || this.canRemoveHimselfDecider
        },
        canBill() {
          return this.isClosed
            && this.isApproved
            && (this.isAdmin || this.isGlobal || this.isAccountManager)
            && (appui.plugins['appui-billing'] !== undefined);
        },
        canSeeViewers(){
          return this.isManager
            || this.isGlobal
            || this.isAccountManager
            || this.isAccountViewer
            || this.isProjectSupervisor
            || this.isProjectManager;
        }
      },
      methods: {
        start() {
          if (this.canStart) {
            this.confirm(bbn._('Are you sure you want to put this task on ongoing?'), () => {
              this.update('state', this.mainPage.source.states.ongoing);
            });
          }
        },
        hold() {
          if (this.canHold) {
            this.confirm(bbn._('Are you sure you want to put this task on hold?'), () => {
              this.update('state', this.mainPage.source.states.holding);
            });
          }
        },
        close() {
          if (this.canClose) {
            this.confirm(bbn._("Are you sure you want to close this task?"), () => {
              this.update('state', this.mainPage.source.states.closed);
            });
          }
        },
        resume() {
          if (this.canResume) {
            this.confirm(bbn._('Are you sure you want to resume this task?'), () => {
              this.update('state', this.mainPage.source.states.ongoing);
            });
          }
        },
        cancel(){
          if (this.canCancel) {
            this.confirm(bbn._('Are you sure you want to cancel this task?'), () => {
              this.update('state', this.mainPage.source.states.canceled);
            });
          }
        },
        removeTask(){
          if (this.canRemoveTask) {
            this.confirm(bbn._('Are you sure you want to remove this task?'), () => {
              this.update('state', this.mainPage.source.states.deleted);
            });
          }
        },
        ping(){
          if (this.canPing) {
            this.confirm(bbn._('Are you sure you want to ping the task?'), () => {
              this.post(this.root + 'actions/task/ping', { id_task: this.source.id }, (d) => {
                if ( d.success ){
                  appui.success(bbn._('Pinged'));
                }
                else{
                  appui.error(bbn._('Error'));
                }
              });
            });
          }
        },
        reopen() {
          if (this.canReopen) {
            this.confirm(bbn._("Are you sure you want to reopen this task?"), () => {
              this.update('state', this.mainPage.source.states.opened);
            });
          }
        },
        update(prop, val) {
          return this.post(this.root + 'actions/task/update', {
            id_task: this.source.id,
            prop: prop,
            val: val
          }, d => {
            if (!d.success) {
              this.alert(bbn._('Error'));
              return false;
            }
            let props = {
              [prop]: val
            }
            if (prop === 'state') {
              this.source.state = val;
              this.source.tracker = d.tracker;
              if (!d.trakcer) {
                let tracker = appui.getRegistered('appui-task-tracker');
                if (bbn.fn.isVue(tracker)
                  && !!tracker.active
                  && (tracker.active.id === this.source.id)
                ) {
                  tracker.clear();
                }
              }
              this.source.trackers = d.trackers;
              props.tracker = d.tracker;
              props.trackers = d.trackers;
              let columns = this.closest('appui-task-columns');
              if (bbn.fn.isVue(columns)
                && (columns.order === 'status')
              ) {
                columns.reloadAll();
              }
            }
            if (prop === 'price') {
              if (d.lastChangePrice !== undefined) {
                this.source.lastChangePrice = d.lastChangePrice;
                props.lastChangePrice = d.lastChangePrice;
              }
              if (!val) {
                this.source.price = null;
                this.source.state = this.mainPage.states.opened;
                props.state = this.mainPage.states.opened;
                this.$set(this.source, 'approved', null);
                this.$set(this.source, 'lastChangePrice', null);
                props.approved = null;
                props.lastChangePrice = null;
              }
              else {
                this.source.state = this.mainPage.states.unapproved;
                props.state = this.mainPage.states.unapproved;
              }
            }
            if (prop === 'priority') {
              let columns = this.closest('appui-task-columns');
              if (bbn.fn.isVue(columns)
                && (columns.order === 'priority')
              ) {
                columns.reloadAll();
              }
            }
            let lastAction = dayjs().format('YYYY-MM-DD HH:mm:ss');
            this.$set(this.source, 'last_action', lastAction);
            props.last_action = lastAction;
            let comps = bbn.fn.unique(this.mainPage.findAllByKey(this.source.id, 'appui-task-item'));
            let t = appui.getRegistered('appui-task-' + this.source.id, true);
            if (t) {
              comps.push(t);
            }
            if (comps.length) {
              bbn.fn.each(comps, c => {
                if (c._uid !== this._uid) {
                  bbn.fn.iterate(props, (v, i) => {
                    if (!bbn.fn.isSame(v, c.source[i])) {
                      c.$set(c.source, i, v)
                    }
                  });
                }
              });
            }
            if (!!d.toUpdate && d.toUpdate.length) {
              this.updateItems(d.toUpdate);
            }
            if ((prop === 'state')
              && (val === this.mainPage.states.deleted)
            ) {
              let t = appui.getRegistered('appui-task-' + this.source.id, true);
              if (t) {
                let cont = t.closest('bbn-container');
                if (cont) {
                  cont.router.close(cont.idx, true);
                }
              }
            }
          });
        },
        updateItems(items){
          if (!!items) {
            if (!bbn.fn.isArray(items)) {
              items = [items];
            }
            bbn.fn.each(items, item => {
              if (bbn.fn.isObject(item)) {
                let comps = bbn.fn.unique(this.mainPage.findAllByKey(item.id, 'appui-task-item'));
                let t = appui.getRegistered('appui-task-' + item.id, true);
                if (t) {
                  comps.push(t);
                }
                if (comps.length) {
                  bbn.fn.each(comps, c => {
                    if (c._uid !== this._uid) {
                      bbn.fn.iterate(item, (v, i) => {
                        if ((c.source[i] !== undefined)
                          && !bbn.fn.isSame(v, c.source[i])
                        ) {
                          c.$set(c.source, i, v);
                        }
                      });
                      if ((c.$options._componentTag === 'appui-task-task')
                        && (c.source.state === this.mainPage.states.deleted)
                      ) {
                        let cont = c.closest('bbn-container');
                        if (cont) {
                          cont.router.close(cont.idx, true);
                        }
                      }
                    }
                  });
                }
              }
            })
          }
        },
        askSetSubtasksRoles(){
          if (!!this.source.children && this.source.children.length) {
            this.confirm(bbn._('Do you want to apply the same roles configurations of this task in its related subtasks?'), () => {
              this.post(this.mainPage.root + 'actions/role/subtasks', {
                id: this.source.id
              }, d => {
                if (d.success) {
                  if (d.roles && bbn.fn.numProperties(d.roles)) {
                    bbn.fn.iterate(d.roles, (roles, id) => {
                      let child = bbn.fn.getRow(this.source.children, 'id', id);
                      if (child) {
                        this.$set(child, 'roles', roles);
                      }
                      let comps = this.mainPage.findAllByKey(id, 'appui-task-item');
                      if (comps.length) {
                        bbn.fn.each(comps, c => c.$set(c.source, 'roles', roles));
                      }
                      let t = appui.getRegistered('appui-task-' + id, true);
                      if (t) {
                        this.$set(t.source, 'roles', roles);
                      }
                    });
                  }
                  appui.success();
                }
                else {
                  appui.error();
                }
              });
            });
          }
        },
        addTask(){
          if (this.canChange && !!this.source.id) {
            let roles = bbn.fn.extend(true, {}, this.source.roles);
            if (roles.deciders) {
              delete roles.deciders;
            }
            bbn.fn.iterate(roles, (us, ro) => {
              if (us.includes(appui.user.id)) {
                us.splice(us.indexOf(appui.user.id), 1);
              }
              if (!us.length) {
                delete roles[ro];
              }
            });
            this.getPopup({
              title: bbn._('New task'),
              width: 500,
              component: 'appui-task-form-new',
              componentOptions: {
                source: {
                  title: '',
                  type: '',
                  id_parent: this.source.id,
                  private: !!this.source.private ? 1 : 0
                },
                roles: bbn.fn.numProperties(roles) ? roles : false
              }
            });
          }
        },
        searchTask(){
          if (this.canChange && !!this.source.id) {
            this.getPopup({
              title: bbn._('Search and add subtasks'),
              width: 500,
              height: 400,
              scrollable: false,
              component: 'appui-task-search',
              componentOptions: {
                source: this.source,
                idParent: this.source.id
              }
            });
          }
        }
      }
    }
  }

  return {
    data(){
      return {
        root: appui.plugins['appui-task'] + '/',
        priorities: Array.from({length: 5}, (v, n) => {
          return {
            text: n + 1,
            value: n + 1,
            class: 'appui-task-pr' + (n + 1),
            backgroundColor: 'var(--appui-task-pr' + (n + 1) + ')',
            color: 'white'
          }
        })
      };
    },
    computed: {
      fullCategories(){
        let res = [];
        const getItem = (cat, group) => {
          let items = cat.items || false;
          delete cat.items;
          cat.value = cat.id;
          cat.group = group || (items ? cat.text : '');
          res.push(cat);
          if ( items ){
           bbn.fn.each(items, (c, i) => {
              getItem(c, cat.text);
            });
          }
        };
        if ( this.source.categories ){
          bbn.fn.each(bbn.fn.extend(true, {}, this.source.categories), cat => {
            getItem(cat);
          });
        }
        return res;
      },
      groups(){
        let users = bbn.fn.extend(true, [], appui.users);
        return bbn.fn.map(bbn.fn.extend(true, [], this.source.groups), v => {
          v.text = v.nom || v.text
          v.expanded = false;
          v.items = bbn.fn.map(
            bbn.fn.filter(users, user => {
              return user.active && (user.id_group === v.id);
            }),
            user => {
              user.id = user.value;
              user.icon = 'nf nf-fa-user';
              return user;
            }
          );
          if (v.is_parent) {
            v.icon = 'nf nf-fa-users';
          }
          return v;
        });
      },
      states(){
        return this.source.states;
      },
      optionsStates(){
        return this.source.options.states;
      },
      optionsRoles(){
        return this.source.options.roles;
      },
      privileges(){
        return this.source.privileges;
      },
      isTokensActive(){
        return !!this.source.tokens?.active
          && !!this.source.tokens?.cfg?.billing?.day
          && !!this.source.tokens?.cfg?.billing?.month
          && !!this.source.tokens?.cfg?.billing?.months
          && !!this.source.tokens?.cfg?.step
          && !!this.source.tokens?.cfg?.amount;
      },
      tokensCfg(){
        return this.isTokensActive ? this.source.tokens.cfg || {} : {};
      }
    },
    methods: {
      isMobile: bbn.fn.isMobile,
      userName(id){
        return bbn.fn.getField(appui.users, 'text', 'value', id);
      },
      userGroup(id){
        return bbn.fn.getField(appui.users, 'id_group', 'value', id);
      },
      userAvatar(id){
        const av = bbn.fn.getField(appui.users, 'avatar', 'value', id);
        return av ? av : bbn.var.defaultAvatar;
      },
      userAvatarImg(id){
        const av = this.userAvatar(id),
              name = this.userName(id);
        return '<span class="appui-avatar"><img src="' + av + '" alt="' + name + '" title="' + name + '"></span>';
      },
      userFull(id){
        const user = bbn.fn.getRow(appui.users, 'value', id);
        return '<span class="appui-avatar"><img src="' + user.avatar + '" alt="' + user.text + '"> ' + user.text + '</span>';
      },
      isYou(id){
        return id === appui.user.id;
      },
      formatDate(d){
        return dayjs(bbn.fn.date(d)).format('DD/MM/YYYY HH:mm');
      },
      getRoleColor(code){
        if (this.optionsRoles) {
          return bbn.fn.getField(this.optionsRoles, 'color', {code: code});
        }
        return '';
      },
      getRoleBgColor(code){
        if (this.optionsRoles) {
          return bbn.fn.getField(this.optionsRoles, 'backgroundColor', {code: code});
        }
        return '';
      },
      getStatusColor(code){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'color', {code: code});
        }
        return '';
      },
      getStatusBgColor(code){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'backgroundColor', {code: code});
        }
        return '';
      },
      getStatusCode(idStatus){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'code', {value: idStatus});
        }
        return '';
      }
    },
    created(){
      appui.register('appui-task', this);
    }
  };
})();
