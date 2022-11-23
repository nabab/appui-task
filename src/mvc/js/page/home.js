(() => {
  return {
    name: 'appui-task-home',
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
        orderTypes: [{
          text: bbn._('Types'),
          value: 'types'
        }, {
          text: bbn._('Status'),
          value: 'status'
        }],
        currentSearch: '',
      }
    },
    computed: {
      isColumnsView(){
        return this.currentViewMode === 'columns';
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
    },
    watch: {
      currentOrder(){
        if (this.isColumnsView) {

        }
      }
    }
  }
})();