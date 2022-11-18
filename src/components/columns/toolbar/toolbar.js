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
    computed: {
      isTypes(){
        return !!this.columnsComp && (this.columnsComp.order === 'types');
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
            type: this.isTypes ? this.source.data.id : '',
            private: 0
          }
        });
      },
      collapseAll(){
        if (this.source.data.items && this.source.data.items.length) {
          bbn.fn.each(this.source.data.items, item => item.collapsed = true);
        }
      },
      expandAll(){
        if (this.source.data.items && this.source.data.items.length) {
          bbn.fn.each(this.source.data.items, item => {
            item.collapsed = false
          });
        }
      }
    }
  }
})();