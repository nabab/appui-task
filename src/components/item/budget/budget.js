(() => {
  return {
    mixins: [appuiTaskMixin],
    props: {
      type: Object,
      required: true
    },
    data(){
      return {
        formSource: {
          price: this.source.price
        }
      }
    },
    methods: {
      onSubmit(){
        this.update('price', this.formSource.price).then(d => {
          if (d.data && d.data.success) {
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
})();