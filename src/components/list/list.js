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
      },
      hierarchy: {
        type: Boolean
      }
    },
    data(){
      const mainPage = this.closest('appui-task');
      let filters = {
        conditions: [{
          field: 'state',
          operator: 'neq',
          value: mainPage.source.states.closed
        }]
      };
      if (this.hierarchy) {
        filters.conditions.push({
          field: 'bbn_tasks.id_parent',
          operator: 'isnull'
        });
      }
      return {
        tableData: [],
        taskTitle: '',
        mainPage: mainPage,
        users: bbn.fn.order(appui.app.users, 'text', 'ASC'),
        priority: Array.apply(null, { length: 9 }).map(Function.call, a => { return a + 1 }),
        filters: filters
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
        return `bbn-c appui-task-pr${row.priority}`;
      },
      renderState(row){
        let icon,
            color;
        switch (row.state) {
          case this.mainPage.source.states.opened:
            icon = 'md-book_open_page_variant';
            color = 'orange';
            break;
          case this.mainPage.source.states.pending:
            icon = 'fa-clock';
            color = 'red';
            break;
          case this.mainPage.source.states.ongoing:
            icon = 'fa-play';
            color = 'blue';
            break;
          case this.mainPage.source.states.closed:
            icon = 'fa-check';
            color = 'green';
            break;
          case this.mainPage.source.states.holding:
            icon = 'fa-pause';
            color = 'grey';
            break;
          case this.mainPage.source.states.unapproved:
            icon = 'md-police_badge';
            color = 'red';
            break;
        }
        return `<i class="bbn-m nf nf-${icon}" style="color: ${color}" title="${bbn.fn.getField(this.mainPage.source.options.states, "text", "value", row.state)}"/>`;
      },
      renderPriority(row) {
        return '<span class="bbn-iblock bbn-ratio bbn-xxspadding bbn-m bbn-badge bbn-c appui-task-pr' + row.priority + '">' + row.priority + '</span>';
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
      'currentData.title'(val){
        if ( this.titleTimeout ){
          clearTimeout(this.titleTimeout);
        }
        this.titleTimeout = setTimeout(() => {
          this.refreshTable();
        }, 500);
      },
      hierarchy(newVal){
        let idx = bbn.fn.search(this.filters.conditions, 'field', 'bbn_tasks.id_parent');
        if (newVal) {
          if (idx === -1) {
            this.filters.conditions.push({
              field: 'bbn_tasks.id_parent',
              operator: 'isnull'
            });
          }
          else {
            this.filters.conditions.splice(idx, 1, {
              field: 'bbn_tasks.id_parent',
              operator: 'isnull'
            });
          }
        }
        else if (idx > -1){
          this.filters.conditions.splice(idx, 1);
        }
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
      },
      prioavatar: {
        template: `
<bbn-initial v-if="source.priority"
             :letters="source.priority.toString()"
             :class="'bbn-white appui-task-pr' + source.priority"
             :title="_('Priority') + ' ' + source.priority"
             :height="25"
             :width="25"
             :font-size="15"/>
        `,
        props: ['source']
      }
    }
  }
})();