<bbn-form :source="formData"
          :action="source.root + 'actions/task/bill'"
          @success="afterSubmit"
>
  <div class="bbn-grid-fields bbn-padded">
    <label><?= _('Ref.') ?></label>
    <div>
      <span v-text="currentYear"></span>
      <bbn-masked v-model="formData.ref"
                  mask="00000"
                  @blur="fillRef"
                  maxlength="5"
                  size="5"
                  required
      ></bbn-masked>
    </div>
    <label><?= _('Date') ?></label>
    <div>
      <bbn-datetimepicker v-model="formData.creation"
                          :max="currentYear + '12-31 23:59:59'"
                          :min="currentYear + '01-01 00:00:00'"
                          required
      ></bbn-datetimepicker>
    </div>
    <label><?= _('Description') ?></label>
    <bbn-rte v-model="formData.description"
             required
    ></bbn-rte>
    <label><?= _('Tax') ?></label>
    <div class="bbn-vmiddle">
      <bbn-numeric v-model="formData.tax"
                   required
                   :min="0"
                   :max="100"
      ></bbn-numeric>
      <i class="nf nf-fa-percent bbn-left-sspace"></i>
    </div>
    <label><?= _('Taxable') ?></label>
    <div class="bbn-vmiddle">
      <bbn-numeric v-model="formData.taxable"
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
