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
        taskTitle: '',
        mainPage: mainPage,
        users: bbn.fn.order(appui.app.users, 'text', 'ASC'),
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
      expandAll(){
        this.getRef('tasksList').expandAll();
      },
      collapseAll(){
        this.getRef('tasksList').collapseAll();
      },
      openTask(row){
        bbn.fn.link(this.mainPage.root + 'page/task/' + (typeof row === 'object' ? row.id : row));
      },
      refreshList(){
        this.getRef('tasksList').updateData();
      }
    },
    watch: {
      'currentData.selection'(val){
        this.$nextTick(() => {
          this.refreshList();
        });
      },
      'currentData.title'(val){
        if ( this.titleTimeout ){
          clearTimeout(this.titleTimeout);
        }
        this.titleTimeout = setTimeout(() => {
          this.refreshList();
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
    }
  }
})();