<div class="appui-task-tab-logs bbn-w-100" v-bbn-fill-height>
  <bbn-table class="bbn-h-100 bbn-w-100" :source="tableData">
    <table>
      <thead>
        <tr>
          <th field="id_user" render="renderAvatar" style="padding: 1px; width: 38px"></th>
          <th field="id_user" style="width: 150px" render="renderUser"><?=_("User")?></th>
          <th field="chrono" style="width: 150px" render="renderDate"><?=_("Date")?></th>
          <th field="action"><?=_("Action")?></th>
        </tr>
      </thead>
    </table>
  </bbn-table>
</div>