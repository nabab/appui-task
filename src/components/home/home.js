(() => {
  return {
    mixins: [bbn.cp.mixins.basic, bbn.cp.mixins.localStorage],
    props: {
      source: {
        type: Object
      },
      storage: {
        type: Boolean,
        default: true
      }
    },
    data(){
      const mainPage = this.closest('appui-task'),
            filterTypes = [{
              text: bbn._('Mine'),
              value: 'user'
            }];
      if (!!mainPage.source.privileges
        && (!!mainPage.source.privileges.group
          || !!mainPage.source.privileges.global
          || !!mainPage.source.privileges.account_manager
          || !!mainPage.source.privileges.account_viewer
          || !!mainPage.source.privileges.project_manager
          || !!mainPage.source.privileges.financial_manager
          || !!mainPage.source.privileges.financial_viewer
          || !!mainPage.source.privileges.project_supervisor)
      ) {
        filterTypes.push({
          text: bbn._('My group'),
          value: 'group'
        });
      }
      if (!!mainPage.source.privileges
        && (!!mainPage.source.privileges.group
          || !!mainPage.source.privileges.global
          || !!mainPage.source.privileges.account_manager
          || !!mainPage.source.privileges.account_viewer
          || !!mainPage.source.privileges.project_manager
          || !!mainPage.source.privileges.financial_manager
          || !!mainPage.source.privileges.financial_viewer
          || !!mainPage.source.privileges.project_supervisor)
      ) {
        filterTypes.push({
          text: bbn._('All'),
          value: 'all'
        });
      }
      return {
        mainPage: mainPage,
        viewModes: [{
          text: bbn._('Columns'),
          value: 'columns'
        }, {
          text: bbn._('Table'),
          value: 'table'
        }, {
          text: bbn._('List'),
          value: 'list'
        }],
        currentViewMode: 'columns',
        filterTypes: filterTypes,
        currentFilter: 'user',
        currentOrder: 'types',
        currentRole: 'all',
        orderTypes: [{
          text: bbn._('Types'),
          value: 'types'
        }, {
          text: bbn._('Status'),
          value: 'status'
        }, {
          text: bbn._('Priority'),
          value: 'priority'
        }],
        currentSearch: '',
        currentHierarchy: true,
        currentComponent: null
      }
    },
    computed: {
      isColumnsView(){
        return this.currentViewMode === 'columns';
      },
      isListView(){
        return this.currentViewMode === 'list';
      },
      isTableView(){
        return this.currentViewMode === 'table';
      },
      roles(){
        let ret = [];
        if (this.mainPage) {
          ret.push({
            text: bbn._('All'),
            value: 'all'
          });
          bbn.fn.each(this.mainPage.optionsRoles, r => ret.push(bbn.fn.extend(true, {}, r)));
        }
        return ret;
      }
    },
    methods: {
      clearSearch(){
        if (this.currentSearch.length) {
          this.currentSearch = '';
        }
      },
      addTask(){
        this.getPopup({
          title: bbn._('New task'),
          width: 500,
          component: 'appui-task-form-new',
          source: {
            title: this.currentSearch,
            type: '',
            private: 0
          }
        });
      },
      onTaskCreated(d, openAfterCreation){
        if (d.success && !!d.id) {
          this.currentSearch = '';
          if (openAfterCreation) {
            bbn.fn.link(this.mainPage.root + 'page/task/' + d.id);
          }
        }
      },
      setCfgToStorage(){
        this.setStorage({
          currentViewMode: this.currentViewMode,
          currentFilter: this.currentFilter,
          currentOrder: this.currentOrder,
          currentRole: this.currentRole,
          currentHierarchy: this.currentHierarchy
        });
      }
    },
    beforeMount(){
      if (this.hasStorage) {
        const storage = this.getStorage();
        bbn.fn.iterate(storage, (v, i) => {
          this[i] = v;
        })
      }
    },
    mounted(){
      this.$on('taskcreated', this.onTaskCreated);
    },
    beforeDestroy(){
      this.$off('taskcreated', this.onTaskCreated);
    },
    watch: {
      currentViewMode(newVal){
        this.setCfgToStorage();
      },
      currentFilter(newVal){
        this.setCfgToStorage();
      },
      currentOrder(newVal){
        this.setCfgToStorage();
      },
      currentRole(newVal){
        this.setCfgToStorage();
      },
      currentHierarchy(newVal){
        this.setCfgToStorage();
      }
    }
  }
})();