// Javascript Document
(() => {
  return {
    computed: {
      tasks(){
        return this.closest('bbn-router').$parent;
      }
    }
  }
})();