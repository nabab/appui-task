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
        main: this.closest('appui-task-tab-main'),
        showPriceForm: false,
        deciders: this.source.roles.deciders.slice(),
        oldPrice: 0,
        oldLastChangePrice: {}
      }
    },
    computed: {
      price(){
        if ( this.source.price ){
          return bbn.fn.money(this.source.price);
        }
        return '';
      },
      approvedOn(){
        if ( this.main.isApproved ){
          return bbn.fn.fdate(this.source.approved.chrono);
        }
        return '';
      },
      approvedBy(){
        if ( this.main.isApproved ){
          return bbn.fn.get_field(appui.app.users, 'value', this.source.approved.id_user, 'text');
        }
        return '';
      }
    },
    methods: {
      getUserName: appui.app.getUserName,
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
        if ( this.source.price && this.main.isAdmin && !this.main.isClosed ){
          this.confirm(bbn._('Are you sure you want to save this price?'), () => {
            this.main.update('price', this.source.price);
            if ( this.source.state !== this.main.tasks.source.states.unapproved ){
              this.main.update('state', this.main.tasks.source.states.unapproved);
            }
            this.showPriceForm = false;
            this.$parent.updateButtons();
          });
        }
      },
      removePrice(){
        if ( this.main.isAdmin && !this.main.isClosed ){
          this.confirm(bbn._('Are you sure you want to remove the price?'), () => {
            this.main.update('price', null);
            this.main.update('state', this.main.tasks.source.states.opened);
            this.source.price = null;
            this.source.roles.deciders = [];
            this.showPriceForm = false;
            this.$parent.updateButtons();
          });
        }
      },
      addDecider(){
        if ( this.main.canChangeDecider ){
          this.getPopup().open({
            component: this.$options.components.deciderPicker,
            source: this.source.roles,
            title: bbn._('Select deciders'),
            width: 400,
            height: 600
          });
        }
      },
      removeDecider(decider){
        if ( this.main.canChangeDecider ){
          this.confirm(bbn._('Are you sure you want to remove this decider?'), () => {
            let idx = this.source.roles.deciders.indexOf(decider);
            if ( idx > -1 ){
              this.source.roles.deciders.splice(idx, 1);
            }
          });
        }
      },
      approve(){
        if ( this.main.canApprove ){
          this.confirm(bbn._('Are you sure you want to approve this price?'), () => {
            bbn.fn.post(`${this.main.tasks.source.root}actions/task/approve`, {
              id_task: this.source.id
            }, d => {
              if ( d.success && d.data.approved ){
                this.source.approved = d.data.approved;
                this.main.update('state', this.main.tasks.source.states.opened);
                appui.success('Price approved');
              }
            });
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
          return moment(this.source.invoice.creation).format('YYYY') + '-' + this.source.invoice.ref;
        }
        return '';
      },
      getInvoiceDate(){
        if ( this.source.invoice ){
          return moment(this.source.invoice.creation).format('DD/MM/YYYY');
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
          bbn.fn.post_out(appui.plugins['appui-billing'] + '/actions/pdf', {
            id_media: this.source.invoice.id_media
          });
        }
      }
    },
    watch: {
      'source.roles.deciders'(newVal){
        if ( this.main.canChangeDecider ){
          let added = newVal.filter(a => {
                return this.deciders.indexOf(a) === -1;
              }),
              deleted = this.deciders.filter(d => {
                return newVal.indexOf(d) === -1;
              }),
              deciderAction = (idUser, action) => {
                bbn.fn.post(`${this.main.tasks.source.root}actions/role/${action}`, {
                  id_task: this.source.id,
                  role: 'deciders',
                  id_user: idUser
                }, (d) => {
                  if ( !d.success ){
                    this.alert(bbn._('Error during user insert'));
                  }
                });
              };
          if ( added.length ){
            added.forEach(a => deciderAction(a, 'insert'));
          }
          if ( deleted.length ){
            deleted.forEach(d => deciderAction(d, 'delete'));
          }
          this.deciders = this.source.roles.deciders.slice();
        }
      },
      showPriceForm(newVal){
        if ( newVal ){
          this.oldPrice = this.source.price;
          this.oldLastChangePrice = this.source.lastChangePrice;
          this.source.lastChangePrice = {};
        }
      }
    },
    components: {
      deciderPicker: {
        props: ['source'],
        template: `
<bbn-form :scrollable="false"
          class="bbn-full-screen"
          :source="source"
>
  <div class="bbn-full-screen bbn-hpadded bbn-vspadded">
    <appui-usergroup-picker :multi="true"
                            class="bbn-h-100"
                            v-model="source.deciders"
                            :as-array="true"
                            :self-excluded="true"
    ></appui-usergroup-picker>
  </div>
</bbn-form>
        `
      }
    }
  }
})();
