<div class="appui-task-task-widget-budget">
  <div class="bbn-grid-fields">
    <label v-if="showPriceForm && task.isAdmin"><?=_('Price')?></label>
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
    <label v-if="!showPriceForm && source.price"><?=_('Price')?></label>
    <div v-if="!showPriceForm && source.price"
         class="bbn-flex-width bbn-vmiddle">
      <div class="bbn-flex-fill"
           v-text="price"/>
      <bbn-button v-if="task.isAdmin && !task.isClosed"
                  icon="nf nf-fa-edit"
                  title="<?=_('Edit price')?>"
                  @click="showPriceForm = true"
                  class="bbn-hsmargin"
                  :notext="true"/>
      <bbn-button v-if="task.isAdmin && !task.isClosed"
                  icon="nf nf-fa-trash"
                  title="<?=_('Remove price')?>"
                  @click="removePrice"
                  :notext="true"/>
    </div>
    <template v-if="source.price && ((source.state === states.unapproved) || !!source.approved)">
      <label><?=_('Status')?></label>
      <template v-if="!!source.approved">
        <div class="bbn-green"><?=_('Approved')?></div>
        <label><?=_('Approved on')?></label>
        <div v-text="approvedOn"/>
        <label><?=_('Approved by')?></label>
        <div class="bbn-vmiddle">
          <bbn-initial :user-id="source.approved.id_user"
                       :height="25"
                       :width="25"
                       title="approvedBy"
                       font-size="1em"/>
          <span v-text="approvedBy"
                style="margin-left: 0.5rem"/>
        </div>
      </template>
      <div v-else class="bbn-orange"><?=_('Unapproved')?></div>
    </template>
  </div>
  <div v-if="source.price && ((source.state === states.unapproved) || (source.state === states.approved))"
       class="bbn-box"
       :style="{
         marginTop: '10px',
         backgroundColor: getRoleBgColor('deciders')
       }">
    <div class="bbn-header bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadded"
         :style="{
           backgroundColor: getRoleBgColor('deciders'),
           color: getRoleColor('deciders')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-b bbn-flex-fill"><i class="nf nf-fa-gavel bbn-hsmargin"/><?=_('Deciders')?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
          @click="addDecider"
          v-if="task.canChangeDecider && !task.isApproved"/>
      </div>
    </div>
    <div class="bbn-hspadded">
      <div v-for="decider in deciders"
           class="bbn-smargin">
        <div class="bbn-vmiddle bbn-flex-width bbn-background"
            style="border-radius: 3px">
          <bbn-initial :user-id="decider.idUser"
                       :height="25"
                       :width="25"
                       font-size="1em"/>
          <span v-text="decider.userName"
                class="bbn-hsmargin bbn-flex-fill"/>
          <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
            v-if="task.canChangeDecider && (task.userId !== decider.idUser) && !task.isApproved"
            @click="removeDecider(decider.idUser)"
            :title="_('Remove decider')"/>
        </div>
      </div>
      <div v-if="!source.roles.deciders.length"
           class="bbn-middle bbn-spadded"
           :style="{color: getRoleColor('deciders')}">
        <?=_('Not set')?>
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
