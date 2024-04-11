(() => {
  return {
    mixins: [bbn.cp.mixins.basic],
    props: {
      source: {
        type: Object,
        required: true
      }
    },
    data(){
      return {
        currentFilters: {
          logic: 'AND',
          conditions: [{
            field: 'id_parent',
            value: this.source.id
          }]
        },
        currentOrder: [{
          field: 'last_action',
          dir: 'DESC'
        }]
      }
    },
    methods: {
      refresh(){
        this.getRef('list').updateData();
      }
    }
  }
})();