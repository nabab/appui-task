(() => {
  return {
    mixins: [appuiTaskMixin],
    props: {
      source: {
        type: Object,
        require: true
      }
    },
    data(){
      return {
        formSource: {
          title: this.source.title
        }
      }
    },
    methods: {
      onSubmit(){
        if (this.formSource.title.length) {
          this.update('title', this.formSource.title).then(d => {
            if (d.data && d.data.success) {
              this.source.title = this.formSource.title;
              this.currentPopup.close(this.currentPopup.items.length - 1, true);
              appui.success();
            }
            else {
              appui.error();
            }
          });
        }
      }
    }
  }
})();