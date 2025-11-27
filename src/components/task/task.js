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
        return !!this.mainPage ? this.mainPage.privileges : {};
      },
      isTokensActive(){
        return !!this.mainPage ? this.mainPage.isTokensActive : false;
      },
      tokensCfg(){
        return !!this.mainPage ? this.mainPage.tokensCfg : {};
      },
      hasTokensActive(){
        return !!this.isTokensActive && !!this.source.tokens_category;
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
          d = bbn.date(d);
          if (d.isValid) {
            return d.format('DD/MM/YYYY HH:mm');
          }
        }
        return '';
      }
    },
    created(){
      this.mainPage = appui.getRegistered('appui-task');
      this.task = this.closest('appui-task-task');
    }
  }];
  bbn.cp.addPrefix('appui-task-task-', null, mixins);

  return  {
    mixins: [
      bbn.cp.mixins.basic,
      bbn.cp.mixins.localStorage,
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
        creation: bbn.date(this.source.creation_date).format('DD/MM/YYYY HH:mm'),
        ref: bbn.date().unix(),
        indexes: ['budget', 'roles', 'tracker', 'subtasks', 'logs', 'notes'],
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
      budgetIsVisible(){
        return this.canSeeBudget
          && (!this.isClosed || (!!this.source.price || !!this.source.children_price))
          && !this.source.parent_has_price;
      },
      widgetsAvailable(){
        if (!this.dashboard || !this.dashboard.widgets) {
          return [];
        }

        return bbn.fn.order(
          bbn.fn.filter(
            Object.values(this.dashboard.widgets),
            w => {
              if (w.code === 'budget') {
                return this.budgetIsVisible
                  && !this.currentWidgets[w.code];
              }

              if ((w.code === 'info')
                || (w.code === 'actions')
              ) {
                return false;
              }

              if (w.code === 'logs') {
                return (this.isAdmin || this.isAccountManager || this.isGlobal)
                  && !this.currentWidgets[w.code];
              }

              return !this.currentWidgets[w.code];
            }
          ),
        'text');
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
          && !this.source.roles[role].includes(appui.user.id)
          && !!this['canBecome' + bbn.fn.substr(bbn.fn.correctCase(role), 0, -1)]
        ) {
          return this.post(this.root + 'actions/role/insert', {
            id_task: this.source.id,
            role: this.mainPage.source.roles[role],
            id_user: appui.user.id
          }, d => {
            if (d.success) {
              this.source.roles[role].push(appui.user.id);
              if (d.roles !== undefined) {
                let comps = this.mainPage.findAllByKey(this.source.id, 'appui-task-item');
                if (comps.length) {
                  bbn.fn.each(comps, c => c.source.roles = d.roles);
                }

                let t = appui.getRegistered('appui-task-' + this.source.id, true);
                if (t) {
                  t.source.roles = d.roles;
                }
              }
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
            let exists = !!bbn.fn.filter([].concat(...Object.values(this.source.roles)), v => v.includes(appui.user.id)).length;
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

        if (this.mainPage?.isMobile() && this.toggleMobileMenu) {
          const slider = this.getRef('slider');
          if (slider?.currentVisible) {
            this.toggleMobileMenu();
          }
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
              id_user: appui.user.id,
              role: this.mainPage.source.roles[prop]
            }, d => {
              if (d.success) {
                const idx = this.source.roles[prop].indexOf(appui.user.id);
                if (idx > -1) {
                  this.source.roles[prop].splice(idx, 1);
                }
                if (d.roles !== undefined) {
                  let comps = this.mainPage.findAllByKey(this.source.id, 'appui-task-item');
                  if (comps.length) {
                    bbn.fn.each(comps, c => c.source.roles = d.roles);
                    }
                  let t = appui.getRegistered('appui-task-' + this.source.id, true);
                  if (t) {
                    t.source.roles = d.roles;
                  }
                }
              }
            });
          }
        }

        if (this.mainPage?.isMobile() && this.toggleMobileMenu) {
          const slider = this.getRef('slider');
          if (slider?.currentVisible) {
            this.toggleMobileMenu();
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
              let m = new bbn.date(d.comment.creation);
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
        return bbn.date(d).fromNow();
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
        if ((this.isAdmin || this.isAccountManager || this.isGlobal)
          && !this.isClosed
          && !this.source.price
          && !this.source.parent_has_price
          && !this.source.children_price
        ) {
          this.find('appui-task-task-widget-budget').showPriceForm = true;
          if (this.mainPage.isMobile()) {
            const slider = this.getRef('slider');
            if (slider?.currentVisible) {
              this.task.toggleMobileMenu();
            }
          }
        }
      },
      trackerButtons(){
        return [{
          text: bbn._('See tracker detail'),
          icon: 'nf nf-md-chart_timeline',
          action: this.openTrackerDetail
        }];
      },
      logsButtons(){
        return [{
          text: bbn._('See all logs'),
          icon: 'nf nf-md-chart_timeline',
          action: this.openAllLogs
        }];
      },
      closeButton(d){
        bbn.fn.log('closeButton', d);

        return [{
          text: bbn._('Close'),
          icon: 'nf nf-fa-close',
          action: this.removeWidgetFromTask
        }];
      },
      openTask(task){
        if (!!task) {
          bbn.fn.link(this.root + 'page/task/' + task);
        }
      },
      addWidgetToTask(code){
        bbn.fn.log('addWidget', code)
        if (!this.currentWidgets[code]) {
          this.currentWidgets[code] = 1;
          bbn.fn.log('currentWidgets', this.currentWidgets)
          let dash = this.getRef('dashboard');
          if (dash) {
            dash.showWidget(code);
          }
        }
      },
      removeWidgetFromTask(widget){
        if (!!this.currentWidgets[widget.uid]) {
          this.currentWidgets[widget.uid] = 0;
          widget.close();
        }
      },
      openTrackerDetail(){
        this.getPopup({
          label: bbn._('Tracker detail'),
          component: 'appui-task-task-widget-tracker-detail',
          source: this.source,
          width: '90%',
          height: '90%'
        })
      },
      openAllLogs(){
        this.getPopup({
          label: bbn._('Logs'),
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
              if (d.success && d.approved) {
                if (!!d.toUpdate && d.toUpdate.length) {
                  this.updateItems(d.toUpdate);
                }
                appui.success(bbn._('Price approved'));
              }
              else {
                appui.error();
              }
            });
          });
        }

        if (this.mainPage?.isMobile() && this.toggleMobileMenu) {
          const slider = this.getRef('slider');
          if (slider?.currentVisible) {
            this.toggleMobileMenu();
          }
        }
      },
      startTracker(){
        if (!this.source.tracker
          && this.isOngoing
          && (this.isWorker || this.isManager)
        ){
          let tracker = appui.getRegistered('appui-task-tracker');
          if (bbn.cp.isComponent(tracker)) {
            tracker.start(this.source.id);
          }
        }

        if (this.mainPage?.isMobile() && this.toggleMobileMenu) {
          const slider = this.getRef('slider');
          if (slider?.currentVisible) {
            this.toggleMobileMenu();
          }
        }
      },
      stopTracker(){
        let tracker = appui.getRegistered('appui-task-tracker');
        if (bbn.cp.isComponent(tracker)) {
          tracker.stop();
        }

        if (this.mainPage?.isMobile() && this.toggleMobileMenu) {
          const slider = this.getRef('slider');
          if (slider?.currentVisible) {
            this.toggleMobileMenu();
          }
        }
      },
      editPrice(){
        let cp = this.find('appui-task-task-widget-budget');
        if (bbn.cp.isComponent(cp)) {
          cp.showPriceForm = true;
        }

        if (this.mainPage?.isMobile() && this.toggleMobileMenu) {
          const slider = this.getRef('slider');
          if (slider?.currentVisible) {
            this.toggleMobileMenu();
          }
        }
      },
      removePrice(){
        if ((this.isAdmin || this.isGlobal || this.isAccountManager)
          && !this.isClosed
          && !bbn.fn.isNull(this.source.price)
        ) {
          this.confirm(bbn._('Are you sure you want to remove the price?'), () => {
            this.update('price', null).then(d => {
              if (d.data && d.data.success) {
                /*
                if (this.source.state !== this.states.opened) {
                  this.update('state', this.states.opened);
                }
                this.$set(this.source, 'approved', null);
                this.$set(this.source, 'lastChangePrice', null);
                */
                let cp = this.find('appui-task-task-widget-budget');
                if (bbn.cp.isComponent(cp)) {
                  cp.showPriceForm = false;
                  cp.closest('bbn-widget').updateButtons();
                }
                let cpSubTasks = this.find('appui-task-task-widget-subtasks');
                if (bbn.cp.isComponent(cpSubTasks)) {
                  cpSubTasks.refresh();
                }
              }
            });
          });
        }

        if (this.mainPage?.isMobile() && this.toggleMobileMenu) {
          const slider = this.getRef('slider');
          if (slider?.currentVisible) {
            this.toggleMobileMenu();
          }
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
      if (this.source.roles
        && this.mainPage.source.roles
      ) {
        bbn.fn.each(this.mainPage.source.roles, (i, n) => {
          if (!this.source.roles[n]) {
            this.source.roles[n] = [];
          }
        });
      }

      if (this.hasStorage) {
        const storage = this.getStorage();
        if (!!storage
          && (storage.widgets !== undefined)
        ) {
          bbn.fn.iterate(storage.widgets, (v, k) => this.currentWidgets[k] = v)
        }
      }

      appui.register('appui-task-' + this.source.id, this);
    },
    beforeMount(){
      /* if (this.hasStorage) {
        const storage = this.getStorage();
        if (!!storage
          && (storage.widgets !== undefined)
        ) {
          bbn.fn.iterate(storage.widgets, (v, k) => this.currentWidgets[k] = v)
        }
      } */
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
             this.update('title', val).then(d => {
              if (!!d.data && !!d.data.success) {
                this.closest('bbn-container').setTitle(val);
              }
             });
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
