<bbn-form :source="formData"
          :action="source.root + 'actions/task/bill'"
          @success="afterSubmit"
>
  <div class="bbn-grid-fields bbn-padding">
    <label><?= _('Ref.') ?></label>
    <div>
      <span bbn-text="currentYear"></span>
      <bbn-masked bbn-model="formData.ref"
                  mask="00000"
                  @blur="fillRef"
                  maxlength="5"
                  size="5"
                  required
      ></bbn-masked>
    </div>
    <label><?= _('Date') ?></label>
    <div>
      <bbn-datetimepicker bbn-model="formData.creation"
                          :max="currentYear + '12-31 23:59:59'"
                          :min="currentYear + '01-01 00:00:00'"
                          required
      ></bbn-datetimepicker>
    </div>
    <label><?= _('Description') ?></label>
    <bbn-rte bbn-model="formData.description"
             required
    ></bbn-rte>
    <label><?= _('Tax') ?></label>
    <div class="bbn-vmiddle">
      <bbn-numeric bbn-model="formData.tax"
                   required
                   :min="0"
                   :max="100"
      ></bbn-numeric>
      <i class="nf nf-fa-percent bbn-left-sspace"></i>
    </div>
    <label><?= _('Taxable') ?></label>
    <div class="bbn-vmiddle">
      <bbn-numeric bbn-model="formData.taxable"
                   required
                   :min="0"
                   :decimals="2"
      ></bbn-numeric>
      <i class="nf nf-fa-euro bbn-left-sspace"></i>
    </div>
    <label><?= _('Amount') ?></label>
    <div class="bbn-vmiddle">
      <bbn-numeric :value="formData.amount"
                   readonly
                   required
                   :spinners="false"
                   :decimals="2"
      ></bbn-numeric>
      <i class="nf nf-fa-euro bbn-left-sspace"></i>
    </div>
  </div>
</bbn-form>
