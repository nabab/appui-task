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
        return !!this.mainPage ? this.mainPage.states : {};
      },
      optionsStates(){
        return !!this.mainPage ? this.mainPage.optionsStates : [];
      },
      optionsRoles(){
        return !!this.mainPage ? this.mainPage.optionsRoles : [];
      },
      privileges(){
        return!!this.mainPage ? this.mainPage.privileges : {};
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
        return !!this.mainPage ? this.mainPage.getRoleColor(code) : '';
      },
      getRoleBgColor(code){
        return !!this.mainPage ? this.mainPage.getRoleBgColor(code) : '';
      },
      getStatusColor(code){
        return !!this.mainPage ? this.mainPage.getStatusColor(code) : '';
      },
      getStatusBgColor(code){
        return !!this.mainPage ? this.mainPage.getStatusBgColor(code) : '';
      },
      getStatusCode(idStatus){
        return !!this.mainPage ? this.mainPage.getStatusCode(idStatus) : '';
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
    mixins: [
      bbn.vue.basicComponent,
      bbn.vue.localStorageComponent,
      ...mixins,
      appuiTaskMixin
    ],
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
        currentWidgets: {
          notes: 1
        }
      }
    },
    computed: {
      dashboard(){
        return this.mainPage.source.dashboard;
      },
      hasConfig(){
        if (this.source.cfg && bbn.fn.isString(this.source.cfg)) {
          let c = JSON.parse(this.source.cfg);
          return bbn.fn.isObject(c) && Object.keys(c).length ;
        }
        return false;
      },
      widgetsAvailable(){
        return bbn.fn.order(bbn.fn.filter(Object.values(this.dashboard.widgets), w => {
          if (w.code === 'budget') {
            return (this.isAdmin || this.isDecider || this.isGlobal || this.isProjectManager)
              && ((this.isClosed && this.source.price) || !this.isClosed)
              && !this.currentWidgets[w.code]
          }
          if ((w.code === 'info') || (w.code === 'actions')) {
            return false;
          }
          return !this.currentWidgets[w.code];
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
      hasComments() {
        return !!this.source.notes.length;
      },
      numManagers() {
        if (this.source.roles.managers) {
          return this.source.roles.managers.length;
        }
        return 0;
      },
      hasInvoice() {
        return !!this.source.invoice;
      },
    },
    methods: {
      preventAll(e) {
        if (e.key !== "Tab") {
          e.preventDefault();
          e.stopImmediatePropagation();
        }
      },
      removeDeadline() {
        this.source.deadline = null;
      },
      _makeMe(role){
        if (this.source.id
          && !!role
          && this.mainPage.source.roles[role]
          && !this.source.roles[role].includes(appui.app.user.id)
          && !!this['canBecome' + bbn.fn.substr(bbn.fn.correctCase(role), 0, -1)]
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
          && !!this['canBecome' + bbn.fn.substr(bbn.fn.correctCase(role), 0, -1)]
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
          text: bbn._('Search and add'),
          icon: 'nf nf-fa-search_plus',
          action: this.searchTask
        }, {
          text: bbn._('Add task'),
          icon: 'nf nf-fa-plus',
          action: this.addTask
        }] : [];
      },
      closeButton(){
        return [{
          text: bbn._('Close'),
          icon: 'nf nf-fa-close',
          action: this.removeWidgetFromTask
        }];
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
      searchTask(){
        if (this.canChange && !!this.source.id) {
          this.getPopup({
            title: bbn._('Search and add'),
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
      },
      openTask(task){
        if (!!task) {
          bbn.fn.link(this.root + 'page/task/' + task);
        }
      },
      addWidgetToTask(code){
        if (!this.currentWidgets[code]) {
          this.$set(this.currentWidgets, code, 1);
          this.getRef('dashboard').showWidget(code);
        }
      },
      removeWidgetFromTask(widget){
        if (!!this.currentWidgets[widget.uid]) {
          this.$set(this.currentWidgets, widget.uid, 0);
          widget.close();
        }
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
      },
      onTaskCreated(d, openAfterCreation){
        if (d.success && !!d.id) {
          if (openAfterCreation) {
            this.openTask(d.id);
          }
          if ((d.children !== undefined)
            && bbn.fn.isArray(this.source.children)
          ) {
            this.source.children.splice(0, this.source.children.length, ...d.children);
            this.source.num_children = d.children.length;
            this.source.has_children = !!d.children.length;
            if (!!this.dashboard.widgets && !!this.dashboard.widgets.subtasks) {
              let widget = this.findByKey(this.dashboard.widgets.subtasks.code, 'bbn-widget');
              if (bbn.fn.isVue(widget)) {
                widget.reload();
              }
            }
          }
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
      this.$on('taskcreated', this.onTaskCreated);
    },
    beforeMount(){
      if (this.hasStorage) {
        const storage = this.getStorage();
        if (!!storage && (storage.widgets !== undefined)) {
          this.$set(this, 'currentWidgets', storage.widgets);
        }
      }
    },
    beforeDestroy(){
      appui.unregister('appui-task-' + this.source.id);
      this.$off('taskcreated', this.onTaskCreated);
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
      },
      currentWidgets: {
        deep: true,
        handler(newVal) {
          let storage = this.getStorage();
          if (!storage) {
            storage = {};
          }
          storage.widgets = newVal;
          this.setStorage(storage);
        }
      }
    }
  }
})();
