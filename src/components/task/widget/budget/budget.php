<div class="appui-task-task-widget-budget bbn-bottom-oadded">
  <div bbn-if="!showPriceForm && (!!source.price || !!source.children_price)"
        :class="['bbn-radius', 'bbn-flex-width', 'bbn-border', 'bbn-vmiddle', {
          'bbn-bg-green': task.isApproved,
          'bbn-bg-orange': !!task.isUnapproved && !task.isApproved && (!!source.price || (!!source.children_price && !source.num_children_noprice)),
          'bbn-bg-red': !!task.isUnapproved && !task.isApproved && !!source.children_price && !!source.num_children_noprice
        }]"
        :style="{borderColor: 'var(' + (task.isApproved ? '--green' : (!!source.children_price && !!source.num_children_noprice ? '--red' : '--orange')) + ') !important'}">
    <div class="bbn-spadding bbn-b bbn-background bbn-radius bbn-flex-fill bbn-c">
      <div class="bbn-secondary-text-alt"
           bbn-text="price"
           title="<?=_('Price')?>"/>
      <div bbn-if="hasTokensActive"
           class="bbn-tertiary-text-alt"
           title="<?=_('Tokens')?>">
        <span bbn-text="tokens"/>
        <i class="nf nf-mdi-coins bbn-m"/>
      </div>
    </div>
    <div class="bbn-vspadding bbn-hxlpadding bbn-c bbn-b bbn-white">
      <span bbn-if="task.isApproved"><?= _('Approved') ?></span>
      <span bbn-else><?= _('Unapproved') ?></span>
      <i bbn-if="!task.isApproved && !!source.children_price && !!source.num_children_noprice"
         class="nf nf-fa-info_circle bbn-left-sspace"
         :title="_('%d sub-tasks need their price to be set', source.num_children_noprice)"/>
    </div>
  </div>
  <div bbn-else-if="showPriceForm && task.canChangeBudget"
        class="bbn-flex-width bbn-vmiddle">
    <div class="bbn-flex-column bbn-flex-fill"
         style="gap: var(--sspace)">
      <span class="bbn-flex-width">
        <span class="bbn-leftlabel"><?=_('Price')?></span>
        <bbn-numeric class="bbn-flex-fill"
                     :decimals="2"
                     :min="0"
                     unit="€"
                     bbn-model="source.price"/>
      </span>
      <span bbn-if="hasTokensActive"
            class="bbn-flex-width">
        <span class="bbn-leftlabel"><?=_('Tokens')?></span>
        <bbn-numeric class="bbn-flex-fill"
                      :decimals="2"
                      :min="0"
                      bbn-model="tokens"/>
      </span>
    </div>
    <bbn-button icon="nf nf-fa-check"
                :disabled="!source.price || (source.price == oldPrice)"
                @click="saveForm"
                title="<?= _('Save') ?>"
                class="bbn-white bbn-bg-green bbn-left-sspace bbn-right-xsspace"
                :notext="true"/>
    <bbn-button icon="nf nf-fa-times"
                @click="cancelForm"
                title="<?= _('Cancel') ?>"
                :notext="true"
                class="bbn-bg-red bbn-white"/>
  </div>
  <div bbn-if="task.isApproved"
        class="bbn-middle bbn-background bbn-flex-width bbn-radius bbn-top-sspace">
    <div class="bbn-flex-fill bbn-vmiddle">
      <bbn-initial :user-id="source.approved.id_user"
                    :height="25"
                    :width="25"
                    user-name="approvedBy"
                    font-size="1em"/>
      <span class="bbn-left-sspace"
            bbn-text="approvedBy"/>
    </div>
    <i class="nf nf-mdi-calendar bbn-left-sspace bbn-right-xsspace"
       style="line-height: normal !important"/>
    <span class="bbn-right-sspace"
          bbn-text="approvedOn"/>
  </div>
  <div bbn-if="!showPriceForm && !source.price && !source.children_price"
       class="bbn-background bbn-radius bbn-spadding bbn-c">
    <span bbn-if="source.children.length"
          bbn-text="_('You can set the price of this work globally here or individually in each subtask, and in this case you will still be able to see the global price here')"/>
    <span bbn-else
          bbn-text="_('You can set the price of this work here')"/>
  </div>

  <div bbn-if="(source.price || source.children_price) && ((source.state === states.unapproved) || task.isApproved)"
       class="bbn-w-100 bbn-radius bbn-border bbn-background bbn-top-space"
       :style="{borderColor: getRoleBgColor('deciders') + '!important'}">
    <div class="bbn-b bbn-no-border bbn-radius-top bbn-xspadding"
         :style="{
           backgroundColor: getRoleColor('deciders'),
           color: getRoleBgColor('deciders')
         }">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-flex-fill bbn-c bbn-upper">
          <i class="nf nf-fa-gavel bbn-hsmargin"/>
          <?= _('Deciders') ?>
        </span>
        <bbn-button class="bbn-left-sspace bbn-no-border"
                    icon="nf nf-fa-plus"
                    @click="addDecider"
                    bbn-if="task.canChangeDecider && !task.isApproved"
                    :notext="true"
                    :style="{color: getRoleBgColor('deciders')}"/>
      </div>
    </div>
    <div bbn-for="decider in deciders"
          class="bbn-smargin">
      <div class="bbn-vmiddle bbn-flex-width bbn-radius bbn-alt-background bbn-alt-text">
        <bbn-initial :user-id="decider.idUser"
                      :height="25"
                      :width="25"
                      font-size="1em"/>
        <span bbn-text="decider.userName"
              class="bbn-hsmargin bbn-flex-fill"/>
        <i class="nf nf-fa-trash bbn-p bbn-red bbn-right-sspace"
           bbn-if="task.canChangeDecider && (task.userId !== decider.idUser) && !task.isApproved"
           @click="removeDecider(decider.idUser)"
           :title="_('Remove decider')"/>
      </div>
    </div>
    <div bbn-if="!source.roles.deciders || !source.roles.deciders.length"
          class="bbn-c bbn-spadding bbn-radius-bottom"
          :style="{
            color: getRoleColor('deciders'),
            backgroundColor: getRoleBgColor('deciders')
          }">
      <?= _('Not set') ?>
    </div>
  </div>
  <div bbn-if="task.canBill"
       class="bbn-box"
       style="margin-top: 10px">
    <div class="bbn-header bbn-no-border-top bbn-no-hborder bbn-radius-top bbn-xspadding">
      <div class="bbn-vmiddle bbn-flex-width">
        <span class="bbn-b bbn-flex-fill"><i class="nf nf-fa-file_invoice bbn-hsmargin"></i><?= _('Invoice') ?></span>
        <i class="nf nf-fa-plus bbn-p bbn-hsmargin"
           @click="makeInvoice"
           bbn-if="!task.hasInvoice"/>
        <i bbn-if="task.hasInvoice && source.invoice.id_media"
          class="nf nf-fa-file_pdf_o bbn-p bbn-hsmargin"
          @click="getInvoicePDF"/>
      </div>
    </div>
    <div bbn-if="task.hasInvoice"
         class="bbn-spadding bbn-grid-fields">
      <label><?= _('Ref') ?></label>
      <div bbn-text="getInvoiceRef()"/>
      <label><?= _('Date') ?></label>
      <div bbn-text="getInvoiceDate()"/>
      <label><?= _('Taxable') ?></label>
      <div bbn-text="money(source.invoice.taxable)"/>
      <label><?= _('Tax') ?></label>
      <div bbn-text="getInvoiceTax()"/>
      <label><?= _('Amount') ?></label>
      <div bbn-text="money(source.invoice.amount)"/>
    </div>
  </div>
</div>
