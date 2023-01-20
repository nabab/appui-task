(() => {
  return {
    props: {
      idParent: {
        type: String,
        required: true
      }
    },
    data(){
      return {
        currentSearch: ''
      }
    },
    methods: {
      clearSearch(){
        if (this.currentSearch.length) {
          this.currentSearch = '';
        }
      }
    },
    watch: {
      
    }
  }
})();