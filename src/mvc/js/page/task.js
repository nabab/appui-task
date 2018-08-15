// Javascript Document
(() => {
  return {
    computed: {
      tasks(){
        return this.closest('bbn-tabnav').$parent;
      }
    }
  }
})();