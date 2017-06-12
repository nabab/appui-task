<div class="appui-task-tab-logs bbn-full-height bbn-w-100">
  <bbn-table class="bbn-h-100 bbn-w-100" :source="tableData">
    <table>
      <thead>
        <tr>
          <th style="padding: 1px; width: 38px" render="renderAvatar"></th>
          <th field="id_user" style="width: 150px" render="renderUser"><?=_("User")?></th>
          <th field="chrono" style="width: 150px" render="renderDate"><?=_("Date")?></th>
          <th field="action"><?=_("Action")?></th>
        </tr>
      </thead>
    </table>
  </bbn-table>
</div>