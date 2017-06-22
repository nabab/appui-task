(() => {
  return {
    beforeMount(){
      bbn.vue.setComponentRule(this.source.appui_tasks.root + 'components/', 'appui');
      bbn.vue.addComponent('task/form/new_task');
      bbn.vue.unsetComponentRule();
    },
    data(){
      const vm = this;
      return $.extend({}, vm.source, {
        typeSelection: [{
          text: bbn._('Mine'),
          value: 'user'
        }, {
          text: bbn._('My groups'),
          value: 'group'
        }, {
          text: bbn._('All'),
          value: 'all'
        }],
        typeSelected: 'user',
        tableData: [],
        taskTitle: '',
        appui_tasks: $.extend({}, bbn.vue.closest(bbn.vue.closest(vm, '.bbn-tabnav'), '.bbn-tab').$children[0].$data)
      });
    },
    methods: {
      createTask(){
        const vm = this;
        if ( vm.taskTitle.length ){
          vm.formNew();
          $input.val("");
        }
      },
      // Form creating a new task
      formNew: function(){
        var vm = this;
        appui.popup({
          title: bbn._('New task'),
          width: 800,
          height: 250,
          component: 'appui-task-form-new_task',
          source: vm.source,
          close: function(){
            vm.readTable();
          }
        });
      },
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
        /** @todo Render a bbn-component into a column */
        return '<bbn-initial :user-id="' + val + '"></bbn-initial>';
      },
      renderPriority: function(val, text, row){
        /** @todo  */
        return '<div class="bbn-h-100" style="background-color: ' + this.appui_tasks.priority_colors[val] + '">' + val + '</div>';
      },
      renderState: function(val, text, row){
        var vm = this,
            icon,
            color;
        if ( val === vm.appui_tasks.states.opened ){
          icon = 'clock-o';
          color = 'orange';
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
        return moment(val).calendar(null, {sameElse: 'DD/MM/YYYY'});
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
        return moment(val).calendar(null, {sameElse: 'DD/MM/YYYY'});
      },
      renderDeadline: function(val, text, row){
        var t = moment(val),
            now = moment(),
            diff = t.unix() - now.unix(),
            col = 'green',
            d = row.state === this.appui_tasks.states.closed ? t.calendar(null, {sameElse: 'DD/MM/YYYY'}) : t.fromNow();

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
      typeSelected: function(val){
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
