/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:37
 */
(() => {
  Vue.component('appui-task-tab-main', {
    template: '#bbn-tpl-component-appui-task-tab-main',
    props: ['source'],
    data(){
      const vm = this;
      return $.extend({}, vm.source, {
        creation: bbn.fn.fdate(vm.source.creation_date),
        ref: moment().unix(),
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
        rolesMenu: [{
          text: '<i class="fa fa-user-plus"></i>',
          encoded: false,
          items: [{
            text: bbn._('Make me a supervisor'),
            role: 'managers',
            select(e){
              return vm.makeMe(e);
            }
          }, {
            text: bbn._('Make me a worker'),
            role: 'workers',
            select(e){
              return vm.makeMe(e);
            }
          }, {
            text: bbn._('Make me a viewer'),
            role: 'viewers',
            select(e){
              return vm.makeMe(e);
            }
          }]
        }],
        showCommentAdder: false
      });
    },
    computed: {
      stateText(){
        const vm = this;
        return bbn.fn.get_field(vm.appui_tasks.options.states, "value", vm.state, "text");
      }
    },
    methods: {
      update(prop, val){
        const vm = this;
        bbn.fn.post(vm.appui_tasks.root + 'actions/task/update', {
          id_task: vm.id,
          prop: prop,
          val: val
        }, (d) => {
          if ( !d.success ){
            bbn.fn.alert(bbn._('Error'));
            return false;
          }
          if ( prop === 'state' ){
            vm.state = val;
          }
        });
      },
      preventAll(e){
        if ( e.key !== "Tab" ){
          e.preventDefault();
          e.stopImmediatePropagation();
        }
      },
      removeDeadline(){
        this.deadline = null;
      },
      isAdded(){
        return this.isManager() || this.isWorker() || this.isViewer();
      },
      isMaster(){
        if ( this.isManager() ){
          return true;
        }
        return bbn.env.userId === this.id_user;
      },
      isViewer(){
        const viewers = this.roles.viewers;
        return viewers && ($.inArray(bbn.env.userId, viewers) > -1);
      },
      isManager(){
        const managers = this.roles.managers;
        return managers && ($.inArray(bbn.env.userId, managers) > -1);
      },
      isWorker(){
        const workers = this.roles.workers;
        return workers && ($.inArray(bbn.env.userId, workers) > -1);
      },
      isHolding(){
        return this.state === appui.tasks.states.holding;
      },
      isClosed(){
        return this.state === this.appui_tasks.states.closed;
      },
      isOpened(){
        return this.state === appui.tasks.states.opened;
      },
      isOngoing(){
        return this.state === appui.tasks.states.ongoing;
      },
      isOpenedOrOngoing(){
        return this.isOngoing() || this.isOpened();
      },
      isActive(){
        return !this.isClosed() && !this.isHolding();
      },
      isHoldingOrOpened(){
        return this.isHolding() || this.isOpened();
      },
      canHold(){
        return (this.isOngoing() || this.isOpened()) && (this.isManager() || this.isWorker());
      },
      hold(){
        const vm = this;
        bbn.fn.confirm(bbn._('Are you sure you want to put this task on hold?'), () => {
          vm.update('state', vm.appui_tasks.states.holding);
        });
      },
      canClose(){
        return this.isManager() && !this.isClosed();
      },
      close(){
        const vm = this;
        bbn.fn.confirm(vm.appui_tasks.lng.sure_to_close, () => {
          vm.update(vm.id, 'state', vm.appui_tasks.states.closed);
        });
      },
      canResume(){
        return (this.isHolding() || this.isOpened()) && (this.isManager() || this.isWorker());
      },
      resume(){
        const vm = this;
        bbn.fn.confirm(bbn._('Are you sure you want to resume this task?'), () => {
          vm.update('state', vm.appui_tasks.states.ongoing);
        });
      },
      canPing(){
        /** @todo ???? */
        return true;
      },
      ping(){
        /** @todo ??? */
      },
      canOpen(){
        /** @todo ????? */
      },
      reopen(){
        /** @todo ???? */
      },
      canChang(){
        return !this.isClosed() && this.isMaster();
      },

      makeMe(e){
        /** @todo Fix it */
        const vm = this,
            role = e.sender.options.dataSource[0].items[$(e.target).parent().index()].role;
        if ( role && vm.appui_tasks.roles[role] ){
          bbn.fn.post(vm.appui_tasks.root + 'actions/role/insert', {
            id_task: vm.id,
            role: vm.appui_tasks.roles[role],
            id_user: bbn.env.userId
          }, (d) => {
            if ( d.success ){
              /*if ( app.userUID ){
               // @todo Do something to update the roles tab
               app.tree.findByUid(app.userUID);
               }*/
              if ( !vm.roles[role] ){
                vm.roles[role] = [];
              }
              vm.roles[role].push(bbn.env.userId);
            }
          });
        }
      },
      unmakeMe(){
        const vm = this;
        let prop;
        if ( vm.isManager() ){
          prop = "managers";
        }
        if ( vm.isViewer() ){
          prop = "viewers";
        }
        if ( vm.isWorker() ){
          prop = "workers";
        }
        if ( prop ){
          bbn.fn.confirm(vm.appui_tasks.lng.sure_to_unfollow, () => {
            bbn.fn.post(vm.appui_tasks.root + "actions/role/delete", {
              id_task: vm.id,
              id_user: bbn.env.userId,
              role: vm.appui_tasks.roles[prop]
            }, (d) => {
              const idx = $.inArray(bbn.env.userId, vm.roles[prop]);
              if ( idx > -1 ){
                vm.roles[prop].splice(idx, 1);
                /** @todo Fix it */
                tabstrip.tabNav("disable", 1);
              }
            });
          });
        }
      },
      addComment(e){
				const vm = this;
        let v = {
          id: vm.id,
          title: vm.commentTitle,
          text: vm.commentText,
          ref: vm.$refs.ref.$refs.input.value
        };
        if ( !v.title && !v.text ){
          bbn.fn.alert(bbn.tasks.lng.no_comment_text)
        }
        else{
          bbn.fn.post(vm.appui_tasks.root + 'actions/comment/insert', v, (d) => {
            if ( d.success && d.comment ){
              d.comment.creation = new Date().getSQL(1);
              let m = new moment(d.comment.creation);
              d.comment.since = m.fromNow();
              vm.notes.push(d.comment);
              //app.createUpload();
              vm.commentText = '';
              vm.commentTitle = '';
              vm.showCommentAdder = false;
            }
            else{
              bbn.fn.alert();
            }
          });
        }
      },
      hasComments(){
        return !!this.notes.length;
      },
      /** @todo It is not used but maybe think about redoing the comment part */
      changeCommentType(){
        const vm = this;
        let mode;
        vm.commentType = vm.$refs.comment_type.widget.value();
        if ( (vm.commentType === 'bbn-code') && (mode = vm.$refs.comment_type.widget.dataItem()['mode']) ){
          setTimeout(() => {
            vm.$refs.comment.widget.setOption('mode', mode);
          }, 500);
        }
      },
      renderSince(d){
        return moment(d).fromNow();
      },
      linkEnter(){
        const vm = this,
            $input = $(vm.$refs.link.$refs.input),
            $target = $(vm.$refs.links_container);
        let v = vm.$refs.link.$refs.input.value,
            $li;
        if ( v.toLowerCase().indexOf("http") !== 0 ){
          v = "http://" + v;
        }
        $target.append(
          '<tr><td class="k-file k-file-progress">' +
            '<div class="k-progress">' +
              '<table><tr>' +
                '<td class="bbn-task-link-image"><i class="fa fa-link"> </i></td>' +
                '<td class="bbn-task-link-title"><div><strong><a href="' + v + '">' + v + '</a></strong><br></div></td>' +
                '<td class="bbn-task-link-actions">' +
                  '<span class="k-upload-pct"> </span>' +
                  '<button type="button" class="k-button k-button-bare k-upload-action" style="display: inline-block;">' +
                    '<span title="Supprimer" class="k-icon k-i-close k-delete"></span>' +
                  '</button>' +
              '</tr></table>' +
            '</div>' +
          '</td></tr>'
        );
        bbn.fn.analyzeContent($target, true);
        $input.val("");
        $li = $target.find("tr:last").parent().closest("tr");
        bbn.fn.post(vm.appui_tasks.root + "link_preview", {url: v, ref: vm.ref}, (d) => {
          if ( d.res && d.res.realurl ){
            $li.find("td.k-file").removeClass("k-file-progress").addClass("k-file-success");
            if ( d.res.pictures ){
              $.each(d.res.pictures, (i, v) => {
                if ( v.h96 ){
                  $li.find("td.bbn-task-link-image").html('<img src="pm/image/tmp/' + info.ref + '/' + v.h96 + '">');
                  return false;
                }
              });
            }
            let st = '<strong><a href="' + d.res.realurl + '">' +
              ( d.res.title ? d.res.title : d.res.realurl ) +
              '</a></strong><br>';
            if ( d.res.desc ){
              st += d.res.desc;
            }
            bbn.fn.insertContent(st, $li.find("td.bbn-task-link-title div"));
          }
          else{
            $li.find("td.k-file").removeClass("k-file-progress").addClass("k-file-error");
          }
        });
      },
      renderUpload(e){
        /** @todo Fix it */
        const vm = this;
        let size,
            unit;
        if ( e.size > 5000000 ){
          unit = 'Mb';
          size = Math.floor(e.size / 1024 / 1024).toString();
        }
        else if ( e.size > 1024 ){
          unit = 'Kb';
          size = Math.floor(e.size / 1024).toString();
        }
        else{
          unit = 'b';
          size = e.size;
        }
        if ( e.files && e.files[0] ){
          let st = '<div class="k-progress"><table><tbody><tr><td class="bbn-task-link-image">',
              done = false;
          if ( e.files[0].imgs ){
            $.each(e.files[0].imgs, (i, v) => {
              if ( v.h96 ){
                st += '<img src="' + vm.appui_tasks.root + 'image/tmp/' + vm.ref + v.h96 + '">';
                done = 1;
                return false;
              }
            });
          }
          if ( !done ){
            st += '<i class="fa fa-' + vm.fileIconClass(e.files[0]) + '"> </i>';
          }
          st += '</td><td class="bbn-task-link-title">' + e.name + '</td>' +
            '<td class="bbn-task-link-actions">' +
            '<span class="k-upload-pct">' + kendo.toString(size, 'n0') + ' ' + unit + '</span>' +
            '<button class="k-button k-button-bare k-upload-action" type="button">' +
            '<span class="k-icon k-i-close k-delete" title="Supprimer"></span>' +
            '</button>' +
            '</td></tr></tbody></table></div>';
          return st;
        }
      },
      fileIconClass(file){
        if ( file.extension ){
          const ext = file.extension.substr(1);
          if ( ext === "pdf" ){
            return "file-pdf-o";
          }
          else if ( $.inArray(ext, ["xls", "xlsx", "csv", "ods"]) > -1 ){
            return "file-excel-o";
          }
          else if ( $.inArray(ext, ["rtf", "doc", "docx", "odt"]) > -1 ){
            return "file-word-o";
          }
          else if ( $.inArray(ext, ["svg", "gif", "png", "jpg", "jpeg"]) > -1 ){
            return "file-picture-o";
          }
          else if ( $.inArray(ext, ["zip", "gz", "rar", "7z", "bz2", "xz"]) > -1 ){
            return "file-archive-o";
          }
          return "file-o";
        }
        return false;
      }
    },
    watch: {
      title(val){
        const vm = this;
        if ( vm.titleTimeout ){
          clearTimeout(vm.titleTimeout);
        }
        vm.titleTimeout = setTimeout(() => {
          return vm.update('title', val);
        }, 1000);
      },
      type(val){
        return this.update('type', val);
      },
      priority(val){
        return this.update('priority', val);
      },
      deadline(val){
        return this.update('deadline', moment(val).format('YYYY-MM-DD'));
      },
      showCommentAdder(val){
        const vm = this;
        if ( val === true ){
          setTimeout(() => {
            $(vm.$el).bbn('analyzeContent', true);
          }, 50);
        }
      }
    },
    mounted(){
      const vm = this;
      vm.$nextTick(() => {
        $(vm.$el).bbn('analyzeContent', true);
      });
    }
  });
})();