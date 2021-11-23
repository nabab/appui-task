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
        return dayjs(row.last_action).calendar(null, {sameElse: 'DD/MM/YYYY'});
      },
      renderRole(row){
        return bbn.fn.getField(this.tasks.source.options.roles, "text", "value", row.role) || '-';
      },
      renderType(row){
        return bbn.fn.getField(this.tasks.source.options.cats, "text", "value", row.type);
      },
      renderDuration(row){
        let start = dayjs(row.creation_date),
            end = dayjs(row.last_action);
        if ( row.state === this.tasks.source.states.closed ){
          end = dayjs();
          return end.from(start, true);
        }
        if ( !row.duration ){
          return bbn._('Unknown');
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
        return dayjs(row.creation_date).calendar(null, {sameElse: 'DD/MM/YYYY'});
      },
      renderDeadline(row){
        let t = dayjs(row.deadline),
            now = dayjs(),
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
></bbn-initial>
        `,
        props: ['source'],
        computed: {
          userName(){
            return appui.app.getUserName(this.source.id_user);
          }
        }
      },
      'appui-tasks-create-form': {
        template: `
<bbn-form :action="cp.tasks.source.root + 'actions/task/insert'"
          :source="source"
          @success="refreshTable"
>
  <div class="bbn-padded bbn-grid-fields">
    <div>` + bbn._('Title') + `</div>
    <bbn-input maxlength="255"
               required="required"
               v-model="source.title"
               class="bbn-w-100"
    ></bbn-input>
    <div>` + bbn._('Type') + `</div>
    <bbn-dropdown :source="cp.tasks.fullCategories"
                  v-model="source.type"
                  group="group"
                  source-value="id"
                  required="required"
                  placeholder="`+ bbn._('Select a type...') +`"
                  class="bbn-w-100"
    ></bbn-dropdown>
  </div>
</bbn-form>
        `,
        props: ['source'],
        data(){
          return {
            cp: this.closest('bbn-container').getComponent()
          }
        },
        methods: {
          refreshTable(d){
            this.cp.taskTitle = '';
            if ( d.success ){
              this.cp.openTask(d.success);
            }
          }
        }
      }
    }
  }
})();