<!-- HTML Document -->
<div class="bbn-overlay">
  <bbn-table :source="root + 'budgets'"
             :sortable="true"
             :limit="100"
             :pageable="true">
    <bbns-column field="ref"
                 title="<?= _("Ref.") ?>"
                 :width="120"
                 cls="bbn-c"/>
  	<bbns-column field="title"
                 title="<?= _("Nom") ?>"
                 :render="renderTitle"
                 :min-width="250"/>
  	<bbns-column field="price"
                 :render="renderPrice"
                 :width="150"
                 title="<?= _("Price") ?>"/>
  	<bbns-column field="state"
                 :width="100"
                 :source="source.states"
                 title="<?= _("Statut") ?>"/>
  	<bbns-column field="last_date"
                 :width="150"
                 title="<?= _("Last chamge") ?>"
                 type="datetime"/>
  </bbn-table>
</div>
