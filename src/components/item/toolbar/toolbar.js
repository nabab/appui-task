(() => {
  return {
    mixins: [appuiTaskMixin],
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
        currentSearch: ''
      }
    },
    computed: {
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
        return cond;
      }
    },
    methods: {
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
            bbn.fn.link(this.root + 'page/task/' + d.id);
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
      }
    },
    created(){
      this.$set(this, 'mainPage', this.closest('appui-task'));
      this.$set(this, 'columnList', this.closest('bbn-column-list'));
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
      }
    }
  }
})();