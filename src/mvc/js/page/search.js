(() => {
  return {
    data(){
      let tasks = this.closest('bbn-router').$parent;
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
        tasks: tasks,
        users: bbn.fn.order(appui.app.users, 'text', 'ASC'),
        priority: Array.apply(null, { length: 9 }).map(Function.call, a => { return a + 1 }),
        filters: {
          conditions: [{
            field: 'state',
            operator: 'neq',
            value: tasks.source.states.closed
          }]
        }
      };
    },
    methods: {
      createTask(){
        if ( this.taskTitle.length ){
          this.getPopup().open({
            title: bbn._('New task'),
            width: 500,
            component: 'appui-task-form-new',
            source: {
              title: this.taskTitle,
              type: ''
            },
            opener: this
          });
        }
      },
      openTask(row){
        bbn.fn.link(this.source.root + 'task/' + (typeof row === 'object' ? row.id : row));
      },
      refreshTable(){
        this.$refs.tasksTable.updateData();
      },
      renderPriority(row){
        return '<div class="bbn-overlay bbn-middle">' + row.priority + '</div>';
      },
      priorityClass(row){
        return 'bbn-c bbn-task-pr' + row.priority;
      },
      renderState(row){
        let icon,
            color;
        if ( row.state === this.tasks.source.states.opened ){
          icon = 'clock';
          color = 'orange';
        }
        else if ( row.state === this.tasks.source.states.pending ){
          icon = 'clock';
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
        return '<i class="bbn-lg nf nf-fa-' + icon + '" style="color: ' + color + '" style="" title="' + bbn.fn.getField(this.tasks.source.options.states, "text", "value", row.state) + '"> </i>';
      },
      renderLast(row){
        return dayjs(row.last_action).calendar();
      },
      renderRole(row){
        return bbn.fn.getField(this.tasks.source.options.roles, "text", "value", row.role) || '-';
      },
      renderType(row){
        return bbn.fn.getField(this.tasks.source.options.cats, "text", "value", row.type);
      },
      renderDuration(row){
        let start = dayjs(row.creation_date);
        return row.state === this.tasks.source.states.closed ?
          dayjs(row.last_action).from(start, true) :
          dayjs().from(start, true);
      },
      renderCreationDate(row){
        return dayjs(row.creation_date).calendar();
      },
      renderDeadline(row){
        let t = dayjs(row.deadline),
            diff = t.unix() - dayjs().unix(),
            col = 'green',
            isClosed = row.state === appui.options.tasks.states.closed,
            d = isClosed ? t.calendar() : t.fromNow();

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
        return `<span class="${isClosed ? '' : 'bbn-b '}bbn-${col}">${d}</span>`;
      }
    },
    watch: {
      typeSelected(val){
        this.$nextTick(() => {
          this.refreshTable();
        });
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
        template: `
<bbn-initial v-if="source.id_user"
             :user-id="source.id_user"
             :title="userName"
             :height="25"
             :width="25"
             :font-size="15"
></bbn-initial>
        `,
        props: ['source'],
        computed: {
          userName(){
            return appui.app.getUserName(this.source.id_user);
          }
        }
      }
    }
  }
})();