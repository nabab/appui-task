(() => {
  return {
    props: ['source'],
    data(){
      return {
        currentYear: moment().year() + '-',
        formData: {
          ref: 0,
          creation: moment().format('YYYY-MM-DD HH:mm:ss'),
          description: this.source.title,
          tax: 20,
          taxable: parseFloat(this.source.price),
          amount: 0,
          id_task: this.source.id,
          approved: this.source.approved ? {
            user: bbn.fn.getField(appui.app.users, 'text', 'value', this.source.approved.id_user),
            moment: moment.unix(this.source.approved.chrono).format('DD/MM/YYYY')
          } : false
        }
      }
    },
    methods:{
      setAmount(){
        if ( this.formData.tax && this.formData.taxable ){
          this.formData.amount = this.formData.taxable * this.formData.tax / 100 + this.formData.taxable;
        }
        else {
          this.formData.amount = 0;
        }
      },
      fillRef(){
        let diff = 5 - this.formData.ref.length;
        if ( diff > 0 ){
          this.formData.ref = '0'.repeat(diff) + this.formData.ref;
        }
      },
      afterSubmit(d){
        if ( d.success && d.data ){
          if ( d.data.id_media ){
            this.postOut(appui.plugins['appui-billing'] + '/actions/pdf', {
              id_media: d.data.id_media
            });
          }
          this.$set(this.source, 'invoice', d.data);
          appui.success(bbn._('Billed'));
        }
        else {
          appui.error(bbn._('Error'));
        }
      }
    },
    watch: {
      'formData.tax'(newVal){
        this.setAmount();
      },
      'formData.taxable'(newVal){
        this.setAmount();
      }
    },
    mounted(){
      this.setAmount();
    }
  }
})();
