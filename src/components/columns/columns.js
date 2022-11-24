(() => {
  let mixins = [{
    data(){
      return {
        mainPage: {},
        homePage: {},
        columnsComp: {}
      };
    },
    computed:{
      root(){
        return !!this.mainPage ? this.mainPage.root : '';
      }
    },
    created(){
      this.$set(this, 'mainPage', this.closest('appui-task'));
      this.$set(this, 'homePage', this.closest('appui-task-home'));
      this.$set(this, 'columnsComp', this.closest('appui-task-columns'));
    }
  }];
  bbn.vue.addPrefix('appui-task-columns-', (tag, resolve, reject) => {
    return bbn.vue.queueComponent(
      tag,
      appui.plugins['appui-task'] + '/components/columns/' + bbn.fn.replaceAll('-', '/', tag).substr('appui-task-columns-'.length),
      mixins,
      resolve,
      reject
    );
  });

  return {
    mixins: mixins,
    props: {
      source: {
        type: Object,
        required: true
      },
      order: {
        type: String,
        required: true,
        validation: o => ['types', 'status', 'priority'].includes(o)
      },
      filter: {
        type: String,
        required: true,
        validation: f => ['user', 'group', 'all'].includes(f)
      },
      search: {
        type: String
      }
    },
    data(){
      return {
        ready: false,
        mainPage: this.closest('appui-task'),
        currentData: {
          selection: this.filter,
          title: this.search
        }
      }
    },
    computed: {
      isOrderedByTypes(){
        return this.order === 'types';
      },
      isOrderedByPriority(){
        return this.order === 'priority';
      },
      isOrderedByStatus(){
        return this.order === 'status';
      },
      sections(){
        let sec = [],
            vals;
        switch (this.order) {
          case 'types':
            vals = this.mainPage.source.options.cats;
            break;
          case 'status':
            vals = this.mainPage.source.options.states;
            break;
          case 'priority':
            vals = this.mainPage.priorities;
            break;
        }
        if (bbn.fn.isArray(vals)) {
          bbn.fn.each(vals, v => {
            sec.push({
              id: v.value,
              title: v.text,
              items: this.mainPage.root + 'data/list',
              backgroundColor: v.backgroundColor || '',
              fontColor: v.color || v.fontColor || '',
              canAdd: true
            });
          })
        }
        return sec;
      }
    },
    methods: {
      expandAll(){
        this.getRef('sections').expandAll();
      },
      collapseAll(){
        this.getRef('sections').collapseAll();
      },
      getFilters(src, data, idx){
        let conditions = [];
        switch (this.order) {
          case 'types':
            conditions.push({
              field: 'bbn_tasks.type',
              operator: '=',
              value: src.id
            }, {
              field: 'bbn_tasks.state',
              operator: 'neq',
              value: this.mainPage.source.states.closed
            });
            break;
          case 'status':
            conditions.push({
              field: 'bbn_tasks.state',
              operator: '=',
              value: src.id
            });
            break;
          case 'priority':
            conditions.push({
              field: 'bbn_tasks.priority',
              operator: '=',
              value: src.id
            }, {
              field: 'bbn_tasks.state',
              operator: 'neq',
              value: this.mainPage.source.states.closed
            });
            break;
        }
        return {
          conditions: conditions
        };
      }
    },
    mounted(){
      this.$nextTick(() => {
        this.ready = true;
      });
    },
    watch: {
      order(){
        this.$nextTick(() => {
          this.getRef('sections').updateData();
        });
      },
      filter(newVal){
        this.getRef('sections').setAllCheckCollapse();
        this.currentData.selection = newVal;
      },
      search(newVal){
        if (!!this.searchTimeout) {
          clearTimeout(this.searchTimeout);
        }
        this.searchTimeout = setTimeout(() => {
          this.getRef('sections').setAllCheckCollapse();
          this.currentData.title = newVal;
        }, 500);
      }
    }
  }
})();