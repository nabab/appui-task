<!-- HTML Document -->
<div class="bbn-overlay">
  <bbn-table :source="root + 'budgets'"
             :sortable="true"
             :limit="100"
             :pageable="true">
    <bbns-column field="ref"
                 label="<?= _("Ref.") ?>"
                 :width="120"
                 cls="bbn-c"/>
  	<bbns-column field="title"
                 label="<?= _("Nom") ?>"
                 :render="renderTitle"
                 :min-width="250"/>
  	<bbns-column field="price"
                 :render="renderPrice"
                 :width="150"
                 label="<?= _("Price") ?>"/>
  	<bbns-column field="state"
                 :width="100"
                 :source="source.states"
                 label="<?= _("Statut") ?>"/>
  	<bbns-column field="last_date"
                 :width="150"
                 label="<?= _("Last chamge") ?>"
                 type="datetime"/>
  </bbn-table>
</div>
