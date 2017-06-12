(function(){
  return {
    data: function(){
      var vm = this;
      return $.extend(vm.source, {
        typeSelection: [{
          text: vm.source.lng.mine,
          value: 'user'
        }, {
          text: vm.source.lng.my_groups,
          value: 'group'
        }, {
          text: vm.source.lng.all,
          value: 'all'
        }],
        typeSelected: 'user',
        tableData: [],
        taskTitle: ''
      });
    },
    methods: {
      /*createTask: function(){
        var $input = $("input[name=title]", ele),
            v = $input.val();
        if ( v.length ){
          bbn.tasks.formNew(v);
          $input.val("");
          ds.read();
        }
      },*/
      readTable: function(){
        var vm = this;
        bbn.fn.post(vm.root + 'treelist', {
          selection: vm.typeSelected,
          title: vm.taskTitle
        }, function(d){
          vm.tableData = d.tasks || [];
        });
      },
      renderUserAvatar: function(val, text, row){
        return 'a';
      },
      renderState: function(val, text, row){
        var vm = this,
            icon,
            color;
        if ( val === vm.appui_tasks.states.opened ){
          icon = 'clock-o';
          color = 'white';
        }
        else if ( val === vm.appui_tasks.states.pending ){
          icon = 'clock-o';
          color = 'red';
        }
        else if ( val === vm.appui_tasks.states.ongoing ){
          icon = 'play';
          color = 'blue';
        }
        else if ( val === vm.appui_tasks.states.closed ){
          icon = 'check';
          color = 'green';
        }
        else if ( val === vm.appui_tasks.states.holding ){
          icon = 'pause';
          color = 'grey';
        }
        return '<i class="bbn-lg fa fa-' + icon + '" style="color: ' + color + '" style="" title="' + bbn.fn.get_field(vm.appui_tasks.options.states, "value", val, "text") + '"> </i>';
      },
      renderLast: function(val){
        bbn.fn.log('vallll', val);
        /** @todo Fix it */
        return moment(Date(val)).calendar();
      },
      renderRole: function(val){
        return bbn.fn.get_field(this.appui_tasks.options.roles, "value", val, "text") || '-';
      },
      renderType: function(val){
        return bbn.fn.get_field(this.appui_tasks.options.cats, "value", val, "text");
      },
      renderDuration: function(val, text, row){
        var start = moment(row.creation_date),
            end = moment(row.last_action);
        if ( row.state === this.appui_tasks.states.closed ){
          end = moment();
        }
        return end.from(start, true);
      },
      renderCreationDate: function(val){
        /** @todo Fix it */
        return moment(Date(val)).calendar();
      },
      renderDeadline: function(val, text, row){
        var t = moment(val),
            now = moment(),
            diff = t.unix() - now.unix(),
            col = 'green',
            d = row.state === this.appui_tasks.states.closed ? t.calendar() : t.fromNow();

        if ( !t.isValid() ){
          return '-';
        }
        if ( diff < 0 ){
          col = 'brown'
        }
        else if ( diff < (3*24*3600) ){
          col = 'red'
        }
        else if ( diff < (7*24*3600) ){
          col = 'orange'
        }
        else if ( diff < (7*24*3600) ){
          col = 'orange'
        }
        return '<strong style="color: ' + col + '">' + d + '</strong>';
      },
      renderId: function(val){
        return '<a href="' + this.root + 'tasks/' + val + '/main" title="' + this.appui_tasks.lng.see_task + '"><button class="k-button"><i class="fa fa-eye"> </i></button></a>';
      }
    },
    watch: {
      typeSelected: function(){
        this.readTable();
      }
    },
    mounted: function(){
      var vm = this;
      vm.appui_tasks = $.extend({}, bbn.vue.closest(bbn.vue.closest(vm, '.bbn-tab'), '.bbn-tab').$children[0].$data);
      vm.$nextTick(function(){
        vm.readTable();
      });
    }
  }
})();
