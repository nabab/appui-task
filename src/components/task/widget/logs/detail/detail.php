<div class="appui-task-task-widget-logs-detail">
  <bbn-table class="bbn-100"
             :source="root + 'data/logs'"
             :data="{id_task: source.id}">
    <bbns-column title="<i class='nf nf-fa-user bbn-hsmargin'></i><?= _("User") ?>"
                 field="id_user"
                 :width="190"
                 :component="$options.components['useravatar']"/>
    <bbns-column title="<i class='nf nf-fa-calendar bbn-hsmargin'></i><?= _("Date") ?>"
                 field="chrono"
                 :width="150"
                 type="datetime"
                 cls="bbn-c"/>
    <bbns-column title="<i class='nf nf-fa-history bbn-hsmargin'></i><?= _("Action") ?>"
                 field="action"/>
  </bbn-table>
</div>
