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
      const mainPage = appui.getRegistered('appui-task');
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
        taskTitle: '',
        mainPage: mainPage,
        users: bbn.fn.order(appui.users, 'text', 'ASC'),
        filters: filters,
        states: bbn.fn.order(bbn.fn.filter(mainPage.source.options.states, s => s.code !== 'deleted'), 'text', 'asc')
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
      renderBudget(row){
        if (!!row.price
          && (!!appui.user.isAdmin
            || !!this.mainPage.privileges.global
            || !!this.mainPage.privileges.account_manager
            || !!this.mainPage.privileges.account_viewer
            || !!this.mainPage.privileges.financial_manager
            || !!this.mainPage.privileges.financial_viewer
            || (!!row.roles.deciders
              && row.roles.deciders.includes(appui.user.id)))
        ) {
          return `<span>${bbn.fn.money(row.price)}</span>`
        }
        return '';
      },
      renderBudgetSubtasks(row){
        if (!!row.children_price
          && (!!appui.user.isAdmin
            || !!this.mainPage.privileges.global
            || !!this.mainPage.privileges.account_manager
            || !!this.mainPage.privileges.account_viewer
            || !!this.mainPage.privileges.financial_manager
            || !!this.mainPage.privileges.financial_viewer
            || (!!row.roles.deciders
              && row.roles.deciders.includes(appui.user.id)))
        ) {
          return `<span>${bbn.fn.money(row.children_price)}</span>`
        }
        return '';
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
          case this.mainPage.source.states.canceled:
            icon = 'fa-remove';
            color = 'slateblue';
            break;
        }
        return `<i class="bbn-m nf nf-${icon}" style="color: ${color}" title="${bbn.fn.getField(this.mainPage.source.options.states, "text", "value", row.state)}"/>`;
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
<bbn-initial bbn-if="source.id_user"
             :user-id="source.id_user"
             :user-name="userName"
             :height="25"
             :width="25"
             :font-size="15"/>
        `,
        props: ['source'],
        computed: {
          userName(){
            return appui.getUserName(this.source.id_user);
          }
        }
      },
      prioavatar: {
        template: `
<bbn-initial bbn-if="source.priority"
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