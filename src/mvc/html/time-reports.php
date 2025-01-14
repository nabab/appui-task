<!-- HTML Document -->
<div class="bbn-overlay">
  <bbn-table :source="root + 'time-reports'"
             :sortable="true"
             :limit="100"
             :pageable="true">
    <bbns-column field="id"
                 label="<?= _("ID") ?>"
                 :width="120"
                 :hidden="true"/>
  	<bbns-column field="title"
                 label="<?= _("Title") ?>"
                 :render="renderTitle"
                 :min-width="250"/>
  	<bbns-column field="id_user"
                 label="<?= _("User") ?>"
                 :source="users"
                 :width="200"/>
  	<bbns-column field="beginning"
                 type="date"
                 :width="150"
                 flabel="<?= _("First session taken into account") ?>"
                 title="<?= _("First") ?>"/>
  	<bbns-column field="end"
                 type="date"
                 :width="150"
                 flabel="<?= _("Last session taken into account") ?>"
                 title="<?= _("Last") ?>"/>
  	<bbns-column field="hours"
                 :width="100"
                 unit="<?= _("Hours") ?>"
                 type="number"
                 title="<?= _("Time spent") ?>"/>
  </bbn-table>
</div>
