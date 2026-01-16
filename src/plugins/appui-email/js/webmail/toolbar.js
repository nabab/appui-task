(() => {
  return {
    data(){
      reader: null
    },
    methods: {
      mailToTask(){}
    },
    created() {
      this.reader = this.closest('appui-email-webmail-reader');
    }
  }
})();