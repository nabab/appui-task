// Javascript Document
(() => {
  if (!window.appuiTaskMixin) {
    window.appuiTaskMixin = {
      data(){
        return {
          userId: appui.app.user.id,
          isAdmin: appui.app.user.isAdmin
        }
      },
      computed: {
        statusText(){
          return !!this.mainPage ? bbn.fn.getField(this.mainPage.optionsStates, 'text', 'value', this.source.state) : '';
        },
        isGlobal(){
          return !!this.privileges.global;
        },
        isProjectManager(){
          return !!this.privileges.project_manager;
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
        isActive() {
          return !this.isClosed && !this.isHolding;
        },
        isApproved() {
          return this.source.price
            && !!this.source.approved
            && !!this.source.lastChangePrice
            && (this.source.approved.chrono !== undefined)
            && (this.source.lastChangePrice.chrono !== undefined)
            && (this.source.approved.chrono > this.source.lastChangePrice.chrono);
        },
        isUnapproved() {
          return this.source.price
            && (!this.source.approved
              || !this.source.lastChangePrice
              || (!this.source.approved.chrono
                || (this.source.approved.chrono < this.source.lastChangePrice.chrono)));
        },
        canChange() {
          return !this.isClosed && (this.isMaster || this.isGlobal || (!this.source.private && this.isManager));
        },
        canStart() {
          return this.isOpened && (this.isManager || this.isWorker || this.isGlobal);
        },
        canClose() {
          return (this.isManager || this.isGlobal) && !this.isClosed;
        },
        canHold() {
          return (this.isOngoing || this.isOpened) && (this.isManager || this.isWorker || this.isGlobal);
        },
        canResume() {
          return (this.isHolding && !this.isOpened) && (this.isManager || this.isWorker || this.isGlobal);
        },
        canReopen() {
          return (this.isManager || this.isGlobal) && this.isClosed;
        },
        canPing() {
          return (this.isManager || this.isGlobal) && !this.isClosed;
        },
        canApprove() {
          return this.isDecider && !this.isClosed;
        },
        canChangeDecider() {
          return (this.isDecider || this.isAdmin || this.isGlobal || this.isProjectManager)
          && (this.source.roles.deciders !== undefined)
          && !this.isClosed;
        },
        canBecomeManager(){
          return (!!this.privileges.manager || this.isGlobal) && !this.isManager;
        },
        canBecomeWorker(){
          return (!!this.privileges.worker || this.isGlobal)
            && !this.isWorker
            && (!this.isManager || (this.source.roles.managers.length > 1));
        },
        canBecomeViewer(){
          return (!!this.privileges.viewer || this.isGlobal)
            && !this.isViewer
            && (!this.isManager || (this.source.roles.managers.length > 1));
        },
        canBecomeDecider(){
          return (!!this.privileges.decider || this.isGlobal)
            && !this.isDecider
            && (!this.isManager
              || (this.source.roles.managers.length > 1));
        },
        canRevemoHimselfManager(){
          return (!!this.privileges.manager || this.isGlobal)
            && !!this.isManager
            && !this.isMaster
            && (this.source.roles.managers.length > 1);
        },
        canRevemoHimselfWorker(){
          return (!!this.privileges.worker || this.isGlobal)
            && !!this.isWorker;
        },
        canRevemoHimselfViewer(){
          return (!!this.privileges.viewer || this.isGlobal)
            && !!this.isViewer;
        },
        canRevemoHimselfDecider(){
          return (!!this.privileges.decider || this.isGlobal)
            && !!this.isDecider;
        },
        canUnmakeMe() {
          return this.canRevemoHimselfManager
            || this.canRevemoHimselfWorker
            || this.canRevemoHimselfViewer
            || this.canRevemoHimselfDecider
        },
        canBill() {
          return (this.source.state === this.mainPage.source.states.closed)
            && this.isApproved
            && this.isAdmin
            && (appui.plugins['appui-billing'] !== undefined);
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
            }
            if ((prop === 'price') && (d.lastChangePrice !== undefined)) {
              this.source.lastChangePrice = d.lastChangePrice;
            }
            this.$set(this.source, 'last_action', dayjs().format('YYYY-MM-DD HH:mm:ss'));
          });
        },
        askSetSubtasksRoles(){
          if (!!this.source.children && this.source.children.length) {
            this.confirm(bbn._('Do you want to apply the same role configuration of this task in its related subtasks?'), () => {
              this.post(this.mainPage.root + 'actions/role/subtasks', {
                id: this.source.id
              }, d => {
                if (d.success) {
                  appui.success();
                }
                else {
                  appui.error();
                }
              });
            });
          }
        }
      }
    }
  }

  return {
    name: 'appui-task',
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
        let users = bbn.fn.extend(true, [], appui.app.users);
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
      }
    },
    methods: {
      isMobile: bbn.fn.isMobile,
      userName(id){
        return bbn.fn.getField(appui.app.users, 'text', 'value', id);
      },
      userGroup(id){
        return bbn.fn.getField(appui.app.users, 'id_group', 'value', id);
      },
      userAvatar(id){
        const av = bbn.fn.getField(appui.app.users, 'avatar', 'value', id);
        return av ? av : bbn.var.defaultAvatar;
      },
      userAvatarImg(id){
        const av = this.userAvatar(id),
              name = this.userName(id);
        return '<span class="appui-avatar"><img src="' + av + '" alt="' + name + '" title="' + name + '"></span>';
      },
      userFull(id){
        const user = bbn.fn.getRow(appui.app.users, 'value', id);
        return '<span class="appui-avatar"><img src="' + user.avatar + '" alt="' + user.text + '"> ' + user.text + '</span>';
      },
      isYou(id){
        return id === appui.app.user.id;
      },
      formatDate(d){
        return dayjs(d).format('DD/MM/YYYY HH:mm');
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
    }
  };
})();
