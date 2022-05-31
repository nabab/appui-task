// Javascript Document
(() => {
  return {
    computed: {
      mainPage(){
        return this.closest('appui-task');
      }
    }
  }
})();