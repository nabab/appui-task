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
        if (!!col && col.currentData.length) {
          bbn.fn.each(col.currentData, item => {
            col.$set(item, 'collapsed', true)
          });
        }
      },
      expandAll(){
        const col = this.closest('column');
        if (!!col && col.currentData.length) {
          bbn.fn.each(col.currentData, item => {
            col.$set(item, 'collapsed', false)
          });
        }
      },
      onTaskCreated(d, openAfterCreation){
        if (d.success && !!d.id) {
          if (openAfterCreation) {
            bbn.fn.link(this.root + 'page/task/' + d.id);
          }
          this.closest('column').updateData();
        }
      }
    },
    mounted(){
      this.$on('taskcreated', this.onTaskCreated);
    },
    beforeDestroy(){
      this.$off('taskcreated', this.onTaskCreated);
    }
  }
})();