(() => {
  return {
    props: {
      source: {
        type: Object,
        required: true
      },
      filter: {
        type: String,
        required: true,
        validation: f => ['user', 'group', 'all'].includes(f)
      },
      search: {
        type: String
      }
    },
    data(){
      const mainPage = this.closest('appui-task');
      return {
        tableData: [],
        taskTitle: '',
        mainPage: mainPage,
        users: bbn.fn.order(appui.app.users, 'text', 'ASC'),
        priority: Array.apply(null, { length: 9 }).map(Function.call, a => { return a + 1 }),
        filters: {
          conditions: [{
            field: 'state',
            operator: 'neq',
            value: mainPage.source.states.closed
          }]
        }
      };
    },
    computed: {
      currentData(){
        return {
          selection: this.filter,
          title: this.search
        }
      }
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
              type: '',
              private: 0
            },
            opener: this
          });
        }
      },
      openTask(row){
        bbn.fn.link(this.mainPage.root + 'page/task/' + (typeof row === 'object' ? row.id : row));
      },
      refreshTable(){
        this.getRef('tasksTable').updateData();
      },
      renderPriority(row){
        return `<div class="bbn-overlay bbn-middle">${row.priority}</div>`;
      },
      priorityClass(row){
        return `bbn-c bbn-task-pr${row.priority}`;
      },
      renderState(row){
        let icon,
            color;
        switch (row.state) {
          case this.mainPage.source.states.opened:
            icon = 'clock';
            color = 'orange';
            break;
          case this.mainPage.source.states.pending:
            icon = 'clock';
            color = 'red';
            break;
          case this.mainPage.source.states.ongoing:
            icon = 'play';
            color = 'blue';
            break;
          case this.mainPage.source.states.closed:
            icon = 'check';
            color = 'green';
            break;
          case this.mainPage.source.states.holding:
            icon = 'pause';
            color = 'grey';
            break;
        }
        return `<i class="bbn-lg nf nf-fa-${icon}" style="color: ${color}" title="${bbn.fn.getField(this.mainPage.source.options.states, "text", "value", row.state)}"/>`;
      },
      renderLast(row){
        return dayjs(row.last_action).calendar();
      },
      renderRole(row){
        return bbn.fn.getField(this.mainPage.source.options.roles, "text", "value", row.role) || '-';
      },
      renderType(row){
        return bbn.fn.getField(this.mainPage.source.options.cats, "text", "value", row.type);
      },
      renderDuration(row){
        let start = dayjs(row.creation_date);
        return row.state === this.mainPage.source.states.closed ?
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
            isClosed = row.state === this.mainPage.source.states.closed,
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
      'currentData.selection'(val){
        this.$nextTick(() => {
          this.refreshTable();
        });
      },
      'currenData.title'(val){
        if ( this.titleTimeout ){
          clearTimeout(this.titleTimeout);
        }
        this.titleTimeout = setTimeout(() => {
          this.refreshTable();
        }, 500);
      }
    },
    components: {
      useravatar: {
        template: `
<bbn-initial v-if="source.id_user"
             :user-id="source.id_user"
             :title="userName"
             :height="25"
             :width="25"
             :font-size="15"/>
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