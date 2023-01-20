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
            this.source.price = this.formSource.price;
            if ( this.source.state !== this.mainPage.states.unapproved ){
              this.update('state', this.mainPage.states.unapproved);
            }
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