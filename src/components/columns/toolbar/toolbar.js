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
    data(){
      return {
        columnList: null,
        columnsComp: null
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
            type: !!this.columnsComp && this.columnsComp.isOrderedByTypes ? this.source.id : '',
            private: 0
          }
        });
      },
      collapseAll(){
        if (this.columnList?.component
          && this.columnList.currentData.length
        ) {
          let items = this.columnList.findAll(this.columnList.component);
          bbn.fn.each(items, item => {
            item.$set(item, 'collapsed', true);
          });
        }
      },
      expandAll(){
        if (this.columnList?.component
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
            bbn.fn.link(this.root + 'page/task/' + d.id);
          }
          this.columnList.updateData();
        }
      }
    },
    mounted() {
      this.$on('taskcreated', this.onTaskCreated);
      this.columnList = this.closest('bbn-column-list');
    },
    beforeDestroy(){
      this.$off('taskcreated', this.onTaskCreated);
    }
  }
})();