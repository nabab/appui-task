<div class="appui-task-task-widget-budget">
  <div v-if="!showPriceForm && !!source.price"
        :class="['bbn-radius', 'bbn-flex-width', 'bbn-bordered', {
          'bbn-bg-green': task.isApproved,
          'bbn-bg-orange': !task.isApproved
        }]"
        :style="{borderColor: 'var(' + (task.isApproved ? '--green' : '--orange') + ') !important'}">
    <div class="bbn-spadded bbn-secondary-text-alt bbn-b bbn-background bbn-radius bbn-flex-fill bbn-c"
          v-text="price"/>
    <div class="bbn-vspadded bbn-hxlpadded bbn-c bbn-b bbn-white">
      <span v-if="task.isApproved"><?=_('Approved')?></span>
      <span v-else><?=_('Unapproved')?></span>
    </div>
  </div>
  <div v-else-if="showPriceForm && task.isAdmin"
        class="bbn-flex-width bbn-vmiddle">
    <bbn-numeric class="bbn-flex-fill"
                  :decimals="2"
                  :min="0"
                  v-model="source.price"
                  style="margin-right: 5px"/>
    <bbn-button icon="nf nf-fa-check"
                :disabled="!source.price || (source.price == oldPrice)"
                @click="saveForm"
                title="<?=_('Save')?>"
                class="bbn-white bbn-bg-green bbn-left-sspace bbn-right-xsspace"
                :notext="true"/>
    <bbn-button icon="nf nf-fa-times"
                @click="cancelForm"
                title="<?=_('Cancel')?>"
                :notext="true"
                class="bbn-bg-red bbn-white"/>
  </div>
  <div v-if="task.isApproved"
        class="bbn-middle bbn-background bbn-flex-width bbn-radius bbn-top-sspace">
    <div class="bbn-flex-fill bbn-vmiddle">
      <bbn-initial :user-id="source.approved.id_user"
                    :height="25"
                    :width="25"
                    title="approvedBy"
                    font-size="1em"/>
      <span class="bbn-left-sspace"
            v-text="approvedBy"/>
    </div>
    <i class="nf nf-mdi-calendar bbn-left-sspace bbn-right-xsspace"
       style="line-height: normal !important"/>
    <span class="bbn-right-sspace"
          v-text="approvedOn"/>
  </div>
  <div v-if="!showPriceForm && !source.price"
       class="bbn-background bbn-radius bbn-spadded bbn-c">
    <?=_('No price set')?>
  </div>

  <div v-if="source.price && ((source.state === states.unapproved) || (source.state === states.approved))"
       class="bbn-w-100 bbn-radius bbn-bordered bbn-background bbn-top-space"
       :style="{borderColor: getRoleBgColor('deciders') + '!important'}">
    <div class="bbn-b bbn-no-border bbn-radius-top bbn-xspadded"
         :style="{
           backgroundColor: getRoleColor('deciders'),
           color: getRoleBgColor('deciders')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-flex-fill bbn-c bbn-upper">
          <i class="nf nf-fa-gavel bbn-hsmargin"/>
          <?=_('Deciders')?>
        </span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="addDecider"
                    v-if="task.canChangeDecider && !task.isApproved"
                    :notext="true"
                    :style="{color: getRoleBgColor('deciders')}"/>
      </div>
    </div>
    <div v-for="decider in deciders"
          class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-radius bbn-alt-background bbn-alt-text">
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
          class="bbn-c bbn-spadded bbn-radius-bottom"
          :style="{
            color: getRoleColor('deciders'),
            backgroundColor: getRoleBgColor('deciders')
          }">
      <?=_('Not set')?>
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
