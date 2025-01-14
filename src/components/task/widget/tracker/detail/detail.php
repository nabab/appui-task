<bbn-table :source="root + 'data/tracker'"
           :data="{id_task: source.id}"
           :groupable="true"
           :server-grouping="true"
           :group-by="0"
           :pageable="false"
           class="bbn-100"
           editable="popup"
           aggregate="tot"
           :editor="$options.components.editor"
           ref="table"
           :limit="0">
  <bbns-column field="id_user"
               label="<?=_('Worker')?>"
               :render="renderUser"/>
  <bbns-column field="start"
               label="<i class='nf nf-fa-calendar_alt'></i> <?=_('Start')?>"
               :render="renderStart"
               cls="bbn-c"
               :width="200"/>
  <bbns-column field="end"
               label="<i class='nf nf-fa-calendar_check'></i> <?=_('End')?>"
               :render="renderEnd"
               cls="bbn-c"
               :width="200"/>
  <bbns-column field="length"
               label="<i class='nf nf-fa-clock'></i> <?=_('Duration')?>"
               :render="renderLength"
               cls="bbn-c"
               :width="150"
               :aggregate="true"/>
  <bbns-column field="message"
               label="<i class='nf nf-fa-clock'></i> <?=_('Message')?>"/>
  <bbns-column :buttons="gridButtons"
               :width="100"
               cls="bbn-c"/>
</bbn-table>
