<div class="bbn-padded">
  <div class="bbn-grid-fields bbn-padded">
    <label v-if="showPriceForm && task.isAdmin"
           class="bbn-vmiddle"
    ><?=_('Price')?></label>
    <div v-if="showPriceForm && task.isAdmin"
         class="bbn-flex-width bbn-vmiddle">
      <bbn-numeric class="bbn-flex-fill"
                   :decimals="2"
                   :min="0"
                   v-model="source.price"
                   style="margin-right: 5px"/>
      <bbn-button icon="nf nf-fa-save"
                  :disabled="!source.price || (source.price == oldPrice)"
                  @click="saveForm"
                  title="<?=_('Save')?>"
                  class="bbn-hsmargin"
                  :notext="true"/>
      <bbn-button icon="nf nf-fa-times"
                  @click="cancelForm"
                  title="<?=_('Cancel')?>"
                  :notext="true"/>
    </div>
    <div v-if="!showPriceForm && !source.price"
         class="bbn-grid-full bbn-c"><?=_('No price set')?></div>
    <label v-if="!showPriceForm && source.price"
           class="bbn-vmiddle"><?=_('Price')?></label>
    <div v-if="!showPriceForm && source.price"
         class="bbn-flex-width bbn-vmiddle">
      <div class="bbn-flex-fill"
           v-text="price"/>
      <bbn-button v-if="task.isAdmin && !task.isClosed"
                  icon="nf nf-fa-edit"
                  title="<?=_('Edit price')?>"
                  @click="showPriceForm = true"
                  :notext="true"/>
      <bbn-button v-if="task.isAdmin && !task.isClosed"
                  icon="nf nf-fa-trash"
                  title="<?=_('Remove price')?>"
                  @click="removePrice"
                  :notext="true"/>
      <bbn-button v-if="task.canApprove && !task.isApproved"
                  icon="nf nf-fa-thumbs_up"
                  @click="approve"
                  title="<?=_('Approve price')?>"
                  :notext="true"/>
    </div>
    <label v-if="source.price"><?=_('Status')?></label>
    <div v-if="task.isApproved && source.price"
         class="bbn-green"><?=_('Approved')?></div>
    <div v-else-if="!task.isApproved && source.price" class="bbn-orange"><?=_('Unapproved')?></div>
    <label v-if="task.isApproved"><?=_('Approved on')?></label>
    <div v-if="task.isApproved"
         v-text="approvedOn"/>
    <label v-if="task.isApproved"><?=_('Approved by')?></label>
    <div v-if="task.isApproved"
         class="bbn-vmiddle">
      <bbn-initial :user-id="source.approved.id_user"
                   :height="25"
                   :width="25"
                   title="approvedBy"
                   font-size="1em"/>
      <span v-text="approvedBy"
            style="margin-left: 0.5rem"/>
    </div>
  </div>
  <div v-if="source.price"
       class="bbn-box"
       style="margin-top: 10px">
    <div class="bbn-header bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-b bbn-flex-fill"><i class="nf nf-fa-gavel bbn-hsmargin"/><?=_('Deciders')?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
          @click="addDecider"
          v-if="task.canChangeDecider && !task.isApproved"/>
      </div>
    </div>
    <div class="bbn-hspadded">
      <div v-for="decider in source.roles.deciders"
           class="bbn-flex-width bbn-vmiddle bbn-vsmargin">
        <bbn-initial :user-id="decider"
                     :height="25"
                     :width="25"
                     font-size="1em"/>
        <span v-text="getUserName(decider)"
              class="bbn-flex-fill bbn-hsmargin"/>
        <i class="nf nf-fa-trash bbn-p bbn-red"
           v-if="task.canChangeDecider && (task.userId !== decider) && !task.isApproved"
           @click="removeDecider(decider)"
           title="<?=_('Remove decider')?>"/>
      </div>
    </div>
  </div>
  <div v-if="task.canBill"
       class="bbn-box"
       style="margin-top: 10px">
    <div class="bbn-header bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-b bbn-flex-fill"><i class="nf nf-fa-file_invoice bbn-hsmargin"></i><?=_('Invoice')?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
           @click="makeInvoice"
           v-if="!task.hasInvoice"/>
        <i v-if="task.hasInvoice && source.invoice.id_media"
          class="nf nf-fa-file_pdf_o bbn-p bbn-hsmargin"
          @click="getInvoicePDF"/>
      </div>
    </div>
    <div v-if="task.hasInvoice"
         class="bbn-spadded bbn-grid-fields">
      <label><?=_('Ref')?></label>
      <div v-text="getInvoiceRef()"/>
      <label><?=_('Date')?></label>
      <div v-text="getInvoiceDate()"/>
      <label><?=_('Taxable')?></label>
      <div v-text="money(source.invoice.taxable)"/>
      <label><?=_('Tax')?></label>
      <div v-text="getInvoiceTax()"/>
      <label><?=_('Amount')?></label>
      <div v-text="money(source.invoice.amount)"/>
    </div>
  </div>
</div>
