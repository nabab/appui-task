/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:37
 */
(function(){
  Vue.component('appui-task-tab-main', {
    template: '#bbn-tpl-component-appui-task-tab-main',
    props: ['source'],
    data: function(){
      return $.extend({}, this.source, {
        creation: bbn.fn.fdate(this.source.creation_date),
        ref: moment().unix(),
        commentTypes: [{
          text: this.source.appui_tasks.lng.simple_text,
          value: 'bbn-textarea'
        }, {
          text: this.source.appui_tasks.lng.rich_text,
          value: 'bbn-rte'
        }, {
          text: this.source.appui_tasks.lng.markdown,
          value: 'bbn-markdown'
        }, {
          text: this.source.appui_tasks.lng.php_code,
          value: 'bbn-code',
          mode: 'php'
        }, {
          text: this.source.appui_tasks.lng.javascript_code,
          value: 'bbn-code',
          mode: 'javascript'
        }, {
          text: this.source.appui_tasks.lng.css_code,
          value: 'bbn-code',
          mode: 'less'
        }],
        commentType: 'bbn-textarea',
        showCommentAdder: false
      });
    },
    methods: {
      update: function(prop, val){
        var vm = this;
        if ( prop === 'deadline' ){
          val = moment(val).format("yyyy-MM-dd");
        }
        bbn.fn.post(vm.root + 'actions/task/update', {
          id_task: vm.id,
          prop: prop,
          val: val
        }, function(d){
          if ( !d.success ){
            bbn.fn.alert();
            return false;
          }
          return true;
        })
      },
      preventAll: function(e){
        if ( e.key !== "Tab" ){
          e.preventDefault();
          e.stopImmediatePropagation();
        }
      },
      removeDeadline: function(){
        this.deadline = null;
      },
      isAdded: function(){
        return this.isManager() || this.isWorker() || this.isViewer();
      },
      isMaster: function(){
        if ( this.isManager() ){
          return true;
        }
        return bbn.env.userId === this.id_user;
      },
      isViewer: function(){
        var viewers = this.roles.viewers;
        return viewers && ($.inArray(bbn.env.userId, viewers) > -1);
      },
      isManager: function(){
        var managers = this.roles.managers;
        return managers && ($.inArray(bbn.env.userId, managers) > -1);
      },
      isWorker: function(){
        var workers = this.roles.workers;
        return workers && ($.inArray(bbn.env.userId, workers) > -1);
      },
      isHolding: function(){
        return this.state === appui.tasks.states.holding;
      },
      isClosed: function(){
        return this.state === this.appui_tasks.states.closed;
      },
      isOpened: function(){
        return this.state === appui.tasks.states.opened;
      },
      isOngoing: function(){
        return this.state === appui.tasks.states.ongoing;
      },
      isOpenedOrOngoing: function(){
        return this.isOngoing() || this.isOpened();
      },
      isActive: function(){
        return !this.isClosed() && !this.isHolding();
      },
      isHoldingOrOpened: function(){
        return this.isHolding() || this.isOpened();
      },
      canHold: function(){
        return (this.isOngoing() || this.isOpened()) && (this.isManager() || this.isWorker());
      },
      hold: function(){
        var vm = this;
        bbn.fn.confirm(vm.appui_tasks.lng.sure_to_hold, function(){
          if ( vm.update(vm.id, 'state', vm.appui_tasks.states.holding) ){
            vm.state = vm.appui_tasks.states.holding;
          }
        });
      },
      canClose: function(){
        return this.isManager() && !this.isClosed();
      },
      close: function(){
        var vm = this;
        bbn.fn.confirm(vm.appui_tasks.lng.sure_to_close, function(){
          if ( vm.update(vm.id, 'state', vm.appui_tasks.states.closed) ){
            vm.state = vm.appui_tasks.states.closed;
          }
        });
      },
      canResume: function(){
        return (this.isHolding() || this.isOpened()) && (this.isManager() || this.isWorker());
      },
      resume: function(){
        var vm = this;
        bbn.fn.confirm(vm.appui_tasks.lng.sure_to_resume, function(){
          if ( vm.update(vm.id, 'state', vm.appui_tasks.states.ongoing) ){
            vm.state = vm.appui_tasks.states.ongoing;
          }
        });
      },
      canPing: function(){
        /** @todo ???? */
        return true;
      },
      ping: function(){
        /** @todo ??? */
      },
      canOpen: function(){
        /** @todo ????? */
      },
      reopen: function(){
        /** @todo ???? */
      },
      canChange: function(){
        return !this.isClosed() && this.isMaster();
      },
      stateText: function(){
        return bbn.fn.get_field(this.appui_tasks.options.states, "value", this.state, "text");
      },
      makeMeDisplay: function(){
        return this.isAdded() ? 'none' : 'inline-block';
      },
      makeMe: function(e){
        /** @todo Fix it */
        var vm = this,
            role = $(e.item).attr("data-task-role");
        if ( role && vm.appui_tasks.roles[role] ){
          bbn.fn.post(vm.appui_tasks.root + 'actions/role/insert', {
            id_task: vm.id,
            role: vm.appui_tasks.roles[role],
            id_user: bbn.env.userId
          }, function(d){
            if ( app.userUID ){
              // @todo Do something to update the roles tab
              app.tree.findByUid(app.userUID);
            }
            if ( !vm.roles[role] ){
              vm.roles[role] = [];
            }
            vm.roles[role].push(bbn.env.userId);
            tabstrip.tabNav("enable", 1);
          });
        }
      },
      unmakeMe: function(){
        var prop,
            vm = this;
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
          bbn.fn.confirm(vm.appui_tasks.lng.sure_to_unfollow, function(){
            bbn.fn.post(vm.appui_tasks.root + "actions/role/delete", {
              id_task: vm.id,
              id_user: bbn.env.userId,
              role: vm.appui_tasks.roles[prop]
            }, function(d){
              var idx = $.inArray(bbn.env.userId, vm.roles[prop]);
              if ( idx > -1 ){
                vm.roles[prop].splice(idx, 1);
                /** @todo Fix it */
                tabstrip.tabNav("disable", 1);
              }
            });
          });
        }
      },
      addComment: function(e){
				var vm = this,
            v = {
              id: vm.id,
              title: vm.$refs.comment_title.$refs.input.value || '',
              text: vm.$refs.comment.value || '',
              ref: vm.$refs.ref.$refs.input.value
            };
        if ( !v.title && !v.text ){
          bbn.fn.alert(bbn.tasks.lng.no_comment_text)
        }
        else{
          bbn.fn.post(vm.appui_tasks.root + 'actions/comment/insert', v, function(d){
            if ( d.success && d.comment ){
              d.comment.creation = new Date().getSQL(1);
              var m = new moment(d.comment.creation);
              d.comment.since = m.fromNow();
              vm.notes.push(d.comment);
              //app.createUpload();
              $(vm.$refs.comment).val('');
              $(vm.$refs.comment_title).val('');
              vm.showCommentAdder = false;
            }
            else{
              bbn.fn.alert();
            }
          });
        }
      },
      hasComments: function(){
        return !!this.notes.length;
      },
      /** @todo It is not used but maybe think about redoing the comment part */
      changeCommentType: function(){
        var vm = this,
            mode;
        vm.commentType = vm.$refs.comment_type.widget.value();
        if ( (vm.commentType === 'bbn-code') && (mode = vm.$refs.comment_type.widget.dataItem()['mode']) ){
          setTimeout(function(){
            vm.$refs.comment.widget.setOption('mode', mode);
          }, 500);
        }
      },
      renderSince: function(d){
        return moment(d).fromNow();
      },
      linkEnter: function(){
        var vm = this,
            $input = $(vm.$refs.link.$refs.input),
            v = vm.$refs.link.$refs.input.value,
            $target = $(vm.$refs.links_container),
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
        bbn.fn.post(vm.appui_tasks.root + "link_preview", {url: v, ref: vm.ref}, function(d){
          if ( d.res && d.res.realurl ){
            $li.find("td.k-file").removeClass("k-file-progress").addClass("k-file-success");
            if ( d.res.pictures ){
              $.each(d.res.pictures, function(i, v){
                if ( v.h96 ){
                  $li.find("td.bbn-task-link-image").html('<img src="pm/image/tmp/' + info.ref + '/' + v.h96 + '">');
                  return false;
                }
              });
            }
            var st = '<strong><a href="' + d.res.realurl + '">' +
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
      }
    },
    watch: {
      title: function(val){
        return this.update('title', val);
      },
      type: function(val){
        return this.update('type', val);
      },
      priority: function(val){
        return this.update('priority', val);
      },
      deadline: function(val){
        return this.update('deadline', val);
      },
      showCommentAdder: function(val){
        var vm = this;
        if ( val === true ){
          setTimeout(function(){
            $(vm.$el).bbn('analyzeContent', true);
          }, 50);
        }
      }
    },
    mounted: function(){
      var vm = this;
      $(vm.$el).bbn('analyzeContent', true);
    }
  });
})();