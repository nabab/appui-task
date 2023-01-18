(() => {
  return {
    mixins: [bbn.vue.basicComponent, bbn.vue.localStorageComponent],
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
      if (!!mainPage.source.privileges && !!mainPage.source.privileges.group) {
        filterTypes.push({
          text: bbn._('My groups'),
          value: 'group'
        });
      }
      if (!!mainPage.source.privileges && !!mainPage.source.privileges.global) {
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
        currentHierarchy: true
      }
    },
    computed: {
      isColumnsView(){
        return this.currentViewMode === 'columns';
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
      onTaskCreated(d){
        if (d.success && !!d.id) {
          this.currentSearch = '';
          bbn.fn.link(this.mainPage.root + 'page/task/' + d.id);
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