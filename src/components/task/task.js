/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 07/06/2017
 * Time: 12:06
 */
(() => {
  let mixins = [{
    data(){
      return {
        task: null,
        mainPage: null
      };
    },
    computed:{
      root(){
        return !!this.mainPage ? this.mainPage.root : '';
      },
      categories(){
        return !!this.mainPage ? this.mainPage.fullCategories : [];
      },
      states(){
        return !!this.mainPage ? this.mainPage.source.states : {};
      },
      optionsStates(){
        return !!this.mainPage ? this.mainPage.source.options.states : [];
      },
      optionsRoles(){
        return !!this.mainPage ? this.mainPage.source.options.roles : [];
      },
      privileges(){
        return!!this.mainPage ? this.mainPage.source.privileges : {};
      }
    },
    methods: {
      userName(id){
        return this.mainPage.userName(id);
      },
      userGroup(id){
        return this.mainPage.userGroup(id);
      },
      userAvatar(id){
        return this.mainPage.userAvatar(id);
      },
      userAvatarImg(id){
        return this.mainPage.userAvatarImg(id);
      },
      userFull(id){
        return this.mainPage.userFull(id);
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
      },
      secToTime(seconds, cut){
        let h = Math.floor(seconds / 3600),
            m,
            s;

        seconds %= 3600;
        m = Math.floor(seconds / 60);
        s = seconds % 60;
        h = (h < 10) ? '0' + h : h;
        m = (m < 10) ? '0' + m : m;
        s = (s < 10) ? '0' + s : s;
        return h + ':' + m + (cut ? '' : ':'+ s);
      },
      fdatetime(d){
        if (!!d) {
          d = dayjs(bbn.fn.date(d));
          if (d.isValid()) {
            return d.format('DD/MM/YYYY HH:mm');
          }
        }
        return '';
      }
    },
    created(){
      this.$set(this, 'mainPage', this.closest('appui-task'));
      this.$set(this, 'task', this.closest('appui-task-task'));
    }
  }];
  bbn.vue.addPrefix('appui-task-task-', (tag, resolve, reject) => {
    return bbn.vue.queueComponent(
      tag,
      appui.plugins['appui-task'] + '/components/task/' + bbn.fn.replaceAll('-', '/', tag).substr('appui-task-task-'.length),
      mixins,
      resolve,
      reject
    );
  });

  return  {
    mixins: mixins,
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        creation: bbn.fn.fdate(this.source.creation_date),
        ref: dayjs().unix(),
        commentTypes: [{
          text: bbn._('Simple text'),
          value: 'bbn-textarea'
        }, {
          text: bbn._('Rich text'),
          value: 'bbn-rte'
        }, {
          text: bbn._('Markdown'),
          value: 'bbn-markdown'
        }, {
          text: bbn._('PHP code'),
          value: 'bbn-code',
          mode: 'php'
        }, {
          text: bbn._('JavaScript code'),
          value: 'bbn-code',
          mode: 'javascript'
        }, {
          text: bbn._('CSS code'),
          value: 'bbn-code',
          mode: 'less'
        }],
        commentType: 'bbn-textarea',
        commentText: '',
        commentTitle: '',
        commentLinks: [],
        showCommentAdder: false,
        userId: appui.app.user.id,
        isAdmin: appui.app.user.isAdmin
      }
    },
    computed: {
      dashboard(){
        return this.mainPage.source.dashboard;
      },
      hasConfig(){
        if (this.source.cfg && bbn.fn.isString(this.source.cfg)) {
          let c = JSON.parse(this.source.cfg);
          return !!c.widgets;
        }
        return false;
      },
      currentConfig: {
        get(){
          let cfg = {}
          if (this.hasConfig) {
            let c = JSON.parse(this.source.cfg);
            cfg = c.widgets;
          }
          else {
            bbn.fn.each(Object.keys(this.dashboard.widgets), code => cfg[code] = 1);
          }
          return cfg;
        },
        set(widgets){
          let cfg = {}
          if (this.source.cfg) {
            if (bbn.fn.isString(this.source.cfg)) {
              cfg = bbn.fn.isString(this.source.cfg) ? JSON.parse(this.source.cfg) : bbn.fn.extend(true, {},this.source.cfg);
            }
          }
          cfg.widgets = widgets;
          this.$set(this.source, 'cfg', JSON.stringify(cfg));
        }
      },
      widgetsAvailable(){
        return bbn.fn.order(bbn.fn.filter(Object.values(this.dashboard.widgets), w => {
          if (w.code === 'budget') {
            return (this.isAdmin || this.isDecider)
              && ((this.isClosed && this.source.price) || !this.isClosed)
              && !this.currentConfig[w.code]
          }
          if ((w.code === 'info') || (w.code === 'actions')) {
            return false;
          }
          return !this.currentConfig[w.code];
        }), 'text');
      },
      mediaFileType(){
        return this.mainPage.source.media_types
          && this.mainPage.source.media_types.file ?
            this.mainPage.source.media_types.file.id :
            ''
      },
      mediaLinkType(){
        return this.mainPage.source.media_types
          && this.mainPage.source.media_types.link ?
            this.mainPage.source.media_types.link.id :
            ''
      },
      stateText() {
        return bbn.fn.getField(this.mainPage.source.options.states, "text", "value", this.source.state);
      },
      isAdded() {
        return this.isManager || this.isWorker || this.isViewer;
      },
      isGlobal(){
        return !!this.privileges.global;
      },
      isMaster() {
        return this.userId === this.source.id_user;
      },
      isViewer() {
        return this.source.roles.viewers.includes(this.userId);
      },
      isManager() {
        if (this.isMaster) {
          return true;
        }
        return this.source.roles.managers.includes(this.userId);
      },
      isWorker() {
        return this.source.roles.workers.includes(this.userId);
      },
      isDecider() {
        return this.source.roles.deciders.includes(this.userId);
      },
      isHolding() {
        return this.source.state === this.mainPage.source.states.holding;
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
      isOpenedOrOngoing() {
        return this.isOngoing || this.isOpened;
      },
      isActive() {
        return !this.isClosed && !this.isHolding;
      },
      isHoldingOrOpened() {
        return this.isHolding || this.isOpened;
      },
      isUnapproved() {
        return this.source.price
          && (!this.source.approved
            || !this.source.lastChangePrice
            || (!this.source.approved.chrono
              || (this.source.approved.chrono < this.source.lastChangePrice.chrono)));
      },
      isApproved() {
        return this.source.price
          && !!this.source.approved
          && !!this.source.lastChangePrice
          && (this.source.approved.chrono !== undefined)
          && (this.source.lastChangePrice.chrono !== undefined)
          && (this.source.approved.chrono > this.source.lastChangePrice.chrono);
      },
      canStart() {
        return this.isOpened && (this.isManager || this.isWorker || this.isGlobal);
      },
      canHold() {
        return (this.isOngoing || this.isOpened) && (this.isManager || this.isWorker || this.isGlobal);
      },
      canClose() {
        return (this.isManager || this.isGlobal) && !this.isClosed;
      },
      canResume() {
        return (this.isHolding && !this.isOpened) && (this.isManager || this.isWorker || this.isGlobal);
      },
      canPing() {
        return (this.isManager || this.isGlobal) && !this.isClosed;
      },
      canReopen() {
        return (this.isManager || this.isGlobal) && this.isClosed;
      },
      canChange() {
        return !this.isClosed && (this.isMaster || this.isGlobal || (!this.source.private && this.isManager));
      },
      canApprove() {
        return this.isDecider && !this.isClosed;
      },
      canChangeDecider() {
        return (this.isDecider || this.isAdmin || this.isGlobal) && (this.source.roles.deciders !== undefined) && !this.isClosed;
      },
      hasComments() {
        return !!this.source.notes.length;
      },
      canUnmakeMe() {
        return this.canRevemoHimselfManager
          || this.canRevemoHimselfWorker
          || this.canRevemoHimselfViewer
          || this.canRevemoHimselfDecider
      },
      numManagers() {
        if (this.source.roles.managers) {
          return this.source.roles.managers.length;
        }
        return 0;
      },
      canBill() {
        return (this.source.state === this.mainPage.source.states.closed)
          && this.isApproved
          && this.isAdmin
          && (appui.plugins['appui-billing'] !== undefined);
      },
      hasInvoice() {
        return !!this.source.invoice;
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
        return !!this.privileges.decider
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
      }
    },
    methods: {
      update(prop, val) {
        this.post(this.root + 'actions/task/update', {
            id_task: this.source.id,
            prop: prop,
            val: val
        }, d => {
          if ( !d.success ) {
            this.alert(bbn._('Error'));
            return false;
          }
          if ( prop === 'state' ) {
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
        });
      },
      preventAll(e) {
        if (e.key !== "Tab") {
          e.preventDefault();
          e.stopImmediatePropagation();
        }
      },
      removeDeadline() {
        this.source.deadline = null;
      },
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
      _makeMe(role){
        if (this.source.id
          && !!role
          && this.mainPage.source.roles[role]
          && !this.source.roles[role].includes(appui.app.user.id)
          && !!this['canBecome' + bbn.fn.correctCase(role)]
        ) {
          return this.post(this.root + 'actions/role/insert', {
            id_task: this.source.id,
            role: this.mainPage.source.roles[role],
            id_user: appui.app.user.id
          }, d => {
            if (d.success) {
              this.source.roles[role].push(appui.app.user.id);
              appui.success();
            }
            else {
              appui.error();
            }
          });
        }
      },
      makeMe(role) {
        if (!!role
          && this.mainPage.source.roles[role]
          && !!this['canBecome' + bbn.fn.correctCase(role)]
        ) {
          this.confirm(bbn._('Are you sure?'), () => {
            let exists = !!bbn.fn.filter([].concat(...Object.values(this.source.roles)), v => v.includes(appui.app.user.id)).length;
            if (exists && this.canUnmakeMe) {
              this._unmakeMe().then(d => {
                if (d.data && d.data.success) {
                  this.$nextTick(() => {
                    this._makeMe(role);
                  })
                }
              })
            }
            else if (!exists) {
              this._makeMe(role);
            }
          });
        }
      },
      _unmakeMe(prop){
        if (this.canUnmakeMe
          && !!this.source.id
          && !!this.mainPage
        ) {
          let prop = false;
          if (this.isManager) {
            prop = 'managers';
          }
          else if (this.isWorker) {
            prop = 'workers';
          }
          else if (this.isViewer) {
            prop = 'viewers';
          }
          else if (this.isDecider) {
            prop = 'deciders';
          }
          if (!!prop && !!this.mainPage.source.roles[prop]) {
            return this.post(this.root + 'actions/role/delete', {
              id_task: this.source.id,
              id_user: appui.app.user.id,
              role: this.mainPage.source.roles[prop]
            }, d => {
              if (d.success) {
                const idx = this.source.roles[prop].indexOf(appui.app.user.id);
                if (idx > -1) {
                  this.source.roles[prop].splice(idx, 1);
                }
              }
            });
          }
        }
      },
      unmakeMe() {
        this.confirm(bbn._('Are you sure you want to remove your role from this task??'), this.unmakeMe);
      },
      addComment(e) {
        let v = {
          id: this.source.id,
          title: this.commentTitle,
          text: this.commentText,
          ref: this.ref,
          /*links: $.grep(this.commentLinks, (l) => {
            return l.error === false;
          })*/
          link: bbn.fn.filter(this.commentLinks, 'error', false)
        };

        if ( !v.title && !v.text ){
          this.alert(bbn._("You have to enter a comment, a link, or a file"))
        }
        else {
          this.post(this.root + 'actions/comment/insert', v, (d) => {
            if (d.success && d.comment) {
              d.comment.creation = new Date().getSQL(1);
              let m = new dayjs(d.comment.creation);
              d.comment.since = m.fromNow();
              this.source.notes.push(d.comment);
              this.clearComment();
            }
            else {
              this.alert();
            }
          });
        }
      },
      clearComment() {
        this.showCommentAdder = false;
        this.commentText = '';
        this.commentTitle = '';
        this.commentLinks = [];
        this.commentType = 'bbn-textarea';
      },
      downloadMedia(id) {
        if ( id ){
          this.postOut(this.root + 'download/media/' + id);
        }
      },
      /** @todo It is not used but maybe think about redoing the comment part */
      changeCommentType() {
         let mode;
         if (this.$refs.comment_type.widget) {
           this.commentType = this.$refs.comment_type.widget.value();
           if ((this.commentType === 'bbn-code') && (mode = this.$refs.comment_type.widget.dataItem()['mode'])) {
             setTimeout(() => {
               this.$refs.comment.widget.setOption('mode', mode);
             }, 500);
           }
         }
      },
      renderSince(d) {
        return dayjs(d).fromNow();
      },
      linkEnter() {
         const link = (this.$refs.link.$refs.element.value.indexOf('http') !== 0 ? 'http://' : '') + this.$refs.link.$refs.element.value,
               idx = this.commentLinks.push({
                  inProgress: true,
                  url: link,
                  image: false,
                  desc: false,
                  title: false,
                  error: false
               }) - 1;
         this.post(this.root + "link_preview", {
           url: link,
           ref: this.ref
         }, (d) => {
           if ( d.data && d.data.realurl ){
             if ( d.data.picture ){
               this.commentLinks[idx].image = d.data.picture;
             }
             if ( d.data.title ){
               this.commentLinks[idx].title = d.data.title;
             }
             if ( d.data.desc ){
               this.commentLinks[idx].desc = d.data.desc;
             }
             this.commentLinks[idx].inProgress = false;
             this.$refs.link.$refs.element.value = '';
           }
           else {
             this.commentLinks[idx].error = true;
           }
         });
      },
      linkRemove(idx) {
        if ( idx !== undefined ){
          this.confirm(bbn._('Are you sure you want to remove this link?'), () => {
            this.commentLinks.splice(idx, 1);
          });
        }
      },
      hasFiles(medias) {
        if ( Array.isArray(medias) && this.mediaFileType ){
          return bbn.fn.search(medias, 'type', this.mediaFileType) > -1;
        }
        return false;
      },
      hasLinks(medias) {
        if ( Array.isArray(medias) && this.mediaLinkType ){
          return bbn.fn.search(medias, 'type', this.mediaLinkType) > -1;
        }
        return false;
      },
      fileIconClass(file) {
        if ( file.extension ){
          const ext = file.extension.substring(1);
          if ( ext === "pdf" ){
            return "file-pdf-o";
          }
          //else if ( $.inArray(ext, ["xls", "xlsx", "csv", "ods"]) > -1) {
          else if ( ["xls", "xlsx", "csv", "ods"].indexOf(ext) > -1 ){
            return "file-excel-o";
          }
          //else if ($.inArray(ext, ["rtf", "doc", "docx", "odt"]) > -1) {
          else if ( ["rtf", "doc", "docx", "odt"].indexOf(ext) > -1 ){
            return "file-word-o";
          }
          //else if ($.inArray(ext, ["svg", "gif", "png", "jpg", "jpeg"]) > -1) {
          else if ( ["svg", "gif", "png", "jpg", "jpeg"].indexOf(ext) > -1 ){
            return "file-picture-o";
          }
          //else if ($.inArray(ext, ["zip", "gz", "rar", "7z", "bz2", "xz"]) > -1) {
          else if ( ["zip", "gz", "rar", "7z", "bz2", "xz"].indexOf(ext) > -1 ){
            return "file-archive-o";
          }
          return "file-o";
        }
        return false;
      },
      addBudget() {
        if (this.isAdmin) {
          this.find('appui-task-task-widget-budget').showPriceForm = true;
        }
      },
      budgetButtons(){
        return !this.source.price && this.isAdmin && !this.isClosed ? [{
          text: bbn._('Add budget'),
          icon: 'nf nf-fa-plus',
          action: this.addBudget
        }] : [];
      },
      trackerButtons(){
        return [{
          text: bbn._('See tracker detail'),
          icon: 'nf nf-mdi-chart_timeline',
          action: this.openTrackerDetail
        }];
      },
      logsButtons(){
        return [{
          text: bbn._('See all logs'),
          icon: 'nf nf-mdi-chart_timeline',
          action: this.openAllLogs
        }];
      },
      tasksButtons(){
        return this.canChange ? [{
          text: bbn._('Add task'),
          icon: 'nf nf-fa-plus',
          action: this.addTask
        }] : [];
      },
      closeButton(){
        return this.canChange ? [{
          text: bbn._('Close'),
          icon: 'nf nf-fa-close',
          action: this.removeWidgetFromTask
        }] : [];
      },
      addTask(){
        if (this.canChange && !!this.source.id) {
          this.getPopup().open({
            title: bbn._('New task'),
            width: 500,
            component: 'appui-task-form-new',
            source: {
              title: '',
              type: '',
              id_parent: this.source.id,
              private: !!this.source.private ? 1 : 0
            },
            opener: this
          });
        }
      },
      openTask(task){
        if (!!task) {
          bbn.fn.link(this.root + 'page/task/' + task);
        }
      },
      addWidgetToTask(code){
        this.post(this.root + 'actions/widget/add', {
          id: this.source.id,
          code: code
        }, d => {
          if (d.success) {
            this.currentConfig = bbn.fn.extend(true, {}, this.currentConfig, {[code]: 1});
            this.getRef('dashboard').showWidget(code);
          }
          else {
            appui.error();
          }
        })
      },
      removeWidgetFromTask(widget){
        this.post(this.root + 'actions/widget/remove', {
          id: this.source.id,
          code: widget.uid
        }, d => {
          if (d.success) {
            this.currentConfig = bbn.fn.extend(true, {}, this.currentConfig, {[widget.uid]: 0});
            widget.close();
          }
          else {
            appui.error();
          }
        })
      },
      openTrackerDetail(){
        this.getPopup({
          title: bbn._('Tracker detail'),
          component: 'appui-task-task-widget-tracker-detail',
          source: this.source,
          width: '90%',
          height: '90%'
        })
      },
      openAllLogs(){
        this.getPopup({
          title: bbn._('Logs'),
          component: 'appui-task-task-widget-logs-detail',
          source: this.source,
          width: '90%',
          height: '90%'
        })
      },
      approve(){
        if (this.canApprove) {
          this.confirm(bbn._('Are you sure you want to approve this price?'), () => {
            this.post(`${this.root}actions/task/approve`, {
              id_task: this.source.id
            }, d => {
              if (d.success && d.data.approved) {
                this.source.approved = d.data.approved;
                this.update('state', this.states.opened);
                appui.success(bbn._('Price approved'));
              }
            });
          });
        }
      },
      startTracker(){
        if (!this.source.tracker
          && this.isOngoing
          && (this.isWorker || this.isManager)
        ){
          let tracker = appui.getRegistered('appui-task-tracker');
          if (bbn.fn.isVue(tracker)) {
            tracker.start(this.source.id);
          }
        }
      },
      stopTracker(){
        let tracker = appui.getRegistered('appui-task-tracker');
        if (bbn.fn.isVue(tracker)) {
          tracker.stop();
        }
      },
      editPrice(){
        let cp = this.find('appui-task-task-widget-budget');
        if (bbn.fn.isVue(cp)) {
          cp.showPriceForm = true;
        }
      },
      removePrice(){
        if (this.isAdmin && !this.isClosed) {
          this.confirm(bbn._('Are you sure you want to remove the price?'), () => {
            if (!bbn.fn.isNull(this.source.price)) {
              this.update('price', null);
              this.source.price = null;
            }
            if (this.source.state !== this.states.opened) {
              this.update('state', this.states.opened);
            }
            this.$set(this.source, 'approved', null);
            this.$set(this.source, 'lastChangePrice', null);
            let cp = this.find('appui-task-task-widget-budget');
            if (bbn.fn.isVue(cp)) {
              cp.showPriceForm = false;
              cp.closest('bbn-widget').updateButtons();
            }
          });
        }
      },
      toggleMobileMenu(){
        let widgetPanel = this.getRef('slider');
        if (widgetPanel) {
          widgetPanel.toggle();
        }
      }
    },
    created(){
      if (this.source.roles && this.mainPage.source.roles) {
        bbn.fn.each(this.mainPage.source.roles, (i, n) => {
          if (!this.source.roles[n]) {
            this.$set(this.source.roles, n, []);
          }
        });
      }
      appui.register('appui-task-' + this.source.id, this);
    },
    beforeDestroy(){
      appui.unregister('appui-task-' + this.source.id);
    },
    watch: {
      'source.title'(val){
         if ( this.titleTimeout ){
           clearTimeout(this.titleTimeout);
         }
         if ( val.length ){
           this.titleTimeout = setTimeout(() => {
             this.update('title', val);
           }, 1000);
         }
      },
      'source.content'(val){
        if (this.descriptionTimeout) {
          clearTimeout(this.descriptionTimeout);
        }
        this.descriptionTimeout = setTimeout(() => {
          this.update('content', val);
        }, 1000);
     },
      'source.type'(val){
         return this.update('type', val);
      },
      'source.priority'(val){
         return this.update('priority', val);
      },
      'source.deadline'(val){
        return this.update('deadline', val);
      },
      showCommentAdder(val) {
        if ( val === false ){
          this.clearComment();
        }
      }
    }
  }
})();
