(() => {
  return {
    mixins: [
      appuiTaskMixin,
      bbn.cp.mixins.basic
    ],
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        columnList: null,
        mainPage: null,
        mode: null,
        currentSearch: '',
        orderSource: bbn.fn.order([{
          text: bbn._('Last edit'),
          value: 'last_action',
          asc: 'nf nf-md-sort_calendar_ascending bbn-lg',
          desc: 'nf nf-md-sort_calendar_descending bbn-lg'
        }, {
          text: bbn._('Priority'),
          value: 'priority',
          asc: 'nf nf-md-sort_numeric_ascending bbn-lg',
          desc: 'nf nf-md-sort_numeric_descending bbn-lg'
        }, {
          text: bbn._('Title'),
          value: 'title',
          asc: 'nf nf-md-sort_alphabetical_ascending bbn-lg',
          desc: 'nf nf-md-sort_alphabetical_descending bbn-lg'
        }, {
          text: bbn._('Status'),
          value: 'state',
          asc: 'nf nf-md-sort_alphabetical_ascending bbn-lg',
          desc: 'nf nf-md-sort_alphabetical_descending bbn-lg'
        }], 'text'),
        currentOrder: 'last_action',
        currentSort: 'asc',
        filtersSource: [],
        currentFilters: {
          status: false,
          priority: false,
          role: false
        }
      }
    },
    computed: {
      currentOrderIcon(){
        return !!this.currentOrder ? bbn.fn.getField(this.orderSource, this.currentSort, 'value', this.currentOrder) : '';
      },
      filters(){
        let cond = [{
          field: 'id_parent',
          value: this.source.id
        }];
        if (this.currentSearch.length) {
          cond.push({
            field: 'title',
            operator: 'contains',
            value: this.currentSearch
          });
        }
        if (this.currentFilters.status) {
          cond.push({
            field: 'state',
            value: this.currentFilters.status
          });
        }
        if (this.currentFilters.priority) {
          cond.push({
            field: 'priority',
            value: this.currentFilters.priority
          });
        }
        if (this.currentFilters.role) {
          cond.push({
            field: 'role',
            value: this.currentFilters.role
          });
        }
        return cond;
      },
      order(){
        let cond = [];
        if (this.currentOrder) {
          cond.push({
            field: this.currentOrder,
            dir: this.currentSort
          });
        }
        return cond;
      },
      hasFilters(){
        return !!Object.values(this.currentFilters).filter(f => f !== false).length;
      }
    },
    methods: {
      getField: bbn.fn.getField,
      collapseAll(){
        if (!!this.columnList
          && !!this.columnList.component
          && this.columnList.currentData.length
        ) {
          let items = this.columnList.findAll(this.columnList.component);
          bbn.fn.each(items, item => {
            item.$set(item, 'collapsed', true);
          });
        }
      },
      expandAll(){
        if (!!this.columnList
          && !!this.columnList.component
          && this.columnList.currentData.length
        ) {
          let items = this.columnList.findAll(this.columnList.component);
          bbn.fn.each(items, item => {
            item.$set(item, 'collapsed', false);
          });
        }
      },
      onTaskCreated(d, openAfterCreation){
        if (d.success && !!d.id) {
          if (openAfterCreation) {
            bbn.fn.link(this.mainPage.root + 'page/task/' + d.id);
          }
          if ((d.children !== undefined)
            && bbn.fn.isArray(this.source.children)
          ) {
            this.source.children.splice(0, this.source.children.length, ...d.children);
            this.source.num_children = d.children.length;
            this.source.has_children = !!d.children.length;
          }
          this.columnList.updateData();
        }
      },
      setMode(mode){
        this.mode = mode;
      },
      clearSearch(){
        if (this.currentSearch.length) {
          this.currentSearch = '';
        }
      },
      removeFilters(){
        bbn.fn.iterate(this.currentFilters, (v, f) => {this.currentFilters[f] = false});
      },
      getStatusMenuSource(){
        let res = [];
        res = bbn.fn.map(
          bbn.fn.extend(true, res, bbn.fn.filter(this.mainPage.optionsStates, s => s.code !== 'deleted')),
          s => {
            s.action = () => {
              this.currentFilters.status = s.value;
            };
            return s;
          }
        );
        if (this.currentFilters.status) {
          res.push({
            text: bbn._('Remove filter'),
            value: false,
            action: () => {
              this.currentFilters.status = false;
            }
          });
        }
        return res;
      },
      getPriorityMenuSource(){
        let res = [];
        res = bbn.fn.map(bbn.fn.extend(true, res, this.mainPage.priorities), s => {
          s.action = () => {
            this.currentFilters.priority = s.value;
          };
          return s;
        });
        if (this.currentFilters.priority) {
          res.push({
            text: bbn._('Remove filter'),
            value: false,
            action: () => {
              this.currentFilters.priority = false;
            }
          });
        }
        return res;
      },
      getMyRoleMenuSource(){
        let res = [];
        res = bbn.fn.map(bbn.fn.extend(true, res, this.mainPage.optionsRoles), s => {
          s.action = () => {
            this.currentFilters.role = s.value;
          };
          return s;
        });
        if (this.currentFilters.role) {
          res.push({
            text: bbn._('Remove filter'),
            value: false,
            action: () => {
              this.currentFilters.role = false;
            }
          });
        }
        return res;
      }
    },
    created(){
      this.mainPage = appui.getRegistered('appui-task');
      this.columnList = this.closest('bbn-kanban-element');
    },
    mounted(){
      this.$on('taskcreated', this.onTaskCreated);
    },
    beforeDestroy(){
      this.$off('taskcreated', this.onTaskCreated);
    },
    watch: {
      filters: {
        deep: true,
        handler(newVal){
          if (this.columnList) {
            this.columnList.$set(this.columnList.filters, 'conditions', newVal);
          }
        }
      },
      order: {
        deep: true,
        handler(newVal){
          if (this.columnList) {
            this.columnList.order.splice(0, this.columnList.order.length, ...newVal);
            this.columnList.updateData();
          }
        }
      }
    }
  }
})();