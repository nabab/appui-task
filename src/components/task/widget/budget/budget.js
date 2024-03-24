/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 28/06/2018
 * Time: 18:43
 */
(() => {
  return {
      data(){
      return {
        showPriceForm: false,
        oldPrice: 0,
        oldLastChangePrice: {}
      }
    },
    computed: {
      statusText(){
        return this.task.isApproved ? bbn._('Approved') : bbn._('Unapproved');
      },
      price(){
        if (this.source.price) {
          return bbn.fn.money(this.source.price);
        }
        if (this.source.children_price) {
          return bbn.fn.money(this.source.children_price);
        }
        return '';
      },
      approvedOn(){
        if (!!this.source.approved && !!this.source.approved.chrono) {
          return this.fdatetime(this.source.approved.chrono);
        }
        return '';
      },
      approvedBy(){
        if ( this.task.isApproved ){
          return bbn.fn.getField(appui.users, 'text', 'value', this.source.approved.id_user);
        }
        return '';
      },
      deciders(){
        if (this.source.roles.deciders === undefined) {
          return [];
        }
        return bbn.fn.order(bbn.fn.map(this.source.roles.deciders.slice(), u => {
          return {
            idUser: u,
            userName: this.userName(u)
          }
        }), 'userName');
      }
    },
    methods: {
      getUserName: appui.getUserName,
      money: bbn.fn.money,
      cancelForm(){
        if ( this.source.price !== this.oldPrice ){
          this.source.price = this.oldPrice;
        }
        this.source.lastChangePrice = this.oldLastChangePrice;
        this.oldLastChangePrice = {};
        this.oldPrice = 0;
        this.showPriceForm = false;
      },
      saveForm(){
        if (!!this.source.price
          && this.task.canChangeBudget
          && !this.task.isClosed
        ) {
          this.confirm(bbn._('Are you sure you want to save this price?'), () => {
            this.task.update('price', this.source.price).then(d => {
              if (d.data && d.data.success) {
                this.showPriceForm = false;
                this.$parent.updateButtons();
                let w = this.task.find('appui-task-task-widget-subtasks');
                if (w) {
                  w.refresh();
                }
              }
            });
          });
        }
      },
      addDecider(){
        if ( this.task.canChangeDecider ){
          this.getPopup().open({
            component: 'appui-task-form-role',
            componentOptions: {
              source: this.source,
              role: 'deciders'
            },
            title: bbn._('Select decider(s)'),
            width: 400,
            height: 600
          });
        }
      },
      removeDecider(decider){
        if ( this.task.canChangeDecider ){
          this.confirm(bbn._('Are you sure you want to remove this decider?'), () => {
            const idx = this.source.roles.deciders.indexOf(decider);
            if (idx > -1) {
              this.post(this.root + 'actions/role/delete', {
                id_task: this.source.id,
                id_user: decider
              }, d => {
                if (d.success) {
                  this.source.roles.deciders.splice(idx, 1);
                  if (d.roles !== undefined) {
                    let comps = this.mainPage.findAllByKey(this.source.id, 'appui-task-item');
                    if (comps.length) {
                      bbn.fn.each(comps, c => c.$set(c.source, 'roles', d.roles));
                      }
                    let t = appui.getRegistered('appui-task-' + this.source.id, true);
                    if (t) {
                      this.$set(t.source, 'roles', d.roles);
                    }
                  }
                  appui.success();
                }
                else {
                  appui.error();
                }
              });
            }
          });
        }
      },
      makeInvoice(){
        this.getPopup().open({
          title: bbn._('Invoice creation'),
          height: '90%',
          width: '70%',
          component: 'appui-task-invoice',
          source: this.source
        });
      },
      getInvoiceRef(){
        if ( this.source.invoice ){
          return dayjs(this.source.invoice.creation).format('YYYY') + '-' + this.source.invoice.ref;
        }
        return '';
      },
      getInvoiceDate(){
        if ( this.source.invoice ){
          return dayjs(this.source.invoice.creation).format('DD/MM/YYYY');
        }
        return '';
      },
      getInvoiceTax(){
        if ( this.source.invoice ){
          return `${bbn.fn.money(this.source.invoice.taxable * this.source.invoice.tax / 100)} (${this.source.invoice.tax}%)`;
        }
        return '';
      },
      getInvoicePDF(){
        if ( this.source.invoice && this.source.invoice.id_media ){
          this.postOut(appui.plugins['appui-billing'] + '/actions/pdf', {
            id_media: this.source.invoice.id_media
          });
        }
      }
    },
    watch: {
      showPriceForm(newVal){
        if ( newVal ){
          this.oldPrice = this.source.price;
          this.oldLastChangePrice = this.source.lastChangePrice;
          this.source.lastChangePrice = null;
        }
      }
    }
  }
})();
