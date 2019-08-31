<div>
  <div class="bbn-grid-fields">
    <label v-if="showPriceForm && main.isAdmin"
           class="bbn-vmiddle"
    ><?=_('Price')?></label>
    <div v-if="showPriceForm && main.isAdmin"
         class="bbn-flex-width bbn-vmiddle"
    >
      <bbn-numeric class="bbn-flex-fill"
                   :decimals="2"
                   :min="0"
                   v-model="source.price"
                   style="margin-right: 5px"
                   
      ></bbn-numeric>
      <bbn-button icon="nf nf-fa-save"
                  :disabled="!source.price || (source.price == oldPrice)"
                  @click="saveForm"
                  title="<?=_('Save')?>"
                  class="bbn-hsmargin"
      ></bbn-button>
      <bbn-button icon="nf nf-fa-times"
                  @click="cancelForm"
                  title="<?=_('Cancel')?>"
      ></bbn-button>
    </div>
    <label v-if="!showPriceForm && source.price"><?=_('Price')?></label>
    <div v-if="!showPriceForm && source.price"
         class="bbn-flex-width bbn-vmiddle"
    >
      <div class="bbn-flex-fill"
           v-text="price"
      ></div>
      <bbn-button v-if="main.isAdmin && !main.isClosed"
                  icon="nf nf-fa-edit"
                  title="<?=_('Edit price')?>"
                  @click="showPriceForm = true"
      ></bbn-button>
      <bbn-button v-if="main.isAdmin && !main.isClosed"
                  icon="nf nf-fa-trash"
                  title="<?=_('Remove price')?>"
                  @click="removePrice"
      ></bbn-button>
      <bbn-button v-if="main.canApprove && !main.isApproved"
                  icon="nf nf-fa-thumbs_up"
                  @click="approve"
                  title="<?=_('Approve price')?>"
      ></bbn-button>
    </div>
    <label v-if="source.price"><?=_('Status')?></label>
    <div v-if="main.isApproved && source.price"
         class="bbn-green"
    ><?=_('Approved')?></div>
    <div v-else-if="!main.isApproved && source.price" class="bbn-orange"><?=_('Unapproved')?></div>
    <label v-if="main.isApproved"><?=_('Approved on')?></label>
    <div v-if="main.isApproved"
         v-text="approvedOn"
    ></div>
    <label v-if="main.isApproved"><?=_('Approved by')?></label>
    <div v-if="main.isApproved"
         class="bbn-vmiddle"
    >
      <bbn-initial :user-id="source.approved.id_user"
                   :height="20"
                   :width="20"
                   title="approvedBy"
      ></bbn-initial>
      <span v-text="approvedBy"
            style="margin-left: 0.5rem"
      ></span>
    </div>
  </div>
  <div v-if="source.price"
       class="bbn-block"
       style="margin-top: 10px"
  >
    <div class="bbn-header">
      <span class="bbn-b"><i class="nf nf-fa-gavel bbn-hsmargin"></i><?=_('Deciders')?></span>
      <i class="nf nf-fa-plus bbn-p"
         style="position: absolute; top: 7px; right: 7px;"
         @click="addDecider"
         v-if="main.canChangeDecider && !main.isApproved"
      ></i>
    </div>
    <div class="bbn-hspadded">
      <div v-for="decider in source.roles.deciders"
           class="bbn-flex-width bbn-vmiddle bbn-vsmargin"
      >
        <bbn-initial :user-id="decider"
                     :height="20"
                     :width="20"
        ></bbn-initial>
        <span v-text="getUserName(decider)"
              class="bbn-flex-fill bbn-hsmargin"
        ></span>
        <i class="nf nf-fa-trash_alt bbn-p bbn-red"
           v-if="main.canChangeDecider && (main.userId !== decider) && !main.isApproved"
           @click="removeDecider(decider)"
           title="<?=_('Remove decider')?>"
        ></i>
      </div>
    </div>
  </div>
  <div v-if="main.canBill"
       class="bbn-block"
       style="margin-top: 10px"
  >
    <div class="bbn-header">
      <span class="bbn-b"><i class="nf nf-fa-file_invoice bbn-hsmargin"></i><?=_('Invoice')?></span>
      <i class="nf nf-fa-plus bbn-p"
         style="position: absolute; top: 7px; right: 7px;"
         @click="makeInvoice"
         v-if="!main.hasInvoice"
      ></i>
      <i v-if="main.hasInvoice && source.invoice.id_media"
         class="nf nf-fa-file_pdf bbn-p"
         style="position: absolute; top: 7px; right: 7px;"
         @click="getInvoicePDF"
      ></i>
    </div>
    <div v-if="main.hasInvoice"
         class="bbn-hspadded bbn-grid-fields"
    >
      <label><?=_('Ref')?></label>
      <div v-text="getInvoiceRef()"></div>
      <label><?=_('Date')?></label>
      <div v-text="getInvoiceDate()"></div>
      <label><?=_('Taxable')?></label>
      <div v-text="money(source.invoice.taxable)"></div>
      <label><?=_('Tax')?></label>
      <div v-text="getInvoiceTax()"></div>
      <label><?=_('Amount')?></label>
      <div v-text="money(source.invoice.amount)"></div>
    </div>
  </div>
</div>
