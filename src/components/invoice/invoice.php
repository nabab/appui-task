<bbn-form class="bbn-full-screen"
          :source="formData"
          :action="source.root + 'actions/task/bill'"
          @success="afterSubmit"
>
  <div class="bbn-grid-fields bbn-padded">
    <label><?=_('Ref.')?></label>
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
    <label><?=_('Date')?></label>
    <bbn-datetimepicker v-model="formData.creation"
                        :max="currentYear + '12-31 23:59:59'"
                        :min="currentYear + '01-01 00:00:00'"
                        required
    ></bbn-datetimepicker>
    <label><?=_('Description')?></label>
    <bbn-rte v-model="formData.description"
             required
    ></bbn-rte>
    <label><?=_('Tax')?></label>
    <div>
      <bbn-numeric v-model="formData.tax"
                   format="# \%"
                   required
      ></bbn-numeric>
    </div>
    <label><?=_('Taxable')?></label>
    <div>
      <bbn-numeric v-model="formData.taxable"
                   format="c2"
                   required
      ></bbn-numeric>
    </div>
    <label><?=_('Amount')?></label>
    <div>
      <bbn-numeric :value="formData.amount"
                   readonly
                   required
                   format="c2"
                   :spinners="false"
      ></bbn-numeric>
    </div>
  </div>
</bbn-form>
