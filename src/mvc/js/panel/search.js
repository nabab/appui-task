(() => {
  return {
    beforeMount(){
      const vm = this;
      bbn.vue.setComponentRule(vm.appui_tasks.root + 'components/', 'appui');
      bbn.vue.addComponent('task/form/new_task');
      bbn.vue.unsetComponentRule();
    },
    data(){
      const vm = this;
      bbn.fn.log("BEFOER CRASH", vm);
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
        }
      },
      formNew(){
        const vm = this;
        appui.popup({
          title: bbn._('New task'),
          width: 800,
          height: 250,
          component: 'appui-task-form-new_task',
          source: {
            appui_tasks: vm.appui_tasks,
            taskTitle: vm.taskTitle
          },
          close(){
            vm.taskTitle = '';
            vm.readTable();
          }
        });
      },
      readTable(){
        const vm = this;
        bbn.fn.post(vm.root + 'list', {
          selection: vm.typeSelected,
          title: vm.taskTitle
        }, (d) => {
          vm.tableData = d.tasks || [];
        });
      },
      renderUserAvatar(val, text, row){
        /** @todo Render a bbn-component into column */
        return '<bbn-initial :user-id="' + val + '"></bbn-initial>';
      },
      renderPriority(val, text, row){
        /** @todo  */
        return '<div class="bbn-h-100" style="background-color: ' + this.appui_tasks.priority_colors[val] + '">' + val + '</div>';
      },
      renderState(val, text, row){
        const vm = this;
        let icon,
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
      renderLast(val){
        bbn.fn.log('vallll', val);
        return moment(val).calendar(null, {sameElse: 'DD/MM/YYYY'});
      },
      renderRole(val){
        return bbn.fn.get_field(this.appui_tasks.options.roles, "value", val, "text") || '-';
      },
      renderType(val){
        return bbn.fn.get_field(this.appui_tasks.options.cats, "value", val, "text");
      },
      renderDuration(val, text, row){
        let start = moment(row.creation_date),
            end = moment(row.last_action);
        if ( row.state === this.appui_tasks.states.closed ){
          end = moment();
        }
        return end.from(start, true);
      },
      renderCreationDate(val){
        return moment(val).calendar(null, {sameElse: 'DD/MM/YYYY'});
      },
      renderDeadline(val, text, row){
        let t = moment(val),
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
      renderId(val){
        return '<a href="' + this.root + 'tasks/' + val + '/main" title="' + bbn._('See task') + '"><button class="k-button"><i class="fa fa-eye"></i></button></a>';
      }
    },
    watch: {
      typeSelected(val){
        this.readTable();
      },
      taskTitle(val){
        const vm = this;
        if ( vm.titleTimeout ){
          clearTimeout(vm.titleTimeout);
        }
        vm.titleTimeout = setTimeout(() => {
          vm.readTable();
        }, 500);
      }
    },
    mounted(){
      const vm = this;
      vm.$nextTick(() => {
        vm.readTable();
      });
    }
  }
})();
