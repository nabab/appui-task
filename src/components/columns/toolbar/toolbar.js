(() => {
  return {
    props: {
      source: {
        type: Object
      },
      total: {
        type: Number
      }
    },
    methods: {
      addTask(){
        this.getPopup({
          title: bbn._('New task'),
          width: 500,
          component: 'appui-task-form-new',
          source: {
            title: '',
            type: this.columnsComp.isOrderedByTypes ? this.source.data.id : '',
            private: 0
          }
        });
      },
      collapseAll(){
        const col = this.closest('column');
        if (!!col && col.filteredData.length) {
          bbn.fn.each(col.filteredData, item => col.$set(item, 'collapsed', true));
        }
      },
      expandAll(){
        const col = this.closest('column');
        if (!!col && col.filteredData.length) {
          bbn.fn.each(col.filteredData, item => col.$set(item, 'collapsed', false));
        }
      }
    }
  }
})();