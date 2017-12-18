(() => {
  var cp;
  return {
    created(){
      cp = this;
    },
    data(){
      return {
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
        tasks: bbn.vue.closest(this, 'bbn-tabnav').$parent
      };
    },
    methods: {
      createTask(){
        if ( this.taskTitle.length ){
          bbn.vue.closest(this, '.bbn-tab').popup().open({
            title: bbn._('New task'),
            width: 500,
            height: 200,
            component: this.$options.components['appui-tasks-create-form'],
            source: {
              title: this.taskTitle,
              type: ''
            }
          });
        }
      },
      openTask(row){
        bbn.fn.link(this.source.root + 'task/' + (typeof row === 'object' ? row.id : row) + '/main');
      },
      refreshTable(){
        this.$refs.tasksTable.updateData();
      },
      renderPriority(row){
        return '<div class="bbn-100" style="background-color: ' + this.tasks.priority_colors[row.priority] + '">' + row.priority + '</div>';
      },
      renderState(row){
        let icon,
            color;
        if ( row.state === this.tasks.source.states.opened ){
          icon = 'clock-o';
          color = 'orange';
        }
        else if ( row.state === this.tasks.source.states.pending ){
          icon = 'clock-o';
          color = 'red';
        }
        else if ( row.state === this.tasks.source.states.ongoing ){
          icon = 'play';
          color = 'blue';
        }
        else if ( row.state === this.tasks.source.states.closed ){
          icon = 'check';
          color = 'green';
        }
        else if ( row.state === this.tasks.source.states.holding ){
          icon = 'pause';
          color = 'grey';
        }
        return '<i class="bbn-lg fa fa-' + icon + '" style="color: ' + color + '" style="" title="' + bbn.fn.get_field(this.tasks.source.options.states, "value", row.state, "text") + '"> </i>';
      },
      renderLast(row){
        return moment(row.last_action).calendar(null, {sameElse: 'DD/MM/YYYY'});
      },
      renderRole(row){
        return bbn.fn.get_field(this.tasks.source.options.roles, "value", row.role, "text") || '-';
      },
      renderType(row){
        return bbn.fn.get_field(this.tasks.source.options.cats, "value", row.type, "text");
      },
      renderDuration(row){
        let start = moment(row.creation_date),
            end = moment(row.last_action);
        if ( row.state === this.tasks.source.states.closed ){
          end = moment();
          return end.from(start, true);
        }
        if ( !row.duration ){
          return bbn._('Inconnu');
        }
        if ( row.duration < 3600 ){
          return Math.round(row.duration/60) + ' ' + bbn._('months');
        }
        if ( row.duration < (24*3600) ){
          return Math.round(row.duration/3600) + ' ' + bbn._('hours');
        }
        return Math.round(row.duration/(24*3600)) + ' ' + bbn._('days');
      },
      renderCreationDate(row){
        return moment(row.creation_date).calendar(null, {sameElse: 'DD/MM/YYYY'});
      },
      renderDeadline(row){
        let t = moment(row.deadline),
            now = moment(),
            diff = t.unix() - now.unix(),
            col = 'green',
            d = row.state === this.tasks.source.states.closed ? t.calendar(null, {sameElse: 'DD/MM/YYYY'}) : t.fromNow();

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
      }
    },
    watch: {
      typeSelected(val){
        this.refreshTable();
      },
      taskTitle(val){
        if ( this.titleTimeout ){
          clearTimeout(this.titleTimeout);
        }
        this.titleTimeout = setTimeout(() => {
          this.refreshTable();
        }, 500);
      }
    },
    components: {
      'appui-tasks-user-avatar': {
        template: '#appui-tasks-user-avatar',
        props: ['source'],
        methods: {
          userName(id){
            return cp.tasks.userName(id);
          },
        }
      },
      'appui-tasks-create-form': {
        template: '#appui-tasks-create-form',
        props: ['source'],
        data(){
          return {
            root: cp.tasks.source.root,
            categories: cp.tasks.fullCategories
          }
        },
        methods: {
          refreshTable(d){
            cp.taskTitle = '';
            if ( d.success ){
              cp.openTask(d.success);
            }
          }
        }
      }
    }
  }
})();
