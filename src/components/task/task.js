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
        _task: null,
        _mainPage: null
      };
    },
    computed:{
      task(){
        return this._task;
      },
      mainPage(){
        return this._mainPage;
      },
      root(){
        return this.mainPage.root;
      },
      categories(){
        return this.mainPage.fullCategories;
      },
      states(){
        return this.mainPage.source.states;
      },
      optionsStates(){
        return this.mainPage.source.options.states;
      },
      optionsRoles(){
        return this.mainPage.source.options.roles;
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
      }
    },
    created(){
      this._mainPage = this.closest('appui-task');
      this._task = this.closest('appui-task-task');
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
        //categories: this.mainPage.fullCategories,
        userId: appui.app.user.id,
        isAdmin: appui.app.user.isAdmin,
        /*mediaFileType: this.mainPage.source.media_types
          && this.mainPage.source.media_types.file ?
            this.mainPage.source.media_types.file.id :
            '',
        mediaLinkType: this.mainPage.source.media_types
          && this.mainPage.source.media_types.link ?
            this.mainPage.source.media_types.link.id :
            ''*/
      }
    },
    computed: {
      dashboard(){
        return this.mainPage.source.dashboard;
      },
      currentConfig(){
        return {};
      },
      widgetsAvailable(){
        return bbn.fn.order(bbn.fn.filter(Object.values(this.dashboard.widgets), w => {
          bbn.fn.log('asdasdsa', w.code);
          if (w.code === 'budget') {
            return (this.isAdmin || this.isDecider)
              && ((this.isClosed && this.source.price) || !this.isClosed)
          }
          if ((w.code === 'info') || (w.code === 'actions')) {
            bbn.fn.log('yesssss');
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
        return this.source.price && (!this.source.approved.chrono || (this.source.approved.chrono < this.source.lastChangePrice.chrono));
      },
      isApproved() {
        return !!(this.source.price &&
        (this.source.approved.chrono !== undefined) &&
        (this.source.lastChangePrice.chrono !== undefined) &&
        (this.source.approved.chrono > this.source.lastChangePrice.chrono));
      },
      canStart() {
        return this.isOpened && (this.isManager || this.isWorker);
      },
      canHold() {
        return (this.isOngoing || this.isOpened) && (this.isManager || this.isWorker);
      },
      canClose() {
        return this.isManager && !this.isClosed;
      },
      canResume() {
        return (this.isHolding && !this.isOpened) && (this.isManager || this.isWorker);
      },
      canPing() {
        return this.isManager && !this.isClosed;
      },
      canOpen() {
        /** @todo ??? */
        return false;
      },
      canChange() {
        return !this.isClosed && (this.isMaster || (!this.source.private && this.isManager));
      },
      canApprove() {
        return this.isDecider && !this.isClosed;
      },
      canChangeDecider() {
        return (this.isDecider || this.isAdmin) && (this.source.roles.deciders !== undefined) && !this.isClosed;
      },
      hasComments() {
        return !!this.source.notes.length;
      },
      canMakeMe() {
        /** @todo to create a configuration interface */
        return (this.mainPage.source.usergroup === 1) ||
          (this.mainPage.source.usergroup === 7) ||
          (this.mainPage.source.usergroup === 18);
      },
      canUnmakeMe() {
        if ( this.isManager && (this.numManagers < 2) ){
          return false;
        }
        return true;
      },
      numManagers() {
        if (this.source.roles.managers) {
          return this.source.roles.managers.length;
        }
        return 0;
      },
      canBill() {
        return (this.source.state === this.mainPage.source.states.closed) &&
        this.isApproved &&
          this.isAdmin &&
          (appui.plugins['appui-billing'] !== undefined);
      },
      hasInvoice() {
        return !!this.source.invoice;
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
        this.confirm(bbn._('Are you sure you want to put this task on ongoing?'), () => {
          this.update('state', this.mainPage.source.states.ongoing);
        });
      },
      hold() {
        this.confirm(bbn._('Are you sure you want to put this task on hold?'), () => {
          this.update('state', this.mainPage.source.states.holding);
        });
      },
      close() {
        this.confirm(bbn._("Are you sure you want to close this task?"), () => {
          this.update('state', this.mainPage.source.states.closed);
        });
      },
      resume() {
        this.confirm(bbn._('Are you sure you want to resume this task?'), () => {
          this.update('state', this.mainPage.source.states.ongoing);
        });
      },
      ping(){
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
      },
      reopen() {
        /** @todo ???? */
      },
      makeMe(role) {
        let exists = false;
        const setRole = () => {
            return this.post(this.root + 'actions/role/insert', {
                id_task: this.source.id,
                role: this.mainPage.source.roles[role],
                id_user: appui.app.user.id
            }, (d) => {
                if (d.success) {
                    this.source.roles[role].push(appui.app.user.id);
                    return true;
                }
            });
        };
        if (this.canUnmakeMe && role && this.mainPage.source.roles[role]) {
          bbn.fn.each(this.source.roles, (v, i) => {
            if ( v.indexOf(appui.app.user.id) > -1 ) {
              exists = true;
            }
          });
          if (exists && this.unmakeMe(true)) {
            setTimeout(() => {
                setRole();
            }, 100);
          }
          if (!exists) {
            setRole();
          }
        }
      },
      unmakeMe(force) {
        let prop;
        const removeRole = () => {
          return this.post(this.root + "actions/role/delete", {
            id_task: this.source.id,
            id_user: appui.app.user.id,
            role: this.mainPage.source.roles[prop]
          }, (d) => {
            const idx = this.source.roles[prop].indexOf(appui.app.user.id);
            if ( idx > -1 ) {
              this.source.roles[prop].splice(idx, 1);
                return true;
              }
            });
        };
        if ( this.isManager ) {
          if ( this.numManagers < 2 ) {
            return false;
          }
          prop = "managers";
        }
        if ( this.isViewer ) {
          prop = "viewers";
        }
        if ( this.isWorker ) {
          prop = "workers";
        }
        if (prop) {
          if (force) {
            return removeRole();
          }
          this.confirm(bbn._('Are you sure you want to unfollow this task?'), removeRole);
        }
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
        if ( this.isAdmin ){
          this.find('appui-task-widget-budget').showPriceForm = true;
        }
      },
      budgetButtons(){
        return !this.source.price && this.isAdmin && this.isOpened ? [{
          text: bbn._('Add budget'),
          icon: 'nf nf-fa-plus',
          action: this.addBudget
        }] : [];
      },
      tasksButtons(){
        return this.canChange ? [{
          text: bbn._('Add task'),
          icon: 'nf nf-fa-plus',
          action: this.addTask
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
              id_parent: this.source.id
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
      todoButtons(){
        return this.canChange ? [{
          text: bbn._('Add'),
          icon: 'nf nf-fa-plus',
          action: this.addTodo
        }] : [];
      },
      addTodo(){
        
      },
      addToTask(code){

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
