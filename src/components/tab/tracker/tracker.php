<bbn-table :source="tasks.source.root+'data/tracker'"
           :data="{id_task: source.id}"
           :groupable="true"
           :server-grouping="false"
           :group-by="0"
           :pageable="false"
           class="bbn-100"
           editable="popup"
           :editor="$options.components.editor"
           ref="table"
>
  <bbns-column field="id_user"
               title="<?=_('Worker')?>"
               :render="renderUser"
  ></bbns-column>
  <bbns-column field="start"
               title="<i class='fas fa-calendar-alt'></i> <?=_('Start')?>"
               :render="renderStart"
               cls="bbn-c"
               :width="200"
  ></bbns-column>
  <bbns-column field="end"
               title="<i class='fas fa-calendar-check'></i> <?=_('End')?>"
               :render="renderEnd"
               cls="bbn-c"
               :width="200"
  ></bbns-column>
  <bbns-column field="length"
               title="<i class='fas fa-clock'></i> <?=_('Duration')?>"
               :render="renderLength"
               cls="bbn-c"
               :width="150"
               aggregate="tot"
  ></bbns-column>
  <bbns-column field="message"
               title="<i class='fas fa-clock'></i> <?=_('Message')?>"
  ></bbns-column>
  <bbns-column :buttons="gridButtons"
               :width="100"
               cls="bbn-c"
  ></bbns-column>
</bbn-table>
